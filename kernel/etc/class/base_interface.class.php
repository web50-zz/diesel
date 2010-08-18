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
	}
	
	/**
	*	Get arguments
	* @access	public
	* @param	boolean|string	$ind	Get argument by name
	*/
	public function get_args($ind = false)
	{
		return ($ind !== false) ? $this->args[$ind] : (array)$this->args;
	}
	
	/**
	*	Paush arguments to stack
	* @access	public
	* @param	array	$args		Arguments
	*/
	public function push_args($args)
	{
		$this->args_stack[] = $this->args;
		$this->args = $args;
	}
	
	/**
	*	Pop arguments from stack
	* @access	public
	*/
	public function pop_args()
	{
		return array_pop($this->args_stack);
	}
}
?>
