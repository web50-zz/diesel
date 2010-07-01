<?php
/**
*	Библиотека обработки шаблонов
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.5
* @access	public
* @package	CFsCMS2(SE)
* @since	17-05-2006
*/
class tmpl
{
	/**
	* @var array $templates Массив шаблонов
	*/
	private $templates = array();
	
	/**
	* @var array $loops_bodies Массив "тел" для циклических \ условных операторов
	*/
	private $loops_bodies = array();
	
	/**
	* @var string $tmpl Шаблон
	*/
	private $tmpl = FALSE;
	
	/**
	* @var string $tmpl_path		The path to templates`s files
	*/
	private $tmpl_path = '';
	
	/**
	* @var string $local_data Исходный массив данных
	*/
	private $local_data = array();
	
	/**
	* @var string $root_data Исходный массив данных
	*/
	private $root_data = array();
	
	/**
	* @var string $current_path Текущая позиция
	*/
	private $current_path = array('/');
	
	/**
	* @var array $tmpl_vars Массив переменных (локальных) шаблона
	*/
	private $tmpl_vars = array();
	
	/**
	* @var array $tmpl_vars Массив переменных (глобальных) шаблона
	*/
	private $global_vars = array();
	
	/**
	* @var boolean $parse_templates Включить разборку шаблона на логические (именнованные) части
	* @see this::parse_templates()
	*/
	private $parse_templates = TRUE;
	
	/**
	* @var boolean $set_index Включить индексацию шаблона
	* @see this::index_template()
	*/
	private $set_index = TRUE;
	
	/**
	* @var boolean $show_parse_time Вычислить и отобразить время обработки шаблона
	*/
	private $show_parse_time = FALSE;
	
	/**
	* @var array $loop_operators Массив с именами циклических и условных операторов
	*/
	private $loop_operators = array(
			'apply',
			'foreach',
			'if'
		);
	
	/**
	* @var	array	$expr_vars	Массив с переменными выражения
	*/
	private $expr_vars = array();
	
	/**
	* @var array $err	Массив ошибок парсера
	*/
	private $errs = array();

	public function __construct($tmpl = FALSE, $type = 'FILE')
	{
		switch(strtoupper($type))
		{
			case 'FILE':
				$this->set_tmpl_dir(dirname($tmpl));
				$this->load_template(basename($tmpl));
			break;
			case 'TEXT':
			default:
				$this->tmpl = $tmpl;
			break;
		}
		$this->tmpl = "\n" . $this->tmpl;
	}
	
	/**
	*	Set the template directory
	*
	* @param	string	$tmpl_path	The path to templates directory
	*/
	private function set_tmpl_dir($tmpl_path)
	{
		$this->tmpl_path = $tmpl_path . '/';
	}
	
	/**
	*	Загрузить шаблон из файла
	*
	* @param	string	$tmpl_name	Путь к файлу шаблона
	*/
	private function load_template($tmpl_name)
	{
		$tmpl_file = $this->tmpl_path . $tmpl_name;
		
		if (file_exists($tmpl_file))
			if (($this->tmpl = file_get_contents($tmpl_file)) !== FALSE)
				return TRUE;
			else
				throw new Exception('Can`t load tmpl file "' . $tmpl_file . '".');
		else
			throw new Exception('The tmpl file "' . $tmpl_file . '" not exists.');
	}
	
	/**
	*	Разборка источника на отделные конструкции
	*/
	private function parse_templates()
	{
		if ($this->parse_templates)
			$this->tmpl = trim(preg_replace_callback('/{__\(template\s*(\w*)\(([^)]+)\)__}(.+?){__template\)__}(?:\n|\r|)+/ms', array(&$this, 'push_template'), $this->tmpl));
	}
	
	/**
	*	Добавляет тело найденного шаблона в массив
	*
	* @param	array	$args	Результат работы регулярного выражения;
	*				[1] => Имя шаблона;
	*				[2] => Имя узла, обрабатываемого шаблоном, или параметры вызова
	*				[3] => Тело шаблона;
	* @see	this::parse_template()
	*/
	private function push_template($args)
	{
		$name = $args[1];
		$params = $args[2];
		$body = $args[3];
		
		if ($name)
		{
			$x = preg_split('/,/', $params);
			$params = array();
			foreach ($x as $param) $params[] = trim($param);
			
			$this->templates[$name] = array(
				'params' => $params,
				'body' => $body
				);
		}
		else
		{
			$this->templates[$params] = array(
				'body' => $body
				);
		}
	}
	
