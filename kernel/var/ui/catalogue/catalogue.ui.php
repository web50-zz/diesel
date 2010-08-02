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
		$di = data_interface::get_instance('catalogue_item');
		$di->set_args($this->get_args());
//9* 13072010		dbg::show(SRCH_URI);
		return '<pre>' . print_r($di->get_items(), true) . '</pre>';
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
