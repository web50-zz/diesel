<?php
/**
*	Data Interface "Registry"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_registry extends data_interface
{
	public $title = 'Registry';

	/**
	* @var	string	$cfg	DB configurations name
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	DB name
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Tables name
	*/
	protected $name = 'registry';
	
	/**
	* @var	array	$fields	Tables configuration
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'name' => array('type' => 'string'),		// Имя параметра
		'type' => array('type' => 'integer'),		// Тип параметра
		'value' => array('type' => 'text'),		// Значение параметра
		'comment' => array('type' => 'text'),		// Комментарий
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	/**
	*	Get records list in JSON
	* @access protected
	*/
	protected function sys_list()
	{
		$this->_flush();
		if (!empty($this->args['query']) && !empty($this->args['field']))
		{
			if($this->args['field'] != 'id')
			{
				$this->args["_s{$this->args['field']}"] = "%{$this->args['query']}%";
			}
			else
			{
				$this->args["_s{$this->args['field']}"] = "{$this->args['query']}";
			}
		}
		$this->extjs_grid_json();
	}
	
	/**
	*	Get record in JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}
	
	/**
	*	Set data to storage and return results in JSON
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		$this->extjs_set_json();
	}

	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_mset()
	{
		$records = (array)json_decode($this->get_args('records'), true);

		foreach ($records as $record)
		{
			$record['_sid'] = $record['id'];
			unset($record['id']);
			$this->_flush();
			$this->push_args($record);
			$this->insert_on_empty = true;
			$data = $this->extjs_set_json(false);
			$this->pop_args();
		}

		response::send(array('success' => true), 'json');
	}
	
	/**
	*	Unset data to storage and return results in JSON
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$this->extjs_unset_json();
	}
}
?>
