<?php
/**
*	ПИ "Каталог"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class ui_catalogue extends user_interface
{
	public $title = 'Каталог';

	protected $deps = array(
		'main' => array(
			'catalogue.item_list',
			'catalogue.item_form',
			'catalogue.filter_form'
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
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}

	protected function pub_content()
	{
		if (preg_match('/^item_(\d+)/', SRCH_URI, $matches))
		{
			return $this->get_item((int)$matches[1]);
		}
		else
		{
			return $this->get_list();
		}
	}

	/**
	*	Вывести описание товара
	*/
	private function get_item($id)
	{
		$di = data_interface::get_instance('catalogue_item');
		$di->set_args(array('_sid' => $id));
		$data = $di->get_item();
		return $this->parse_tmpl('item.html',$data);
	}

	/**
	*	Вывести список товаров в каталоге
	*/
	private function get_list()
	{
		$limit = 20;
		$page = request::get('page', 1);
		$di = data_interface::get_instance('catalogue_item');
		$di->set_args(array(
			'sort' => 'id',
			'dir' => 'DESC',
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
		));
		$di->set_args($this->get_args(), true);
		$data = $di->get_items();
		$data['page'] = $page;
		$data['limit'] = $limit;

		$cart = data_interface::get_instance('cart');
		$data['cart'] = $cart->_list();
		$pager = user_interface::get_instance('pager');
		$data['pager'] = $pager->get_pager(array('page' => $page, 'total' => $data['total'], 'limit' => $limit));
		return $this->parse_tmpl('default.html',$data);
	}
	
	/**
	*       Main interface
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'catalogue.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Item`s list
	*/
	protected function sys_item_list()
	{
		$tmpl = new tmpl($this->pwd() . 'item_list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Edit form
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Filter form
	*/
	protected function sys_filter_form()
	{
		$tmpl = new tmpl($this->pwd() . 'filter_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Files list
	*/
	protected function sys_files()
	{
		$tmpl = new tmpl($this->pwd() . 'files.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Files form
	*/
	protected function sys_file_form()
	{
		$tmpl = new tmpl($this->pwd() . 'file_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Files form
	*/
	protected function sys_resize_form()
	{
		$tmpl = new tmpl($this->pwd() . 'resize_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Styles list
	*/
	protected function sys_styles()
	{
		$tmpl = new tmpl($this->pwd() . 'styles.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Page configure form
	*/
	protected function sys_configure_form()
	{
		$tmpl = new tmpl($this->pwd() . 'configure_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
