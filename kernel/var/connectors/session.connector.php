<?php
/**
*	Session connector
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class connector_session
{
	/**
	* @access	protected
	* @var	object	$di	Ссылка на интерфейс данных, для которого создается коннектор
	*/
	protected $di;
	
	public function __construct($di)
	{
		$this->di = $di;
	}
	
	/**
	*	Сбросить все служебные значения
	*/
	public function _reset()
	{
	}
	
	/**
	*	Выполнить выборку из БД на основе условий указанных пользователем и внешних данных
	* @param	string	$sql	Запрос на языке SQL
	*/
	public function _get()
	{
	}
	
	/**
	*	Сохранить данные
	* @access	public
	*/
	public function _set()
	{
		$data = $this->prepare_set();

		if (!empty($data))
		{
			session::set($data, NULL, $this->di->name);
		}

	}

	private function prepare_set()
	{
		$data = array();
		foreach ($this->di->fields as $field => $params)
		{
			if (array_key_exists($field, $this->di->args))
			{
				$value = $this->di->args[$field];
			}
		}
		return $data;
	}
	
	/**
	*	Удалить данные
	*/
	public function _unset()
	{
	}
}
?>
