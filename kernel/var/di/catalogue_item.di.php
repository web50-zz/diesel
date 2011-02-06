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
		'number' => array('type' => 'string'),		// Номер по каталогу
		'title' => array('type' => 'string'),
		'year' => array('type' => 'string'),		// Год выхода
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
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Get item
	*/
	public function get_item()
	{
		$this->_flush(true);
		$gt = $this->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
		$gc = $this->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'str_collection'));
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'));
		$gp = $this->join_with_di('guide_price', array('price_id' => 'id'), array('cost' => 'price_cost'));
		$csn = $this->join_with_di('market_soon', array('id' => 'm_soon_product_id'),array('id'=>'m_soon_id'));
		return $this->extjs_grid_json(array(
			'id', 'on_offer', 'recomended', 'title', 'description', 'type_id', 'collection_id', 'group_id', 'price_id','picture', 
			array('di' => $gt, 'name' => 'name'),
			array('di' => $gg, 'name' => 'name'),
			array('di' => $gp, 'name' => 'cost'),
			array('di' => $gc, 'name' => 'name'),
			array('di' => $gc, 'name' => 'discount'),
			array('di' => $csn, 'name' => 'id')
		), false);
	}
	/* get related products
	*/

	public function get_related()
	{
		$this->_flush();
		$id = $this->args['_sid']; 
//		$sql = "select order_item.item_id from order_item where order_id in  (select order_id from order_item where item_id = $id and order_id !=0) and order_id != 0 and item_id != $id limit 0,10";
		$sql = "select order_item.item_id from order_item, order_item z where order_item.order_id = z.order_id and z.item_id = $id and order_item.order_id !=0 and order_item.item_id != $id  limit 0,10";
		
		$res =  $this->_get($sql);
		if(count($res) == 0)
		{
			return;
		}
		foreach($res as $key=>$value)
		{
			$final[] = $value['item_id'];
		}
		$this->push_args(array('_sid'=>$final));
		$res2 =  $this->get_items();
		$this->pop_args();
		return $res2['records'];
	}

	/*
		get 'see also' products
	*/

	public function get_see_also()
	{
	
		$id = $this->args['_sid']; 
		$sql = "select id from ".$this->name." where group_id in (select group_id from ".$this->name." where id = $id )";
		$res =  $this->_get($sql);
		if(count($res) == 0)
		{
			return;
		}
		foreach($res as $key=>$value)
		{
			if($value['id'] != $id)
			{
				$final[] = $value['id'];
			}
		}
		$this->push_args(array('_sid'=>$final));
		$res2 =  $this->get_items();
		$this->pop_args();
		return $res2['records'];
	}

	/**
	*	Get items for page
	*/
	public function get_items()
	{
		$this->_flush(true);
		$gt = $this->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
		$gc = $this->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'str_collection'));
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'));
		$gp = $this->join_with_di('guide_price', array('price_id' => 'id'), array('cost' => 'price_cost'));
		$cs = $this->join_with_di('catalogue_style', array('id' => 'catalogue_item_id'));
		$gs = $this->join_with_di('guide_style', array('style_id' => 'id'), array('name' => 'style_name', 'id' => 'style_id'), $cs);
		$csn = $this->join_with_di('market_soon', array('id' => 'm_soon_product_id'),array('id'=>'m_soon_id'));
		$this->set_args(array('_son_offer' => 1), true);
		$where = array();
		if(($query = request::get('group', false)) != false)
		{
			$name = $gg->get_alias();
			$where[] = "`{$name}`.`name` LIKE \"%{$query}%\"";
		}
		if(($query = request::get('title', false)) != false)
		{
			$name = $this->get_alias();
			$where[] = "`{$name}`.`title` LIKE \"%{$query}%\"";
		}
