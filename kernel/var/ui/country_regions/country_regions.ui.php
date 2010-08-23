<?php
/**
*	UI FAQ 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_country_regions extends user_interface
{
	public $title = 'Страны и регионы справочник';

	protected $deps = array(
		'main' => array(
			'country_regions.region_list',
			'country_regions.region_form',
			'country_regions.country_list',
			'country_regions.country_form',
		),
	);

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_content()
        {
		$data = array();
		return $this->parse_tmpl('default.html',$data);
	}

	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'country_regions.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма редактирования региона 
	*/
	public function sys_region_form()
	{
		$tmpl = new tmpl($this->pwd() . 'region.form.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*      Грид списка регионов 
	*/
	public function sys_region_list()
	{
		$tmpl = new tmpl($this->pwd() . 'region.list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*  Грид стран       
	*/
	public function sys_country_list()
	{
		$tmpl = new tmpl($this->pwd() . 'country.list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*  Форма  страны       
	*/
	public function sys_country_form()
	{
		$tmpl = new tmpl($this->pwd() . 'country.form.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
