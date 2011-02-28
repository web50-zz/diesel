<?php
/**
*	Data Interface "Interfaces link to group"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_entry_point_group extends data_interface
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
	protected $name = 'entry_point_group';
	
	/**
	* @var	array	$fields	Tables configuration
	*/
	public $fields = array(
		'entry_point_id' => array('type' => 'integer', 'alias' => 'epid'),
		'group_id' => array('type' => 'integer', 'alias' => 'gid'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Remove all entry_points from group
	* @param	integer	$gid	The group`s ID
	*/
	public function remove_entry_points_from_group($gid)
	{
		$this->_flush();
		$this->set_args(array('_sgid' => $gid));
		$this->_unset();
	}

	/**
	*	Remove user from all groups
	* @param	integer|array	$uid	The user`s ID
	*/
	public function remove_entry_point_from_groups($epid)
	{
		$this->_flush();
		$this->set_args(array('_sepid' => $epid));
		$this->_unset();
	}

	/**
	*	Add entry_points to group
	* @access protected
	*/
	protected function sys_add_entry_points_to_group()
	{
		//dbg::write($this->get_args());
		$success = true;
		$gid = $this->get_args('gid');
		$epids = split(',', $this->get_args('epids'));
		if (!empty($epids) && $gid > 0)
		{
			foreach ($epids as $epid)
			{
				$this->_flush();
				$this->insert_on_empty = true;
				$this->set_args(array(
					'epid' => $epid,
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
	*	Remove entry_points from group
	* @access protected
	*/
	protected function sys_remove_entry_points_from_group()
	{
		//dbg::write($this->get_args());
		$success = true;
		$gid = $this->get_args('gid');
		$epids = split(',', $this->get_args('epids'));
		if (!empty($epids) && $gid > 0)
		{
			foreach ($epids as $epid)
			{
				$this->_flush();
				$this->set_args(array(
					'_sepid' => $epid,
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
