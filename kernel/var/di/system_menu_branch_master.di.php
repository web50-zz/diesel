<?php
/**
*
* @author	9* Fedot B Pozdnyakov <9@u9.ru>21062013
* @package	SBIN Diesel
*/
class di_system_menu_branch_master extends data_interface
{
	public $title = 'Site system_menu: бранч мастер ';

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
	protected $name = 'system_menu_branch_master';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'created_datetime' => array('type' => 'datetime'),
		'creator_uid' => array('type' => 'integer'),
		'changed_datetime' => array('type' => 'datetime'),
		'changer_uid' => array('type' => 'integer'),
		'deleted_datetime' => array('type' => 'datetime'),
		'deleter_uid' => array('type' => 'integer'),
		'title'=>array('type'=>'string'), 
		'preset_data'=>array('type'=>'string'), 
		);

	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		if (!empty($this->args['query']) && !empty($this->args['field']))
		{
			$this->args["_s{$this->args['field']}"] = "%{$this->args['query']}%";
		}
		$cr = $this->join_with_di('user', array('creator_uid' => 'id'), array('name' => 'str_creator_name'));
		$flds = array(
			'id',
			'created_datetime',
			'title',
			array('di' => $cr, 'name' => 'name')
		);
		$this->set_order('id','DESC');
		$this->extjs_grid_json($flds);
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$data = $this->extjs_form_json(false,false);
		if($data['data']['id']>0)
		{
			response::send($data,'json');
		}
		response::send(array('success'=>'true'),'json');
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_item()
	{
		$this->_flush();
		$this->extjs_form_json();
	}
	
	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_set()
	{
		if($this->args['pid']>0)
		{
			$ret = $this->save_branch();
			response::send($ret,'json');
		}
		else if($_FILES['file'])
		{
			try{
				$this->set_args(array('created_datetime' => date('Y-m-d H:i:S')), true);
				$this->set_args(array('changed_datetime' => date('Y-m-d H:i:S')), true);
				$this->set_args(array('changer_uid' => UID), true);
				$this->set_args(array('creator_uid' => UID), true);
				if($_FILES['file']['error'] != 0)
				{
					throw new Exception('Проблемы  с загрузкой файла');
				}
				$preset_data = file_get_contents($_FILES['file']['tmp_name']);
				$this->set_args(array('preset_data'=>$preset_data),true);
				$this->_flush();
				$this->insert_on_empty = true;
				$result = $this->extjs_set_json(false);
			}
			catch(Exception $e)
			{
				$result = array(
					'success'=>false,
					'error'=>$e->getMessage(),
				);
			}
			response::send(response::to_json($result), 'html');
		}
		if($this->args['_sid']>0)
		{
			try{
				$this->set_args(array('changed_datetime' => date('Y-m-d H:i:S')), true);
				$this->set_args(array('changer_uid' => UID), true);
				$this->_flush();
				$result = $this->extjs_set_json(false);
			}
			catch(Exception $e)
			{
				$result = array(
					'success'=>false,
					'error'=>$e->getMessage(),
				);
			}
			response::send($result, 'json');
		}
		response::send(array('success'=>false,'error'=>'Не определена операция'));
	}

	protected function sys_export()
	{

		$this->_flush();
		$resp = $this->extjs_form_json(false,false);
		if($resp['data']['id']>0)
		{
			$out =  $resp['data']['preset_data'];	
			header("Pragma: ");
			header("Cache-Control: ");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			// END extra headers to resolve IE caching bug
			header("MIME-Version: 1.0");
			header( "Content-length: ".strlen($out));
			header( 'Content-type: text/plain');
			header( 'Content-disposition: attach; filename= "system_menu_export.txt";');
			echo($out);
			die();
		}
	}

	/**
	*	Удалить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$this->extjs_unset_json();
	}
	
	protected function sys_attach()
	{
		$res = $this->attach_preset();
		response::send($res,'json');
	}
		
// присоединяем пресет $this->args['sid'] к узлу $this->args['pid'] 
	public function attach_preset()
	{
		$this->_flush();
		$this->args['_sid'] =  $this->args['sid'];
		$data = $this->extjs_form_json(false,false);
		$change_current = false;
		if($data['success'] == true)
		{
			$preset_data =  unserialize($data['data']['preset_data']);
			if($this->args['type'] == 1)
			{
				$this->update_node($this->args['pid'],$preset_data);
				$this->attach_to($this->args['pid'],$preset_data);
				$change_current = true;
			}
			else if($this->args['type'] == 2)
			{
				$this->attach_to($this->args['pid'],$preset_data,true);
			}
			else
			{
				$this->attach_to($this->args['pid'],$preset_data);
			}
		}
		return array('success'=>true,'root_title'=>$preset_data['text'],'sync'=>$change_current);
	}

// сохраняем потомков узла $this->args['pid']  как новый пресет
	public function save_branch()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		if ($this->args['_sid']>0)
		{
			$this->set_args(array('changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('changer_uid' => UID), true);
		}
		else
		{
			$this->set_args(array('created_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('changer_uid' => UID), true);
			$this->set_args(array('creator_uid' => UID), true);
		}
		if($this->args['pid']>0)
		{
			$preset_data = serialize($this->prepare_branch($this->args['pid']));
			$this->set_args(array('preset_data'=>$preset_data),true);
		}
		return $this->extjs_set_json(false);
	}

// клонируем потомков узла  $parent в узел $new_parent 
	public function clone_branch_to($parent,$new_parent)
	{
		try{
			if(!($parent>0)||!($new_parent>0))
			{
				throw new Exception('Не достаточно параметров');
			}
			$re = $this->get_branch_data($parent);
			$this->attach_to($new_parent,$re2);
			$data['success'] = true;
		}
		catch(Exception $e)
		{
			$data['success'] = false;
			$data['message'] =  $e->getMessage();
		}
		return $data;
	}
	public function prepare_branch($parent)
	{
		try{
			if(!($parent>0))
			{
				throw new Exception('Не достаточно параметров');
			}
			$re = $this->get_branch_data($parent);
		}
		catch(Exception $e)
		{
			$data['success'] = false;
			$data['message'] =  $e->getMessage();
		}
		return $re;
	}

	public function get_branch_data($parent = 1)
	{
		$this->_flush();
		$di =  data_interface::get_instance('system_menu');
		$di->set_args(array(
				'sort'=>'left',
				'dir'=>'ASC',
				));
		$this->data =  $di->extjs_grid_json(false,false);
		$level = 1;
		$this->get_childs(0);
		if($parent != 1){
			$this->search_parent($this->data['records'],$parent);
			return $this->result;
		}
		return $this->data['records'][0];
	}

	public function get_childs($index)
	{
		$this->cnt++;
		$this->data['records'][$index]['childs']= array();
		foreach($this->data['records'] as $key=>$value){
			if($value['level'] == $this->data['records'][$index]['level']+1)
			{
				if($value['left']>$this->data['records'][$index]['left'] && $value['right']<$this->data['records'][$index]['right'])
				{
						$this->get_childs($key);
						array_push($this->data['records'][$index]['childs'],$this->data['records'][$key]);
				}
			}
		}
	}

	public function search_parent($array_in,$parent)
	{
		foreach($array_in as $key=>$value)
		{
			if($value['id'] == $parent)
			{
				$this->result = $value;
				return;
			}
			else{
				$this->search_parent($value['childs'],$parent);
			}
		}
	}

	public function attach_to($parent = 1,$branch = array(), $include_root = false)
	{
		if($include_root == true)
		{
			$this->process_node($parent,$branch);
		}
		else
		{
			if(count($branch['childs'])>0)
			{
				foreach($branch['childs'] as $key=>$value)
				{
					$this->process_node($parent,$branch['childs'][$key]);
				}
			}
		}
	}

	private function process_node($parent,&$node)
	{
		$di = data_interface::get_instance('system_menu');
		$di->_flush();
		$args = $node;
		$args['pid'] = $parent;
		unset($args['vp_list']);
		unset($args['childs']);
		unset($args['level']);
		unset($args['left']);
		unset($args['right']);
		unset($args['uri']);
		unset($args['id']);
		$di->set_args($args);
		$data = $di->node_set();
		if($data['success'] == true)
		{
			$next_parent = $data['data']['id'];
			if(count($node['childs'])>0)
			{
				foreach($node['childs'] as $key=>$value)
				{
					$this->process_node($next_parent,$node['childs'][$key]);
				}
			}
		}
	}
	private function update_node($current,&$node)
	{
		$di = data_interface::get_instance('system_menu');
		$di->_flush();
		$args = $node;
		$args['_sid'] = $current;
		unset($args['vp_list']);
		unset($args['childs']);
		unset($args['level']);
		unset($args['left']);
		unset($args['right']);
		unset($args['uri']);
		unset($args['id']);
		$di->set_args($args);
		$data = $di->node_set();
	}

}
?>
