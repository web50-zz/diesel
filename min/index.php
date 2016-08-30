<?php
        session_start();
        $z = $_GET['z'];
        if($_SESSION['paths'][$z])
        {
                $files = explode(',',$_SESSION['paths'][$z]);
                $cont = '';
                $path = substr(realpath(dirname(__FILE__)),0,-4);
                foreach($files as $key=> $value)
                {
                        $out = substr($value,-3);
                        $cont .= file_get_contents($path.$value)."\n ;\n";
                }
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
?>