//9*  single line search through catalogue based on title an group name field contents
		if(($q = request::get('q',false)) != false)
		{
			$group_tbl  = $gg->get_alias();
			$item_tbl = $this->get_alias();
			$where[] = "`{$group_tbl}`.`name` LIKE \"%{$q}%\" OR `{$item_tbl}`.`title` LIKE \"%{$q}%\""; 
		}

		if (!empty($where))
		{
			$this->where = join(' OR ', $where);
		}
		//$this->connector->debug = true;
		$this->set_group('id');
		if($this->args['with_description'] == true)//9* 20101122 'это если надо выводить еще и дексрипшн по товару то флаг "width description" надо выставлять
		{
			return $this->extjs_grid_json(array(
			'id', 'on_offer', 'recomended', 'title', 'preview', 'picture', 'type_id', 'collection_id', 'group_id', 'price_id','description',
			'GROUP_CONCAT(`'.$gs->get_alias().'`.`name` SEPARATOR ",")' => 'Styles',
			'CONVERT(GROUP_CONCAT(`'.$gs->get_alias().'`.`id` SEPARATOR ",") USING utf8)' => 'StyleIds',
			array('di' => $gt, 'name' => 'name'),
			array('di' => $gg, 'name' => 'name'),
			array('di' => $gp, 'name' => 'cost'),
			array('di' => $gc, 'name' => 'name'),
			array('di' => $gs, 'name' => 'name'),
			array('di' => $gc, 'name' => 'discount'),
			array('di' => $csn, 'name' => 'id')
			), false);

		}
		return $this->extjs_grid_json(array(
			'id', 'on_offer', 'recomended', 'title', 'preview', 'picture', 'type_id', 'collection_id', 'group_id', 'price_id',
			'GROUP_CONCAT(`'.$gs->get_alias().'`.`name` SEPARATOR ",")' => 'Styles',
			'CONVERT(GROUP_CONCAT(`'.$gs->get_alias().'`.`id` SEPARATOR ",") USING utf8)' => 'StyleIds',
			array('di' => $gt, 'name' => 'name'),
			array('di' => $gg, 'name' => 'name'),
			array('di' => $gp, 'name' => 'cost'),
			array('di' => $gc, 'name' => 'name'),
			array('di' => $gs, 'name' => 'name'),
			array('di' => $gc, 'name' => 'discount'),
			array('di' => $csn, 'name' => 'id')
		), false);
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$gt = $this->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group'));
		$gp = $this->join_with_di('guide_price', array('price_id' => 'id'), array('title' => 'str_price'));
		$gc = $this->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'str_collection'));

		if (!empty($this->args['_stitle']))
			$this->args['_stitle'] = "%{$this->args['_stitle']}%";
		else
			unset($this->args['_stitle']);

		if ($this->args['_son_offer'] == '') unset($this->args['_son_offer']);
		if ($this->args['_stype_id'] == '') unset($this->args['_stype_id']);
		if ($this->args['_sgroup_id'] == '') unset($this->args['_sgroup_id']);

		if(($query = request::get('query', false)) != false)
		{
			$name = $this->get_alias();
			$where[] = "`{$name}`.`title` LIKE \"%{$query}%\"";
			$name = $gg->get_alias();
			$where[] = "`{$name}`.`name` LIKE \"%{$query}%\"";
		}

		if (!empty($where))
		{
			$this->where = join(' OR ', $where);
		}

		$this->connector->debug = true;
		$this->extjs_grid_json(array(
			'id',
			'on_offer',
			'recomended',
			'title',
			//'prepayment',
			//'payment_forward',
			array('di' => $gt, 'name' => 'name'),		// Тип
			array('di' => $gg, 'name' => 'name'),		// Группа
			array('di' => $gp, 'name' => 'title'),		// Прайс
			array('di' => $gp, 'name' => 'cost'),		// Цена
			array('di' => $gc, 'name' => 'name'),		// Колеекция
			array('di' => $gc, 'name' => 'discount'),	// Скидка
		));
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$data = $this->extjs_form_json(false,false);
		if($data['data']['group_id']>0)//9* 05022011 Вот это для того чтобы в форме название группы выводилось по дефолту в хитрый комбобокс с поиском 
		{
			$di = data_interface::get_instance('guide_group');
			$di->_flush();
			$di->what = array('id', 'name');
			$di->set_args(array('_sid' => $data['data']['group_id']));
			$name = $di->extjs_form_json(false,false);
			$data['data']['group_name'] = $name['data']['name'];
		}
		response::send($data, 'json');
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
		$this->_flush();
		$this->what = array('id');
		$records = $this->_get();
		$ids = array();
		foreach ($records as $rec)
			$ids[] = $rec->id;
		
		$oi = data_interface::get_instance('order_item');
		if (!$oi->item_exists($ids))
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
		}
		else
		{
			$data = array(
				'success' => false,
				'errors' => 'Данный элемент привязан к заказам.'
			);
		}

		response::send($data, 'json');
	}
}
?>
