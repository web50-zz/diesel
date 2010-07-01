<?php
/**
*	Data Interface "Interfaces link to group"
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @package	FlugerCMS
*/
class di_interface_group extends data_interface
{
	public $title = 'Link between Interfaces and Groups';

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
	protected $name = 'interface_group';
	
	/**
	* @var	array	$fields	Tables configuration
	*/
	public $fields = array(
		'interface_id' => array('type' => 'integer', 'alias' => 'iid'),
		'group_id' => array('type' => 'integer', 'alias' => 'gid'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Remove all interfaces from group
	* @param	integer	$gid	The group`s ID
	*/
	public function remove_interfaces_from_group($gid)
	{
		$this->_flush();
		$this->set_args(array('_sgid' => $gid));
		$this->_unset();
	}

	/**
	*	Remove user from all groups
	* @param	integer	$uid	The user`s ID
	*/
	public function remove_interface_from_groups($iid)
	{
		$this->_flush();
		$this->set_args(array('_siid' => $iid));
		$this->_unset();
	}

	/**
	*	Add interfaces to group
	* @access protected
	*/
	protected function sys_add_interfaces_to_group()
	{
		//dbg::write($this->get_args());
		$success = true;
		$gid = $this->get_args('gid');
		$iids = split(',', $this->get_args('iids'));
		if (!empty($iids) && $gid > 0)
		{
			foreach ($iids as $iid)
			{
				$this->_flush();
				$this->insert_on_empty = true;
				$this->set_args(array(
					'iid' => $iid,
					'gid' => $gid
				));
				$this->_set();
			}
		}
		else
		{
			$success = false;
		}
		response::send(array('success' => $success), 'json');
	}

	/**
	*	Remove interfaces from group
	* @access protected
	*/
	protected function sys_remove_interfaces_from_group()
	{
		//dbg::write($this->get_args());
		$success = true;
		$gid = $this->get_args('gid');
		$iids = split(',', $this->get_args('iids'));
		if (!empty($iids) && $gid > 0)
		{
			foreach ($iids as $iid)
			{
				$this->_flush();
				$this->set_args(array(
					'_siid' => $iid,
					'_sgid' => $gid
				));
				$this->_unset();
			}
		}
		else
		{
			$success = false;
		}
		response::send(array('success' => $success), 'json');
	}
}
?>
