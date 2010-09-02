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
			'm_latest_l_issue_datetime' => array('type' => 'datetime')

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
			'm_latest_l_text'
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
					'm_latest_l_created_datetime',
					'm_latest_l_changed_datetime',
					'm_latest_l_text',
					'm_latest_l_title',
					'm_latest_l_issue_datetime',
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
		if ($this->get_args('_sid')>0)
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
