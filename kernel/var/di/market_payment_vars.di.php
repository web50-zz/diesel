<?php
/**
*	Data interface "market_payments_vars"
*
* @author 9*	
* @package	SBIN Diesel
*/
class di_market_payment_vars extends data_interface
{
	public $title = 'Список вариантов способов платежей';

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
	protected $name = 'market_payment_vars';

	/**
	* @var	array	$user	Current logged in user`s data
	*/
	protected $user = array();
	
	/**
	* @var	array	$fields	The fields configuration
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => TRUE, 'protected' => FALSE),
					'pay_var_title' => array('type' => 'integer'),
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
		$this->_flush();
		$this->extjs_grid_json(array());
	}
	
// 9* overload default DI method cause need hack
	public function extjs_grid_json($fields,$with_response = true)
	{
		$data['records'] = array(
					array('id'=>'1','pay_var_title'=>'WebMoney'),
					array('id'=>'2','pay_var_title'=>'Курьеру наличными(Москва)'),
					array('id'=>'3','pay_var_title'=>'Наложенный платеж'),
					array('id'=>'4','pay_var_title'=>'Предоплата'),
					array('id'=>'5','pay_var_title'=>'Яндекс деньги'),
					);
		$data['success'] = true;
		$data['total'] = 2;
		if ($with_response)
			response::send($data, 'json');
		else
			return $data;
	}

	/**
	*	Get record
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$data = array();
		response::send($data, 'json');
	}

	/**
	*	Save record
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$data = array();
		response::send($data, 'json');
	}
	
	
	/**
	*	Delete user
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$data = array();
		response::send($data, 'json');
	}
}
?>
