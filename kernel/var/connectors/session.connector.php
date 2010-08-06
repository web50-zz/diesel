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
		$this->_init();
	}
	
	protected function _init()
	{
		try
		{
			$cfg = $this->di->get_cfg();
		}
		catch(PDOException $e)
		{
			throw new Exception('Init error: '.$e->getMessage());
		}
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
	}
	
	/**
	*	Удалить данные
	*/
	public function _unset()
	{
	}
}
?>
