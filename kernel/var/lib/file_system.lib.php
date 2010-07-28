<?php
/**
*	Библиотека для работы с деревьями
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class file_system
{
	/**
	*	Сохранить файл загруженный по HTTP
	* @param	string	$name	Имя поля загружаемого файла
	*/
	public static function upload_file($name, $storage_path = null)
	{
		try
		{
			if (!isset($_FILES[$name])) throw new Exception('The fileset "'.$name.'" is not set.');
			$fset = $_FILES[$name];
			$uploadErrors = array(
				UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
				UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
				UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
				UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
				UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
				UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
				UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
			);
			
			$errorCode = $fset['error'];
			if ($errorCode === UPLOAD_ERR_NO_FILE) return array();
			if ($errorCode !== UPLOAD_ERR_OK)
				if (isset($uploadErrors[$errorCode]))
					throw new Exception($uploadErrors[$errorCode]);
				else
					throw new Exception("Unknown error uploading file.");
			
			$data['name'] = $fset['name'];
			$data['real_name'] = md5($fset['name'] . mktime()) . '.' . array_pop(preg_split("/\./", $fset['name']));
			$data['type'] = $fset['type'];
			$data['size'] = $fset['size'];
			if (!$storage_path) $storage_path = FILE_STORAGE_PATH;
			$file = $storage_path . $data['real_name'];
			if(!@copy($fset['tmp_name'], $file))
				throw new Exception('Error while copy "' . $fset['tmp_name'] . '" to "' . $file . '"');
			
			return $data;
		}
		catch(Exception $e)
		{
			dbg::write("Error while uploading file: " . $e->getMessage());
			return false;
		}
	}
	
	/**
	*	Удалить существующий файл из хранилища
	* @param	string	$real_name	Имя удаляемого файла
	*/
	public static function remove_file($real_name, $storage_path = null)
	{
		try
		{
			if (empty($real_name)) throw new Exception('The file name not present.');
			if (!$storage_path) $storage_path = FILE_STORAGE_PATH;
			$file = $storage_path . $real_name;
			if (file_exists($file))
				if (!@unlink($file))
					throw new Exception('Error while unlink "' . $file . '"');
			return true;
		}
		catch(Exception $e)
		{
			dbg::write("Error while removing file \"{$file}\": " . $e->getMessage());
			return false;
		}
	}
	
	/**
	*	Заменить существующий файл на загруженный по HTTP
	* @param	string	$name		Имя поля загружаемого файла
	* @param	string	$old_file_name	Имя удаляемого файла
	*/
	public static function replace_file($name, $old_file_name, $storage_path = null)
	{
		$data = self::upload_file($name, $storage_path);
		if ($data !== false && !empty($data))
		{
			if (!self::remove_file($old_file_name, $storage_path))
			{
				self::remove_file($data['real_name'], $storage_path);
				return false;
			}
		}
		return $data;
	}
	
	/**
	*	Вывести содержимое файла в буфер вывода
	* @param	string	$file_name		Имя файла
	*/
	public static function file_content($file_name, $storage_path = null)
	{
		if (!$storage_path) $storage_path = FILE_STORAGE_PATH;
		$file = $storage_path . $file_name;
		if (!file_exists($file)) throw new Exception('The file "' . $file . '" NOT exists.');
		readfile($file);
	}
}
?>
