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

	public function pub_default()
	{
		if (preg_match('/archive/', SRCH_URI, $matches))
		{
			return $this->get_data(array('mode'=>'archive'));
		}
		else
		{
			if (preg_match('/issue\/(\d+)/', SRCH_URI, $matches))
			{
				return $this->get_data(array('id'=>$matches[1],'mode'=>'item'));
			}
			else
			{
				return $this->get_data(array('mode'=>'item','id'=>'0'));
			}
		}
	}


	private function get_data($input)
	{
		$di1  = data_interface::get_instance('market_latest_long');
		$di1->_flush(true);
		$ignore = array();	
		if($input['mode'] == 'archive')
		{
			$limit = 10;
			$page = request::get('page', 1);
			$di1->set_args(array(
				'sort' => 'id',
				'dir' => 'DESC',
				'start' => ($page - 1) * $limit,
				'limit' => $limit,
			));
			//$ignore = array('4'); //оказалось нендо так как  полюбому кусок дексрипшна вытягиваем 
		}
		elseif($input['mode'] == 'item'&& $input['id'] >0)
		{
			$di1->set_args(array('_sid'=>$input['id']));
			$prev_next = $di1->get_prev_next_ids($input['id']);
		}
		else
		{
			$di1->set_order('id', 'DESC');
			$di1->set_limit(0,2);
		}
		$list= $di1->_get_extended_data($ignore);
		if($input['mode'] == 'item'&&$list['records'][0]['id']>0)
		{
			$di2 = data_interface::get_instance('market_latest_long_list');
			$di2->_flush(true);
			$di2->set_args(array('_sm_latest_ls_issue_id' => $list['records'][0]['id']));
			$di2->set_order('p_collection', 'ASC');
			$res = $di2->_get_list_data();
			$data = $list['records'][0];
			$data['records'] = $res['records'];
			if($prev_next)
			{
				$data['previous'] = $prev_next[0][0]; 
				$data['next'] = $prev_next[1][0];
			}
			else
			{
				$data['previous'] = array('id'=>$list['records'][1]['id'],'issue_date'=>$list['records'][1]['m_latest_l_issue_datetime']);
			}
			return $this->parse_tmpl('issue.html',$data);
		}

		$pager = user_interface::get_instance('pager');
		$list['page'] = $page;
		$list['limit'] = $limit;
		$list['pager'] = $pager->get_pager(array('page' => $page, 'total' => $list['total'], 'limit' => $limit, 'prefix' => $_SERVER['QUERY_STRING']));
		return $this->parse_tmpl('list.html',$list);
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
