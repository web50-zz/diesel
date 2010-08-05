<?php
/**
*	Интерфейс данных "Каталог: товары"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_catalogue_item extends data_interface
{
	public $title = 'Каталог: товары';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'catalogue_item';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'created_datetime' => array('type' => 'datetime'),
		'creator_uid' => array('type' => 'integer'),
		'changed_datetime' => array('type' => 'datetime'),
		'changer_uid' => array('type' => 'integer'),
		'income_date' => array('type' => 'date'),
		'on_offer' => array('type' => 'integer'),
		'recomended' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'preview' => array('type' => 'string'),
		'picture' => array('type' => 'string'),
		'description' => array('type' => 'text'),
		'price_id' => array('type' => 'integer'),
		'prepayment' => array('type' => 'float'),
		'payment_forward' => array('type' => 'float'),
		'type_id' => array('type' => 'integer'),
		'producer_id' => array('type' => 'integer'),
		'collection_id' => array('type' => 'integer'),
		'group_id' => array('type' => 'integer'),
		'style_id' => array('type' => 'integer'),
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Get items for page
	*/
	public function get_items()
	{
		$this->_flush();
		$this->set_limit(0, 20);
		$this->set_order('title', 'ASC');
		return $this->_get();
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		if (!empty($this->args['_stitle']))
			$this->args['_stitle'] = "%{$this->args['_stitle']}%";
		else
			unset($this->args['_stitle']);

		if ($this->args['_son_offer'] == '') unset($this->args['_son_offer']);
		if ($this->args['_stype_id'] == '') unset($this->args['_stype_id']);
		if ($this->args['_sgroup_id'] == '') unset($this->args['_sgroup_id']);

		$this->_flush(true);
		$gt = $this->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'));
		$gp = $this->join_with_di('guide_price', array('price_id' => 'id'), array('title' => 'str_price'));
		$this->extjs_grid_json(array(
			'id',
			'on_offer',
			'recomended',
			'title',
			'prepayment',
			'payment_forward',
			array('di' => $gt, 'name' => 'name'),
			array('di' => $gg, 'name' => 'name'),
			array('di' => $gp, 'name' => 'title'),
		));
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}
	
	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		if ($this->get_args('_sid') == 0)
		{
			$this->set_args(array(
				'created_datetime' => date('Y-m-d H:i:s'),
				'creator_uid' => UID,
				'changed_datetime' => date('Y-m-d H:i:s'),
				'changer_uid' => UID,
			), true);
		}
		else
		{
			$this->set_args(array(
				'changed_datetime' => date('Y-m-d H:i:s'),
				'changer_uid' => UID,
			), true);
		}
		$this->extjs_set_json();
	}
	
	/**
	*	Удалить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_unset()
	{
		$cf = data_interface::get_instance('catalogue_file');
		$cs = data_interface::get_instance('catalogue_style');
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		$ids = $this->get_lastChangedId();
		
		// Remove all files and styles from catalogue items
		if (($ids > 0 || count($ids) > 0))
		{
			$cf->remove_files($ids);
			$cs->remove_styles_from_item($ids);
		}

		response::send($data, 'json');
	}
}
?>
