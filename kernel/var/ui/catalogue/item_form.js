ui.catalogue.item_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/catalogue_item/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/catalogue_item/set.do',
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
	ui.catalogue.item_form.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Нет'], [1, 'Да']] }),
				fieldLabel: this.labelExist, hiddenName: 'exist', width: 50, 
				valueField: 'value',
				displayField: 'title',
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true,
				editable: false,
				value: 0
			}),
			{fieldLabel: this.labelName, name: 'title', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 256, maxLengthText: this.maxLengthText},
			{fieldLabel: this.labelCost, name: 'cost', width: 100}
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
Ext.extend(ui.catalogue.item_form , Ext.form.FormPanel, {
	labelExist: 'В наличии',
	labelName: 'Наименование',
	labelCost: 'Стоимость',
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
