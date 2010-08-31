<?php
/**
*	ПИ "Новости"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class ui_news extends user_interface
{
	public $title = 'Новости';

	protected $deps = array(
		'main' => array(
			'news.item_form',
		)
	);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
        
	/**
        *       Отрисовка контента для внешней части
        */
        public function pub_content()
        {
		/*$tmpl = new tmpl($this->pwd() . 'content.html');*/
                $di = data_interface::get_instance('news');
		$di->set_args($this->args);
//                return $tmpl->parse($di->_get());
//		$data = array();
		$data['news'] = $di->_get();
//		dbg::show($data);
		return $this->parse_tmpl('default.html',$data);
        }
	
	/**
	*       Управляющий JS админки
	*/
	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'news.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS - Форма редактирования
	*/
	public function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}

	public function pub_double()
        {
		return 'guest book here';
	}


}
?>
