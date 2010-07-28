ui.catalogue.resize_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/catalogue_file/get_size.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/catalogue_file/resize.do',
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
	var fldWidth = new Ext.form.NumberField({name: 'width', width: 70, xtype: 'numberfield', decimalPrecision: 0});
	var fldHeight = new Ext.form.NumberField({name: 'height', width: 70, xtype: 'numberfield', decimalPrecision: 0});
	fldWidth.on({
		change: function(fld, nv, ov){
			var f = this.getForm();
			if (f.findField('sync').getValue() === true){
				var k = nv / ov;
				fldHeight.setValue(fldHeight.getValue() * k);
			}
		}, scope: this
	});
	fldHeight.on({
		change: function(fld, nv, ov){
			var f = this.getForm();
			if (f.findField('sync').getValue() === true){
				var k = nv / ov;
				fldWidth.setValue(fldWidth.getValue() * k);
			}
		}, scope: this
	});
	ui.catalogue.resize_form.superclass.constructor.call(this, {
		frame: true,
		hideLabels: true,
		items: [
			{name: '_sid', xtype: 'hidden'},
			{xtype: 'compositefield', items: [
				{xtype: 'displayfield', value: this.labelWidth, allowEmpty: false},
				fldWidth,
				{name: 'sync', xtype: 'checkbox', checked: true},
				{xtype: 'displayfield', value: this.labelHeight, allowEmpty: false},
				fldHeight
				
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
}
Ext.extend(ui.catalogue.resize_form , Ext.form.FormPanel, {
	loadText: 'Загрузка данных формы',

	labelWidth: 'Ширина',
	labelHeight: 'Высота',

	saveText: 'Сохранение...',

	bttSave: 'Создать',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