	/**
	*	Индексирует шаблон.
	*/
	private function index_template()
	{
		if ($this->set_index)
		{
			$this->tmpl = preg_replace('/(\{REM.+?\MER})/sm', '', $this->tmpl);
			foreach($this->loop_operators as $operator)
			{
				$this->set_indexes($operator);
			}
		}
	}
	
	/**
	*	Устанавливает индексы для циклического / условного оператора
	* @param	string	$operator	Имя циклического / условного оператора
	*/
	private function set_indexes($operator)
	{
		$template = $this->tmpl;
		if (strpos($template, '{__(' . $operator))
		{
			$indexes = array();
			$indexed = '';
			// NOTE: Разделяем шаблон по открывающим конструкциям
			$parts = preg_split('/\{__\(' . $operator . '/', $template);
			// NOTE: Перебираем получившийся массив
			foreach ($parts as $x => $part)
			{
				// NOTE: Если шаблон содержит закрывающую конструкцию, то обратываем её
				if (strpos($part, '{__' . $operator . ')'))
				{
					// NOTE: Разделяем шаблон по закрывающим конструкциям
					$eparts = preg_split('/\{__' . $operator . '\)/', $part);
					$part = '';
					// NOTE: Перебираем получившийся массив
					foreach ($eparts as $y => $epart)
					{
						// NOTE: Если происходит обработка оператора IF, то производим поиск для индексации ELSEIF
						$this->set_indexes_elseif($epart, $operator, $indexes);
						
						// NOTE: Конкатенируем части и устанавливаем индексы
						$part.= $epart;
						if (($y + 1) < count($eparts))
						{
							// NOTE: Получаем текущий индекс
							$ex = array_pop($indexes);
							$part.= '{__' . $operator . '[i' . $ex . '])';
						}
					}
				}
				else
				{
					// NOTE: Если происходит обработка оператора IF, то производим поиск для индексации ELSEIF
					$this->set_indexes_elseif($part, $operator, $indexes);
				}
				
				// NOTE: Конкатенируем части и устанавливаем индексы
				$indexed.= $part;
				if (($x + 1) < count($parts))
				{
					$indexes[] = $x;
					// NOTE: Добавляем индекс в массив
					$indexed.= '{__(' . $operator . '[i' . $x . ']';
				}
			}
			$this->tmpl = $indexed;
		}
	}
	
	/**
	*	Индексация условного оператора ELSEIF
	*
	* @param	string	$part		Индексируемый участок шалона
	* @param	string	$operator	Индексируемый оператор
	* @param	array	$indexes	Массив индексов
	*/
	private function set_indexes_elseif(&$part, &$operator, &$indexes)
	{
		$cx = $indexes[count($indexes) - 1];
		
		if ($operator == 'if' AND strpos($part, '{__elseif'))
		{
			$ifparts = preg_split('/\{__elseif/', $part);
			$part = '';
			foreach($ifparts as $z => $ifpart)
			{
				// NOTE: Конкатенируем части и устанавливаем индексы
				$part.= $ifpart;
				if (($z + 1) < count($ifparts))
				{
					$part.= '{__elseif[i' . $cx . ']';
				}
			}
		}
		
		if ($operator == 'if' AND strpos($part, '{__else__}'))
		{
			$ifparts = preg_split('/\{__else__\}/', $part);
			$part = '';
			foreach($ifparts as $z => $ifpart)
			{
				// NOTE: Конкатенируем части и устанавливаем индексы
				$part.= $ifpart;
				if (($z + 1) < count($ifparts))
				{
					$part.= '{__else[i' . $cx . ']__}';
				}
			}
		}
	}

