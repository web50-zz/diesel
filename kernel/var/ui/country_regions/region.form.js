ui.country_regions.region_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id,part_id){
		var f = this.getForm();
		f.load({
			url: 'di/country_regions/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		if(part_id>0)
		{
			f.setValues([{id: '_sid', value: id},{id: 'cr_regions_part_id', value: part_id}]);
		}
		else
		{
			f.setValues([{id: '_sid', value: id}]);
		}

	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/country_regions/set.do',
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
	ui.country_regions.region_form.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{name: 'cr_regions_part_id', xtype: 'hidden'},
			{fieldLabel: this.labelTitle, name: 'cr_regions_title', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
			{fieldLabel: this.labelPostZone, name: 'cr_regions_post_zone', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText}
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
Ext.extend(ui.country_regions.region_form, Ext.form.FormPanel, {
	labelTitle: 'Регион',
	labelPostZone:'Ценовая зонаЖ',
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
