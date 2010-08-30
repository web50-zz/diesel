<?php
/**
*	UI Market latest products 
*
* @author	elgarat 	
* @access	public
* @package	SBIN Diesel 	
*/
// see also guestbook.di.php 
class ui_market_recomendations extends user_interface
{
	public $title = 'Магазин рекомендует';

		protected $deps = array(
		'main' => array(
			'catalogue.item_list',
			'catalogue.item_form',
			'catalogue.filter_form',
			'market_recomendations.catalogue_list',
			'market_recomendations.recomend_list'
		),
		'item_form' => array(
			'catalogue.files',
			'catalogue.styles'
		),
		'files' => array(
			'catalogue.file_form',
			'catalogue.resize_form'
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
	protected function sys_recomend_list()
	{
		$tmpl = new tmpl($this->pwd() . 'recomend_list.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