	/**
	*	Анализ шаблона
	* Накладывает шаблон на массив
	*
	* @param	array	$data	Массив с данными для обработки
	* @return	string	Результат обработки шаблона
	*/
	public function parse($data = FALSE)
	{
		$time_start = $this->getmicrotime();
		
		// NOTE: Если источник не пуст
		if ($this->tmpl)
		{
			// NOTE: Подгружаем внешние шаблоны
			$this->tmpl = preg_replace_callback('/\s*{__include\((.+?)\)__}/', array(&$this, '_parse_includes'), $this->tmpl);
			// NOTE: Индексируем его элементы
			$this->index_template();
			// NOTE: Разбираем его на конструкции шаблонов
			$this->parse_templates();
		}
		
		$this->local_data = $data;
		if (!$this->root_data) $this->root_data = $data;
		$results = $this->tmpl;
		
		$results = preg_replace_callback('/{__\((\w+)\[([\w\-]+)\]\((.+?|)\)__}(.+?){__\1\[\2\]\)__}(?:\r|\n)*/ms', array(&$this, '_parse_cut_loops'), $results);
		
		$results = preg_replace_callback('/({__(?:.+?)__})/', array(&$this, '_parse_operators'), $results);
		
		$time_end = $this->getmicrotime();
		$time = $time_end - $time_start;
		
		if ($this->show_parse_time) dbg::show($time . ' сек.', 'Затраченое время на обработку');
		
		return trim($results);
	}
	
	private function _parse_operators($argv)
	{
		$results = $argv[1];
		
		$results = preg_replace_callback('/{__ui_(\w+)(?:::(\w+))?\((.*)\)__}/', array(&$this, '_parse_call_ui'), $results);
		
		$results = preg_replace_callback('/{__php::(\w+)\((.+?)\)__}/', array(&$this, '_parse_operator'), $results);
		
		$results = preg_replace_callback('/{__(\w+)::(\w+)\((.*)\)__}/', array(&$this, '_parse_call_function'), $results);
		
		$results = preg_replace_callback('/\s*{__set\(#(\w+)\s*=\s*(.*)\)__}(?:\r|\n)*/', array(&$this, '_parse_sets'), $results);
		
		$results = preg_replace_callback('/\s*{__copy\(#(\w+)\s*=\s*(.+?)\)__}(?:\r|\n)*/', array(&$this, '_parse_copy'), $results);
		
		$results = preg_replace_callback('/{__echo\((.+?)\)__}/', array(&$this, '_parse_echo'), $results);
		
		$results = preg_replace_callback('/{__((?:#|@|\$)?\w+)__}/', array(&$this, '_parse_variable'), $results);
		
		$results = preg_replace_callback('/{__((?:#|@|\$|\/).+?)__}/', array(&$this, '_parse_variable'), $results);
		
		$results = preg_replace_callback('/{__loop\[(\w+)\]\[(.+?)\]__}/', array(&$this, '_parse_loops'), $results);
		
		$results = preg_replace_callback('/{__apply\((.+?)\)__}/', array(&$this, '_parse_call_apply'), $results);
		
		$results = preg_replace_callback('/{__apply\s+(\w+)\((.+?|)\)__}/', array(&$this, '_parse_call_named_apply'), $results);
		
		$results = preg_replace_callback('/{__fckeditor\[(.*)\]\((.*)\)__}/', array(&$this, '_parse_fckeditor'), $results);
		
		return $results;
	}
	
	/**
	*	Получение циклических / условных операторов.
	*
	* @param	array	$argv	Результат работы регулярного выражения
	* @see	this::parse()
	*/
	private function _parse_cut_loops($argv)
	{
		$params = array(
				'operator' => $argv[1],
				'id' => $argv[2],
				'params' => $argv[3],
				'body' => $argv[4],
			);
		$this->loops_bodies[$argv[1]][$argv[2]] = $params;
		return '{__loop[' . $argv[1] . '][' . $argv[2] . ']__}';
	}
	
	/**
	*	Анализ циклических / условных операторов.
	*
	* @param	array	$argv	Результат работы регулярного выражения
	* @see	this::parse()
	*/
	private function _parse_loops($argv)
	{
		$res = '';
		$argv = $this->loops_bodies[$argv[1]][$argv[2]];
		$operator = $argv['operator'];
		$id = $argv['id'];
		$params = $argv['params'];
		$body = $argv['body'];
		switch($operator)
		{
			case 'apply':
				$res = $this->_parse_apply($params, $body);
			break;
			case 'foreach':
				$res = $this->_parse_foreach($params, $body);
			break;
			case 'if':
				$res = $this->_parse_if($params, $body, $id);
			break;
		}
		
		// DEBUG: if ($this->log_show) main::debug($res);
		
		return $res;
	}
	
