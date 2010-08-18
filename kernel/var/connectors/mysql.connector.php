<?php
/**
*	Коннектор к БД MySQL
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	0.5 beta
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-05-26
*/
class connector_mysql
{
	/**
	* @access	protected
	* @var	object	$di	Ссылка на интерфейс данных, для которого создается коннектор
	*/
	protected $di;
	
	/**
	* @access	public
	* @var	integer	$mysql_int_version	Весрия MySQL
	*/
	public $mysql_int_version;
	
	/**
	* @access	public
	* @var	string	$mysql_str_version	Весрия MySQL
	*/
	public $mysql_str_version;
	
	/**
	* @var	mixed	$dbh	Handler подключения к БД
	*/
	protected $dbh;
	
	/**
	* @access	private
	* @var	string	$_what		Поля для выборки
	*/
	private $_what;
	
	/**
	* @access	private
	* @var	string	$_fields	Поля для записи в БД
	*/
	private $_fields = array();
	
	/**
	* @access	private
	* @var	string	$_fields_values	Данные полей для записи в БД
	*/
	private $_fields_values = array();
	
	/**
	* @access	private
	* @var	string	$_from		Таблицы для выборки
	*/
	private $_from;
	
	/**
	* @access	private
	* @var	string	$_joins		Таблицы для выборки
	*/
	private $_joins = array();
	
	/**
	* @access	private
	* @var	array	$_dis		ИД для выборки
	*/
	private $_dis = array();
	
	/**
	* @access	private
	* @var	string	$_where		Условия для выборки
	*/
	private $_where;
	
	/**
	* @access	private
	* @var	string	$_where_values	Данные полей для выборки
	*/
	private $_where_values = array();
	
	/**
	* @access	private
	* @var	string	$_order		Сортировка выборки
	*/
	private $_order;
	
	/**
	* @access	private
	* @var	string	$_limit		Ограничение выборки
	*/
	private $_limit;
	
	/**
	* @access	public
	* @var	integer	$fetchMethod	Способ формирования результатов запроса
	*/
	public $fetchMethod = PDO::FETCH_OBJ;

	/**
	* @access	public
	* @var	boolean	$debug		Записать запрос в debug-файл
	*/
	public $debug = FALSE;
	
	public function __construct($di)
	{
		$this->di = $di;
		$this->_init();
	}
	
	function __destruct()
	{
		unset($this->dbh);
	}
	
	protected function _init()
	{
		try
		{
			$cfg = $this->di->get_cfg();
			$param = $cfg['type'];
			$param.= ':host=' . $cfg['host'];
			$param.= ';dbname=' . $this->di->get_db();
			$user = $cfg['user'];
			$pass = $cfg['pass'];
			$this->dbh = new PDO($param, $user, $pass, array(
				// SQL-ошибки отдавать как PDOException
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				));
			$this->get_version();
			$this->set_character_set($cfg['charset']);
		}
		catch(PDOException $e)
		{
			throw new Exception('Init error: '.$e->getMessage());
		}
	}
	
