<?php
/**
*	Data Interface "Catalogue item link to style"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_catalogue_style extends data_interface
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
	protected $name = 'catalogue_style';
	
	/**
	* @var	array	$fields	Tables configuration
	*/
	public $fields = array(
		'catalogue_item_id' => array('type' => 'integer', 'alias' => 'iid'),
		'style_id' => array('type' => 'integer', 'alias' => 'sid'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Remove all catalogue items from style
	* @param	integer|array	$sid	The style`s ID
	*/
	public function remove_items_from_style($sid)
	{
		if (is_array($sid))
		{
			foreach ($sid as $id)
			{
				$this->_flush();
				$this->set_args(array('_ssid' => $id));
				$this->_unset();
			}
		}
		else
		{
			$this->_flush();
			$this->set_args(array('_ssid' => $sid));
			$this->_unset();
		}
	}

	/**
	*	Remove all styles from catalog item
	* @param	integer|array	$iid	The item`s ID
	*/
	public function remove_styles_from_item($iid)
	{
		if (is_array($iid))
		{
			foreach ($iid as $id)
			{
				$this->_flush();
				$this->set_args(array('_siid' => $id));
				$this->_unset();
			}
		}
		else
		{
			$this->_flush();
			$this->set_args(array('_siid' => $iid));
			$this->_unset();
		}
	}

	/**
	*	Add styles to item
	* @access protected
	*/
	protected function sys_add_styles_to_item()
	{
		$success = true;
		$iid = $this->get_args('iid');
		$sids = split(',', $this->get_args('sids'));

		if (!empty($sids) && $iid > 0)
		{
			foreach ($sids as $sid)
			{
				$this->_flush();
				$this->insert_on_empty = true;
				$this->set_args(array(
					'iid' => $iid,
					'sid' => $sid
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
	*	Remove styles from item
	* @access protected
	*/
	protected function sys_remove_styles_from_item()
	{
		//dbg::write($this->get_args());
		$success = true;
		$iid = $this->get_args('iid');
		$sids = split(',', $this->get_args('sids'));

		if (!empty($sids) && $iid > 0)
		{
			foreach ($sids as $sid)
			{
				$this->_flush();
				$this->set_args(array(
					'_siid' => $iid,
					'_ssid' => $sid
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
