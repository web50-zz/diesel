<?php
/**
*	UI  structure of market product types
*
* @author	9* ported from structure UI of Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package      SBIN Diesel	
*/
class ui_market_types extends user_interface
{
	public $title = 'Структура';

	protected $deps = array(
		'main' => array(
			'market_types.tree',
		),
		'tree' => array(
			'market_types.node_form',
		)
	);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
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
	protected function sys_tree()
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
}
?>
