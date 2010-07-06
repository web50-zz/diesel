ui.catalogue.configure_form = function(config){
	Ext.apply(this, config);
	this.Load = function(data){
		var f = this.getForm();
		f.setValues(Ext.decode(data));
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			this.fireEvent('saved', f.getValues());
		}
	}.createDelegate(this);
	var Cancel = function(){
		this.fireEvent('cancelled');
	}.createDelegate(this);
	ui.catalogue.configure_form.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [
					['cd', 'CD (Компакт диски)'],
					['dvd', 'DVD (Видео диски)'],
					['shirt', 'Футболки']
				]}),
				fieldLabel: this.labelType, hiddenName: 'type', 
				valueField: 'value',
				displayField: 'title',
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true,
				editable: false,
				value: 0
			})
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
Ext.extend(ui.catalogue.configure_form , Ext.form.FormPanel, {
	labelType: 'Тип товаров',

	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',
});
