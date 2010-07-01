<?php
/**
*	Интерфейс данных "Структура сайта"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @package	CFsCMS2(PE)
*/
class di_structure extends data_interface
{
	public $title = 'Структура сайта';
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
	protected $name = 'structure';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => 1, 'readonly' => 1),
			'hidden' => array('type' => 'boolean'),
			'title' => array('type' => 'string'),
			'name' => array('type' => 'string'),
			'uri' => array('type' => 'string'),
			'redirect' => array('type' => 'string'),
			'module' => array('type' => 'string'),
			'params' => array('type' => 'string'),
			'template' => array('type' => 'string'),
			'private' => array('type' => 'boolean'),
			'auth_module' => array('type' => 'string'),
			'left' => array('type' => 'integer', 'protected' => 1),
			'right' => array('type' => 'integer', 'protected' => 1),
			'level' => array('type' => 'integer', 'readonly' => 1),
			'mtitle' => array('type' => 'string'),
			'mkeywords' => array('type' => 'string'),
			'mdescr' => array('type' => 'string')
		);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}
	
	public function get_page_by_uri($uri)
	{
		$sql = 'SELECT * FROM `' . $this->name . '` WHERE `uri` = :uri';
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$result = $this->connector->exec($sql, array('uri' => $uri), true, true);
		if (empty($result))
		{
			$sql = 'SELECT * FROM `' . $this->name . '` WHERE `id` = :id';
			$this->connector->fetchMethod = PDO::FETCH_ASSOC;
			$result = $this->connector->exec($sql, array('id' => 1), true, true);
		}
		return $result[0];
	}
	
	public function get_main_menu()
	{
		$this->where = '`sp1`.`hidden` = 0';
		$ns = new nested_sets($this);
		$branch = $ns->get_childs(1, 1);
		return $branch;
	}
	
	public function get_sub_menu()
	{
		$this->where = '`sp1`.`hidden` = 0';
		$ns = new nested_sets($this);
		$data['root'] = $ns->get_parent(PAGE_ID, 2);
		$data['page'] = $ns->get_node(PAGE_ID);
		if (empty($data['root'])) $data['root'] = $data['page'];
		$data['childs'] = $ns->get_childs($data['root']['id'], NULL);
		return $data;
	}
	
	public function get_trunc_menu()
	{
		$ns = new nested_sets($this);
		return $ns->get_parents(PAGE_ID, true);
	}
	
	/**
	*	Добавить узел
	* @access protected
	*/
	protected function sys_set()
	{
		if ($this->args['_sid'] > 0)
		{
			$uri = $this->get_args('uri');
			$this->calc_uri();
			
			$this->_flush();
			$this->insert_on_empty = false;
			$data = $this->extjs_set_json(false);
			$data['data']['uri'] = $this->get_args('uri');
			
			if ($data['data']['uri'] != $uri)
				$this->recalc_uri($this->args['_sid']);
		}
		else if($this->args['pid'] > 0)
		{
			$ns = new nested_sets($this);
			unset($this->args['_sid']); // Иначе будет пытаться обновить нулевую ноду
			
			if ($ns->add_node($this->args['pid']))
			{
				$this->args['_sid'] = $this->get_lastChangedId(0);
				$this->calc_uri();
				
				$this->_flush();
				$this->insert_on_empty = false;
				$data = $this->extjs_set_json(false);
				$data['data']['uri'] = $this->args['uri'];
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
	*	Расчитать URI страницы
	*/
	private function calc_uri()
	{
		$ns = new nested_sets($this);
		
		if ($this->args['_sid'] > 0)
			$parents = $ns->get_parents($this->args['_sid']);
		else
			return FALSE;
		
		$uri = '/';
		foreach ($parents as $parent)
			if ($parent['id'] > 1)
				$uri.= (($parent['name']) ? $parent['name'] : 'p' . $parent['id']) . '/';
		$uri.= (($this->args['name']) ? $this->args['name'] : 'p' . $this->args['_sid']) . '/';
		
		$this->set_args(array('uri' => $uri), true);
		return TRUE;
	}
	
	/**
	*	Пересчитать URI всех потомков
	*/
	private function recalc_uri()
	{
		$ns = new nested_sets($this);
		
		if ($this->args['_sid'] > 0)
			$childs = $ns->get_childs($this->args['_sid']);
		else
			return FALSE;
		
		$this->insert_on_empty = false;
		
		foreach ($childs AS $child)
		{
			$this->set_args(array(
				'_sid' => $child['id'],
				'name' => $child['name']));
			
			if ($this->calc_uri() !== FALSE)
				$this->extjs_set_json(false);
		}
		
		return TRUE;
	}
	
	/**
	*	Переместить узел
	* @access protected
	*/
	protected function sys_move()
	{
		$id = intval($this->args['_sid']);
		$pid = intval($this->args['pid']);
		if ($id > 0)
		{
			$ns = new nested_sets($this);
			if ($ns->move_node($id, $pid))
			{
				$node = $ns->get_node($id);
				$this->args['name'] = $node['name'];
				$this->calc_uri();
				$this->insert_on_empty = false;
				$data = $this->extjs_set_json(false);
				
				$this->recalc_uri();
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
		$this->unset_recursively($id);

		$ns = new nested_sets($this);
		if ($id > 0 && $ns->delete_node($id))
			$data = array('success' => true);
		else
			$data = array('success' => false);
		response::send($data, 'json');
	}

	/**
	*	Удалить рекурсивно все связи
	* @access protected
	*/
	protected function unset_recursively($id)
	{
		$sc = data_interface::get_instance('structure_content');
		$ns = new nested_sets($this);

		$childs = $ns->get_childs($id);
		$ids = array($id);
		foreach ($childs as $child)
			$ids[] = $child['id'];

		foreach ($ids as $pid)
			$sc->remove_by_page($pid);
	}
	
	/**
	*	Получить XML-пакет данных для ExtJS-формы
	* @access protected
	*/
	protected function sys_item()
	{
		//$this->extjs_form_xml();
		$this->extjs_form_json();
	}
	
	/**
	*	Получить XML-пакет данных для ExtJS-формы
	* @access protected
	*/
	protected function sys_page()
	{
		$this->extjs_form_json();
	}
	
	/**
	*	Получить JSON-пакет данных для ExtJS-дерева
	* @access protected
	*/
	protected function sys_slice()
	{
                $pid = intval($this->args['node']);
                $fields = array('id', 'title' => 'text', 'module' => 'ui');
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
}
?>
