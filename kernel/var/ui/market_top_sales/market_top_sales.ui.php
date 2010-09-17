<?php
/**
*	UI Top Sales 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_market_top_sales extends user_interface
{
	public $title = 'TOP продаж';

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_content()
        {
		$oi = data_interface::get_instance('order_item');
		$oi->_flush(true);
		$ci = $oi->join_with_di('catalogue_item', array('item_id' => 'id'), array('on_offer' => 'on_offer'));
		$gg = $oi->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'), $ci);
		$oi->what = array('SUM(`'.$oi->get_alias().'`.`count`)' => 'item_count',
			array('di' => $ci, 'name' => 'id'),
			array('di' => $ci, 'name' => 'title'),
			array('di' => $ci, 'name' => 'preview'),
			array('di' => $ci, 'name' => 'group_id'),
			array('di' => $gg, 'name' => 'name'),
		);
		$oi->set_group('item_id');
		$oi->set_order('item_count', 'DESC', null);
		$oi->set_limit(0, 10);
		$oi->set_args(array('_son_offer' => 1));
		$oi->_get();
		$df = data_interface::get_instance('catalogue_file');
		$data = array(
			'storage' => "/{$df->path_to_storage}",
			'records' => $oi->get_results()
		);
		return $this->parse_tmpl('default.html', $data);
	}

	public function pub_double()
        {
		return 'Top sales here';
	}
}
?>
