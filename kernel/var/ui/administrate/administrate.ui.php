<?php
/**
*	UI "Administrate"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	Fluger CMS
*/
class ui_administrate extends user_interface
{
	public $title = 'Administrate';

	protected $deps = array(
		'main' => array(
			'administrate.menu',
			'administrate.home',
		),
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*	Main workspace
	* @access	protected
	*/
	protected function sys_workspace()
	{
		$tmpl = new tmpl($this->pwd() . 'workspace.html');
		$su = data_interface::get_instance(AUTH_DI);
		response::send($tmpl->parse(array(
			'user' => $su->get_user()
		)), 'html');
	}
	
	/**
	*       Main administrate interface 
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'administrate.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Main menu
	*/
	protected function sys_menu()
	{
		$tmpl = new tmpl($this->pwd() . 'menu.js');
		$data = array(
			'menu' => $this->generate_menu()
		);
		response::send($tmpl->parse($data), 'js');
	}

	/**
	*	Генерация главного меню
	*/
	private function generate_menu()
	{
		$menu = array(
			array('text' => 'Запросы', 'icon' => 'comment', 'ui' => 'logistic_request', 'ep' => 'main'),
			array('text' => 'Заявки', 'icon' => 'folder_page', 'ui' => 'logistic_order', 'ep' => 'main'),
			array('text' => 'Справочники', 'icon' => 'book', 'menu' => array(
				array('text' => 'Услуги', 'icon' => 'book', 'ui' => 'services', 'ep' => 'main'),
				array('text' => 'Контрагенты', 'icon' => 'lorry', 'ui' => 'contractor', 'ep' => 'main'),
			)),
			array('text' => 'Приложения', 'icon' => 'application_double', 'menu' => array(
				array('text' => 'Файл-менеджер', 'icon' => 'application_view_tile', 'ui' => 'file_manager', 'ep' => 'main'),
			)),
			array('text' => 'Администрирование', 'icon' => 'shield', 'menu' => array(
				array('text' => 'Пользователи', 'icon' => 'user', 'ui' => 'user', 'ep' => 'main'),
				array('text' => 'Группы', 'icon' => 'group', 'ui' => 'group', 'ep' => 'main'),
				array('text' => 'Безопасность', 'icon' => 'shield', 'ui' => 'security', 'ep' => 'main'),
			)),
			array('text' => 'Страницы помощи', 'icon' => 'help', 'ui' => 'help', 'ep' => 'main'),
			array('->'),
			array('text' => 'Выход', 'icon' => 'logout', 'href' => '/xxx/login/?cll=logout'),
		);

		if (UID == 1)
		{
			return $menu;
		}
		else
		{
			$adi = data_interface::get_instance(AUTH_DI);
			$ifs = $adi->get_available_interfaces('ui');
			return $this->process_menu($menu, $ifs);
		}
	}

	private function process_menu($menu, $ifs)
	{
		foreach ($menu as $i => $item)
		{
			if (is_array($item['menu']))
				$menu[$i]['menu'] = $this->process_menu($item['menu'], $ifs);
			else if (!$this->check_menu_item($item, $ifs))
				unset($menu[$i]);
		}

		return $menu;
	}

	private function check_menu_item($item, $ifs)
	{
		// Если элемент меню не вызывает приложение, то мы его не обрабатываем
		if (!isset($item['ui']) && !isset($item['ep']))
			return true;

		// Перебираем список прав доступа и ищем совпадения
		foreach ($ifs as $if)
			// Если совпадения нашли, то возвращаем TRUE
			if ($item['ui'] == $if['interface'] && "sys_{$item['ep']}" == $if['entry_point'] && $if['type'] == 'ui')
				return true;

		// Иначе FALSE
		return false;
	}
	
	/**
	*       Main Home Tab
	*/
	protected function sys_home()
	{
		$tmpl = new tmpl($this->pwd() . 'home.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	JS locale file
	*/
	protected function sys_app_lang()
	{
		$locale = $this->args['locale'];

		if (file_exists(LOCALES_PATH . "app-lang-{$locale}.js"))
			$file = LOCALES_PATH . "app-lang-{$locale}.js";
		else
			$file = LOCALES_PATH . "app-lang-default.js";

		response::send(file_get_contents($file), 'js');
	}
}
?>
