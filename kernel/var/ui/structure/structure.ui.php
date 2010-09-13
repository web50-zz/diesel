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
			'structure.page_view_points',
		),
		'site_tree' => array(
			'structure.node_form',
		),
		'page_view' => array(
			'structure.page_view_point',
		),
		'page_view_points' => array(
			'structure.page_view_point_form',
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
		$divp->_flush();
		$divp->set_args(array('_spid' => $page['id']));
		if (SRCH_URI != "") $divp->set_args(array('_sdeep_hide' => 0), true);
		$divp->set_order('view_point');
		$divp->set_order('order');
		$vps = $divp->_get();
		$css_resources = array();

		foreach ($vps as $vp)
		{
			try
			{
				$ui = user_interface::get_instance($vp->ui_name);
				$call = !empty($vp->ui_call) ? $vp->ui_call : 'content';
				$data["view_point_{$vp->view_point}"][] = $ui->call($call, json_decode($vp->ui_configure, true));
				// 9*  css output
				if(!$css_resource[$vp->ui_name])
				{
					if($path = $ui->get_resource_path($vp->ui_name.'.css'))
					{
						$data['css_resources'][] = $path;
					}
					$css_resource[$vp->ui_name] = true;
				}
				//9* js output
				if(!$js_resource[$vp->ui_name])
				{
					if($path = $ui->get_resource_path($vp->ui_name.'.res.js'))
					{
						$data['js_resources'][] = $path;
					}
					$js_resource[$vp->ui_name] = true;
				}

			}
			catch(exception $e)
			{
				dbg::write('error: '.$e->getmessage());
			}
		}
		// 9* adding structure css resource to css output
		if($path = $this->get_resource_path($this->interfaceName.'.css'))
		{
			$data['css_resources'][] = $path;
		}
        		if($path = $this->get_resource_path($this->interfaceName.'.res.js'))
		{
			$data['js_resources'][] = $path;
		}
        
                $template = (!empty($page['template'])) ? $page['template'] : pub_template;
		$html = $this->parse_tmpl('main/'.$template, $data);
		response::send($html, 'html');
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
	*	ExtJS Grid - Список view points для страницы
	*/
	protected function sys_page_view_points()
	{
		$tmpl = new tmpl($this->pwd() . 'page_view_points.js');
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
	*	List of available templates to assign as 'main template' for page
	*/
	protected function sys_templates()
	{
		$data = $this->get_tdir_files_list();
		if(count($data) == 0)
		{
			dbg::write("WARNING!! NO TEMPLATES available to assign as main at sys_templates()");
			if(defined('CURRENT_THEME_PATH'))
			{
				
				dbg::write("Trying to get template list from default kernel locations");
				$data = $this->get_tdir_files_list('default');
				if(count($data) == 0)
				{
					dbg::write("WARNING!! NO TEMPLATES  available AT ALL(default locations also) to assign as main at sys_templates()");
				}
				else
				{
					dbg::write("Success");
				}
			}
		}
		response::send($data, 'json');
	}
	
	/**
	*  reads global template filenames from possible locations 
	* ( 'default' - force kernel ui path. 'no parms' - current theme path or default kernel if available
	*/
	public function get_tdir_files_list($mode = '')
	{
		$tdir = $this->get_resource_dir_path($mode) . 'main';
		$dh = dir($tdir);
		$data = array();
		while(false !== ($tmpl = $dh->read()))
			if (!is_dir($tmpl))
			{
				if(preg_match("/^.+\.html$/",$tmpl))
				{
					$data[] = array('template' => $tmpl);
				}
			}
		return $data;
	}
}
?>
