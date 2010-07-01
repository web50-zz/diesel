<?php
/**
*	Data interface "administrators"
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @package	CFsCMS2(PE)
*/
class di_user extends data_interface
{
	public $title = 'Administrators';

	/**
	* @var	string	$cfg	DB configuration`s name
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	The name of data base
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	The name of table
	*/
	protected $name = 'sys_user';

	/**
	* @var	array	$user	Current logged in user`s data
	*/
	protected $user = array();
	
	/**
	* @var	array	$fields	The fields configuration
	*/
	public $fields = array(
			'id' => array('type' => 'integer', 'serial' => TRUE, 'protected' => FALSE),
			'login' => array('type' => 'string'),
			'passw' => array('type' => 'password', 'alias' => 'secret'),
			'name' => array('type' => 'string'),
			'email' => array('type' => 'string'),
			'lang' => array('type' => 'string'),
			'hash' => array('type' => 'string', 'hash' => TRUE, 'protected' => FALSE)
		);
	
	public function __construct () {
		// Call Base Constructor
		parent::__construct(__CLASS__);
	}

	/**
	*	Get user`s data if user already logged in
	* @access	public
	* @return	array|boolean		User`s data in array or FALSE if user not logged in
	*/
	public function get_user()
	{
		return (!empty($this->user)) ? $this->user : FALSE;
	}
	
	/**
	*	Get user`s data by id, login, hash
	* @access	public
	* @param	integer	$id	User`s id
	* @param	string	$login	User`s login
	* @param	string	$hash	User`s hash
	* @return	array|boolean	User`s data in array or FALSE if error
	*/
	public function get_by_hash($id, $login, $hash)
	{
		$sql = 'SELECT `id`, `login`, `name`, `email`, `lang`, `hash` FROM `' . $this->name . '` WHERE `id` = :id AND `login` = :login AND `hash` = :hash';
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$result = $this->connector->exec($sql, array('id' => $id, 'login' => $login, 'hash' => $hash), true, true);
		if (count($result) == 1)
		{
			$this->user = $result[0];
			return $result[0];
		}
		else
			return FALSE;
	}
	
	/**
	*	Get user`s data by login, password
	* @access	public
	* @param	string	$login		User`s login
	* @param	string	$password	User`s password
	* @return	array|boolean		User`s data in array or FALSE if error
	*/
	public function get_by_password($login, $password)
	{
		$sql = 'SELECT `id`, `login`, `name`, `email`, `lang`, `hash` FROM `' . $this->name . '` WHERE `login` = :login AND `passw` = PASSWORD(:password)';
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$result = $this->connector->exec($sql, array('login' => $login, 'password' => $password), true, true);
		if (count($result) == 1)
		{
			$this->user = $result[0];
			return $result[0];
		}
		else
			return FALSE;
	}
	
	/**
	*	Update user`s hash in user`s table
	* @param	integer	$id	User`s ID
	* @return	string	Generated hash
	*/
	public function update_hash($id)
	{
		$sql = 'UPDATE `' . $this->name . '` SET `hash` = :hash, `login_date` = NOW(), `remote_addr` = :remote_addr WHERE `id` = :id';
		$hash = md5($id . mktime());
		$remote_addr = $_SERVER['REMOTE_ADDR'];
		$this->connector->exec($sql, array('id' => $id, 'hash' => $hash, 'remote_addr' => $remote_addr));
		return $hash;
	}
	
	/**
	*	Get users in group
	*/
	protected function sys_user_in_group()
	{
		$this->_flush(true);
		$gu = $this->join_with_di('group_user', array('id' => 'user_id', intval($this->get_args('gid')) => 'group_id'), array('group_id' => 'gid'));
		return $this->extjs_grid_json(array('id', 'login', 'name'));
	}

	/**
	*	Get available interfaces
	*/
	public function is_available_interfaces($interface, $entry_point, $type)
	{
		if (UID == 1) return true;

		$sql = 'SELECT DISTINCT
			i.id
		FROM
			`sys_user` AS `u`
			LEFT JOIN `group_user` AS `gu` ON gu.user_id = u.id
			LEFT JOIN `interface_group` AS `ig` ON ig.group_id = gu.group_id
			LEFT JOIN `interface` AS `i` ON i.id = ig.interface_id
		WHERE
			u.id = ' . UID . "
			AND i.type = '{$type}'
			AND i.name = '{$interface}'
			AND i.entry_point = '{$entry_point}'
			";
		$records = $this->_get($sql);
		return ($this->get_rowCount() > 0);
	}
	
	/**
	*	Get records
	* @access protected
	*/
	protected function sys_list()
	{
		$this->extjs_grid_json(array('id', 'login', 'name', 'email', 'lang'));
	}
	
	/**
	*	Get record
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json(array('login', 'name', 'email', 'lang'));
	}
	
	/**
	*	Add new user
	* @access protected
	*/
	protected function sys_new()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		$this->args['name'] = 'new user';
		$this->args['login'] = 'new_user';
		$this->args['passw'] = substr(md5(mktime()), 0, 8);
		$data = $this->extjs_set_json(false);
		$data['data']['name'] = $this->args['name'];
		response::send($data, 'json');
	}
	
	/**
	*	Save record
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		$data = $this->extjs_set_json(false);
		response::send($data, 'json');
	}
	
	/**
	*	Change user`s password
	* @access protected
	*/
	protected function sys_passwd()
	{
		if ($this->args['_sid'] > 0)
		{
			$this->_flush();
			$this->insert_on_empty = true;
			if (empty($this->args['passw'])) $this->args['passw'] = substr(md5(mktime()), 0, 8);
			$data = $this->extjs_set_json(false);
		}
		else
		{
			$data = array(
				'success' => false,
				'error' => 'Не указан пользователь'
				);
		}
		response::send($data, 'json');
	}
	
	/**
	*	Delete user
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$data = $this->extjs_unset_json(false);

		// Remove all links between groups and deleted users
		$gu = data_interface::get_instance('group_user');
		$ids = (array)$this->get_lastChangedId();
		foreach ($ids as $uid) $gu->remove_user_from_groups($giu);

		response::send($data, 'json');
	}
}
?>
