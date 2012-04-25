<?php
/**
*	Библиотека создания thumbnail
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class thumbnail
{
	/**
	* @var	string	source_file	The name of source file
	*/
	private $source_file;

	/**
	* @var	string	source_ext	The extension of source file
	*/
	private $source_ext;

	/**
	* @var	array	source_size	The size of source file
	*/
	private $source_size = array();

	/**
	* @var	array	path_to_storage	The path to store thumbnails
	*/
	private $path_to_storage = array();

	public function __construct($source, $path_to_storage)
	{
		$this->source_file = $source;
		$this->source_ext = strtolower(array_pop(preg_split('/\./', $source)));
		$this->path_to_storage = $path_to_storage;
		list($this->source_size['width'], $this->source_size['height']) = getimagesize($this->path_to_storage . $source);
	}

	public function create($width, $height, $file_name = false)
	{
		$image = imagecreatetruecolor($width, $height);
		if (!$file_name)
			$file_name = md5($this->source_file . mktime()) . '.' . $this->source_ext;

		//dbg::write(array('width' => $width, 'height' => $height, 'file_name' => $file_name));
		//dbg::write(array_merge($this->source_size, array('file_name' => $this->source_file)));
		switch($this->source_ext)
		{
			case 'gif':
				$source = imagecreatefromgif($this->path_to_storage . $this->source_file);
				imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $this->source_size['width'], $this->source_size['height']);
				$result = imagegif($image, $this->path_to_storage . $file_name);
			break;
			case 'jpeg': case 'jpg':
				$source = imagecreatefromjpeg($this->path_to_storage . $this->source_file);
				imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $this->source_size['width'], $this->source_size['height']);
				$result = imagejpeg($image, $this->path_to_storage . $file_name, 100);
			break;
			case 'png':
				$source = imagecreatefrompng($this->path_to_storage . $this->source_file);
				imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $this->source_size['width'], $this->source_size['height']);
				$result = imagepng($image, $this->path_to_storage . $file_name, 100);
			break;
		}

		if ($result)
			return $file_name;
		else
			return FALSE;
	}
/* 9* 
	$image_to   - file name with fullpath to image 
	$sType      - 'jpeg,png,gif' 
	$fwatermark - filename with fullpath to png watermark
	watermark will be set at left bottom of the image
*/
	public	function SetWatermark($image_to, $sType, $sfWatermark)
	{
		if(!file_exists($image_to))
		{
			return false;
		}
		if('png' == $sType)
		{
			$rImg = imagecreatefrompng($image_to);
		}
		if('jpeg' == $sType || 'jpg' == $sType)
		{
			$rImg = imagecreatefromjpeg($image_to);
		}
		if('gif' == $sType) 
		{
			$rImg = imagecreatefromgif($image_to);
		}
		$iDelta = 5;
		$xImg = imagesx($rImg);
		$yImg = imagesy($rImg);
		$r = imagecreatefrompng($sfWatermark);
		$x = imagesx($r);
		$y = imagesy($r);
		$xDest= $iDelta;
		$yDest=$yImg-($y + $iDelta);
		imageAlphaBlending($rImg,TRUE);
		imagecopy($rImg,$r, $xDest,$yDest, 0,0, $x,$y);
		if('png' == $sType) imagepng($rImg,$image_to);
		if('jpeg' == $sType || 'jpg' == $sType) imagejpeg($rImg,$image_to,100);
		if('gif' == $sType) imagegif($rImg,$image_to);
		imagedestroy($r);
		imagedestroy($rImg);
		return true;
	}
}
?>
