<?php
/**
*	Data interface "market_clients"
*
* @author 9*	
* @package	SBIN Diesel
*/
class di_market_clients extends data_interface
{
	public $title = 'Клиенты магазина';

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
	protected $name = 'market_clients';

	/**
	* @var	array	$user	Current logged in user`s data
	*/
	protected $user = array();
	
	/**
	* @var	array	$fields	The fields configuration
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => TRUE, 'protected' => FALSE),
			'clnt_created_datetime' => array('type' => 'datetime'),
			'clnt_changed_datetime' => array('type' => 'datetime'),
			'clnt_deleted_datetime' => array('type' => 'datetime'),
			'clnt_creator_uid' => array('type' => 'integer'),
			'clnt_changer_uid' => array('type' => 'integer'),
			'clnt_deleter_uid' => array('type' => 'integer'),
			'clnt_deleted_flag' => array('type' => 'integer'),
			'clnt_sys_uid' => array('type' => 'integer'),
			'clnt_name' => array('type' => 'string'),
			'clnt_lname' => array('type' => 'string'),
			'clnt_mname' => array('type' => 'string'),
			'clnt_email' => array('type' => 'string'),
			'clnt_country' => array('type' => 'integer'),
			'clnt_region' => array('type' => 'integer'),
			'clnt_region_custom' => array('type' => 'string'),
			'clnt_nas_punkt' => array('type' => 'string'),
			'clnt_address' => array('type' => 'string'),
			'clnt_phone' => array('type' => 'string'),
			'clnt_payment_pref' => array('type' => 'integer'),
			'clnt_payment_curr' => array('type' => 'integer'),
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
		$this->extjs_grid_json(array('id', 
						'clnt_created_datetime', 
						'clnt_name',
						'clnt_mname',
						'clnt_lname',
						'clnt_email',
						'clnt_sys_uid'
					));
	}
	
	/**
	*	Get record
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$data = $this->extjs_form_json(array('id',
					'clnt_created_datetime',
					'clnt_changed_datetime',
					'clnt_deleted_datetime',
					'clnt_creator_uid',
					'clnt_changer_uid',
					'clnt_deleter_uid',
					'clnt_deleted_flag',
					'clnt_sys_uid',
					'clnt_name',
					'clnt_lname',
					'clnt_mname',
					'clnt_email',
					'clnt_country',
					'clnt_region',
					'clnt_region_custom',
					'clnt_nas_punkt',
					'clnt_address',
					'clnt_phone',
					'clnt_payment_pref',
					'clnt_payment_curr',
					),false);

		$reg_di = data_interface::get_instance('country_regions');
		$reg_di->_flush();
		$reg_di->set_args(array('_scr_regions_part_id'=>$data['data']['clnt_country']));
		$regions = $reg_di->extjs_grid_json(array('id','cr_regions_title'),false);
	
		$country_di = data_interface::get_instance('country_regions_cntry');
		$country = $country_di->extjs_grid_json(array('id','cr_cntry_title'),false);

		$currency_di = data_interface::get_instance('market_currency');
		$currency = $currency_di->extjs_grid_json(array('id','curr_title'),false);

		$pay_var_di = data_interface::get_instance('market_payment_vars');
		$pay_var = $pay_var_di->extjs_grid_json(array('id','pay_var_title'),false);

		$data['data']['regs']['records'] = $regions['records'];	
		$data['data']['cntrys']['records'] = $country['records'];	
		$data['data']['currencys']['records'] = $currency['records'];	
		$data['data']['payvar']['records'] = $pay_var['records'];	

		$data['data']['clnt_region_selected'] = $data['data']['clnt_region'];
		$data['data']['clnt_country_selected'] = $data['data']['clnt_country'];
		$data['data']['clnt_payment_curr_selected'] = $data['data']['clnt_payment_curr'];
		$data['data']['clnt_payment_pref_selected'] = $data['data']['clnt_payment_pref'];

		unset($data['data']['clnt_region']);
		unset($data['data']['clnt_country']);
		unset($data['data']['clnt_payment_curr']);
		unset($data['data']['clnt_payment_pref']);

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
			$this->set_args(array('clnt_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('clnt_changer_uid' => UID), true);
		}
		else
		{
			$this->set_args(array('clnt_created_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('clnt_changed_datetime' => date('Y-m-d H:i:S')), true);
			$this->set_args(array('clnt_changer_uid' => UID), true);
			$this->set_args(array('clnt_creator_uid' => UID), true);
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
