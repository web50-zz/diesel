<?php
/**
*	ПИ "Справочники"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @version	1.0
* @access	public
*/
class ui_guide extends user_interface
{
	public $title = 'Справочники';

	protected $deps = array(
		'producer' => array(
			'guide.producer_form'
		),
		'collection' => array(
			'guide.collection_form'
		),
		'group' => array(
			'guide.group_form'
		),
		'style' => array(
			'guide.style_form'
		),
		'type' => array(
			'guide.type_form'
		),
		'price' => array(
			'guide.price_form'
		),
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Производители
	*/
	public function sys_producer()
	{
		$tmpl = new tmpl($this->pwd() . 'producer.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования (Производители)
	*/
	public function sys_producer_form()
	{
		$tmpl = new tmpl($this->pwd() . 'producer_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Коллекции
	*/
	public function sys_collection()
	{
		$tmpl = new tmpl($this->pwd() . 'collection.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования (Коллекции)
	*/
	public function sys_collection_form()
	{
		$tmpl = new tmpl($this->pwd() . 'collection_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Группы
	*/
	public function sys_group()
	{
		$tmpl = new tmpl($this->pwd() . 'group.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования (Группы)
	*/
	public function sys_group_form()
	{
		$tmpl = new tmpl($this->pwd() . 'group_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Стили
	*/
	public function sys_style()
	{
		$tmpl = new tmpl($this->pwd() . 'style.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования (Стили)
	*/
	public function sys_style_form()
	{
		$tmpl = new tmpl($this->pwd() . 'style_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Типы
	*/
	public function sys_type()
	{
		$tmpl = new tmpl($this->pwd() . 'type.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования (Типы)
	*/
	public function sys_type_form()
	{
		$tmpl = new tmpl($this->pwd() . 'type_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Цены
	*/
	public function sys_price()
	{
		$tmpl = new tmpl($this->pwd() . 'price.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования (Цены)
	*/
	public function sys_price_form()
	{
		$tmpl = new tmpl($this->pwd() . 'price_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
