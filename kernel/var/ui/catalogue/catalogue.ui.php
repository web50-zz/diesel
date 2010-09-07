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
		if (preg_match('/item_(\d+)/', SRCH_URI, $matches))
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
		$df = data_interface::get_instance('catalogue_file');
		$data = $di->get_item();
		$diStyles = data_interface::get_instance('guide_style');
		$data['styles'] = $diStyles->get_styles_in_item($id);
		$data['args'] = array_merge($this->get_args(), $this->parse_uri());
		$data['storage'] = "/{$df->path_to_storage}";
		return $this->parse_tmpl('item.html', $data);
	}

	/**
	*	Вывести список товаров в каталоге
	*/
	private function get_list()
	{
		$limit = 20;
		$page = request::get('page', 1);
		$di = data_interface::get_instance('catalogue_item');
		$df = data_interface::get_instance('catalogue_file');
		$di->set_args(array(
			'sort' => 'id',
			'dir' => 'DESC',
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
		));
		$di->set_args($this->get_args(), true);
		$di->set_args($this->parse_uri(), true);
		$data = $di->get_items();
		$data['page'] = $page;
		$data['limit'] = $limit;

		$cart = data_interface::get_instance('cart');
		$data['cart'] = $cart->_list();
		$pager = user_interface::get_instance('pager');
		//dbg::show($_SERVER);
		$data['pager'] = $pager->get_pager(array('page' => $page, 'total' => $data['total'], 'limit' => $limit, 'prefix' => $_SERVER['QUERY_STRING']));
		$data['search'] = $this->get_search_form();
		$data['filters'] = $this->get_filters();
		$data['storage'] = "/{$df->path_to_storage}";
		$data['args'] = $di->get_args();
		return $this->parse_tmpl('default.html',$data);
	}

	/**
	*	Форма поиска по каталогу
	*/
	private function get_search_form()
	{
		$data = request::get();
		return $this->parse_tmpl('search_form.html', $data);
	}

	/**
	*	Обработать SRCH_URI
	*/
	private function parse_uri()
	{
		$args = array();

		if (preg_match_all('/((\w+)\/(\d+))/', SRCH_URI, $matches))
		{
			foreach ($matches[2] as $i => $key)
			{
				$value = intval($matches[3][$i]);
				if ($value > 0)
				{
					switch($key)
					{
						case 'label':
							$args['_scollection_id'] = $value;
						break;
						case 'type':
							$args['_stype_id'] = $value;
						break;
						case 'group':
							$args['_sgroup_id'] = $value;
						break;
						case 'style':
							$args['_sstyle_id'] = $value;
						break;
					}
				}
			}
		}

		return $args;
	}

	/**
	*	Подготовить список фильтров
	*/
	private function get_filters()
	{
		$uri = (empty($_SERVER['REDIRECT_URL'])) ? '/' : $_SERVER['REDIRECT_URL'];
		$filters = array();

		if (preg_match("/label\/(\d+)\//", $uri))
			$filters[] = array('title' => 'Коллекция', 'uri' => preg_replace('/label\/\d+\//', '', $uri));
		if (preg_match("/group\/(\d+)\//", $uri))
			$filters[] = array('title' => 'Группа', 'uri' => preg_replace('/group\/\d+\//', '', $uri));
		if (preg_match("/type\/(\d+)\//", $uri))
			$filters[] = array('title' => 'Тип', 'uri' => preg_replace('/type\/\d+\//', '', $uri));
		if (preg_match("/style\/(\d+)\//", $uri))
			$filters[] = array('title' => 'Стиль', 'uri' => preg_replace('/style\/\d+\//', '', $uri));

		return $this->parse_tmpl('filters.html', array('filters' => $filters));
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
