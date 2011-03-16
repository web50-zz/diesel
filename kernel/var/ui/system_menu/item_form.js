ui.system_menu.item_form = function(config){
	Ext.apply(this, config);
	this.Load = function(p){
		var f = this.getForm();
		f.load({
			url: 'di/system_menu/get.json',
			params: {_sid: p.id},
			waitMsg: this.loadText,
			success:function(frm,act){this.fireEvent('afterloaddata');},
			scope:this
		});
		if (p.id > 0)
			f.setValues([{id: '_sid', value: p.id}]);
		else
			f.setValues([{id: 'pid', value: (p.pid || 1)}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/system_menu/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success)
						this.fireEvent('saved', f.getValues(), d.data);
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
							showError(action.result.errors || this.errSaveText);
					}
				},
				scope: this
			});
		}
	}.createDelegate(this);
	var Cancel = function(){
		this.fireEvent('cancelled');
	}.createDelegate(this);
	ui.system_menu.item_form.superclass.constructor.call(this, {
		frame: true, 
		labelWidth: 100,
		labelAlign: 'right',
		defaults: {xtype: 'textfield', width: 150, anchor: '100%'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{name: 'pid', xtype: 'hidden'},
			{hiddenName: 'type', value: 0, xtype: 'combo', width: 150,
				valueField: 'value', displayField: 'title', allowBlank: false,
				mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false,
				store: new Ext.data.SimpleStore({
					idIndex: 0,
					fields: ['value', 'title'],
					data: [
						[0, 'Пункт меню'],
						[1, 'Спец пункт']
					]
				})
			},
			{fieldLabel: this.lblText, name: 'text'},
			{fieldLabel: this.lblIcon, name: 'icon'},
			{fieldLabel: this.lblUI, name: 'ui'},
			{fieldLabel: this.lblEP, name: 'ep'},
			{fieldLabel: this.lblHref, name: 'href'}
		],
		buttonAlign: 'right',
		buttons: [
			{iconCls: 'disk', text: this.bttSave, handler: Save},
			{iconCls: 'cancel', text: this.bttCancel, handler: Cancel}
		],
		keys: [
			{key: [Ext.EventObject.ENTER], handler: Save}
		]
	});
	this.addEvents(
		"saved",
		"cancelled",
		"afterloaddata"
	);
	this.on({
		saved: function(data){
			this.getForm().setValues([{id: '_sid', value: data.id}]);
		},
		scope: this
	})
}
Ext.extend(ui.system_menu.item_form , Ext.form.FormPanel, {
	lblText: 'Наименование',
	lblIcon: 'Иконка',
	lblUI: 'User Interface',
	lblEP: 'Entry Point',
	lblHref: 'Href',

	loadText: 'Загрузка данных в форму',

	saveText: 'Сохранение...',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
