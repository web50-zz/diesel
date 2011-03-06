<?php
/**
*	Интерфейс данных "Новости"
*
*@author	9*
*@package	SBIN Diesel
*/
class di_cache extends data_interface
{
	public $title = 'Кэширование';
	
	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'ui' => array('type' => 'string'),
		'call' => array('type' => 'string'),
		'params_hash' => array('type' => 'string'),
		'creation_datetime' => array('type' => 'datetime')
	);
	
        public function __construct () {
            // Call Base Constructor
            parent::__construct(__CLASS__);
        }

	public function cached()
	{
		try
		{
			$this->gen_filename();
			$this->search_in();
			return $this->cache_ready;
		}
		catch(Exception $e)
		{
			dbg::write($e->getMessage);
		}
	}

	public function cache_it($tocacho)
	{
		try
		{
			$this->gen_filename();
			$this->tocacho = $tocacho;
			$this->save_data();
		}
		catch(Exception $e)
		{
			dbg::write($e->getMessage);
		}
	}

	public function get_cached()
	{
		return file_get_contents($this->cache_file);
	}


	public function gen_filename()
	{
		if(!$this->args['ui']||!$this->args['call'])
		{
			throw new Exception('Required params missed');
		}
		$this->cache_file = CACHE_PATH.$this->args['ui'].'.ui_'.$this->args['call'].'.cache';
	}


	public function save_data()
	{
		if(!$fh = fopen($this->cache_file, 'w'))
		{
			throw new Exception('cant open cache file for writing');
		}
		fwrite($fh, $this->tocacho);
		fclose($fh);
	}


	public function search_in()
	{
		if(file_exists($this->cache_file))
		{
			$time = filemtime($this->cache_file);
		}
		if(!$time)
		{
			$this->cache_ready =  false;
			return false;
		}
		if($time<(time()-$this->args['timeout']))
		{
			$this->cache_ready =  false;
			return false;
		}
		$this->cache_ready = true;
		return true;
	}
}
?>
