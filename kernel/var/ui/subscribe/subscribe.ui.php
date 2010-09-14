<?php
/**
*	UI Subscribe 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_subscribe extends user_interface
{
	public $title = 'Рассылка';
	public $for_operations = array();
	
	public $req_fields = array('email'=>'e-mail');
	protected $deps = array(
		'main' => array(
			'subscribe.group',
			'subscribe.subscriber_list',
			'subscribe.editForm',
			'subscribe.accounts_list',
			'subscribe.account_form',
			'subscribe.messages_list',
			'subscribe.message_form',
		)
	);




	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_content()
        {
		$data = array();
		return $this->parse_tmpl('default.html',$data);
	}

	public function pub_getfrm()
	{
		$data = array();
		$di = data_interface::get_instance('subscribe');
		$data = $di->extjs_grid_json(false,false);
		$data['email'] = $this->args['email'];
		$resp['code'] = '200';
		$resp['form'] = $this->parse_tmpl('default_form.html',$data);
		response::send($resp,'json');
	}


	public function pub_save_form()
	{
		try
		{
			$this->check_input();
			$req = array();
			$req['email'] = trim($this->args['email']);
			$req['operations'] = $this->for_operations;
			$di = data_interface::get_instance('subscribe_req');
			$data['req'] = serialize($req);
			$di->set_args($data);
			$di->prepare_extras();
			$di->_set();
			$di1 = data_interface::get_instance('subscribe_messages');
			$data = array();
			$data['recipients'] = array(array('email'=>$req['email']));
			$data['body'] = $di->args['hash'];
			$data['title'] = 'подписка рассылка';
			$di1->_send_message_now('',$data);
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

	public function check_input()
	{
		$flds = array();
		$flds = $this->req_fields;
		foreach($flds as $key=>$value)
		{
			if(!$this->args[$key])
			{
				$errors.= "Незаполнено обязательное поле \"$value\" <br>";
				$error = true;
			}
		}
		$strict = true;
		$regex = $strict?
			'/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' : 
			'/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i'; 
		if(!preg_match($regex,trim($this->args['email'])))
		{
				$errors.= "Проверьте правильность написания e-mail <br>";
				$error = true;
		}
		foreach($this->args as $key=>$value)
		{
			if(preg_match('/subscr_(\d+)/',$key,$matches)&&$value>0)
			{
				$some_selected = true;
				$dt = array();
				$dt['id'] =$matches['1'];
				$dt['operation'] = $value;
				array_push($this->for_operations,$dt);
			}
		}
		if(!$some_selected)
		{
			$error = true;
			$errors.= 'Ни одна из операций подписаться/отписаться не была выбрана<br>';
		}
		if($error == true)
		{
			throw new Exception("$errors");
		}
	}
	/**
	*       Управляющий JS админки
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.js');
		response::send($tmpl->parse($this), 'js');
	}

	protected function sys_group()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.group.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_subscriber_list()
	{
		$tmpl = new tmpl($this->pwd() . 'subscriber_list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_editForm()
	{
		$tmpl = new tmpl($this->pwd() . 'editForm.js');
		response::send($tmpl->parse($this), 'js');
	}

	protected function sys_accounts_list()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.accounts_list.js');
		response::send($tmpl->parse($this), 'js');
	}


	protected function sys_account_form()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.account_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_messages_list()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.messages_list.js');
		response::send($tmpl->parse($this), 'js');
	}

	protected function sys_message_form()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.message_form.js');
		response::send($tmpl->parse($this), 'js');
	}


}
?>
