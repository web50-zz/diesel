<?php
/**
*	UI The structure of site
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class ui_market_menu extends ui_structure 
{

public $title = 'Market menu';

	public function  pub_top_menu(){

		$st = data_interface::get_instance('structure');
		$menu['list'] = $st->get_main_menu();
		$template = 'main_menu.html';
		$html = $this->parse_tmpl($template,$menu);

		dbg::show($menu);
//		response::send($html, 'html');
		return $html;
	}

	public function  pub_top_sub_menu(){

		$st = data_interface::get_instance('structure');
		$menu['list'] = $st->get_sub_menu();
		$template = 'main_sub_menu.html';
		$html = $this->parse_tmpl($template,$menu);

		dbg::show($menu);
//		response::send($html, 'html');
		return $html;
	}


	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
}
?>