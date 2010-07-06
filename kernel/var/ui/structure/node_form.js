ui.structure.node_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id, pid){
		var f = this.getForm();
		f.load({
			url: 'di/structure/get.json',
			params: {_sid: id, pid: pid},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/structure/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success)
						this.fireEvent('saved', !(f.findField('_sid').getValue() > 0), f.getValues(), d.data);
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
	ui.structure.node_form.superclass.constructor.call(this,{
		frame: true, 
		labelWidth: 170,
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{name: 'pid', xtype: 'hidden'},
			{fieldLabel: this.labelTitle, name: 'title', allowBlank: false, blankText: this.blankText, maxLength: 64, maxLengthText: 'Не больше 64 символов'},
			new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Да'], [1, 'Нет']] }),
				fieldLabel: 'Видимый',
				hiddenName: 'hidden',
				valueField: 'value',
				displayField: 'title',
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true,
				editable: false,
				value: 0
			}),
			{fieldLabel: 'Имя', name: 'name'},
			{fieldLabel: 'URI', name: 'uri', disabled: true},
			{fieldLabel: 'Перенаправить', name: 'redirect'},
			new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
					['text', 'Текст'],
					['news', 'Новости'],
					['article', 'Статьи'],
					['catalogue', 'Каталог']
				]}),
				fieldLabel: 'Модуль',
				hiddenName: 'module',
				valueField: 'value',
				displayField: 'title',
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true,
				editable: false,
				value: 'text'
			}),
			{fieldLabel: 'Параметры', name: 'params', readOnly: true},
			new Ext.form.ComboBox({
				store: new Ext.data.JsonStore({
					url: 'ui/structure/templates.do',
					fields: ['template']
				}),
				fieldLabel: 'Шаблон',
				hiddenName: 'template',
				valueField: 'template',
				displayField: 'template',
				emptyText: 'Выберите шаблон...',
				typeAhead: true,
				triggerAction: 'all',
				selectOnFocus: true,
				editable: false
			}),
			new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Нет'], [1, 'Да']]}),
				fieldLabel: 'Требует авторизацию',
				hiddenName: 'private',
				valueField: 'value',
				displayField: 'title',
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true,
				editable: false,
				value: 0
			}),
			{fieldLabel: 'Модуль авторизации', name: 'auth_module'}
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
		saved: function(isNew, formData, respData){
			this.getForm().setValues([{id: '_sid', value: respData.id}, {id: 'uri', value: respData.uri}]);
		},
		scope: this
	});
}
Ext.extend(ui.structure.node_form, Ext.form.FormPanel, {
	labelTitle: 'Наименование',
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
