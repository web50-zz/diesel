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
	public static function upload_file($name, $storage_path = null, $real_name = null)
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
			$data['type'] = $fset['type'];
			$data['size'] = $fset['size'];
			if (!$storage_path) $storage_path = FILE_STORAGE_PATH;

			if ($real_name === null)
			{
				$k = 0;
				$file = '';
				do
				{
					$k++;
					$x = preg_split("/\./", $fset['name']);
					$data['real_name'] = md5($fset['name'] . time() . $k) . '.' . strtolower(array_pop($x));
					$file = $storage_path . $data['real_name'];
				}
				while(file_exists($file));
			}
			else
			{
				$data['real_name'] = $real_name;
				$file = $storage_path . $data['real_name'];
			}
			 error_reporting('E_ALL ^ E_NOTICE');//9* проблемы на ряде хостингов с варнинагми при исползованиии move_uploaded_file  valuehost в частноси
			if(!move_uploaded_file($fset['tmp_name'], $file))
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
	*	Сохранить файл загруженный по HTTP
	* @param	string	$name	Имя поля загружаемого файла
	*/
	public static function copy_file($source, $storage_path = null, $real_name = null)
	{
		try
		{
			if (!file_exists($source))
				throw new Exception("The source file '{$source}' not exists.");

			$data['name'] = basename($source);
			$data['type'] = mime_content_type($source);
			$data['size'] = filesize($source);
			if (!$storage_path) $storage_path = FILE_STORAGE_PATH;

			if ($real_name === null)
			{
				$k = 0;
				$file = '';
				do
				{
					$k++;
					$t = preg_split("/\./", $data['name']);
					$data['real_name'] = md5($data['name'] . time() . $k) . '.' . strtolower(array_pop($t));
					$file = $storage_path . $data['real_name'];
				}
				while(file_exists($file));
			}
			else
			{
				$data['real_name'] = $real_name;
				$file = $storage_path . $data['real_name'];
			}

			if(!copy($source, $file))
				throw new Exception('can`t copy file "' . $source . '" to "' . $file . '"');
			
			return $data;
		}
		catch(Exception $e)
		{
			dbg::write("ERROR: " . $e->getMessage());
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
	public static function replace_file($name, $old_file_name, $storage_path = null, $real_name = null)
	{
		$data = self::upload_file($name, $storage_path, $real_name);
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

	/**
	*	Записать содержимое в файл
	*/
	public static function write_to_file($file_name, $file_content)
	{
		//if (!is_writable($file_name)) throw new Exception('The file "' . $file_name . '" is NOT writable.');
		$fh = fopen($file_name, 'w');
		fwrite($fh, $file_content);
		fclose($fh);
	}
}
?>
