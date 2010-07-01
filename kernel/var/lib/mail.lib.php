<?php
class lib_email
{
	var $random = 0;
	function send_message($mail_to, $email, $name, $subject, $text, $cc='')
	{
		global $random;
		$headers = $this->make_mail_header($name, $email, $cc);
		$content ="\n$text\n\n";
		$content .= '------------' . $this->random . "--\n";
		//$header = convert_cyr_string($header, 'w', 'k');
		//$content = convert_cyr_string($content, 'w', 'k');
		if(!mail($mail_to, $subject, $content, $headers, "-f$email")) return false; return true;
	}
	
	function send_html_message($mail_to, $email, $name, $subject, $text, $cc='')
	{
		global $random;
		$headers = $this->make_html_mail_header($name, $email, $cc);
		$content = "\n$text\n\n";
		$content .= '------------' . $this->random . "--\n";
		//$header = convert_cyr_string($header, 'w', 'k');
		//$content = convert_cyr_string($content, 'w', 'k');
		if(!mail($mail_to, $subject, $content, $headers, "-f$email")) return false; return true;
	}
	
	function make_mail_header($name, $email, $cc='')
	{
		if(empty($name))
			$f = $email;
		else
			$f = '"'.$name.'" <'.$email.'>';
		
		$this->random = uniqid (rand());
		
		$headers .= "From: $f\n";
		$headers .= "X-Sender: <$email>\n";
		if(!empty($cc)) $headers .= "Cc: $cc\n";
		$headers .= "X-Mailer: seabuy.ru Mailer (c) Seabuy <support@seabuy.ru>\n"; // mailer
		$headers .= "Return-Path: <".$email.">\n";  // Return path for errors
		$headers .= "Mime-Version: 1.0\n";
		$headers .= 'Content-Type: multipart/mixed; boundary="----------' . $this->random . '"' . "\n\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n\n";
		$headers .= "------------" . $this->random."\n";
		$headers .= 'Content-Type: text/plain; charset="windows-1251"' . "\n";
		$headers .= 'Content-Transfer-Encoding: 8bit' . "\n\n";
		return $headers;
	}
	
	function make_html_mail_header($name, $email, $cc='')
	{
		if(empty($name))
			$f = $email;
		else
			$f = '"'.$name.'" <'.$email.'>';

		$this->random = uniqid (rand());
		
		$headers .= "From: $f\n";
		if(!empty($cc)) $headers .= "Cc: ".$cc."\n";
		$headers .= "X-Sender: <".$email.">\n";
		$headers .= "X-Mailer: seabuy.ru Mailer (c) Seabuy <support@seabuy.ru>\n"; // mailer
		$headers .= "Return-Path: <".$email.">\n";  // Return path for errors
		$headers .= "Mime-Version: 1.0\n";
		$headers .= 'Content-Type: multipart/mixed; boundary="----------' . $this->random . '"' . "\n\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n\n";
		$headers .= "------------" . $this->random."\n";
		$headers .= 'Content-Type: text/html; charset="windows-1251"' . "\n";
		$headers .= 'Content-Transfer-Encoding: 8bit' . "\n\n";
		return $headers;
	}
}
?>
