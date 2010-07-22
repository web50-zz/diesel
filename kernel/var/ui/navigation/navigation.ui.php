<?php
/**
*	UI The structure of site
*
* @author	9* <9@u9.ru>
* @access	public
* @package	SBIN Diesel
*/
class ui_navigation extends user_interface
{
	public $title = 'Навигация по сайту';

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

	/**
	*	main menu
	*/
	protected function pub_top_menu()
	{
		$st = data_interface::get_instance('structure');
		return $this->parse_tmpl('main_menu.html',$st->get_main_menu());
	}
	
	/**
	*	Sub menu
	*/
	protected function pub_sub_menu()
	{
		$st = data_interface::get_instance('structure');
		return $this->parse_tmpl('sub_menu.html',$st->get_sub_menu());
	}
	
	/**
	*	Menu "Thermometer"
	*/
	protected function pub_trunc_menu()
	{
		$st = data_interface::get_instance('structure');
		return $this->parse_tmpl('trunc_menu.html',$st->get_trunc_menu());
	}
	
}
?>
