<?php
/**
*	UI Market latest products 
*
* @author	elgarat,9* 	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_registration extends user_interface
{
	public $title = 'Форма регистрации';
	public $req_fields = array('name'=>'Имя','email'=>'e-mail','passwd'=>'пароль','passwd2'=>'подтверждение пароля');
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_registration_form()
        {
		$data = array();
		if(authenticate::is_logged())
		{
			return $this->parse_tmpl('logged.html',$data);
		}
		return $this->parse_tmpl('default.html',$data);
	}


	public function pub_start_reg()
	{
		$data = array();
		if(authenticate::is_logged())
		{
			return  '';
		}
		return  $this->parse_tmpl('button.html',$data);
	}

	public function pub_register()
        {
		try
		{
			$this->check_input();
			$data['login'] = $this->args['email'];
			$data['name'] = $this->args['fio'];
			$data['email'] = $this->args['email'];
			$data['passw'] = $this->args['passwd'];
			$data['lang'] = 'ru_RU';
			$data['remote_addr'] = $_SERVER['REMOTE_ADDR'];
			$data['created_datetime'] =  date('Y-m-d H:i:S'); 
			$this->create_account($data);
		}
		catch(Exception $e)
		{
			$resp['code'] = '400';	
			$resp['error'] = $e->getMessage();
		}

		if($resp['code'] != '400')
		{
			$resp['code'] = '200';
			$resp['report']  = 'success';
		}
		response::send($resp,'json');
	}

	public function create_account($data)
	{
		$us = data_interface::get_instance('user');
		$us->_flush();
		$us ->set_args(array('_slogin'=>$data['login']));
		$rec = $us->_get();
		if(!empty($rec))
		{
			throw new Exception('Данный логин уже имеется в системе. Попробуйте иной.');
		}
		$us->_flush();
		$us ->set_args($data,false);
		$data2 = $us ->extjs_set_json(false);
		return;

	}

	public function check_input()
	{
		foreach($this->req_fields as $key=>$value)
		{
			if(!$this->args[$key])
			{
				$errors.= "Незаполнено обязательное поле $value <br>";
				$error = true;
			}
		}
		if($error == true)
		{
			throw new Exception("$errors");
		}
		if($this->args['passwd'] != $this->args['passwd2'])
		{
			$errors .= 'Набранные пароли не идентичны<br>';
			$error = true;
		}
		if($error == true)
		{
			throw new Exception("$errors");
		}

	}

}
?>
