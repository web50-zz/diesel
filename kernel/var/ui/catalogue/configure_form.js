ui.catalogue.configure_form = function(config){
	Ext.apply(this, config);
	this.Load = function(data){
		var f = this.getForm();
		f.setValues(Ext.decode(data));
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			var config = {};
			var fData = f.getValues();
			for (var value in fData){
				if (!Ext.isEmpty(fData[value]))
					config[value] = fData[value];
			}
			this.fireEvent('saved', config);
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
				store: new Ext.data.JsonStore({url: 'di/guide_type/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				fieldLabel: this.labelType, emptyText: this.blankTypeText, valueNotFoundText: this.blankTypeText, hiddenName: 'type_id',
				valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
			}),
			new Ext.form.ComboBox({
				store: new Ext.data.JsonStore({url: 'di/guide_producer/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				fieldLabel: this.labelProducer, emptyText: this.blankProducerText, valueNotFoundText: this.blankProducerText, hiddenName: 'producer_id',
				valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
			}),
			new Ext.form.ComboBox({
				store: new Ext.data.JsonStore({url: 'di/guide_collection/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				fieldLabel: this.labelCollection, emptyText: this.blankCollectionText, valueNotFoundText: this.blankCollectionText, hiddenName: 'collection_id',
				valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
			}),
			new Ext.form.ComboBox({
				store: new Ext.data.JsonStore({url: 'di/guide_group/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				fieldLabel: this.labelGroup, emptyText: this.blankGroupText, valueNotFoundText: this.blankGroupText, hiddenName: 'group_id',
				valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
			}),
			new Ext.form.ComboBox({
				store: new Ext.data.JsonStore({url: 'di/guide_style/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				fieldLabel: this.labelStyle, emptyText: this.blankStyleText, valueNotFoundText: this.blankStyleText, hiddenName: 'style_id',
				valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
			})
		],
		buttonAlign: 'right',
		buttons: [
			{iconCls: 'accept', text: this.bttSave, handler: Save},
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
	labelType: 'Тип товара',
	labelProducer: 'Производитель',
	labelCollection: 'Коллекция',
	labelGroup: 'Группа',
	labelStyle: 'Стиль',

	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Применить',
	bttCancel: 'Отмена',
});