	/**
	*	Анализ шаблона вида {__include(путь/к/файлу)__}
	*
	* @param	string	$argv	Результат работы регулярного выражения
	* @see	this::parse()
	*/
	private function _parse_includes($argv)
	{
		$tmpl =& $this->__create_tmpl_obj($this->tmpl_path . $argv[1], 'FILE');
		return $tmpl->tmpl;
	}
	
	/**
	*	Анализ шаблона вида {__ui_name::method(var=val)__}
	* Вызов пользовательского интерфейса
	*
	* @param	array	$args	Результат работы регулярного выражения @see this::parse();
	*				[1] => Имя узла, обрабатываемого шаблоном, или параметры вызова
	* @see	this::parse()
	*/
	private function _parse_call_ui($args)
	{
		$ui = user_interface::get_instance($args[1]);
		return $ui->call($args[2], $this->_convert_params_to_array($this->_parse_line($args[3])));
	}
	
	/**
	*	Анализ шаблона вида {__class::method(var=val)__}
	* Вызов модуля
	*
	* @param	array	$args	Результат работы регулярного выражения @see this::parse();
	*				[1] => Имя узла, обрабатываемого шаблоном, или параметры вызова
	* @see	this::parse()
	*/
	private function _parse_call_function($args)
	{
		$class_name = $args[1];
		$method_name = $args[2];
		$params = $this->_parse_line($args[3]);
		if (class_exists($class_name))
		{
			$obj = new $class_name();
			if (method_exists($class_name, $method_name))
			{
				$result = NULL;
				$cmd = '$result = $obj->' . $method_name . '(' . $params . ');';
				//dbg::show($cmd);
				eval($cmd);
				return $result;
			}
			else
			{
				$this->errs[] = 'The method `' . $method_name . '` of class `' . $class_name . '` not exists.';
			}
		}
		else
		{
			$this->errs[] = 'The class `' . $class_name . '` not exists.';
		}
		
		return NULL;
	}
	
	/**
	*	Анализ шаблона вида {__set(переменная = значение)__}
	*
	* @param	string	$argv	Результат работы регулярного выражения
	* @see	this::parse()
	*/
	private function _parse_sets($args)
	{
		$name = $args[1];
		$expr = $this->_parse_line($args[2]);
		@eval('$value = ' . $expr . ';');
		$this->global_vars[$name] = $value;
		return '';
	}
	
	/**
	*	Анализ шаблона вида {__copy(переменная = значение)__}
	*
	* @param	string	$argv	Результат работы регулярного выражения
	* @see	this::parse()
	*/
	private function _parse_copy($args)
	{
		$data = $this->_parse_path($args[2]);
		$this->global_vars[$args[1]] = $data;
		return '';
	}
	
	/**
	*	Анализ шаблона вида {__(apply[x](/path/to)__}Тело конструкции{__apply[x])__}
	*
	* @param	string	$params	Параметры конструкции
	* @param	string	$body	Тело конструкции
	*/
	private function _parse_apply($params, $body)
	{
		if ($_data = $this->_parse_path($params))
		{
			return $this->apply_template_for_multiple($_data, $body);
		}
	}
	
	/**
	*	Анализ шаблона вида {__(foreach[x](/path/to as @key => @value)__}Тело конструкции{__foreach[x])__}
	*
	* @param	string	$params	Параметры конструкции
	* @param	string	$body	Тело конструкции
	*/
	private function _parse_foreach($params, $body)
	{
		$results = '';
		preg_match_all('/^(.*)\s*as\s*\@(\w+)(\s*=>\s*\@(\w+))?/i', $params, $matches);
		if (
			$_data = $this->_parse_path(trim($matches[1][0]))
			AND is_array($_data)
			)
		{
			$_key = $matches[2][0];
			$_value = $matches[4][0];
			$n = 1;
			$last = count($_data);
			foreach ($_data as $key => $value)
			{
				$tmpl = $this->__create_tmpl_obj($body);
				$tmpl->tmpl_vars['position'] = $n;
				$tmpl->tmpl_vars['last'] = $last;
				if ($_value)
				{
					$tmpl->tmpl_vars[$_key] = $key;
					$tmpl->tmpl_vars[$_value] = $value;
				}
				else
				{
					$tmpl->tmpl_vars[$_key] = $value;
				}
				$results.= $tmpl->parse($value);
				$n++;
			}
		}
		
		return $results;
	}
	
