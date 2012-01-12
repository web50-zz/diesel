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

	lblName: 'Имя',
	lblMulti: "multi-вход",
	lblLogin: 'Login',
	lblType: 'Тип автор.',
	lblServer: 'Сервер',
	lblEMail: 'e-mail',
	lblLang: 'Язык',
	lblPassw: 'Пароль',
	lblRePassw: 'Пароль контр.',
	vYes: 'Yes',
	vNo: 'No',
	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',
	bttSave: 'Сохранить',
	bttCancel: 'Отмена',
	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",

	/**
	 * @constructor
	 */
	constructor: function(config){
		config = config || {};
		Ext.apply(this, {
			formWidth: 400,
			formHeight: 320
		});
		Ext.apply(this, {
			frame: true,
			autoScroll: true,
			labelAlign: 'right', 
			labelWidth: 120,
			defaults: {xtype: 'textfield', width: 150, anchor: '100%'},
			items: [
				{name: '_sid', xtype: 'hidden'},
				{fieldLabel: this.lblMulti, hiddenName: 'multi_login', value: 0, xtype: 'combo', width: 50, anchor: null,
					store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [[1, this.vYes], [0, this.vNo]] }),
					valueField: 'value', displayField: 'title',
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.lblName, name: 'name', allowBlank: false, maxLength: 64},
				{fieldLabel: this.lblLogin, name: 'login', allowBlank: false, maxLength: 64},
				{fieldLabel: this.lblType, hiddenName: 'type', value: 0, xtype: 'combo', width: 100, anchor: null,
					store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [[0, 'MySQL'], [1, 'LDAP']] }),
					valueField: 'value', displayField: 'title',
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.lblServer, name: 'server', maxLength: 64},
				{fieldLabel: this.lblEMail, name: 'email', allowBlank: false, maxLength: 64, vtype: 'email', emailText: 'e-mail введён не верно'},
				{fieldLabel: this.lblLang, hiddenName: 'lang', xtype: 'combo', value: 'ru_RU', width: 150,
					valueField: 'value', displayField: 'title', allowBlank: false,
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false,
					store: ui.user.languages
				},
				{fieldLabel: this.lblPassw, name: 'secret', inputType: 'password', vtype: 'password', initialPasswordField: 'secret2'},
				{fieldLabel: this.lblRePassw, name: 'secret2', inputType: 'password', vtype: 'password', id: 'secret2'}
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
