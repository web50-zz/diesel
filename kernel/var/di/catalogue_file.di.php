<?php
/**
*	Интерфейс данных "Файлы каталога"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_catalogue_file extends data_interface
{
	public $title = 'Файлы каталога';

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
	protected $name = 'catalogue_file';

	/**
	* @var	string	$path_to_storage	Путь к хранилищу файлов каталога
	*/
	protected $path_to_storage = 'storage/';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'catalogue_item_id' => array('type' => 'foreign', 'alias' => 'ciid'),
		'created_datetime' => array('type' => 'datetime'),
		'changed_datetime' => array('type' => 'datetime'),
		'title' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'real_name' => array('type' => 'string'),
		'comment' => array('type' => 'text'),
		'item_type' => array('type' => 'integer'),
		'type' => array('type' => 'string'),
		'size' => array('type' => 'integer'),
	);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	/**
	*	Получить путь к хранилищу файлов на файловой системе
	*/
	public function get_path_to_storage()
	{
		return BASE_PATH . $this->path_to_storage;
	}

	/**
	*	Получить список записей для выбора предпросмотра товара
	*/
	protected function sys_preview_combo()
	{
		$this->_flush();
		$this->set_args(array('_sitem_type' => '0'), true);
		$this->what = array('real_name', 'name');
		$this->_get();
		response::send(array(
			'success' => true,
			'records' => array_merge(array(0 => array('real_name' => '', 'name' => 'Нет изображения')), (array)$this->get_results())
		), 'json');
	}
	
	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json(array('id', 'title', 'name', 'item_type', 'size'));
	}
	
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json(array('catalogue_item_id', 'title', 'item_type', 'comment'));
	}
	
	/**
	*	Добавить \ Сохранить файл
	*/
	protected function sys_set()
	{
		$fid = $this->get_args('_sid');

		if ($fid > 0)
		{
			$this->_flush();
			$this->_get();
			$file = $this->get_results(0);
			$old_file_name = $file->real_name;
		}

		$file = (!empty($old_file_name)) ? file_system::replace_file('file', $old_file_name, $this->get_path_to_storage()) : file_system::upload_file('file', $this->get_path_to_storage());
		
		if ($file !== false)
		{
			if (!($fid > 0)) $file['created_datetime'] = date('Y-m-d : H:i:s');
			$file['changed_datetime'] = date('Y-m-d : H:i:s');
			$this->set_args($file, true);
			$this->_flush();
			$this->insert_on_empty = true;
			$result = $this->extjs_set_json(false);
		}
		else
		{
			$result = array('success' => false);
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
		$files = $this->_get();
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		
		if (!empty($files))
			foreach ($files as $file)
				file_system::remove_file($file->real_name, $this->get_path_to_storage());

		response::send($data, 'json');
	}

	/**
	*	Remove all files
	* @access public
	* @param	array|integer	$ids	The ID for `catalog_item_id` field
	*/
	public function remove_files($ids)
	{
		// TODO: Проверить, почему не удаляются файлы с файловой системы.
		if (is_array($ids))
		{
			foreach ($ids as $id)
			{
				$this->_flush();
				$this->set_args(array('_sciid' => $id));
				$this->extjs_unset_json(false);
			}
		}
		else
		{
			$this->_flush();
			$this->set_args(array('_sciid' => $ids));
			$this->extjs_unset_json(false);
		}
	}
}
?>