	/**
	*	Определить версию MySQL
	*/
	private function get_version()
	{
		if (!$this->mysql_int_version OR empty($this->mysql_int_version))
		{
			$sth = $this->dbh->query('SELECT VERSION() AS version');
			$row = $sth->fetchALL(PDO::FETCH_OBJ);
			$ver = $row[0]->version;
			
			if (empty($ver))
			{
				$this->mysql_int_version = 32332;
				$this->mysql_str_version = '3.23.32';
			}
			else
			{
				$match = explode('.', $ver);
				$this->mysql_int_version = (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2]));
				$this->mysql_str_version = $ver;
			}
		}
	}
	
	public function getLastQuery()
	{
		return $this->queries[count($this->queries) - 1];
	}
	
	/**
	*	Установить кодировку соединения
	*/
	private function set_character_set($character_set = CHARSET)
	{
		if ($this->mysql_int_version >= 40100)
			$this->query('SET NAMES ' . $character_set);
	}
	
	/**
	*	Выполнить пользовательский запрос
	*
	* @param	string	$sql	Запрос на языке SQL
	*/
	public function query($sql)
	{
		try
		{
			$this->dbh->query($sql);
			return TRUE;
		}
		catch(PDOException $e)
		{
			throw new Exception('Query error: '.$e->getMessage()."\nQuery: {$sql}");
		}
	}
	
	/**
	*	Выполнить запрос к БД с набором польз. данных
	* @param	string	$sql		Запрос на языке SQL
	* @param	array	$values		Массив данных
	* @param	boolean	$get_results	Вернуть результат запроса
	* @param	boolean	$users_fetch_style	Использовать fetch_style, заданный пользователем
	*/
	public function exec($sql, $values = array(), $get_results = false, $users_fetch_style = false)
	{
		try
		{
			$sth = $this->dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute($values);

			if ($this->debug)
			{
				dbg::write($sql);
				dbg::write($values);
			}

			$this->di->set_rowCount($sth->rowCount());

			if (($lid = $this->dbh->lastInsertId()) > 0)
				$this->di->set_lastChangedId($lid);

			$results = ($get_results) ? $sth->fetchALL(($users_fetch_style) ? $this->fetchMethod : PDO::FETCH_OBJ) : TRUE;
			if ($get_results)
				$this->di->set_results($results);

			unset($sth);
			return $results;
		}
		catch(PDOException $e)
		{
			throw new Exception('Exec error: '.$e->getMessage()."\nQuery: {$sql}\nValues: ".print_r($values, 1));
		}
	}
	
	/**
	*	Выполнить пользовательский запрос
	*
	* @param	string	$sql	Запрос на языке SQL
	*/
	public function _exec($sql)
	{
		try
		{
			$this->di->set_results(array());
			$this->queries[] = $sql;
			$this->dbh->query($sql);
		}
		catch(PDOException $e)
		{
			throw new Exception('Query error: '.$e->getMessage()."\nQuery: {$sql}");
		}
	}
	
	/**
	*	Сбросить все служебные значения
	*/
	public function _flush()
	{
		$this->_prepare_mode = '';
		$this->_what = '';
		$this->_from = '';
		$this->_joins = array();
		$this->_set_dis($this->di, true);
		$this->_where_values = array();
		$this->_where = '';
		$this->_fields = array();
		$this->_fields_values = array();
		$this->_order = '';
		$this->_limit = '';
	}
	
	/**
	*	Сбросить все служебные значения
	*/
	public function _reset()
	{
		$this->_prepare_mode = '';
		$this->_what = '';
		$this->_from = '';
		$this->_where_values = array();
		$this->_where = '';
		$this->_fields = array();
		$this->_fields_values = array();
	}
	
	/**
	*	Выполнить выборку из БД на основе условий указанных пользователем и внешних данных
	* @param	string	$sql	Запрос на языке SQL
	*/
	public function _get($sql = false)
	{
		$this->_reset();
		$this->di->set_results(array());
		
		if (!$sql)
		{
			$this->_prepare_get();
			$sql = "SELECT {$this->_what} {$this->_from} {$this->_where} {$this->_order} {$this->_limit}";
		}

		$results = $this->exec($sql, $this->_where_values, TRUE, TRUE);
		$this->di->set_results($results);
	}
	
	/**
	*	Объеденить ИД с другим ИД
	* @param	object	$by_di		Объединяющий ИД
	* @param	object	$with_di	Объединяемый ИД
	* @param	string	$on		Массив вида "левое_поле" => "правое_поле"
	* @param	array	$fields_alias	Массив синонимов полей при объединении (для $with_di)
	* @param	string	$join_type	Тип объединения, по умолчанию LEFT JOIN
	* @return	mixed	Возвращает уникальное имя связки, либо FALSE
	*/
	public function _join($by_di, $with_di, $on, $fields_alias, $join_type)
	{
		// Если тип объединения не задан, но устанавливаем по умолчанию LEFT
		if (empty($join_type))
			$join_type = 'LEFT';
		
		// Определяем уникальное имя объединения ИД
		$i = 0;
		$by_name = $by_di->get_name();
		$with_name = $with_di->get_name();
		$name = "{$by_name}_{$with_name}";
		while(in_array($name, $this->_joins))
		{
			$i++;
			$name = "{$by_name}_{$with_name}_{$i}";
		}
		$with_di->set_alias($name);
		
		// Получаем имя или синоним объединяющего ИД
		$by_alias = $by_di->get_alias();
		// Получаем имя или синоним объединяемог ИД
		$with_alias = $with_di->get_alias();
		
		// Подготавливаем ключи объединения
		$_on_ = array();
		foreach ($on as $by_field => $with_field)
		{
			$str = '';
			if ($by_di->field_exists($by_field))
				$str.= "`{$by_alias}`.`{$by_field}` = ";
			else
				$str.= "{$by_field} = ";

			if ($with_di->field_exists($with_field))
				$str.= "`{$with_alias}`.`{$with_field}`";
			else
				$str.= "{$with_field}";

			$_on_[] = $str;
		}
		
		// Определяем имя объединяемой БД
		$with_db = $with_di->get_db();
		// Формируем SQL-код объединения
		$this->_joins[$name] = "{$join_type} JOIN `{$with_db}`.`{$with_name}` AS `{$with_alias}`";
		$this->_joins[$name].= ' ON ' . join(' AND ', $_on_);
		
		// Запоминаем объединяемый ИД
		$this->_set_dis($with_di);
		
		// Запоминаем, если указаны, синонимы полей
		if (!empty($fields_alias))
			foreach ($fields_alias AS $field => $alias)
				$with_di->set_fields_join_alias($field, $alias);
		
		// Возвращаем объединяемый ИД
		return $with_di;
	}
	
	/**
	*	Добавить ИД в массив ИД для выборки
	*
	* @param	object	$di	ИД (объект)
	* @param	boolean	$reset	Если TRUE, то обнуляется массив ИД. По умолчанию FALSE
	*/
	private function _set_dis($di, $reset = false)
	{
		if ($reset)
			$this->_dis = array();
		
		$di_name = $di->get_alias();
		
		if (!array_key_exists($di_name, $this->_dis))
			$this->_dis[$di_name] = $di;
	}
	
	/**
	*	Получить массив ИД используемых в выборке
	*
	* @param	string	$name	Имя ИД
	* @return	boolean|object|array	Если задан $name и он есть в массиве $this->_dis, то объект ИД, иначе FALSE
	*					Если не задан $name, то массив $this->_dis
	*/
	private function _get_dis($name = false)
	{
		if ($name)
			if (array_key_exists($name, $this->_dis))
				return $this->_dis[$name];
			else
				return false;
		else
			return $this->_dis;
	}
	
	/**
	*	Автоматическое формирование SQL для выборки данных из БД
	*/
	private function _prepare_get()
	{
		$this->_prepare_mode = $this->di->get_mode();
		$this->_prepare_what();
		$this->_prepare_from();
		$this->_prepare_where();
	}
	
	/**
	*	Подготовить поля для выгрузки
	* @access	private
	*/
	private function _prepare_what()
	{
		$set = array();
		// NOTE: Если не заполнено поле `what`, то выбираем все поля описанные в поле `fields`
		$fields = (!empty($this->di->what)) ? $this->di->what : array_keys($this->di->fields);
		$name = $this->di->get_name();
		
		if (is_array($fields))
		{
			foreach ($fields as $key => $field)
			{
				if (!preg_match('/^\d+$/', $key))
				{
					$alias = $field;
					$field = $key;
				}
				else
				{
					$alias = false;
				}
				
				if (is_array($field))
				{
					if ($field['alias'])
						$field['di']->set_fields_join_alias($field['name'], $field['alias']);
					
					$di_name = $field['di']->get_alias();
					$alias = $field['di']->get_fields_join_alias($field['name']);
					$field = "`{$di_name}`.`{$field['name']}`";
				}
				else if (preg_match('/^\w+$/', $field))
				{
					$field = "`{$name}`.`{$field}`";
				}
				else if ($field == '*')
				{
					$field = "`{$name}`.*";
				}
				
				$set[] = $field;
				
				if ($alias !== false && !empty($alias))
					$set[] = array_pop($set) . " AS `{$alias}`";
			}
			
			if (count($set) > 0)
				$this->_what  = join(', ', $set);
			else
				throw new Exception('Не указаны поля для выборки.');
		}
		else
		{
			$this->_what = $fields;
		}
	}
	
	/**
	*	Подготовить таблицы для выгрузки
	* @access	private
	*/
	private function _prepare_from()
	{
		switch($this->_prepare_mode)
		{
		case 'NESTED_SETS_SLICE':
			$tbl1 = $this->di->get_name();
			$tbl2 = $tbl1.'_parent';
			$this->_from = "FROM `{$tbl1}` AS `{$tbl1}` ";
			$this->_from.= "LEFT JOIN `{$tbl1}` AS `{$tbl2}` ON {$tbl2}.left < {$tbl1}.left AND {$tbl2}.right > {$tbl1}.right";
		break;
		case  'STANDARD':
		default:
			$this->_from = "FROM `" . $this->di->get_name() . "`";
			if (!empty($this->_joins))
				$this->_from.= ' ' . join("\n ", $this->_joins);
		}
	}
	
	/**
	*	Сформировать условие по входящим параметрам
	* Параметры должны иметь префикс `_s` или `_n`
	* Также условие может быть дополнено пользовательским из поля this::where и значениями this::where_values
	*/
	private function _prepare_where()
	{
		if ($this->di->NOT_prepare_where) return;
		
		$set = array();
		// NOTE: Если установлено пользовательское условие выборки
		if (!empty($this->di->where))
		{
			$this->_where_values = $this->where_values;
			$set[] = '(' . trim($this->di->where) . ')';
		}
		
		$this->_args = $this->di->get_args();
		$dis = $this->_get_dis();
		
		// NOTE: Перебираем все ИД используемые в выборке
		foreach ($dis as $di_name => $di)
		{
			foreach ($di->fields as $field => $params)
			{
				if ($this->di == $di)
					$cond = $this->_prepare_where_field($di, $field, $params);
				else if ($join_alias = $di->get_fields_join_alias($field))
					$cond = $this->_prepare_where_field($di, $field, $params, true, $join_alias);
				
				if (!empty($cond)) $set[] = $cond;
			}
		}
		
		if (!empty($set))
			$this->_where = sprintf('WHERE %s', join(' AND ', array_unique($set)));
	}
	
	/**
	*	Подготовить данные для условия выборки
	*
	* @param	object	$di		Ссылка на объект ИД
	* @param	string	$field		Имя поля
	* @param	array	$params		Массив параметров поля
	* @param	boolean	$byAlias	Поиск по синониму поля, если TRUE, по умолчанию FALSE
	* @param	string	$alias		Предопределённый синоним, по умолчанию NULL
	*/
	private function _prepare_where_field($di, $field, $params, $byAlias = false, $alias = NULL)
	{
		if ($params['protected']) return FALSE;
		$sField = ($byAlias) ? $params['alias'] : $field;
		if ($byAlias && !empty($alias)) $sField = $alias;
		
		$str = '';
		$name = $di->get_alias();
		switch($params['type'])
		{
			case 'integer':
				if (isset($this->_args['_s'.$sField]))
				{
					if ($this->_prepare_mode == 'NESTED_SETS_SLICE' && $params['serial'])
					{
						$str = "{$name}_parent.{$field} = :_s{$sField}";
						$this->_where_values['_s'.$sField] = intval($this->_args['_s'.$sField]);
					}
					else if (is_array($this->_args['_s'.$sField]))
					{
						$str = "{$name}.{$field} IN (" . join(", ", array_map('intval', $this->_args['_s'.$sField])) . ")";
					}
					else if ('null' != strtolower($this->_args['_s'.$sField]))
					{
						$str = "{$name}.{$field} = :_s{$sField}";
						$this->_where_values['_s'.$sField] = intval($this->_args['_s'.$sField]);
					}
					else
					{
						$str = "{$name}.{$field} IS NULL";
					}
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if ('null' != strtolower($this->_args['_n'.$sField]))
					{
						$str = "{$name}.{$field} <> :_n{$sField}";
						$this->_where_values['_n'.$sField] = intval($this->_args['_n'.$sField]);
					}
					else
					{
						$str = "{$name}.{$field} IS NOT NULL";
					}
				}
				else if (isset($this->_args['_m'.$sField]))
				{
					$str = "{$name}.{$field} > :_n{$sField}";
					$this->_where_values['_m'.$sField] = intval($this->_args['_n'.$sField]);
				}
				else if (isset($this->_args['_l'.$sField]))
				{
					$str = "{$name}.{$field} < :_l{$sField}";
					$this->_where_values['_l'.$sField] = intval($this->_args['_n'.$sField]);
				}
			break;
			case 'date':
				if (isset($this->_args['_s'.$sField]))
				{
					$str = "{$name}.{$field} = :_s{$sField}";
					$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					$str = "{$name}.{$field} <> :_n{$sField}";
					$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField]))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = $this->_args['_sFrom'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField.'_day']))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = sprintf('%04d-%02d-%02d', $this->_args['_sFrom'.$sField.'_year'], $this->_args['_sFrom'.$sField.'_month'], $this->_args['_sFrom'.$sField.'_day']);
				}
				else if (isset($this->_args['_sTo'.$sField]))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = $this->_args['_sTo'.$sField];
				}
				else if (isset($this->_args['_sTo'.$sField.'_day']))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = sprintf('%04d-%02d-%02d', $this->_args['_sTo'.$sField.'_year'], $this->_args['_sTo'.$sField.'_month'], $this->_args['_sTo'.$sField.'_day']);
				}
			break;
			case 'time':
				if (isset($this->_args['_s'.$sField]))
				{
					$str = "{$name}.{$field} = :_s{$sField}";
					$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					$str = "{$name}.{$field} <> :_n{$sField}";
					$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField]))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = $this->_args['_sFrom'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField.'_hour']))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = sprintf('H:i:s', $this->_args['_sFrom'.$sField.'_hour'], $this->_args['_sFrom'.$sField.'_min'], $this->_args['_sFrom'.$sField.'_sec']);
				}
				else if (isset($this->_args['_sTo'.$sField]))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = $this->_args['_sTo'.$sField];
				}
				else if (isset($this->_args['_sTo'.$sField.'_hour']))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = sprintf('H:i:s', $this->_args['_sTo'.$sField.'_hour'], $this->_args['_sTo'.$sField.'_min'], $this->_args['_sTo'.$sField.'_sec']);
				}
			break;
			case 'datetime':
				if (isset($this->_args['_s'.$sField]))
				{
					$str = "{$name}.{$field} = :_s{$sField}";
					$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					$str = "{$name}.{$field} <> :_n{$sField}";
					$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField]))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = $this->_args['_sFrom'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField.'_day']))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = sprintf('%04d-%02d-%02d H:i:s', $this->_args['_sFrom'.$sField.'_year'], $this->_args['_sFrom'.$sField.'_month'], $this->_args['_sFrom'.$sField.'_day'], $this->_args['_sFrom'.$sField.'_hour'], $this->_args['_sFrom'.$sField.'_min'], $this->_args['_sFrom'.$sField.'_sec']);
				}
				else if (isset($this->_args['_sTo'.$sField]))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = $this->_args['_sTo'.$sField];
				}
				else if (isset($this->_args['_sTo'.$sField.'_day']))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = sprintf('%04d-%02d-%02d H:i:s', $this->_args['_sTo'.$sField.'_year'], $this->_args['_sTo'.$sField.'_month'], $this->_args['_sTo'.$sField.'_day'], $this->_args['_sTo'.$sField.'_hour'], $this->_args['_sTo'.$sField.'_min'], $this->_args['_sTo'.$sField.'_sec']);
				}
			break;
			case 'string': case 'text':
				if (isset($this->_args['_s'.$sField]))
				{
					if ('null' != strtolower($this->_args['_s'.$sField]))
					{
						$str = "{$name}.{$field} LIKE :_s{$sField}";
						$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
					}
					else
					{
						$str = "{$name}.{$field} IS NULL";
					}
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if ('null' != strtolower($this->_args['_n'.$sField]))
					{
						$str = "{$name}.{$field} NOT LIKE :_n{$sField}";
						$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
					}
					else
					{
						$str = "{$name}.{$field} IS NOT NULL";
					}
				}
			break;
			default:
				if (isset($this->_args['_s'.$sField]))
				{
					if ('null' != strtolower($this->_args['_s'.$sField]))
					{
						$str = "{$name}.{$field} = :_s{$sField}";
						$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
					}
					else
					{
						$str = "{$name}.{$field} IS NULL";
					}
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if ('null' != strtolower($this->_args['_n'.$sField]))
					{
						$str = "{$name}.{$field} <> :_n{$sField}";
						$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
					}
					else
					{
						$str = "{$name}.{$field} IS NOT NULL";
					}
				}
			break;
		}
		
		// NOTE: Если значение по имени поля не было найдено, и есть alias то ищем по нему
		if (!empty($params['alias']) && empty($str) && $sField != $params['alias'])
			return $this->_prepare_where_field($di, $field, $params, true);
		else
			return $str;
	}
	
	/**
	*	Установить сортировку выборки
	* @param	string	$field	Имя поля
	* @param	string	$dir	Направление сортировки ASC или DESC
	*/
	public function set_order($field, $dir = 'ASC')
	{
		$dir = strtoupper($dir);
		if (!empty($field) && preg_match('/^\w+$/', $field)
			&& ($dir == 'ASC' OR $dir == 'DESC'))
			$this->_order = 'ORDER BY '.$this->di->get_name().'.'.$field.' '.$dir;
	}
	
	/**
	*	Установить ограничение выборки
	* @param	integer	$start	Начало
	* @param	integer	$limit	Смещение
	*/
	public function set_limitation($start, $limit)
	{
		if ($limit > 0 && $start == 0)
			$this->_limit = 'LIMIT '.intval($limit);
		else if ($limit > 0 && $start > 0)
			$this->_limit = 'LIMIT '.intval($start).', '.intval($limit);
	}
	
	/**
	*	Сохранить данные
	* @access	public
	*/
	public function _set()
	{
		$this->_reset();
		$this->_prepare_mode = $this->di->get_mode();
		$this->_from = '`' . $this->di->get_name() . '`';
		$this->_prepare_set();
		
		if (empty($this->_where))
		// NOTE: Не указано никаких условий, добавляем запись
		{
			$mode = 'ins';
			$sql = $this->_prepare_insert();
			$values = $this->_fields_values;
		}
		else
		{
			// NOTE: Считаем сколько записей можно найти по такому условию
			$sql = "SELECT COUNT(*) AS `total` FROM {$this->_from} {$this->_where} {$this->_order} {$this->_limit}";
			$count = array_pop($this->exec($sql, $this->_where_values, TRUE));
			
			if ($count->total > 0)
			// NOTE: Найдены записи по данным условиям, поэтому обновляем их
			{
				$mode = 'upd';
				$sql = $this->_prepare_update();
				$values = array_merge($this->_where_values, $this->_fields_values);
			}
			else if ($this->di->insert_on_empty == true)
			// NOTE: Записей по данным условиям не найдено, поэтому добавляем их, согласно флагу
			{
				$mode = 'ins';
				$sql = $this->_prepare_insert();
				$values = $this->_fields_values;
			}
			else
			// NOTE: Нечего обновлять
			{
				return;
			}
		}
		
		$this->exec($sql, $values);
		
		if ($mode == 'ins')
			$this->di->set_lastChangedId($this->dbh->lastInsertId());
		else if ($mode == 'upd')
			if (($ids = $this->_get_changed_id()) !== false)
				$this->di->set_lastChangedId($ids);
	}
	
	/**
	*	Определить список ID затрагиваемых условиями
	*/
	private function _get_changed_id()
	{
		if (($sf = $this->di->get_serial()) === false)
			return false;
		
		$sql = "SELECT `{$sf}` FROM {$this->_from} {$this->_where} {$this->_order} {$this->_limit}";
		$recs = $this->exec($sql, $this->_where_values, TRUE);
		
		if (count($recs) > 1)
		{
			$ids = array();
			foreach ($recs as $rec)
				$ids[] = $rec->$sf;
		}
		else
		{
			$ids = $recs[0]->$sf;
		}
		
		return $ids;
	}
	
	/**
	*	Подготовить SQL вида INSERT
	*/
	private function _prepare_insert()
	{
		$name = '`' . $this->di->get_name() . '`';
		$fields = array();
		$values = array();
		foreach ($this->_fields AS $fld)
		{
			if (!is_array($fld))
			{
				$fields[] = "{$name}.{$fld}";
				$values[] = ":$fld";
			}
			else
			{
				$fields[] = "{$name}.{$fld['name']}";
				$values[] = $fld['function'];
			}
		}
		$q = "INSERT INTO {$this->_from} (" . join(", ", $fields) . ")  VALUES (" . join(', ', $values) . ")";
		return  $q;
	}
	
	/**
	*	Подготовить SQL вида UPDATE
	*/
	private function _prepare_update()
	{
		$name = '`' . $this->di->get_name() . '`';
		$set = array();
		foreach ($this->_fields AS $fld)
			if (!is_array($fld))
				$set[] = "{$name}.{$fld} = :{$fld}";
			else
				$set[] = "{$name}.{$fld['name']} = {$fld['function']}";
		$q = "UPDATE {$this->_from} SET ".join(', ', $set)." {$this->_where} {$this->_order} {$this->_limit}";
		return $q;
	}
	
	/**
	*	Подготовить SQL для сохранения данных
	* @todo	Автоматическое формирование SQL для сохранения данных в БД
	*/
	private function _prepare_set()
	{
		$this->_prepare_data();
		$this->_prepare_where();
		if (empty($this->_fields))
			throw new Exception('Undefined fields for set.');
	}
	
	/**
	*	Подготовить данные для сохранения в БД
	*/
	private function _prepare_data()
	{
		$set = array();
		// NOTE: Если установлено пользовательское условие выборки
		$this->_args = $this->di->get_args();
		// NOTE: Перебираем все описанные в ID поля и ищем значения во входящих параметрах
		foreach ($this->di->fields as $key => $value)
		{
			$cond = $this->_prepare_data_field($key, $value);
			if (!empty($cond)) $set[] = $cond;
		}
	}
	
	/**
	*	Подготовить данные для записи в БД
	*/
	private function _prepare_data_field($field, $params, $byAlias = false)
	{
		if ($params['readonly'] === TRUE) return '';
		
		$sField = ($byAlias) ? $params['alias'] : $field;
		$str = '';
		
		switch($params['type'])
		{
			case 'fkey':
			case 'serial':
				if (intval($this->_args[$sField]) == 0) continue;
			case 'integer':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = intval($this->_args[$sField]);
				}
			break;
			case 'double':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = floatval($this->_args[$sField]);
				}
			break;
			case 'date':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = $this->_args[$sField];
				}
				else if (isset($this->_args[$sField.'_day']))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = sprintf('%04d-%02d-%02d', $this->_args[$sField.'_year'], $this->_args[$sField.'_month'], $this->_args[$sField.'_day']);
				}
			break;
			case 'time':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = $this->_args[$sField];
				}
				else if (isset($this->_args[$sField.'_hour']))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = sprintf('H:i:s', $this->_args[$sField.'_hour'], $this->_args[$sField.'_min'], $this->_args[$sField.'_sec']);
				}
			break;
			case 'datetime':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = $this->_args[$sField];
				}
				else if (isset($this->_args[$sField.'_day']))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = sprintf('%04d-%02d-%02d H:i:s', $this->_args[$sField.'_year'], $this->_args[$sField.'_month'], $this->_args[$sField.'_day'], $this->_args[$sField.'_hour'], $this->_args[$sField.'_min'], $this->_args[$sField.'_sec']);
				}
			break;
			case 'password':
				if (!empty($this->_args[$sField]))
				{
					#$this->_fields[] = $field;
					$this->_fields[] = array('name' => $field, 'function' => "PASSWORD(:{$field})");
					$this->_fields_values[$field] = $this->_args[$sField];
				}
			break;
			default:
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = stripslashes($this->_args[$sField]);
				}
			break;
		}
		
		// NOTE: Если значение по имени поля не было найдено, и есть alias то ищем по нему
		if (!empty($params['alias']) && empty($str) && $sField != $params['alias'])
			return $this->_prepare_data_field($field, $params, true);
		else
			return $str;
	}
	
	/**
	*	Удалить данные
	*/
	public function _unset()
	{
		$this->_reset();
		$this->_from = '`' . $this->di->get_name() . '`';
		$this->_prepare_unset();
		
		// Определить ID записей, которые будут затронуты удалением
		if (($ids = $this->_get_changed_id()) !== false)
			$this->di->set_lastChangedId($ids);
		
		$sql = "DELETE FROM {$this->_from} {$this->_where} {$this->_order} {$this->_limit}";
		$this->exec($sql, $this->_where_values);
	}
	
	/**
	*	Подготовить SQL для удаления данных
	* @todo	Автоматическое формирование SQL для удаления данных из БД
	*/
	private function _prepare_unset()
	{
		$this->_prepare_where();
	}
	
	/**
	*	Проверить, существует ли такая таблица
	* @access	public
	*/
	public function _exists()
	{
		$sql = 'SHOW TABLE LIKE "' . $this->di->get_name() . '"';
		$count = $this->exec($sql, array(), TRUE);
		return (boolean)(count($count) > 0);
	}
	
	/**
	*	Сбросить таблицу в исходное состояние
	* @access	public
	*/
	public function _clear()
	{
		$this->query('TRUNCATE `' . $this->di->get_name() . '`');
	}
}
?>