	/**
	*	Анализ шаблона вида {__(if[x](Условие)__}Тело конструкции{__if[x])__}
	*
	* @param	string	$params	Параметры конструкции
	* @param	string	$body	Тело конструкции
	*/
	private function _parse_if($condition, $body, $id)
	{
		// NOTE: Получаем список условий в операторе ELSEIF
		preg_match_all('/\{__elseif\[' . $id . '\]\((.+?)\)__\}/', $body, $condx);
		// NOTE: Формируем массив условий
		$conditions = $condx[1];
		// NOTE: Добавляем в начало массива первое условие из оператора IF
		array_unshift($conditions, $condition);
		// NOTE: Формируем массив "тел" для положительных результатов условий соответственно.
		$bodies = preg_split('/\{__elseif\[' . $id . '\]\((.+?)\)__\}/ms', $body);
		
		// NOTE: Перебираем полученные условия
		foreach($conditions as $level => $condition)
		{
			// NOTE: Получаем "тело" условия для положительного результата
			$body = $bodies[$level];
			// NOTE: Создаём безимянную функцию для проверки условия
			$code = '$check = (' . $this->_parse_line($condition) . ') ? TRUE : FALSE;';
			
			@eval($code);

			// NOTE: Если это не последнее условие в списке
			if (($level + 1) < count($conditions))
			{
				// NOTE: То проверяем результат его выполнения
				if ($check)
				{
					// NOTE: В случае удачи возвращаем его "тело"
					return $this->apply_template_for_single($this->local_data, $body);
				}
			}
			else
			{
				// NOTE: Разбиваем последний элемент если он содержит ELSE
				$xbody = preg_split('/\{__else\[' . $id . '\]__\}/', $body);
				$body_true = $xbody[0];
				$body_false = $xbody[1];

				if ($check)
				{
					// NOTE: В случае удачи возвращаем его "тело"
					return $this->apply_template_for_single($this->local_data, $body_true);
				}
				elseif ($body_false)
				{
					// NOTE: В случае неудачи возвращаем его "тело" после ELSE (если таковой присутствовал)
					return $this->apply_template_for_single($this->local_data, $body_false);
				}
			}
		}
	}
	
	private function _convert_params_to_array($str_params)
	{
		$arr_params = array();
		if ($x = preg_split('/[,;]/', $str_params))
		{
			foreach($x as $y)
			{
				// DEBUG: dbg::dump(strpos($y, '='), $y);
				if ($y AND strpos($y, '=') !== FALSE)
				{
					list($var, $val) = preg_split('/=/', $y);
					@eval('$arr_params[\'' . trim($var) . '\'] = ' . $val . ';');
				}
			}
		}
		
		return $arr_params;
	}
	
	/**
	*	Анализ шаблона вида {__apply(/path/to)__}
	* Вызов предопределённого или именнованого шаблона
	*
	* @param	array	$args	Результат работы регулярного выражения @see this::parse();
	*				[1] => Имя узла, обрабатываемого шаблоном, или параметры вызова
	* @see	this::parse()
	*/
	private function _parse_call_apply($args)
	{
		$params = $args['1'];
		if ($_data = $this->_parse_path($params))
		{
			$tmpl_name = preg_replace('/(\[[^\]]+\])/', '', $params);
			
			$template = (isset($this->templates[$tmpl_name])) ? $this->templates[$tmpl_name]['body'] : '';
			
			if (isset($_data[0]))
			{
				return $this->apply_template_for_multiple($_data, $template);
			}
			else
			{
				return $this->apply_template_for_single($_data, $template);
			}
		}

	}
	
