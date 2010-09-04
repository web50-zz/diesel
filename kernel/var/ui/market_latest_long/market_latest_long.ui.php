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
//  Last issue 
        public function pub_last_issue()
        {
		$data = array();

		$di1  = data_interface::get_instance('market_latest_long');
		$di1->_flush(true);
		$di1->set_order('id', 'DESC');
		$di1->set_limit(0,1);
		$issue = $di1->_get_list_data();

		$di3  = data_interface::get_instance('catalogue_item');
		$di3->_flush(true);
		$di3->set_args(array('_sid' => $issue['records'][0]['m_latest_l_product_id']), true);
		$gt = $di3->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
		$gc = $di3->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'str_collection'));
		$gg = $di3->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'));
		$description = $di3->extjs_form_json(array(
							'description',
							'title',
							'group_id',
							'collection_id',
							'type_id',
							array('di' => $gt, 'name' => 'name'),
							array('di' => $gg, 'name' => 'name'),
							array('di' => $gc, 'name' => 'name'),
							),false);

		$di2 = data_interface::get_instance('market_latest_long_list');
		$di2->_flush(true);
		$di2->set_args(array('_sm_latest_ls_issue_id' => $issue['records'][0]['id']));
		$di2->set_order('p_collection', 'ASC');
		$res = $di2->_get_list_data();


		$data = $issue['records'][0];
		$data['records'] = $res['records'];
		$data = array_merge($data,$description['data']);
		return $this->parse_tmpl('issue.html',$data);
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
