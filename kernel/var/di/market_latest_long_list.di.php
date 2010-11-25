<?php
/**
*	Data interface "market latest long list"
*
* @author 9*	
* @package	SBIN Diesel
*/
class di_market_latest_long_list extends data_interface
{
	public $title = 'Маркет новнки DI';

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
	protected $name = 'market_latest_long_list';

	/**
	* @var	array	$user	Current logged in user`s data
	*/
	protected $user = array();
	
	/**
	* @var	array	$fields	The fields configuration
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => TRUE, 'protected' => FALSE),
			'm_latest_ls_created_datetime' => array('type' => 'datetime'),
			'm_latest_ls_changed_datetime' => array('type' => 'datetime'),
			'm_latest_ls_deleted_datetime' => array('type' => 'datetime'),
			'm_latest_ls_creator_uid' => array('type' => 'integer'),
			'm_latest_ls_changer_uid' => array('type' => 'integer'),
			'm_latest_ls_deleter_uid' => array('type' => 'integer'),
			'm_latest_ls_deleted_flag' => array('type' => 'integer'),
			'm_latest_ls_product_id' => array('type' => 'integer'),
			'm_latest_ls_issue_id' => array('type' => 'integer')
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
		$dd = $this->join_with_di('catalogue_item', array('m_latest_ls_product_id' => 'id'), array('title' => 'p_title','preview'=>'preview','picture'=>'picture'));
		$gt = $this->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'p_type'),$dd);
		$gc = $this->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'p_collection'),$dd);
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'p_group','id'=>'p_group_id'),$dd);
		$data = $this->extjs_grid_json(array(
			'id',
			'm_latest_ls_created_datetime', 
			'm_latest_ls_product_id',
			'm_latest_ls_issue_id',
			array('di' => $dd, 'name' => 'title'),
			array('di' => $dd, 'name' => 'preview'),
			array('di' => $dd, 'name' => 'picture'),
			array('di' => $gt, 'name' => 'name'),
			array('di' => $gc, 'name' => 'name'),
			array('di' => $gg, 'name' => 'name'),
			array('di' => $gg, 'name' => 'id')
			),false);
		return $data;
	}

	/**
	*	Get record
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$data = $this->extjs_form_json(array('id',
					'm_latest_ls_created_datetime',
					'm_latest_ls_changed_datetime',
					'm_latest_ls_product_id',
					'm_latest_ls_issue_id'
					),false);
		response::send($data, 'json');
	}

	/**
	*	Save record
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		if ($this->args['_sid']>0)
		{
			$this->set_args(array('m_latest_ls_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('m_latest_ls_changer_uid' => UID), true);
		}
		else
		{
			$this->set_args(array('m_latest_ls_created_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('m_latest_ls_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('m_latest_ls_changer_uid' => UID), true);
			$this->set_args(array('m_latest_ls_creator_uid' => UID), true);
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