	/**
	*	Анализ шаблона вида {__apply name(p1, p2, ..., pN)__}
	* Вызов именнованого шаблона
	*
	* @param	array	$args	Результат работы регулярного выражения @see this::parse();
	*				[1] => Имя шаблона
	* @see	this::parse()
	*/
	private function _parse_call_named_apply($args)
	{
		$name = $args['1'];
		$argr = $args['2'];
		if (isset($this->templates[$name]))
		{
			$template = $this->templates[$name]['body'];
			$argn = $this->templates[$name]['params'];
			$tmpl = $this->__create_tmpl_obj($template);
			$tmpl->tmpl_vars = $this->tmpl_vars;
			if (count($argn))
			{
				if (preg_match_all('/(?:\{|\s+|)(.+?)(?:\s+,|,|\})/', '{' . $argr . '}', $matches))
				{
					$argr = $matches[1];
					if (count($argr) <= count($argn))
					{
						foreach ($argn as $ind => $name)
						{
							// FIXED: 2007-02-21 Если получать значение при помощи _parse_path, то будет подставлено значение, вместо указателя.
							//$value = (isset($argr[$ind])) ? $this->_parse_path($argr[$ind]) : '';
							$value = (isset($argr[$ind])) ? $this->_parse_line($argr[$ind]) : '';
							if (!empty($value) AND !is_array($value)) @eval('$value = ' . $value . ';');
							$tmpl->tmpl_vars[$name] = $value;
						}
					}
					else
					{
						throw new Exception('В именнованном шаблон "' . $name . '" было передано больше аргументов, чем предопределено.');
						return FALSE;
					}
				}
				else
				{
					throw new Exception('В именнованном шаблоне "' . $name . '" не удаётся определить указатели на данные.');
					return FALSE;
				}
			}
			
			return $tmpl->parse($data);
		}
		else
		{
			throw new Exception('Именнованного шаблона "' . $name . '" не существует.');
			return FALSE;
		}
	}
	
	/**
	*	Анализ шаблона вида {__fckeditor name(p1)__}
	* Вызов именнованого шаблона
	*
	* @param	array	$args	Результат работы регулярного выражения @see this::parse();
	*				[1] => Указатель на имя поля.
	*				[2] => Указатель на данные.
	* @see	this::parse()
	*/
	private function _parse_fckeditor($args)
	{
		$field_name = $this->_parse_path($args['1']);
		$field_value = $this->_parse_path($args['2']);
		$oFCKeditor = new FCKeditor($field_name) ;
		$oFCKeditor->BasePath = 'fckeditor/';
		$oFCKeditor->Width = 640;
		$oFCKeditor->Height = 350;
		$oFCKeditor->Value = $field_value;
		return $oFCKeditor->CreateHtml();
	}
	
	/**
	*	Анализ шаблона вида {__echo(указатель)__}
	*
	* @param	string	$args	Результат работы регулярного выражения
	*				[1] => Указатель или выражение
	* @see	this::parse()
	*/
	private function _parse_echo($args)
	{
		$results = $this->_parse_line($args[1]);
		@eval('$results = ' . $results . ';');
		return $results;
	}
	
	/**
	*	Анализ шаблона вида {__php::<оператор>(<выражение>)__}
	*
	* @param	string	$args	Результат работы регулярного выражения
	*				[1] => Указатель или выражение
	* @see	this::parse()
	*/
	private function _parse_operator($args)
	{
		$params = $this->_parse_line($args[2]);
		if (function_exists($args[1]))
			@eval('$results = ' . $args[1] . '(' . $params . ');');
		else
			$results = 'error(`' . $args[1] . '` - NOT exists!)';
		return $results;
	}
	
	/**
	*	Анализ шаблона вида {__указатель__}
	*
	* @param	string	$args	Результат работы регулярного выражения
	*				[1] => Указатель
	* @see	this::parse()
	*/
	private function _parse_variable($args)
	{
		return $this->_parse_path($args[1], FALSE, FALSE);
	}
	
