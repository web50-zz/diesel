ui.user.editForm = function(config){
	Ext.apply(this, config);
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/user/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/user/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success)
						this.fireEvent('saved', d.data);
					else
						showError(d.errors);
				},
				failure: function(form, action){
					switch (action.failureType){
						case Ext.form.Action.CLIENT_INVALID:
							showError(this.errInputText);
						break;
						case Ext.form.Action.CONNECT_FAILURE:
							showError(this.errConnectionText);
						break;
						case Ext.form.Action.SERVER_INVALID:
							showError(action.result.errors);
					}
				},
				scope: this
			});
		}
	}.createDelegate(this);
	var Cancel = function(){
		this.fireEvent('cancelled');
	}.createDelegate(this);
	ui.user.editForm.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{fieldLabel: this.labelName, name: 'name', allowBlank: false, blankText: this.blankText, maxLength: 64, maxLengthText: this.maxLengthText},
			{fieldLabel: this.labelLogin, name: 'login', allowBlank: false, blankText: this.blankText, maxLength: 64, maxLengthText: this.maxLengthText},
			{fieldLabel: this.labelEMail, name: 'email', allowBlank: false, blankText: this.blankText, maxLength: 64, maxLengthText: 'Не больше 64 символов', vtype: 'email', emailText: 'e-mail введён не верно'},
			new Ext.form.ComboBox({
				hiddenName: 'lang', mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false,
				store: this.strLang,
				fieldLabel: this.labelLang,
				valueField: 'value',
				displayField: 'title',
				value: 'ru_RU'
			}),
			{fieldLabel: this.labelPassw, name: 'secret', inputType: 'password', vtype: 'password', initialPasswordField: 'secret2'},
			{fieldLabel: this.lebelRePassw, name: 'secret2', inputType: 'password', vtype: 'password', id: 'secret2'}
		],
		buttonAlign: 'right',
		buttons: [
			{iconCls: 'disk', text: this.bttSave, handler: Save},
			{iconCls: 'cancel', text: this.bttCancel, handler: Cancel}
		]
	});
	this.addEvents(
		"saved",
		"cancelled"
	);
	this.on({
		saved: function(data){
			this.getForm().setValues([{id: '_sid', value: data.id}]);
		},
		scope: this
	})
}
Ext.extend(ui.user.editForm, Ext.form.FormPanel, {
	labelName: 'Имя',
	labelLogin: 'Login',
	labelEMail: 'e-mail',
	labelLang: 'Язык',
	labelPassw: 'Пароль',
	lebelRePassw: 'Пароль контр.',

	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
