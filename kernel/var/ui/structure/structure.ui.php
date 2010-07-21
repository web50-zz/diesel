<?php
/**
*	UI The structure of site
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class ui_structure extends user_interface
{
	public $title = 'Структура';

	protected $deps = array(
		'main' => array(
			'structure.site_tree',
			'structure.page_view',
		),
		'site_tree' => array(
			'structure.node_form',
		),
		'page_view' => array(
			'structure.page_view_point',
		),
		'page_view_point' => array(
			'structure.page_view_point_form',
		)
	);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function process_page($page)
        {
		#if ($page['module'])
		#{
		#	try
		#	{
		#		$ui = user_interface::get_instance($page['module']);
		#		$content = $ui->call('content', json_decode($page['params'], true));
		#	}
		#	catch(exception $e)
		#	{
		#		dbg::write('error: '.$e->getmessage());
		#	}
		#}

                $data = array(
                        'args' => request::get(),
                        );

		$divp = data_interface::get_instance('ui_view_point');
		$divp->set_args(array('_spid' => $page['id']));
		$divp->_flush();
		$vps = $divp->_get();

		foreach ($vps as $vp)
		{
			try
			{
				$ui = user_interface::get_instance($vp->ui_name);
				$data["view_point_{$vp->view_point}"][] = $ui->call('content', json_decode($vp->ui_configure, true));
			}
			catch(exception $e)
			{
				dbg::write('error: '.$e->getmessage());
			}
		}
                
                $template = (!empty($page['template'])) ? $page['template'] : pub_template;
		$html = $this->parse_tmpl($template, $data);
		response::send($html, 'html');
        }
	
	/**
	*	main menu
	*/
	protected function pub_top_menu()
	{
		$st = data_interface::get_instance('structure');
		return $this->parse_tmpl('main_menu.html',$st->get_main_menu());
	}
	
	/**
	*	Sub menu
	*/
	protected function pub_sub_menu()
	{
		$st = data_interface::get_instance('structure');
		return $this->parse_tmpl('sub_menu.html',$st->get_sub_menu());
	}
	
	/**
	*	Menu "Thermometer"
	*/
	protected function pub_trunc_menu()
	{
		$st = data_interface::get_instance('structure');
		return $this->parse_tmpl('trunc_menu.html',$st->get_trunc_menu());
	}
	
	/**
	*       ExtJS UI for adm part
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'structure.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS UI Site Tree
	*/
	protected function sys_site_tree()
	{
		$tmpl = new tmpl($this->pwd() . 'site_tree.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS UI Site Tree
	*/
	protected function sys_node_form()
	{
		$tmpl = new tmpl($this->pwd() . 'node_form.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS UI Site Tree
	*/
	protected function sys_page_view()
	{
		$tmpl = new tmpl($this->pwd() . 'page_view.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS UI Site Tree
	*/
	protected function sys_page_view_point()
	{
		$tmpl = new tmpl($this->pwd() . 'page_view_point.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS UI Site Tree
	*/
	protected function sys_page_view_point_form()
	{
		$tmpl = new tmpl($this->pwd() . 'page_view_point_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*	List of available templates
	*/
	protected function sys_templates()
	{
		$tdir = $this->pwd() . 'templates';
		$dh = dir($tdir);
		$data = array();
		while(false !== ($tmpl = $dh->read()))
			if (!is_dir($tmpl))
				$data[] = array('template' => $tmpl);
		response::send($data, 'json');
	}
}
?>
