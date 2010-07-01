<?php
/**
*	The user`s authorisation library
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class authenticate
{
	/**
	*	Check if user is logged in
	* @return	boolean	TRUE - if user is logged in or FALSE
	*/
	public static function is_logged()
	{
		// Get instance of authentification data interface
		$auth = data_interface::get_instance(AUTH_DI);

		// Get values stored in session
		$sess = session::get(array('uid', 'ulogin', 'uhash'), NULL, AUTH_DI);

		// Get authentification`s data
		$data = $auth->get_by_hash($sess['uid'], $sess['ulogin'], $sess['uhash']);

		// If there is no authentification`s data then throw exception
		if (!$data) return FALSE;

		// Define UID of logged user
		define('UID', $data['id']);

		// If account_id is given then define it in AID
		if ($data['account_id']) define('AID', $data['account_id']);
		
		return TRUE;
	}
	
	/**
	*	Log in user by given `user` and `secret` variables
	* The name of DI must be defined in AUTH_DI
	*/
	public static function login()
	{
		// Get instance of authentification data interface
		$auth = data_interface::get_instance(AUTH_DI);

		// Get authentification`s data
		$data = $auth->get_by_password(request::get('user'), request::get('secret'));

		// If there is no authentification`s data then throw exception
		if (!$data) throw new Exception('Login failed.');

		// Update user`s hash
		$hash = $auth->update_hash($data['id']);

		// Set session`s variables
		session::set(array('uid' => $data['id'], 'ulogin' => $data['login'], 'uhash' => $hash), NULL, AUTH_DI);

		// Define UID of logged user
		define('UID', $data['id']);

		// If account_id is given then define it in AID
		if ($data['account_id']) define('AID', $data['account_id']);
	}
	
	/**
	*	Log out user
	*/
	public static function logout()
	{
		session::del(array('uid', 'ulogin', 'uhash'), AUTH_DI);
	}
}
?>
