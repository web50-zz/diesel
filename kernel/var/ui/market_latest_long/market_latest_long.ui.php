<?php
/**
*	UI Market latest long  products 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_market_latest_long extends user_interface
{
	public $title = 'Новинки магазина расширенно';
	public $deps = array('main' => array(
			'market_latest_long.list',
			'market_latest_long.form',
			'market_latest_long.filter_form',
			'catalogue.item_list',
			'catalogue.filter_form'
			),
			'form'=>array(
				'market_latest_long.items_list',
				'market_latest_long.catalogue_list',
			)
		);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_long()
        {
		$data = array();
		return $this->parse_tmpl('default.html',$data);
	}

	public function pub_short()
        {
		$data = array();
		$di  = data_interface::get_instance('market_latest');
		$data = $di->_get_list_data();
		return $this->parse_tmpl('short.html',$data);
	}
	
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}

	protected function sys_list()
	{
		$tmpl = new tmpl($this->pwd() . 'list.js');
		response::send($tmpl->parse($this), 'js');
	}
	protected function sys_filter_form()
	{
		$tmpl = new tmpl($this->pwd() . 'filter_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	protected function sys_form()
	{
		$tmpl = new tmpl($this->pwd() . 'form.js');
		response::send($tmpl->parse($this), 'js');
	}

	protected function sys_items_list()
	{
		$tmpl = new tmpl($this->pwd() . 'items_list.js');
		response::send($tmpl->parse($this), 'js');
	}
	protected function sys_catalogue_list()
	{
		$tmpl = new tmpl($this->pwd() . 'catalogue_list.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
