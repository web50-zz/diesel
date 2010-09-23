<?php
/**
*	Parent class for User Interfaces (UI)
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class user_interface extends base_interface
{
	/**
	* @access	private
	* @var	array	$registry	UI registry
	*/
	private	static $registry = array();
	
	/**
	* @access	protected
	* @var	string	$files_path	Path to UI files
	*/
	protected $files_path;
	
	/**
	* @access	public
	* @var	string	$title		UI title
	*/
	public		$title = 'Unnamed UI';

	protected	$deps = array();

	/**
	*	Constructor which MUST be called from derived Class Constructor
	*/
	protected function __construct($strDerivedClassName)
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
	}
	
	/**
	*	Set instance of User Interface and put it to registry
	* @param	string	$name	Name of UI
	*/
	private static function set_instance($name)
	{
		try
		{	
			$class = UI_CLASS_PREFIX . $name;
			class_exists($class);
			$object = new $class();
			$object->interfaceName = $name;

			if (!$object->files_path)//9* if have no overloads of files_path we set default UI_PATH. IF UI in INSTANCE path this property is required
				$object->files_path = UI_PATH . $name . '/';

			self::$registry[$name] = $object;
		}
		catch(Exception $e)
		{
			throw new Exception('Can`t set user interface: ' . $e->getMessage());
		}
	}
	
	/**
	*	Get instance of User Interface
	* @param	string	$name	Name of UI
	* @return	object	UI instance
	*/
	public static function get_instance($name)
	{
		if (empty($name))
			throw new Exception('The name of user interface not present.');

		if (!isset(self::$registry[$name]))
			self::set_instance($name);

		return self::$registry[$name];
	}
	
	/**
	r
	*	Call method of User Interface
	* @access	public
	* @param	string	$name	Call method`s name
	* @param	array	$args	Call arguments
	*/
	public function call($name, $args = array())
	{
		if (empty($name))
			throw new Exception('The name of method of user interface not present.');

		if ($this->check_access($this->interfaceName, UI_CALL_PREFIX . $name, 'ui'))
		{
			$call_name = UI_CALL_PREFIX . $name;
			
			if (method_exists($this, $call_name))
			{
				$this->set_args($args);
				return $this->$call_name();
			}
			else
			{
				throw new Exception("The method `$name` not exist.");
			}
		}
		else if ($name == 'dependencies')
		{
			response::send('Access denied.', 'error');
		}
		else
		{
			//throw new Exception("Permission denied to method `$name` of user interface `{$this->interfaceName}`.");
			return false;
		}
	}
	
	/**
	*	Get path to UI files
	* @access	public
	* @return	string	The path
	*/
	public function pwd()
	{
		return $this->files_path;
	}

	/**
	*	Parse tmpl according with THEME INSTANCE OR default options
	* @access	public
	* @param	string	$tmpl_name	array() $data to parse out 
	* @return	parsed template 	tmpl2
	*/

	public function parse_tmpl($template_file_name,$data)
	{
		if($tmpl_path = $this->get_resource_path($template_file_name,'absolute'))
		{
			$tmpl = new tmpl($tmpl_path);
			$html = $tmpl->parse($data);
			return $html;
		}
		else
		{
			$dbgs = array_shift(debug_backtrace());
			dbg::write("TEMPLATE WAS NOT FOUND at user_interface::parse_tmpl() \nCall from:.....  ". $dbgs['file']."\nline:..........  ".$dbgs['line']. "\ntemplate: $template_file_name\npath1: $tmpl_path \npath2: $tmpl_path2");
		}
		return false;
	}

	/**
	*	Get tmpl directory path with THEME INSTANCE OR default options
	* @access	public
	* @param	 
	* @return	directory path
	*/

	public function get_resource_dir_path($mode = '')
	{
		if ($mode != 'default')
		{
			$tmpl_path = BASE_PATH.CURRENT_THEME_PATH.'tmpl/'.$this->interfaceName; 
			if (is_dir($tmpl_path)) return "{$tmpl_path}/";
		}

		$tmpl_path2 = $this->pwd() . 'templates';	

		if(is_dir($tmpl_path2))
			return $tmpl_path2.'/';

		return false;
	}
	
	public function get_resource_path($res_name,$mode = 'relative')
	{
		$res_path = $this->get_resource_dir_path().$res_name;
		$res_path2 = $this->get_resource_dir_path('default').$res_name;

		if (file_exists($res_path))
		{
			if($mode == 'relative')
			{
				return str_replace(BASE_PATH,"",$res_path);
			}
			return $res_path;
		}
		else if (file_exists($res_path2))
		{
			if($mode == 'relative')
			{
				return str_replace(BASE_PATH,"",$res_path2);
			}
			return $res_path2;
		}
		else
		{
			//$dbgs = array_shift(debug_backtrace());
			//dbg::write("RESOURCE FILE WAS NOT FOUND at user_interface::get_resource_path() $res_name");
		}
		return false;
	}


	/**
	*	External entry point
	* Return JSON-object neccessary UI for application
	*/
	protected function sys_dependencies()
	{
		$face = $this->get_args('face');
		$faces = $this->get_entry_poins('/^' . UI_CALL_PREFIX . '\w+/');
		
		try
		{
			if (!in_array(UI_CALL_PREFIX . $face, $faces))
				throw new Exception("Приложение {$this->interfaceName}.{$face} не существует.");

			$dependencies  = array();
			$deps = (array)$this->deps[$face];

			while(!empty($deps))
			{
				$app = array_shift($deps);
				list($ui_name, $call) = preg_split('/\./', $app);
				$ui = user_interface::get_instance($ui_name);
				$sub_deps = $ui->get_dependencies($call);
				foreach ($sub_deps as $dep)
					array_push($deps, $dep);

				array_push($dependencies, $app);
			}
			
			$dependencies = array_reverse($dependencies);
			$dependencies = array_unique($dependencies);
			$dependencies = array_reverse($dependencies);

			response::send(array(
				'success' => true,
				'dependencies' => $dependencies
			), 'json');
		}
		catch (Exception $e)
		{
			response::send(array(
				'success' => false,
				'errors' => $e->getMessage()
			), 'json');
		}
	}

	/**
	*	Get neccessary UI for application
	*/
	public function get_dependencies($name)
	{
		$faces = $this->get_entry_poins('/^' . UI_CALL_PREFIX . '\w+/');

		if (!in_array(UI_CALL_PREFIX . $name, $faces))
			throw new Exception("Приложение {$this->interfaceName}.{$name} не существует.");

		return (array)$this->deps[$name];
	}
}
?>
