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

/* 9*  25072012 
	Sharpen function ported outside. See cridentials in the  func body
*/

	public function UnsharpMask($img, $amount, $radius, $threshold)    { 

		////////////////////////////////////////////////////////////////////////////////////////////////  
		////  
		////                  Unsharp Mask for PHP - version 2.1.1  
		////  
		////    Unsharp mask algorithm by Torstein Hønsi 2003-07.  
		////             thoensi_at_netcom_dot_no.  
		////               Please leave this notice.  
		////  
		///////////////////////////////////////////////////////////////////////////////////////////////  



		    // $img is an image that is already created within php using 
		    // imgcreatetruecolor. No url! $img must be a truecolor image. 

		    // Attempt to calibrate the parameters to Photoshop: 
		    if ($amount > 500)    $amount = 500; 
		    $amount = $amount * 0.016; 
		    if ($radius > 50)    $radius = 50; 
		    $radius = $radius * 2; 
		    if ($threshold > 255)    $threshold = 255; 
		     
		    $radius = abs(round($radius));     // Only integers make sense. 
		    if ($radius == 0) { 
			return $img; imagedestroy($img); break;        } 
		    $w = imagesx($img); $h = imagesy($img); 
		    $imgCanvas = imagecreatetruecolor($w, $h); 
		    $imgBlur = imagecreatetruecolor($w, $h); 
		     

		    // Gaussian blur matrix: 
		    //                         
		    //    1    2    1         
		    //    2    4    2         
		    //    1    2    1         
		    //                         
		    ////////////////////////////////////////////////// 
			 

		    if (function_exists('imageconvolution')) { // PHP >= 5.1  
			    $matrix = array(  
			    array( 1, 2, 1 ),  
			    array( 2, 4, 2 ),  
			    array( 1, 2, 1 )  
			);  
			imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h); 
			imageconvolution($imgBlur, $matrix, 16, 0);  
		    }  
		    else {  

		    // Move copies of the image around one pixel at the time and merge them with weight 
		    // according to the matrix. The same matrix is simply repeated for higher radii. 
			for ($i = 0; $i < $radius; $i++)    { 
			    imagecopy ($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h); // left 
			    imagecopymerge ($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50); // right 
			    imagecopymerge ($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50); // center 
			    imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h); 

			    imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 ); // up 
			    imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25); // down 
			} 
		    } 

		    if($threshold>0){ 
			// Calculate the difference between the blurred pixels and the original 
			// and set the pixels 
			for ($x = 0; $x < $w-1; $x++)    { // each row
			    for ($y = 0; $y < $h; $y++)    { // each pixel 
				     
				$rgbOrig = ImageColorAt($img, $x, $y); 
				$rOrig = (($rgbOrig >> 16) & 0xFF); 
				$gOrig = (($rgbOrig >> 8) & 0xFF); 
				$bOrig = ($rgbOrig & 0xFF); 
				 
				$rgbBlur = ImageColorAt($imgBlur, $x, $y); 
				 
				$rBlur = (($rgbBlur >> 16) & 0xFF); 
				$gBlur = (($rgbBlur >> 8) & 0xFF); 
				$bBlur = ($rgbBlur & 0xFF); 
				 
				// When the masked pixels differ less from the original 
				// than the threshold specifies, they are set to their original value. 
				$rNew = (abs($rOrig - $rBlur) >= $threshold)  
				    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))  
				    : $rOrig; 
				$gNew = (abs($gOrig - $gBlur) >= $threshold)  
				    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))  
				    : $gOrig; 
				$bNew = (abs($bOrig - $bBlur) >= $threshold)  
				    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))  
				    : $bOrig; 
				 
				 
					     
				if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) { 
					$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew); 
					ImageSetPixel($img, $x, $y, $pixCol); 
				    } 
			    } 
			} 
		    } 
		    else{ 
			for ($x = 0; $x < $w; $x++)    { // each row 
			    for ($y = 0; $y < $h; $y++)    { // each pixel 
				$rgbOrig = ImageColorAt($img, $x, $y); 
				$rOrig = (($rgbOrig >> 16) & 0xFF); 
				$gOrig = (($rgbOrig >> 8) & 0xFF); 
				$bOrig = ($rgbOrig & 0xFF); 
				 
				$rgbBlur = ImageColorAt($imgBlur, $x, $y); 
				 
				$rBlur = (($rgbBlur >> 16) & 0xFF); 
				$gBlur = (($rgbBlur >> 8) & 0xFF); 
				$bBlur = ($rgbBlur & 0xFF); 
				 
				$rNew = ($amount * ($rOrig - $rBlur)) + $rOrig; 
				    if($rNew>255){$rNew=255;} 
				    elseif($rNew<0){$rNew=0;} 
				$gNew = ($amount * ($gOrig - $gBlur)) + $gOrig; 
				    if($gNew>255){$gNew=255;} 
				    elseif($gNew<0){$gNew=0;} 
				$bNew = ($amount * ($bOrig - $bBlur)) + $bOrig; 
				    if($bNew>255){$bNew=255;} 
				    elseif($bNew<0){$bNew=0;} 
				$rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew; 
				    ImageSetPixel($img, $x, $y, $rgbNew); 
			    } 
			} 
		    } 
		    imagedestroy($imgCanvas); 
		    imagedestroy($imgBlur); 
		    return $img; 

	}


}
?>
