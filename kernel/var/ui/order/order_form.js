ui.order.order_form = function(config){
	Ext.apply(this, config);
	var items = new ui.order.order_items({});
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/order/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
		items.applyStore({_soid: id});
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/order/set.do',
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

	ui.order.order_form.superclass.constructor.call(this, {
		border: false, 
		items: [
			{name: '_sid', xtype: 'hidden'},
			{xtype: 'tabpanel', activeItem: 0, border: false, anchor: '100% 100%', defferedRender: false,
			defaults: {hideMode: 'offsets', frame: true, layout: 'form'}, items: [
				{id: 'order-form-main', title: this.tabMain, layout: 'form', defaults: {xtype: 'textfield', width: '100', anchor: '100%'}, items: [
					{fieldLabel: this.labelUserName, name: 'str_user_name', xtype: 'displayfield'},
					{fieldLabel: this.labelCrtdDate, name: 'created_datetime', xtype: 'displayfield'},
					{fieldLabel: this.labelStatus, hiddenName: 'status', xtype: 'combo',
						store: new Ext.data.JsonStore({url: 'di/guide_order_status/combolist.json', root: 'records', fields: ['id', 'title'], autoLoad: true}),
						valueField: 'id', displayField: 'title', triggerAction: 'all', editable: false
					},
					{fieldLabel: this.labelMetOfPay, hiddenName: 'method_of_payment', xtype: 'combo',
						store: new Ext.data.JsonStore({url: 'di/guide_pay_type/combolist.json', root: 'records', fields: ['id', 'title'], autoLoad: true}),
						valueField: 'id', displayField: 'title', triggerAction: 'all', editable: false
					},
					{fieldLabel: this.labelDiscount, name: 'discount', xtype: 'displayfield'},
					{fieldLabel: this.labelTtlItems, name: 'total_items', xtype: 'displayfield'},
					{fieldLabel: this.labelTtlItemsCost, name: 'total_items_cost', xtype: 'displayfield'},
					{fieldLabel: this.labelNumOfParcels, name: 'number_of_parcels', xtype: 'displayfield'},
					{fieldLabel: this.labelDelCost, name: 'delivery_cost', xtype: 'displayfield'},
					{fieldLabel: this.labelTtlCost, name: 'total_cost', xtype: 'displayfield'},
					{fieldLabel: this.labelComments, name: 'comments', xtype: 'displayfield'}
				]},
				{id: 'order-form-items', title: this.tabItems, frame: false, layout: 'fit', items: [items]}
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
Ext.extend(ui.order.order_form, Ext.form.FormPanel, {
	tabMain: 'Информация',
	tabItems: 'Товары',

	labelUserName: 'Пользователь',
	labelCrtdDate: 'Дата заказа',
	labelStatus: 'Статус',
	labelMetOfPay: 'Способ оплаты',
	labelDiscount: 'Скидка',
	labelDelCost: 'Стоимость доставки',
	labelTtlItems: 'Кол-во товаров',
	labelTtlItemsCost: 'Общая стоимость товаров',
	labelNumOfParcels: 'Кол-во посылок',
	labelTtlCost: 'Общая стоимость заказа',
	labelComments: 'Комментарий',

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
