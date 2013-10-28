<?php
/**
*	Base interfaces class
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class base_interface
{
	/**
	* @access	protected
	* @var	array	$args_stack	The stack of arguments
	*/
	protected $args_stack = array();

	/**
	* @access	protected
	* @var	array	$args		The array of arguments
	*/
	protected $args = array();

	/**
	*	Array of ReflectionMethod Objects is being set upon instanciation
	* derived Classes don't need to know about this array
	*/
	private $arrMethods;

	/**
	*	Constructor which MUST be called from derived Class Constructor
	*/
	protected function __construct ($strDerivedClassName)
	{
		$oRefl = new ReflectionClass ($strDerivedClassName);

		if (is_object($oRefl))
		{
			$this->arrMethods = $oRefl->getMethods();
		}
	}

	/**
	*	Getting all existing entry points in interface
	*
	* @access public
	* @option	boolean|string	$pattern	Selection pattern
	* @return	array		The array of existing entry points
	*/
	public function get_entry_poins($pattern = false)
	{
		$methods = array();

		foreach ($this->arrMethods as $curReflectionMethod)
		{
			if (!$pattern)
			{
				$methods[] = $curReflectionMethod->getName();
			}
			else if (preg_match($pattern, $curReflectionMethod->getName()))
			{
				$methods[] = $curReflectionMethod->getName();
			}
		}

		return (array)$methods;
	}

	public function check_access($iface, $call, $type)
	{
                if (defined('AUTH_DI'))
		{
			$adi = data_interface::get_instance(AUTH_DI);
                        return $adi->is_available_interfaces($iface, $call, $type);
		}
                else
                        return true;
	}
	
	/**
	*	Set arguments
	* @access	public
	* @param	array	$args		Arguments
	*/
	public function set_args($args, $merge = false)
	{
		if (!$merge)
			$this->args = $args;
		else
			$this->args = array_merge($this->args, $args);
		return $this;
	}
	
	/**
	*	Get arguments
	* @access	public
	* @param	boolean|string	$name	Get argument by name
	* @param	boolean		$simple	Return simple array without keys, only values
	*/
	public function get_args($name = NULL, $default = NULL, $simple = FALSE)
	{
		$results = $default;

		if (!$name)
		{
			$results = (array)$this->args;
		}
		else if (is_array($name))
		{
			foreach ($name as $n => $var_name)
			{
				if (isset($this->args[$var_name]))
				{
					if ($simple)
						$results[] = $this->args[$var_name];
					else
						$results[$var_name] = $this->args[$var_name];
				}
				else if (is_array($default) && isset($default[$var_name]))
				{
					if ($simple)
						$results[] = $default[$var_name];
					else
						$results[$var_name] = $default[$var_name];
				}
				else if (is_array($default) && isset($default[$n]))
				{
					if ($simple)
						$results[] = $default[$n];
					else
						$results[$var_name] = $default[$n];
				}
				else
				{
					if ($simple)
						$results[] = $default;
					else
						$results[$var_name] = $default;
				}
			}
		}
		else if (isset($this->args[$name]))
		{
			$results = $this->args[$name];
		}

		return $results;
	}
	
	/**
	*	Paush arguments to stack
	* @access	public
	* @param	array	$args		Arguments
	*/
	public function push_args($args)
	{
		$this->args_stack[] = $this->args;
		$this->args = (array)$args;
		return $this;
	}
	
	/**
	*	Pop arguments from stack
	* @access	public
	*/
	public function pop_args()
	{
		//return array_pop($this->args_stack);
		$this->args =  array_pop($this->args_stack);
		return $this;
	}

	public function fire_event($event_name, $event_params = null, $args = null)
	{
		event_manager::fire_event($this, $event_name, $event_params, $args);
		return $this;
	}
}
?>
