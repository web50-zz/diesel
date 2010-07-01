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
	public $title = 'Structure control';
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}

        public function process_page($page)
        {
		if ($page['module'])
		{
			try
			{
				$ui = user_interface::get_instance($page['module']);
				$content = $ui->call('content', array_merge(request::get(), array('_spid' => $page['id'])));
			}
			catch(Exception $e)
			{
				dbg::write('Error: '.$e->getMessage());
			}
		}

                $data = array(
                        'args' => request::get(),
                        'content' => $content
                        );
                
                $template = (!empty($page['template'])) ? $page['template'] : PUB_TEMPLATE;
		$tmpl = new tmpl($this->pwd() . 'templates/' . $template);
                $html = $tmpl->parse($data);
		response::send($html, 'html');
        }
	
	/**
	*	Main menu
	*/
	protected function pub_top_menu()
	{
		$st = data_interface::get_instance('structure');
		$tmpl = new tmpl($this->pwd() . 'main_menu.html');
		return $tmpl->parse($st->get_main_menu());
	}
	
	/**
	*	Sub menu
	*/
	protected function pub_sub_menu()
	{
		$st = data_interface::get_instance('structure');
		$tmpl = new tmpl($this->pwd() . 'sub_menu.html');
		return $tmpl->parse($st->get_sub_menu());
	}
	
	/**
	*	Menu "Thermometer"
	*/
	protected function pub_trunc_menu()
	{
		$st = data_interface::get_instance('structure');
		$tmpl = new tmpl($this->pwd() . 'trunc_menu.html');
		return $tmpl->parse($st->get_trunc_menu());
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
