<?php
	$no_cache = false;
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
			$cont = '';
			$path = substr(realpath(dirname(__FILE__)),0,-4);
			$cache_path = $path.'/filestorage/min_cache';
			$out = str_replace('.','',substr($parts[3],-3));
			if(is_file($cache_path.'/'.$z) && $no_cache == false)
			{
				$cont = file_get_contents($cache_path.'/'.$z);
			}
			else
			{
				$files = explode(',',$_SESSION['paths'][$z]);
				$cont = '';
				if(count($files)>0)
				{
					foreach($files as $key=> $value)
					{
						//$cont .= file_get_contents($path.$value)."\n ;\n"; /* вставка ; оказалась лишней порою */
						$cont .= file_get_contents($path.$value)."\n \n";
					}
					if(strlen($cont)>0)
					{
						$fn = $cache_path.'/'.$z;
						$fd = fopen($fn,'w');
						fwrite($fd,$cont);
						fclose($fd);
					}
				}
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
		elseif($out == 'js')
		{
			header('Content-type: text/javascript');
		}
		else{
			throw new Exception('Not Found4');
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

