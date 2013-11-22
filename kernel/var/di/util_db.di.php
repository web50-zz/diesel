<?php
/**
*	Интерфейс данных "Утиль ДБ"
*
* @author	9*
* @package	SBIN Diesel
*/
class di_util_db extends data_interface
{
	public $title = 'Утиль ДБ';
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
	
	protected $types_map = array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '3'
			);

	protected $dump_kernel_to_instance_cfg =  false;

	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	//	Возвращает спсиок возможных для выбора  вариантов дампа

	protected function sys_instances_list()
	{
		global $instances;
		$data = array();
		$data['success'] = 'true';
		$data['records'][] = array('id'=>'all','title'=>'all');
		$data['records'][] = array('id'=>'kernel','title'=>'kernel');
		foreach($instances as $key=>$value)
		{
			$data['records'][] = array('id'=>$value,'title'=>$value);
		}
		response::send($data, 'json');
	}
	protected function sys_dop_list()
	{
		global $instances;
		$data = array();
		$data['success'] = 'true';
		$data['records'][] = array('id'=>'only_instance','title'=>'only instnance');
		$data['records'][] = array('id'=>'and_kernel','title'=>'instance &  kernel cnf');
		response::send($data, 'json');
	}

	protected function sys_type_list()
	{
		global $instances;
		$data = array();
		$data['success'] = 'true';
		$data['records'][] = array('id'=>'1','title'=>'struct');
		$data['records'][] = array('id'=>'2','title'=>'data as default');
		$data['records'][] = array('id'=>'3','title'=>'struct + data as default');
		$data['records'][] = array('id'=>'4','title'=>'data as current');
		response::send($data, 'json');
	}

	protected function sys_operations_list()
	{
		global $instances;
		$data = array();
		$data['success'] = 'true';
		$data['records'][] = array('id'=>'1','title'=>'dump');
		$data['records'][] = array('id'=>'2','title'=>'init');
		response::send($data, 'json');
	}


	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_set()
	{
		global $INST_R;
		$subfold = '';
		$inst_id = $this->args['inst_id'];
		$type_id = $this->args['type_id'];
		$ops_id = $this->args['ops_id'];
			
		if($this->args['dop_type'] == 'and_kernel'){
			$this->dump_kernel_to_instance_cfg = true;
		}

		if($type_id == 4)//9* for current  get only data
		{
			$subfold = 'current/';
		}
		if($inst_id == 'kernel') //9* dump kernel
		{
			$path_di = DI_PATH;
			$path_dump = DUMP_PATH.$subfold;
			if($ops_id == 1)
			{
				$this->perform_dump($type_id,$path_di,$path_dump);
			}
			elseif($ops_id == 2)
			{
				$this->perform_init($type_id,$path_di,$path_dump);
			}
		}
		elseif($inst_id == 'all')
		{
			//9* dump instances
			foreach($INST_R['instances_path'] as $key=>$value)
			{
				$path_di = $value['di_path'] ;
				$path_dump = $value['dump_path'].$subfold;

				if($ops_id == 1)
				{
					$this->perform_dump($type_id,$path_di,$path_dump);
				}
				elseif($ops_id == 2)
				{
					$this->perform_init($type_id,$path_di,$path_dump);
				}

			}

			//9* now dump the kernel	
			$path_di = DI_PATH ;
			$path_dump = DUMP_PATH.$subfold;
			if($ops_id == 1)
			{
				$this->perform_dump($type_id,$path_di,$path_dump);
			}
			elseif($ops_id == 2)
			{
				$this->perform_init($type_id,$path_di,$path_dump);
			}
		}
		else //9* only one instance
		{
			foreach($INST_R['instances_path'] as $key=>$value)
			{
				if($inst_id == $value['instance_name'])
				{
					$path_di = $value['di_path'] ;
					$path_dump = $value['dump_path'].$subfold;
					if($ops_id == 1)
					{
						$this->perform_dump($type_id,$path_di,$path_dump);
					}
					elseif($ops_id == 2)
					{
						$this->perform_init($type_id,$path_di,$path_dump);
					}
				}
			}
		}

		$data['success'] = '1';
		$data['data'] = array('msg'=>'Operation completed. See log for errors.');
		response::send($data, 'json');
	}

	protected function perform_dump($type_id,$path_di,$path_dump)
	{
		$type = $this->types_map[$type_id];
			if($type_id == 4)//9* for current  get only data
			{
				$type = 2;
			}
			$dh = dir($path_di);
			while (($i = $dh->read()) !== FALSE)
			{
				if (preg_match('/^(\w+)\.di\.php$/', $i, $match))
				{
					
					if ($iObj = data_interface::get_instance($match[1]))
					{
						if($this->dump_kernel_to_instance_cfg == false)
						{
							if(preg_match('/^cpy_.+/',$match[1],$match2))
							{
								continue;
							}
						}
						try
						{
							$iObj->make_dump2($type,$path_dump);
						}
						catch(Exception $e)
						{
							dbg::write($match[1].'  '.$e->getMessage());
						}
					}
				}
			}
			$dh->close();
	}

	public function perform_init($type_id, $path_di, $path_dump)
	{
		try
		{
			$type = $this->types_map[$type_id];

			if ($type_id == 4)//9* for current get only data
			{
				$type = 2;
			}

			if (file_exists($path_di))
			{
				$dh = dir($path_di);

				while (($i = $dh->read()) !== FALSE)
				{
					if (preg_match('/^(\w+)\.di\.php$/', $i, $match))
					{
						if ($iObj = data_interface::get_instance($match[1]))
						{
						
							if($this->dump_kernel_to_instance_cfg == false)
							{
								if(preg_match('/^cpy_.+/',$match[1],$match2))
								{
									continue;
								}
							}

							try
							{
								$iObj->init_dump2($type,$path_dump);
							}
							catch(Exception $e)
							{
								dbg::write($e->getMessage(), LOG_PATH . 'cmd_init.log');
							}
						}
					}
				}
				$dh->close();
			}
			else
			{
				throw new Exception("{$path_di} directory NOT exists.");
			}
		}
		catch(Exception $e)
		{
			dbg::write($e->getMessage(), LOG_PATH . 'cmd_init.log');
		}
	}


}
?>
