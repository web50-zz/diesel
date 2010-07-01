<?php
/**
*	Интерфейс данных "Папки с файлами"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @package	CFsCMS2(PE)
*/
class di_fm_folders extends data_interface
{
	public $title = 'Папки с файлами';
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
	protected $name = 'fm_folders';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => 1, 'readonly' => 1),
			'title' => array('type' => 'string'),
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
                $fields = array('id', 'title' => 'text');
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
	protected function sys_item()
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
	
	/**
	*	Переместить узел
	* @access protected
	*/
	protected function sys_move()
	{
		$id = intval($this->args['id']);
		$pid = intval($this->args['pid']);

		if ($id > 0)
		{
			$ns = new nested_sets($this);
			if ($ns->move_node($id, $pid))
			{
				$data = array(
					'success' => true
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
}
?>
