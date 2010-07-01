<?php
/**
*	The response library
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	CFsCMS2(PE)
*/
class response
{
	/**
	* @access	public
	* @param	string	$content	The content
	* @param	string	$type		The content type
	*/
	public static function header($code)
	{
		switch($code)
		{
			case '200':
				header("HTTP/1.0 200 Ok");
			break;
			case '401':
				header("HTTP/1.0 401 Unauthorized");
			break;
			case '403':
				header("HTTP/1.0 403 Forbidden");
			break;
			case '404':
				header("HTTP/1.0 404 Not Found");
			break;
			default:
				throw new Exception('Unknown header`s code.');
		}
		die();
	}
	
	/**
	* @access	public
	* @param	string	$content	The content
	* @param	string	$type   	The content type
        * @param        boolean $no_cache       Disable caching, default TRUE
	*/
	public static function send($content, $type, $no_cache = true)
	{
		switch(strtolower($type))
		{
			case 'html':
				header('Content-Type: text/html; charset=' . CHARSET);
			break;
			case 'xml':
				header('Content-Type: text/xml; charset=' . CHARSET);
			break;
			case 'javascript':
			case 'js':
				header('Content-Type: text/javascript; charset=' . CHARSET);
			break;
			case 'json':
				header('Content-Type: text/x-json; charset=UTF-8');
				if (is_array($content))
					$content = response::to_json($content);
			break;
			case 'error':
				$data = array(
					'success' => false,
					'errors' => $content
					);
				header('Content-Type: text/x-json; charset=UTF-8');
				$content = response::to_json($data);
			break;
			case 'text':
			case 'txt':
			default:
				header('Content-Type: text/plain; charset=' . CHARSET);
			break;
		}
		
                if ($no_cache)
                {
                        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );  // disable IE caching
                        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" ); 
                        header( "Cache-Control: no-cache, must-revalidate" ); 
                        header( "Pragma: no-cache" );
                }

		die($content);
	}
	
	/**
	*	Redirect to passed URL
	* @param	string	$url	URL to redirect
	*/
	public static function redirect($url)
	{
		header("Status: 302 Moved");
		header("Location: $url");
		die();
	}
	
	/**
	*	Convert passed data to JSON
	* @param	mixed	$data	Data to convert
	* @return	string	Data converted to JSON
	*/
	public static function to_json($data)
	{
		if (strtoupper(CHARSET) != 'UTF-8' AND strtoupper(CHARSET) != 'UTF8')
		{
			$data = response::to_unicode($data);
		}
		return json_encode($data);
	}
	
	/**
	*	Convert passed data to XML
	* @param	mixed	$data	Data to convert
	* @return	string	Data converted to XML
	*/
	public static function to_xml($data)
	{
		$xml_template = '<?xml version="1.0" encoding="UTF-8"?>
<package>
{__(foreach($. AS @n => @v)__}{__apply branch(@n, @v)__}{__foreach)__}
</package>
{__(template branch(name, value)__}
<{__@name__}>
{__(if(is_array(@value))__}
{__(foreach(@value AS @n => @v)__}{__apply branch(@n, @v)__}{__foreach)__}
{__else__}
<![CDATA[{__@value__}]]>
{__if)__}
</{__@name__}>
{__template)__}';
		$tmpl = new tmpl($xml_template, 'text');
		return $tmpl->_parse($data);
	}
	
	/**
	*	Convert passed data to UTF-8
	* @param	mixed	$data	Data to convert
	* @return	mixed	Converted data to UTF-8
	*/
	public static function to_unicode($data)
	{
		foreach ($data as $key => $value)
			if (is_array($value))
				$data[$key] = response::to_unicode($value);
			else
				$data[$key] = iconv(CHARSET, 'UTF-8', $value);
		
		return $data;	
	}
}
?>
