<?php
	try{
		$parts = explode('/',$_SERVER['REQUEST_URI']);
		if(count($parts) != 4)
		{
			throw new Exception('not found');
		}
		if(strlen($parts[2]) != 32)
		{
			throw new Exception('not found2');
		}
		$z = $parts[2];
		session_start();
		if($_SESSION['paths'][$z])
		{
			$files = explode(',',$_SESSION['paths'][$z]);
			$cont = '';
			$path = substr(realpath(dirname(__FILE__)),0,-4);
			foreach($files as $key=> $value)
			{
				$out = substr($value,-3);
				//$cont .= file_get_contents($path.$value)."\n ;\n"; /* вставка ; оказалась лишней порою */
				$cont .= file_get_contents($path.$value)."\n \n";
			}
		}else{
			throw new Exception('not found 3');
		}
		if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")!==false){
			$cont = gzencode($cont);
			header("Content-Encoding: gzip");
		}
		if($out == 'css')
		{
			header('Content-type: text/css');
		}
		else
		{
			header('Content-type: text/javascript');
		}
		header ("cache-control: must-revalidate");
		$offset = 60 * 60;
		$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset);
		echo($cont);
	}
	catch(Exception $e)
	{
		header(" ",true,'404');
		echo('Not Found');
	}
?>

