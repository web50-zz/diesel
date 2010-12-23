<?php
/**
*	UI Market latest products 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_market_latest extends user_interface
{
	public $title = 'Новинки магазина';
	public $deps = array('main' => array(
			'market_latest.catalogue_list',
			'market_latest.latest_list',
			'catalogue.item_list',
			'catalogue.filter_form',
			)
		);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
		$this->set_lang_data();
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
		$data['storage'] = '/storage/';
		return $this->parse_tmpl('short.html',$data);
	}

	public function pub_rss()
	{
		$data = array();
		$di  = data_interface::get_instance('market_latest');
		$di->args['with_description'] = true;
		$data = $di->_get_list_data();
		$data['host'] = $_SERVER['HTTP_HOST'];
		response::send($this->parse_tmpl('rss.html',$data),'html');
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
