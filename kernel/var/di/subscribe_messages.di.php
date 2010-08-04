<?php
/**
*	Data interface "subscribe messages"
*
* @author 9*	
* @package	SBIN Diesel
*/
class di_subscribe_messages extends data_interface
{
	public $title = 'Subscribe messages';

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
	protected $name = 'subscribe_messages';

	/**
	* @var	array	$user	Current logged in user`s data
	*/
	protected $user = array();
	
	/**
	* @var	array	$fields	The fields configuration
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => TRUE, 'protected' => FALSE),
			'subscr_created_datetime' => array('type' => 'string'),
			'subscr_changed_datetime' => array('type' => 'string'),
			'subscr_deleted_datetime' => array('type' => 'string'),
			'subscr_creator_uid' => array('type' => 'string'),
			'subscr_changer_uid' => array('type' => 'string'),
			'subscr_deleter_uid' => array('type' => 'string'),
			'subscr_title' => array('type' => 'string'),
			'subscr_message_body' => array('type' => 'string'),
			'subscr_id' => array('type' => 'string'),
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
		$this->extjs_grid_json(array('id', 'subscr_created_datetime', 'subscr_title','subscr_id'));
	}
	
	/**
	*	Get record
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json(array('id',
						'subscr_created_datetime',
						'subscr_changed_datetime',
						'subscr_deleted_datetime',
						'subscr_creator_uid',
						'subscr_changer_uid',
						'subscr_deleter_uid',
						'subscr_title',
						'subscr_message_body',
						'subscr_id',
		));
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
			$this->set_args(array('subscr_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('subscr_changer_uid' => UID), true);
		}
		else
		{
			$this->set_args(array('subscr_created_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('subscr_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('subscr_changer_uid' => UID), true);
			$this->set_args(array('subscr_creator_uid' => UID), true);
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
