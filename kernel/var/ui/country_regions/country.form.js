ui.country_regions.country_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/guide_country/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/guide_country/set.do',
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
	ui.country_regions.country_form.superclass.constructor.call(this, {
		frame: true, 
		labelWidth: 120,
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{fieldLabel: this.labelTitle, name: 'title', maxLength: 64, maxLengthText: this.maxLengthText64},
			{fieldLabel: this.labelTitleEng, name: 'title_eng', maxLength: 64, maxLengthText: this.maxLengthText64},
			{fieldLabel: this.labelCode, name: 'code', maxLength: 3, maxLengthText: this.maxLengthText3},
			{fieldLabel: this.labelCost, xtype: 'compositefield', items: [
				{name: 'cost', width: 70, xtype: 'numberfield', decimalPrecision: 2},
				{hiddenName: 'ccy', xtype: 'combo', width: 70,
					store: new Ext.data.JsonStore({url: 'di/guide_currency/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
					valueField: 'id', displayField: 'name', triggerAction: 'all', editable: false
				}
			]}

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
Ext.extend(ui.country_regions.country_form, Ext.form.FormPanel, {
	labelTitle: 'Название страны',
	labelTitleEng: 'Название eng',
	labelCode: 'Код',
	labelCost: 'Стоимость',

	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText64: 'Не больше 64 символов',
	maxLengthText3: 'Не больше 3 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
