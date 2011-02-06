<?php
/**
*	Data interface "market_latest_long"
*
* @author 9*	
* @package	SBIN Diesel
*/
class di_market_latest_long extends data_interface
{
	public $title = 'Маркет новнки  расширенно DI';

	/**
	* @var	string	$cfg	DB configuration`s name
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	The name of data base
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	The name of table
	*/
	protected $name = 'market_latest_long';

	/**
	* @var	array	$user	Current logged in user`s data
	*/
	protected $user = array();
	
	/**
	* @var	array	$fields	The fields configuration
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => TRUE, 'protected' => FALSE),
			'm_latest_l_created_datetime' => array('type' => 'datetime'),
			'm_latest_l_changed_datetime' => array('type' => 'datetime'),
			'm_latest_l_deleted_datetime' => array('type' => 'datetime'),
			'm_latest_l_creator_uid' => array('type' => 'integer'),
			'm_latest_l_changer_uid' => array('type' => 'integer'),
			'm_latest_l_deleter_uid' => array('type' => 'integer'),
			'm_latest_l_deleted_flag' => array('type' => 'integer'),
			'm_latest_l_text' => array('type' => 'string'),
			'm_latest_l_title' => array('type' => 'string'),
			'm_latest_l_issue_datetime' => array('type' => 'datetime'),
			'm_latest_l_product_id' => array('type' => 'integer')

		);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	/**
	*	Get records
	* @access protected
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$data = $this->_get_list_data();
		response::send($data,'json');
	}

	public function _get_list_data()
	{
		if($this->args['_sm_latest_l_title'] == '')
		{
			unset($this->args['_sm_latest_l_title']);
		}
		if($this->args['_sm_latest_l_title'] != '')
		{
			$this->args['_sm_latest_l_title'] = '%'.$this->args['_sm_latest_l_title'].'%';
		}
		
		$data = $this->extjs_grid_json(array(
			'id',
			'm_latest_l_created_datetime', 
			'm_latest_l_changed_datetime',
			'm_latest_l_issue_datetime', 
			'm_latest_l_title',
			'm_latest_l_text',
			'm_latest_l_product_id'
			),false);
		return $data;
	}


// На входе массив с индексами полей в массиве $what которые надо сделать унсет - типа игнорировать поля в выборке
//9* исходя из вышесказанного порядок полей не менять если добавлять новые то в конец
	public function _get_extended_data($ignore_flds = array())
	{
		$ga = $this->join_with_di('catalogue_item', array('m_latest_l_product_id' => 'id'), array('title' => 'title','group_id'=>'group_id','description'=>'description','type_id'=>'type_id','collection_id'=>'collection_id','picture'=>'picture','preview'=>'preview'),$di1);
		$gt = $this->join_with_di('market_types', array('type_id' => 'id'), array('title' => 'str_type',$qa));
		$gc = $this->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'str_collection'),$qa);
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'),$qa);
		$what  = array(
				'id',
				'm_latest_l_title',
				'm_latest_l_product_id',
				'm_latest_l_issue_datetime',
				'm_latest_l_text',
				array('di' => $ga, 'name' => 'description'),
				array('di' => $ga, 'name' => 'group_id'),
				array('di' => $ga, 'name' => 'type_id'),
				array('di' => $ga, 'name' => 'collection_id'),
				array('di' => $ga, 'name' => 'title'),
				array('di' => $ga, 'name' => 'picture'),
				array('di' => $ga, 'name' => 'preview'),
				array('di' => $gt, 'name' => 'title'),
				array('di' => $gg, 'name' => 'name'),
				array('di' => $gc, 'name' => 'name'),
			);
		foreach($ignore_flds as $key=>$value)
		{
			unset($what[$value]);
		}

		$list= $this->extjs_grid_json($what,false);
		return $list;
	}


	public function get_prev_next_ids($id)
	{
		if(!$id)
		return array();
		$data1 = $this->_get("SELECT id,m_latest_l_issue_datetime FROM ".$this->name." WHERE id < $id ORDER by id  DESC limit 0,1");
		$data2 = $this->_get("SELECT id,m_latest_l_issue_datetime FROM ".$this->name." WHERE id > $id limit 0,1");
		return array($data1,$data2);
	}
	/**
	*	Get record
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush(true);
		$data = $this->_get_data();
		response::send($data, 'json');
	}

	protected function _get_data()
	{
		$dd = $this->join_with_di('catalogue_item', array('m_latest_l_product_id' => 'id'), array('title' => 'p_title'));
		$gt = $this->join_with_di('market_types', array('type_id' => 'id'), array('title' => 'p_type'),$dd);
		$gc = $this->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'p_collection'),$dd);
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'p_group','id'=>'p_group_id'),$dd);
		$data = $this->extjs_form_json(array('id',
					'm_latest_l_created_datetime',
					'm_latest_l_changed_datetime',
					'm_latest_l_text',
					'm_latest_l_title',
					'm_latest_l_product_id',
					'm_latest_l_issue_datetime',
					array('di' => $dd, 'name' => 'title'),
					array('di' => $gt, 'name' => 'title'),
					array('di' => $gc, 'name' => 'name'),
					array('di' => $gg, 'name' => 'name'),
					array('di' => $gg, 'name' => 'id')
					),false);
		if($data['data']['m_latest_l_product_id']>0)
		$data['data']['pr_title'] = $data['data']['m_latest_l_product_id'].': '.$data['data']['p_group'].' '.$data['data']['p_title'].' '.$data['data']['p_type'];
		return $data;
	}


	/**
	*	Save record
	* @access protected
	*/
	protected function sys_set()
	{
		dbg::write($this->args);
		$this->_flush();
		$this->insert_on_empty = true;
		if ($this->args['_sid']>0)
		{
			$this->set_args(array('m_latest_l_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('m_latest_l_changer_uid' => UID), true);
		}
		else
		{
			$this->set_args(array('m_latest_l_created_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('m_latest_l_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('m_latest_l_changer_uid' => UID), true);
			$this->set_args(array('m_latest_l_creator_uid' => UID), true);
		}
		$data = $this->extjs_set_json(false);
		response::send($data, 'json');
	}
	
	
	/**
	*	Delete user
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		response::send($data, 'json');
	}
}
?>
