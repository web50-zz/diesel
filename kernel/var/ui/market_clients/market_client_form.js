ui.market_clients.market_client_form = function(config){
	Ext.apply(this, config);
	var items = new ui.order.main({});
	this.Load = function(id,uid){
		var f = this.getForm();
		f.load({
			url: 'di/market_clients/get.json',
			params: {_sid: id},
			waitMsg: this.loadText,
			success:function(form,action){this.fireEvent('dataready',action,form);},
			scope:this
		});
		f.setValues([{id: '_sid', value: id}]);
		items.store.baseParams = {_suser_id: uid};
	}

	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/market_clients/set.do',
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
	uperclass.constructor.call}
	}.createDelegate(this);
	var Cancel = function(){
		this.fireEvent('cancelled');
	}.createDelegate(this);

	ui.market_clients.market_client_form.superclass.constructor.call(this, {
		border: false, 
		items: [
			{name: '_sid', xtype: 'hidden'},
			{xtype: 'tabpanel', activeItem: 0, border: false, anchor: '100% 100%', defferedRender: false,
			defaults: {hideMode: 'offsets', labelWidth:200, frame: true, layout: 'form'}, items: [
				{id: 'market-client-form', title: this.tabMain, layout: 'form', defaults: {xtype: 'textfield', width: '100', anchor: '100%'}, items: [
					{fieldLabel: this.labelId, name: 'id', xtype: 'displayfield'},
					{fieldLabel: this.labelRegDate, name: 'clnt_created_datetime', xtype: 'displayfield'},
					{fieldLabel: this.labelLname, name: 'clnt_lname', width: 200, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelName, name: 'clnt_name', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelMname, name: 'clnt_mname', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelEmail, name: 'clnt_email', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelPhone, name: 'clnt_phone', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelCountry, name: 'clnt_country', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{xtype:'combo', fieldLabel: this.labelRegion, hiddenName: 'clnt_region', allowBlank: true,
						mode:'local',
						valueField: 'id',
						displayField: 'name2',
						triggerAction: 'all',
						typeAhead: true,
						forceSelection: true
					},
					{fieldLabel: this.labelRegionC, name: 'clnt_region_custom', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelNasPunkt, name: 'clnt_nas_punkt', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{xtype:'textarea', fieldLabel: this.labelAddress, name: 'clnt_address', width: 100, height:50, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelPrefPay, name: 'clnt_payment_pref', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelPrefCurr, name: 'clnt_payment_curr', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText}
					]},
				{id: 'market-client-orders', title: this.tabOrders, frame: false, layout: 'fit', items: [items]}
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
		"cancelled",
		"dataready"
	);
	this.on({
		saved: function(data){
			this.getForm().setValues([{id: '_sid', value: data.id}]);
		},
		dataready: function(action,form){
				var cb = this.getForm().findField('clnt_region');
				cb.store = new Ext.data.JsonStore({
						id: 0,
						fields: [ 'id', 'name2' ],
						data:action.result.data.regs
					});
				cb.setValue(action.result.data.clnt_region_selected);
				},
		scope: this
	})
}
Ext.extend(ui.market_clients.market_client_form, Ext.form.FormPanel, {
	tabMain: 'Информация',
	tabOrders: 'Заказы',
	labelId:'Id',
	labelRegDate: 'Дата регистрации',
	labelName: 'Имя',
	labelLname: 'Фамилия',
	labelMname: 'Отчество',
	labelEmail:'E-mail',
	labelPhone:'Телефон',
	labelCountry:'Страна',
	labelRegion:'Регион',
	labelRegionC:'Иной регион',
	labelNasPunkt:'Город/Населенный пункт',
	labelAddress:'Адрес',
	labelPrefPay:'Предпочтительный способ оплаты',
	labelPrefCurr:'Валюта',

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
