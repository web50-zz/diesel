<?php
/**
*	Public site initialization
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
try
{
	$uri = (empty($_SERVER['REDIRECT_URL'])) ? '/' : $_SERVER['REDIRECT_URL'];
        $diStrc = data_interface::get_instance(SITE_DI);
        $page = $diStrc->get_page_by_uri($uri);
	define(PAGE_URI, $page['uri']);
	define(SRCH_URI, str_replace($page['uri'], "", $uri));
	define(PAGE_ID, $page['id']);
	
	if (!empty($page['redirect']))
	{
		response::redirect($page['redirect']);
	}
	else
	{
                $uiStrc = user_interface::get_instance(SITE_UI);
                $uiStrc->set_args(request::get());
                $uiStrc->process_page($page);
	}
}
catch(Exception $e)
{
	dbg::write('Error: '.$e->getMessage());
	response::header('404');
}
?>
