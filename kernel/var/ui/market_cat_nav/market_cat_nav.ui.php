<?php
/**
*	UI simple menu for market catalogue
*
* @author	9* <9@u9.ru>
* @access	public
* @package	SBIN Diesel
*/
class ui_market_cat_nav extends user_interface
{
	public $title = 'Меню по типам товаров в каталоге';

	
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
		$st = data_interface::get_instance('guide_type');
		//	$data = $st->extjs_grid_json(false,false);
		$data['records'] = $st->get_nonempty_types();
		// 9* detects type if already in catalogue to highlite selected

		if (preg_match('/type\/(\d+)\//', SRCH_URI, $matches)&&PAGE_URI == '/products/')
		{
			$data['toselect'] = $matches[1];
		}
		return $this->parse_tmpl('main_menu.html',$data);
	}
}
?>
