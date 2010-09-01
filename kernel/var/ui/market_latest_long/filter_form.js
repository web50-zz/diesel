ui.market_latest_long.filter_form = function(config){
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
	ui.market_latest_long.filter_form.superclass.constructor.call(this, {
		frame: true,
		layout: 'form',
		labelAlign: 'top',
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{fieldLabel: this.labelTitle, name: '_sm_latest_l_title'}
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
Ext.extend(ui.market_latest_long.filter_form , Ext.form.FormPanel, {
	labelTitle: 'Заголовок',
	bttSubmit: 'Применить',
	bttReset: 'Сбросить'
});
