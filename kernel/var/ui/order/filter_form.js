ui.order.filter_form = function(config){
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
	ui.order.filter_form.superclass.constructor.call(this, {
		frame: true,
		layout: 'form',
		labelAlign: 'top',
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{fieldLabel: this.labelTitle, name: '_sstr_user_name'},
			{fieldLabel: this.labelStatus, hiddenName: '_sstatus', xtype: 'combo', value: '',
				store: new Ext.data.JsonStore({url: 'di/guide_order_status/combolist.json', baseParams: {with_empty: 'yes'}, root: 'records', fields: ['id', 'title'], autoLoad: true}),
				valueField: 'id', displayField: 'title', triggerAction: 'all', editable: false
			},
			{fieldLabel: this.labelMoP, hiddenName: '_smethod_of_payment', xtype: 'combo', value: '',
				store: new Ext.data.JsonStore({url: 'di/guide_pay_type/combolist.json', baseParams: {with_empty: 'yes'}, root: 'records', fields: ['id', 'title'], autoLoad: true}),
				valueField: 'id', displayField: 'title', triggerAction: 'all', editable: false
			},
			{xtype:'fieldset', title: this.labelDate,
				defaultType: 'datefield',
				defaults: {width: 100, anchor: '100%', format: 'Y-m-d', allowBlank: true},
				items :[
					{fieldLabel: this.labelDateFrom, name: 'oDateFr'},
					{fieldLabel: this.labelDateTo, name: 'oDateTo'},
				]
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
Ext.extend(ui.order.filter_form , Ext.form.FormPanel, {
	labelTitle: 'Пользователь',
	labelStatus: 'Статус',
	labelMoP: 'Способ оплаты',
	labelDate: 'Дата заказа',
	labelDateFrom: 'с',
	labelDateTo: 'по',

	bttSubmit: 'Применить',
	bttReset: 'Сбросить'
});
