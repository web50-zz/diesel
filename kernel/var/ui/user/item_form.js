ui.user.item_form = Ext.extend(Ext.form.FormPanel, {
	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/user/get.json',
			params: {_sid: data.id},
			waitMsg: this.loadText,
			success: function(frm, act){
				var d = Ext.util.JSON.decode(act.response.responseText);
				f.setValues([{id: '_sid', value: data.id}]);
				this.fireEvent("data_loaded", d.data, data.id);
			},
			scope:this
		});
		f.setValues(data);
	},

	Save: function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/user/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success)
						this.fireEvent('data_saved', d.data, d.data.id);
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
	},
	
	Cancel: function(){
		this.fireEvent('cancelled');
	},

	/**
	 * @constructor
	 */
	constructor: function(config){
		config = config || {};
		Ext.apply(this, {
			formWidth: 350,
			formHeight: 320,
			labelName: 'Имя',
			labelMulti: "multi-вход",
			labelLogin: 'Login',
			labelType: 'Тип автор.',
			labelServer: 'Сервер',
			labelEMail: 'e-mail',
			labelLang: 'Язык',
			labelPassw: 'Пароль',
			lebelRePassw: 'Пароль контр.',

			loadText: 'Загрузка данных формы',

			saveText: 'Сохранение...',

			bttSave: 'Сохранить',
			bttCancel: 'Отмена',

			errSaveText: 'Ошибка во время сохранения',
			errInputText: 'Корректно заполните все необходимые поля',
			errConnectionText: "Ошибка связи с сервером"
		});
		Ext.apply(this, {
			frame: true,
			autoScroll: true,
			labelAlign: 'right', 
			labelWidth: 100,
			defaults: {xtype: 'textfield', width: 150, anchor: '100%'},
			items: [
				{name: '_sid', xtype: 'hidden'},
				{fieldLabel: this.labelMulti, hiddenName: 'multi_login', value: 0, xtype: 'combo', width: 50, anchor: null,
					store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [[1, 'Да'], [0, 'Нет']] }),
					valueField: 'value', displayField: 'title',
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.labelName, name: 'name', allowBlank: false, maxLength: 64},
				{fieldLabel: this.labelLogin, name: 'login', allowBlank: false, maxLength: 64},
				{fieldLabel: this.labelType, hiddenName: 'type', value: 0, xtype: 'combo', width: 100, anchor: null,
					store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [[0, 'MySQL'], [1, 'LDAP']] }),
					valueField: 'value', displayField: 'title',
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.labelServer, name: 'server', maxLength: 64},
				{fieldLabel: this.labelEMail, name: 'email', allowBlank: false, maxLength: 64, vtype: 'email', emailText: 'e-mail введён не верно'},
				{fieldLabel: this.labelLang, hiddenName: 'lang', xtype: 'combo', value: 'ru_RU', width: 150,
					valueField: 'value', displayField: 'title', allowBlank: false,
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false,
					store: ui.user.languages
				},
				{fieldLabel: this.labelPassw, name: 'secret', inputType: 'password', vtype: 'password', initialPasswordField: 'secret2'},
				{fieldLabel: this.lebelRePassw, name: 'secret2', inputType: 'password', vtype: 'password', id: 'secret2'}
			],
			buttonAlign: 'right',
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			],
			keys: [
				{key: [Ext.EventObject.ENTER], handler: this.Save, scope: this}
			]
		});
		Ext.apply(this, config);
		ui.user.item_form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
				this._sid = data.id;
				this.reloadServices(data, id);
			},
			data_loaded: function(data, id){
				this.reloadServices(data, id);
			},
			scope: this
		})
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config){
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o){
	},

	reloadServices: function(data, id){
	}
});
