<?PHP
/**
*	Russian localisation
*/
class LC
{
	private static $DATA = array(
		'ERROR' => array(
			'json_not_supported' => 'The JSON not supported.',
		),
		'GUI' => array(
			'login' => 'Login',
			'password' => 'Password',
			'enter' => 'Enter',
			'adm_login_form' => 'Administrators login',
		)
	);

	public static function apply()
	{
		global $LC_DATA;
		self::$DATA = self::merge(self::$DATA, $LC_DATA);
	}

	private static function merge($data1, $data2)
	{
		foreach ($data2 AS $key => $value)
			if (is_array($value))
				$data1[$key] = self::merge($data1[$key], $value);
			else
				$data1[$key] = $value;
		return $data1;
	}

	public static function get()
	{
		return self::$DATA;
	}

	public static function get_err($name)
	{
		return self::$DATA['ERROR'][$name];
	}

	public static function get_gui($name)
	{
		return self::$DATA['GUI'][$name];
	}

	public static function get_msg($name)
	{
		return self::$DATA['MESSAGE'][$name];
	}
}
?>
