<?php
/**
*	UI The structure of site
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class ui_market_menu extends ui_structure 
{

public $title = 'Market menu';


	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
}
?>
