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
		list($this->source_size['width'], $this->source_size['height']) = getimagesize($source);
	}

	public function create($width, $height, $file_name = false)
	{
		$image = imagecreatetruecolor($width, $height);
		if (!$file_name)
			$file_name = md5($this->source_file . mktime()) . '.' . $this->source_ext;

		dbg::write(array('width' => $width, 'height' => $height, 'file_name' => $file_name));
		dbg::write(array_merge($this->source_size, array('file_name' => $this->source_file)));
		switch($this->source_ext)
		{
			case 'gif':
				$source = imagecreatefromgif($this->source_file);
				imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $this->source_size['width'], $this->source_size['height']);
				$result = imagegif($image, $this->path_to_storage . $file_name);
			break;
			case 'jpeg': case 'jpg':
				$source = imagecreatefromjpeg($this->source_file);
				imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $this->source_size['width'], $this->source_size['height']);
				$result = imagejpeg($image, $this->path_to_storage . $file_name, 100);
			break;
			case 'png':
				$source = imagecreatefrompng($this->source_file);
				imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $this->source_size['width'], $this->source_size['height']);
				$result = imagepng($image, $this->path_to_storage . $file_name, 100);
			break;
		}

		if ($result)
			return $file_name;
		else
			return FALSE;
	}
}
?>
