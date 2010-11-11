<?php
/**
*	UI Market soon products 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_market_soon extends user_interface
{
	public $title = 'Скоро в продаже';
	public $deps = array('main' => array(
			'market_soon.catalogue_list',
			'market_soon.latest_list',
			'catalogue.item_list',
			'catalogue.filter_form',
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
		$di  = data_interface::get_instance('market_soon');
		$data = $di->_get_list_data();
		$data['storage'] = '/storage/';
		return $this->parse_tmpl('short.html',$data);
	}
	
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'catalogue.js');
		response::send($tmpl->parse($this), 'js');
	}
	protected function sys_catalogue_list()
	{
		$tmpl = new tmpl($this->pwd() . 'catalogue_list.js');
		response::send($tmpl->parse($this), 'js');
	}
	protected function sys_latest_list()
	{
		$tmpl = new tmpl($this->pwd() . 'latest_list.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
