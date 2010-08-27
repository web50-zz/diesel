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
					
					{xtype:'combo', fieldLabel: this.labelCountry, hiddenName: 'clnt_country', allowBlank: true,
						mode:'local',
						valueField: 'id',
						displayField: 'cr_cntry_title',
						triggerAction: 'all',
						typeAhead: true,
						forceSelection: true,
						listeners:{
							'change':function(fld,newv,oldv){this.fireEvent('cntrychanged',fld,newv,oldv)},
							scope:this
							}
					},

					{xtype:'combo', fieldLabel: this.labelRegion, hiddenName: 'clnt_region', allowBlank: true,
						mode:'local',
						valueField: 'id',
						displayField: 'cr_regions_title',
						triggerAction: 'all',
						typeAhead: true,
						forceSelection: true
					},
					{fieldLabel: this.labelRegionC, name: 'clnt_region_custom', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{fieldLabel: this.labelNasPunkt, name: 'clnt_nas_punkt', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{xtype:'textarea', fieldLabel: this.labelAddress, name: 'clnt_address', width: 100, height:50, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
					{xtype:'combo', fieldLabel: this.labelPrefPay, hiddenName: 'clnt_payment_pref', allowBlank: true,
						mode:'local',
						valueField: 'id',
						displayField: 'pay_var_title',
						triggerAction: 'all',
						typeAhead: true,
						forceSelection: true
					},
					{xtype:'combo', fieldLabel: this.labelPrefCurr, hiddenName: 'clnt_payment_curr', allowBlank: true,
						mode:'local',
						valueField: 'id',
						displayField: 'curr_title',
						triggerAction: 'all',
						typeAhead: true,
						forceSelection: true
					}
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
		"dataready",
		"cntrychanged"
	);
	this.on({
		saved: function(data){
			this.getForm().setValues([{id: '_sid', value: data.id}]);
		},
		dataready: function(action,form){
				var cb = this.getForm().findField('clnt_region');
				cb.store = new Ext.data.JsonStore({
						id: 0,
						fields: ['id', 'cr_regions_title'],
						url:'di/country_regions/list.do',
						root:'records'
					});
				cb.store.loadData(action.result.data.regs);
				cb.setValue(action.result.data.clnt_region_selected);
				var cc = this.getForm().findField('clnt_country');
				cc.store = new Ext.data.JsonStore({
						id: 0,
						fields: ['id', 'cr_cntry_title'],
						url:'di/country_regions_cntry/list.do',
						root:'records'
					});
				cc.store.loadData(action.result.data.cntrys);
				cc.setValue(action.result.data.clnt_country_selected);
				var cr = this.getForm().findField('clnt_payment_curr');
				cr.store = new Ext.data.JsonStore({
						id: 0,
						fields: ['id', 'curr_title'],
						url:'di/market_currency/list.do',
						root:'records'
					});
				cr.store.loadData(action.result.data.currencys);
				cr.setValue(action.result.data.clnt_payment_curr_selected);
				var cp = this.getForm().findField('clnt_payment_pref');
				cp.store = new Ext.data.JsonStore({
						id: 0,
						fields: ['id', 'pay_var_title'],
						url:'di/market_pay_var/list.do',
						root:'records'
					});
				cp.store.loadData(action.result.data.payvar);
				cp.setValue(action.result.data.clnt_payment_pref_selected);
				},

		cntrychanged:function(fld,newv,oldv){
					var cb = this.getForm().findField('clnt_region');
					cb.reset();
					cb.store.removeAll();
					cb.store.reload({params:{'_scr_regions_part_id':newv}});
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
