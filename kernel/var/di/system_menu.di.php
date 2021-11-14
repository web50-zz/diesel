<?php
/**
*	Интерфейс данных "Ситсемное меню"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @package	SBIN Diesel
*/
class di_system_menu extends data_interface
{
	public $title = 'Системное меню';
	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'system_menu';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => 1, 'readonly' => 1),
			'type' => array('type' => 'integer'),
			'text' => array('type' => 'string'),
			'icon' => array('type' => 'string'),
			'ui' => array('type' => 'string'),
			'ep' => array('type' => 'string'),
			'href' => array('type' => 'string'),
			'left' => array('type' => 'integer', 'protected' => 1),
			'right' => array('type' => 'integer', 'protected' => 1),
			'level' => array('type' => 'integer', 'readonly' => 1),
		);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}
	
	/**
	*	Получить JSON-пакет данных для ExtJS-дерева
	* @access protected
	*/
	protected function sys_slice()
	{
                $pid = intval($this->args['node']);
                $fields = array('id', 'text', 'icon' => 'iconCls');
		$this->mode = 'NESTED_SETS_SLICE';
                if ($pid > 0)
                {
                        $this->set_args(array('_sid' => $pid));
                        $this->extjs_slice_json($fields, 1);
                }
                else
                {
                        $this->set_args(array('_slevel' => 1));
                        $this->extjs_slice_json($fields);
                }
	}
	
	/**
	*	Получить JSON-пакет данных для ExtJS-формы
	* @access protected
	*/
	protected function sys_get()
	{
		$this->extjs_form_json();
	}
	
	/**
	*	Добавить узел
	* @access protected
	*/
	protected function sys_set()
	{
		if ($this->args['_sid'] > 0)
		{
			$this->_flush();
			$this->insert_on_empty = false;
			$data = $this->extjs_set_json(false);
		}
		else if($this->args['pid'] > 0)
		{
			$ns = new nested_sets($this);
			unset($this->args['_sid']); // Иначе будет пытаться обновить нулевую ноду
			
			if ($ns->add_node($this->args['pid']))
			{
				$this->args['_sid'] = $this->get_lastChangedId(0);
				$data = array(
					'success' => true,
					'data' => array(
						'id' =>  $this->get_lastChangedId(0)
					));
			}
			else
			{
				$data = array(
					'success' => false,
					'errors' =>  $e->getMessage()
					);
			}
		}
		
		response::send($data, 'json');
	}
	
	public function node_set()
	{
		if ($this->args['_sid'] > 0)
		{
			$this->_flush();
			$this->insert_on_empty = false;
			$data = $this->extjs_set_json(false);
		}
		else if($this->args['pid'] > 0)
		{
			$ns = new nested_sets($this);
			unset($this->args['_sid']); // Иначе будет пытаться обновить нулевую ноду
			
			if ($ns->add_node($this->args['pid']))
			{
				$this->args['_sid'] = $this->get_lastChangedId(0);
				$data = array(
					'success' => true,
					'data' => array(
						'id' =>  $this->get_lastChangedId(0)
					));
			}
			else
			{
				$data = array(
					'success' => false,
					'errors' =>  $e->getMessage()
					);
			}
		}
		return $data;
	}

	/**
	*	Переместить узел
	* @access protected
	*/
	protected function sys_move()
	{
		$id = intval($this->get_args('_sid'));
		$pid = intval($this->get_args('pid'));
		$ind = intval($this->get_args('ind'));

		if ($id > 0)
		{
			$ns = new nested_sets($this);

			if ($ns->move_node($id, $pid, $ind))
			{
				$data = array(
					'success' => true,
					'message' => 'Moved'
					);
			}
			else
			{
				$data = array(
					'success' => false,
					'error' => 'Не удалось переместить папку'
					);
			}
		}
		else
		{
			$data = array(
				'success' => true
				);
		}
		response::send($data, 'json');
	}
	
	/**
	*	Удалить узел
	* @access protected
	*/
	protected function sys_unset()
	{
		$id = intval($this->args['_sid']);

		$ns = new nested_sets($this);
		if ($id > 0 && $ns->delete_node($id))
			$data = array('success' => true);
		else
			$data = array('success' => false);
		response::send($data, 'json');
	}

	/**
	*	Сгенерировать меню.
	* @access public
	*/
	public function generate_menu()
	{
		$this->_flush();
		$this->set_order('left');
		$this->_get();
		$results = $this->get_results();
		$root = $this->find_by_id($results, 1);
		return $this->process_slice($results, $root->left, $root->right, $root->level + 1);
	}

	/**
	*	Формирование ветки меню
	* @param	array	$results	Массив записей с пунктами меню
	* @param	integer	$left		LEFT индекс
	* @param	integer	$right		RIGHT индекс
	* @param	integer	$level		Уровень вложенности
	*/
	private function process_slice($results, $left, $right, $level)
	{
		$slice = array();

		foreach ($results as $record)
		{
			if ($record->left > $left && $record->right < $right && $record->level == $level)
			{
				if(defined('CURRENT_USER')) // не понмю зачем это было сделано все
				{
					if($record->text == CURRENT_USER)
					{
						$di =  data_interface::get_instance('user');
						$usr = $di->get_user();
						$record->text = $usr['name'].' ('.UID.')';
					}
				}

				if ($record->left + 1 < $record->right)
				{
					$slice[] = array('text' => $record->text, 'icon' => $record->icon, 'menu' => $this->process_slice($results, $record->left, $record->right, $record->level + 1));
				}
				else if ($record->type == 0 && !empty($record->ui) && !empty($record->ep))
				{
					$slice[] = array('text' => $record->text, 'icon' => $record->icon, 'ui' => $record->ui, 'ep' => $record->ep);
				}
				else if ($record->type == 0 && !empty($record->href))
				{
					$slice[] = array('text' => $record->text, 'icon' => $record->icon, 'href' => $record->href);
				}
				else if ($record->type == 1)
				{
					$slice[] = array($record->text);
				}
			}
		}

		return $slice;
	}

	/**
	*	Получить запись по ID
	* @access	private
	* @param	array	$results	Массив записей
	* @param	integer	$id		ID записи
	* @return	mixed	return record or FALSE
	*/
	private function find_by_id($results, $id)
	{
		foreach ($results as $record)
			if ($record->id == $id)
				return $record;
		return false;
	}
}
?>
