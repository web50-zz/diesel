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
