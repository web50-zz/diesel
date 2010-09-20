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
			'clnt_sys_uid' => array('type' => 'integer', 'alias' => 'uid'),
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
	*	Получить данные по пользователю
	*/
	public function get_data($uid = UID)
	{
		$this->_flush(true);
		$this->push_args(array('_suid' => $uid));
		$gc = $this->join_with_di('guide_country', array('clnt_country' => 'id'), array('title' => 'country','title_eng'=>'country_eng'));
		$gr = $this->join_with_di('guide_region', array('clnt_region' => 'id'), array('title' => 'region','title_eng'=>'region_eng')); 
		$gcr= $this->join_with_di('guide_currency', array('clnt_payment_curr' => 'id'), array('title' => 'pcurr'));
		$gp= $this->join_with_di('guide_pay_type', array('clnt_payment_pref' => 'id'), array('title' => 'ppref'));
		$this->what = array(
			'id',
			'CONCAT_WS(" ", `clnt_lname`, `clnt_name`, `clnt_mname`)' => 'name',
			'clnt_address' => 'address',
			'clnt_country' => 'country_id',
			'clnt_region' => 'region_id',
			'clnt_payment_pref',
			'clnt_created_datetime',
			'clnt_email',
			'clnt_phone',
			'clnt_nas_punkt',
			'clnt_region_custom',
			array('di' => $gc, 'name' => 'title'),
			array('di' => $gc, 'name' => 'title_eng'),
			array('di' => $gr, 'name' => 'title'),
			array('di' => $gp, 'name' => 'title'),
			array('di' => $gcr, 'name' => 'title'),
			array('di' => $gr, 'name' => 'title_eng'),
		);
		$this->_get();
		$this->pop_args();
		return $this->get_results(0);
	}

	/**
	*	Get records
	* @access protected
	*/
	protected function sys_list()
	{
		$this->_flush();
		if (!empty($this->args['query']) && !empty($this->args['field']))
		{
			$this->args["_sclnt_{$this->args['field']}"] = "%{$this->args['query']}%";
		}
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
		$data = $this->get_client_data_extended();
		response::send($data, 'json');
	}

	public function get_client_data_extended()
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

		$reg_di = data_interface::get_instance('guide_region');
		$reg_di->_flush();
		$reg_di->what = array('id', 'title');
		$reg_di->set_args(array('_scid' => $data['data']['clnt_country']));
		$reg_di->set_order('title');
		$reg_di->_get();
	
		$country_di = data_interface::get_instance('guide_country');
		$country_di->_flush();
		$country = $country_di->extjs_grid_json(array('id', 'IF (`'.$country_di->get_alias().'`.`title` != "", `'.$country_di->get_alias().'`.`title`, `'.$country_di->get_alias().'`.`title_eng`)' => 'title'),false);

		$currency_di = data_interface::get_instance('guide_currency');
		$currency_di->_flush();
		$currency = $currency_di->extjs_grid_json(array('id', 'name'),false);

		$pay_type_di = data_interface::get_instance('guide_pay_type');
		$pay_type_di->_flush();
		$pay_type = $pay_type_di->extjs_grid_json(array('id', 'title'), false);

		$data['data']['regs']['records'] = $reg_di->get_results();	
		$data['data']['cntrys']['records'] = $country['records'];	
		$data['data']['currencys']['records'] = $currency['records'];	
		$data['data']['payvar']['records'] = $pay_type['records'];	

		$data['data']['clnt_region_selected'] = $data['data']['clnt_region'];
		$data['data']['clnt_country_selected'] = $data['data']['clnt_country'];
		$data['data']['clnt_payment_curr_selected'] = $data['data']['clnt_payment_curr'];
		$data['data']['clnt_payment_pref_selected'] = $data['data']['clnt_payment_pref'];

		unset($data['data']['clnt_region']);
		unset($data['data']['clnt_country']);
		unset($data['data']['clnt_payment_curr']);
		unset($data['data']['clnt_payment_pref']);
		return $data;
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