	/**
	*	Метод анализирует путь и возвращает соответствующий ему массив данных из исходного массива.
	*
	* @param	string	$_path	Указатель (путь) или значение (численное)
	*/
	private function _parse_path($_path, $_data = FALSE, $_empty = 'SELF')
	{
		if (!$_data) $_data = $this->local_data;
		$_path = trim($_path);
		
		if (
			($_path{0} == '"' AND $_path{strlen($_path) - 1} == '"')
			OR ($_path{0} == "'" AND $_path{strlen($_path) - 1} == "'")
			)
		{
			return $_path;
		}
		elseif (preg_match('/^(\d+)$/', $_path, $matches))
		{
			return $_path;
		}
		elseif (preg_match('/^(\w+)$/', $_path, $matches))
		{
			if (($value = $this->_get_value($_data, $matches[1])) !== FALSE)
			{
				return $value;
			}
			elseif ($_empty == 'SELF')
			{
				return $_path;
			}
			else
			{
				return $_empty;
			}
		}
		elseif (preg_match('/^\$(\w+)$/', $_path, $matches))
		{
			return $this->_get_value($_data, $matches[1]);
		}
		elseif (preg_match('/^\@(\w+)$/', $_path, $matches))
		{
			return $this->_get_value($this->tmpl_vars, $matches[1]);
		}
		elseif (preg_match('/^\#(\w+)$/', $_path, $matches))
		{
			return $this->_get_value($this->global_vars, $matches[1]);
		}
		elseif (preg_match_all('/(\/|\.|\#\w+|\w+)(\[[^\]]+\])?(?:\/)?/', $_path, $matches))
		{
			return $this->_parse_XPath($matches, $_data);
		}
		else
		// NOTE: Неизвестный указатель
		{
			return FALSE;
		}
	}
	
	/**
	*	Некий аналог XPath
	*
	* @param	array	$args	Результат работы регулярного выражния /((\.|\w+|)(\/|\[[^\]]+\]))/
	* @return	mixed	Значение результата поиска, или FALSE если указан неверный путь
	* @see	this::_parse_path()
	*/
	private function _parse_XPath($args, $_data = FALSE)
	{
		if (!$_data) $_data = $this->local_data;
		$nodes = $args[1];
		$params = $args[2];
		$data = $_data;
		foreach ($nodes as $key => $name)
		{
			if ($name == '/')
			{
				$data = $this->root_data;
			}
			elseif ($name == '.')
			{
			}
			elseif ($name AND $params[$key] == '')
			{
				$data = $this->_parse_path($name, $data, FALSE);
			}
			elseif ($name AND ($params[$key] != '/' OR $params[$key] != '')
				AND preg_match('/^\[((?:\$|\@|\#)\w+)\]$/', $params[$key], $matches)
				)
			{
				$key = $this->_parse_path($matches[1]);
				$data = $this->_parse_path($name, $data, FALSE);
				$data = $this->_get_value($data, $key);
			}
			elseif ($name AND ($params[$key] != '/' OR $params[$key] != '')
				AND preg_match('/^\[(\d+)\]$/', $params[$key], $matches)
				)
			{
				$data = $this->_parse_path($name, $data, FALSE);
				$data = $this->_get_value($data, $matches[1]);
			}
			elseif ($name AND ($params[$key] != '/' OR $params[$key] != '')
				AND ($matches = $this->_parse_XPath_get_conditions($params[$key]))
				)
			{
				if ($data = $this->_get_value($data, $name))
				{
					if (isset($data[0]))
					{
						$x = $data;
						$data = array();
						foreach ($x as $y)
						{
							if ($z = $this->_parse_XPath_check_conditions($matches, $y))
							{
								$data[] = $z;
							}
						}
					}
					else
					{
						$data = $this->_parse_XPath_check_conditions($matches, $data);
					}
				}
			}
			else
			{
				dbg::show('Не определённый узел указателя "' . $name . '"!');
			}
		}
		
		return $data;
	}
	
	private function _parse_XPath_get_conditions($pattern)
	{
		if (preg_match_all('/([^\s^=^\[]+)\s*([!=<>]+)\s*([^\s^=^\]]+)\s*(\w*)/', $pattern, $matches))
		{
			return $matches;
		}
		else
		{
			return FALSE;
		}
	}
	
