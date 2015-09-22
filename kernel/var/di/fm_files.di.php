<?php
/**
*	Интерфейс данных "Файлы"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @package	CFsCMS2(PE)
*/
class di_fm_files extends data_interface
{
	public $title = 'Файлы';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'fm_files';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'fm_folders_id' => array('type' => 'foreign', 'alias' => 'pid'),
		'created_date' => array('type' => 'datetime'),
		'changed_date' => array('type' => 'datetime'),
		'publication_date' => array('type' => 'datetime'),
		'edition_date' => array('type' => 'datetime'),
		'title' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'real_name' => array('type' => 'string'),
		'comment' => array('type' => 'text'),
		'type' => array('type' => 'string'),
		'display_order' => array('type' => 'integer'),
		'size' => array('type' => 'integer'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	protected function pub_get()
	{
		if ($file = $this->get_file($this->args['id']))
		{
			header ('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT');
			header ('Cache-Control: no-cache, must-revalidate');  // HTTP/1.1
			header ('Pragma: no-cache');                          // HTTP/1.0
			header ('Accept-Ranges: bytes');
			header ('Content-Length: ' . $file->size);
			header ('Connection: close');
			header ('Content-Type: ' . $file->type . '; charset=' . CHARSET);
			if (isset($this->args['download'])) header ('Content-Disposition: attachment; filename="' . $file->name . '"');
			//in some cases buffer have shit so clean it before 
			ob_clean();
			flush();
			file_system::file_content($file->real_name);
		}
		else
		{
			header("HTTP/1.0 404 Not Found");
		}
	}
	
	public function get_file($id)
	{
		$sql = 'SELECT * FROM `'.$this->name.'` WHERE `id` = ' . intval($id);
		$this->_get($sql);
		return $this->get_results(0);
	}
	
	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json(array('id', 'title', 'name', 'type', 'size','real_name','display_order'));
	}
	
	protected function sys_item()
	{
		$this->_flush();
		$this->extjs_form_json();
	}
	
	/**
	*	Добавить \ Сохранить файл
	*/
	public function sys_set($silent =  false)
	{
		$fid = $this->args['_sid'];
		$from_source = $this->get_args('source',false);

		if ($fid > 0)
		{
			$file = $this->get_file($fid);
			$old_file_name = $file->real_name;
		}
			if($from_source != false)
			{
				$file = file_system::copy_file($from_source,'', $old_file_name);
			}
			else
			{
				$file = (!empty($old_file_name)) ? file_system::replace_file('file', $old_file_name) : file_system::upload_file('file');
			}

		
		if ($file !== false)
		{
			if (!($fid > 0)) $file['created_date'] = date('Y-m-d : H:i:s');
			$file['changed_date'] = date('Y-m-d : H:i:s');
			$this->set_args($file, true);
			$this->_flush();
			$this->insert_on_empty = true;
			$result = $this->extjs_set_json(false);
		}
		else
		{
			$result = array('success' => false);
		}
		if($silent == true)
		{
			return $result;
		}
		response::send(response::to_json($result), 'html');
	}
	
	/**
	*	Удалить файл[ы]
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$files = $this->_get()->get_results();
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		
		if (!empty($files))
			foreach ($files as $file)
				file_system::remove_file($file->real_name);

		response::send($data, 'json');
	}
}
?>
