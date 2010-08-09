ui.catalogue.filter_form = function(config){
	Ext.apply(this, config);
	this.Load = function(data){
		var f = this.getForm();
		f.setValues(data);
		//this.fireEvent('submit', f.getValues());
	}
	var Submit = function(){
		var f = this.getForm();
		if (f.isValid())
			this.fireEvent('submit', f.getValues());
	}.createDelegate(this);
	var Reset = function(){
		var f = this.getForm();
		f.reset();
		this.fireEvent('reset', f.getValues());
	}.createDelegate(this);
	ui.catalogue.filter_form.superclass.constructor.call(this, {
		frame: true,
		layout: 'form',
		labelAlign: 'top',
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{fieldLabel: this.labelTitle, name: '_stitle'},
			{fieldLabel: this.labelExist, hiddenName: '_son_offer', xtype: 'combo', width: 50, value: '',
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [['', 'Все'], [1, 'Да'], [0, 'Нет']] }),
				valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
			},
			{fieldLabel: this.labelType, hiddenName: '_stype_id', xtype: 'combo', value: '',
				store: new Ext.data.JsonStore({url: 'di/guide_type/combolist.json', baseParams: {with_empty: 'yes'}, root: 'records', fields: ['id', 'name'], autoLoad: true}),
				valueField: 'id', displayField: 'name', triggerAction: 'all', editable: false
			},
			{fieldLabel: this.labelGroup, hiddenName: '_sgroup_id', xtype: 'combo',
				store: new Ext.data.JsonStore({url: 'di/guide_group/combolist.json', baseParams: {with_empty: 'yes'}, root: 'records', fields: ['id', 'name']}),
				valueField: 'id', displayField: 'name', triggerAction: 'all', queryParam: '_sname'
			}
		],
		buttonAlign: 'right',
		buttons: [
			{iconCls: 'disk', text: this.bttSubmit, handler: Submit},
			{iconCls: 'cancel', text: this.bttReset, handler: Reset}
		]
	});
	this.addEvents(
		"submit",
		"reset"
	);
}
Ext.extend(ui.catalogue.filter_form , Ext.form.FormPanel, {
	labelTitle: 'Наименование',
	labelType: 'Тип',
	labelExist: 'В продаже',
	labelGroup: 'Группа',

	bttSubmit: 'Применить',
	bttReset: 'Сбросить'
});