	private function _parse_XPath_check_conditions($matches, $data = NULL)
	{
		if (!$data) $data = $this->local_data;
		$vall = array();
		$valr = array();
		$condition = '';
		$code = '';
		$check = NULL;
		foreach ($matches[1] as $k => $expr1)
		{
			$expr2 = $matches[3][$k];
			$vall[$k] = $this->_parse_path($expr1, $data, '0');

			// NOTE: При подстановке значений указателей правая часть берётся из родительской ветки
			$valr[$k] = $this->_parse_path($expr2, $this->local_data, '0');

			$condition.= '$vall[' . $k . ']' . $matches[2][$k] . '$valr[' . $k . ']';
			if ($matches[4][$k]) $condition.= ' ' . $matches[4][$k] . ' ';
		}
		
		if ($condition)
		{
			//main::debug($condition, NULL, 'tmp/tmpl2.log');
			$code = '$check = (' . $condition . ') ? TRUE : FALSE;';
			@eval($code);
			if ($check) return $data;
		}

		return FALSE;
	}
	
	/**
	*	Метод возвращает значение указателя
	*
	* @param	mixed	$data	Данные (массив или объект)
	* @param	string	$var	Имя указателя
	*/
	private function _get_value($data, $var)
	{
		if (defined($var))
		{
			return constant($var);
		}
		elseif (is_object($data) AND isset($data->$var))
		{
			return $data->$var;
		}
		elseif (is_array($data))
		{
			if (is_array($data) AND count($data) == 1 AND isset($data[0]) AND isset($data[0][$var]))
				return $data[0][$var];
			elseif (isset($data[$var]))
				return $data[$var];
		}
		
		return FALSE;
	}
	
	/**
	*	Производит поиск указателей в строке и заменяет на их значения
	*
	* @param	string	$str	Строка для обработки
	*/
	private function _parse_line($str)
	{
		// NOTE: Обнуляем набор переменных;
		$this->expr_vars = array();
		$pattern = '/((?:#|@|\$|\&|\/)+(?:\w+|\.)?(?:\[[^\]]+\])?';
		$pattern.= '(?:\/\w+(?:\[[^\]]+\])?)*)/';
		return preg_replace_callback($pattern, array(&$this, '_parse_line_part'), $str);
	}
	
	/**
	*	Заменяем указатели на их значения
	*
	* @param	array	$args	Результат работы регулярного выражения @see this::_parse_line();
	*				[1] => Указатель, или значение
	* @see	this::_parse_line()
	*/
	private function _parse_line_part($args)
	{
		$data = $this->_parse_path($args[1]);
		// NOTE: вносим значение в массив;
		$this->expr_vars[] = $data;
		// NOTE: получаем последний индекс массива
		$last_index = count($this->expr_vars) - 1;
		// NOTE: возвращаем указатель на значение
		return '$this->expr_vars[' . $last_index . ']';
	}
	
	/**
	*	Применяет шалон к одному элементу
	*
	* @param	mixed	$data		Данные
	* @param	string	$template	Шаблон
	*/
	private function apply_template_for_single($data, $template)
	{
		$tmpl = $this->__create_tmpl_obj($template);
		$tmpl->tmpl_vars = $this->tmpl_vars;
		return $tmpl->parse($data);
	}
	
	/**
	*	Применяет шалон к нескольким элементам
	*
	* @param	mixed	$data		Данные
	* @param	string	$template	Шаблон
	*/
	private function apply_template_for_multiple($data, $template)
	{
		$result = '';
		
		if (is_array($data) and !empty($data))
		{
			$n = 1;
			$last = count($data);
			foreach ($data as $tag)
			{
				$tmpl = $this->__create_tmpl_obj($template);
				$tmpl->tmpl_vars['position'] = $n;
				$tmpl->tmpl_vars['last'] = $last;
				$result.= $tmpl->parse($tag);
				$n++;
			}
		}
		
		return $result;
	}
	
	/**
	*	Метод предназначен для отслеживания времени потраченного на обработку шалона
	* @see	this::parse()
	*/
	private function getmicrotime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	private function __create_tmpl_obj($template, $type = 'text')
	{
		$tmpl = new tmpl($template, $type);
		$tmpl->set_index = FALSE;
		$tmpl->parse_templates = FALSE;
		$tmpl->tmpl_path =& $this->tmpl_path;
		$tmpl->global_vars =& $this->global_vars;
		$tmpl->root_data =& $this->root_data;
		$tmpl->templates =& $this->templates;
		$tmpl->loops_bodies =& $this->loops_bodies;
		
		return $tmpl;
	}
}
?>
