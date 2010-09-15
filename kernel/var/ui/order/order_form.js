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
				{id: 'order-form-main', title: this.tabMain, layout: 'form', defaults: {width: '100', anchor: '100%'}, items: [
					{fieldLabel: this.labelStatus, hiddenName: 'status', xtype: 'combo',
						store: new Ext.data.JsonStore({url: 'di/guide_order_status/combolist.json', root: 'records', fields: ['id', 'title'], autoLoad: true}),
						valueField: 'id', displayField: 'title', triggerAction: 'all', editable: false
					},
					{fieldLabel: this.labelAdmComments, name: 'admin_comments', xtype: 'htmleditor'}
				]},
				{id: 'order-form-client', title: this.tabClient, autoScroll: true, layout: 'form', defaults: {xtype: 'displayfield'}, items: [
					{fieldLabel: this.labelUserName, name: 'str_user_name'},
					{fieldLabel: this.labelCrtdDate, name: 'created_datetime'},
					{fieldLabel: this.labelCountry, name: 'country_str'},
					{fieldLabel: this.labelRegion, name: 'region_str'},
					{fieldLabel: this.labelAddress, name: 'address'},
					{fieldLabel: this.labelMetOfPay, name: 'pt_string'},
					{fieldLabel: this.labelDiscount, name: 'discount'},
					{fieldLabel: this.labelTtlItems, name: 'total_items'},
					{fieldLabel: this.labelTtlItemsCost, name: 'total_items_cost'},
					{fieldLabel: this.labelNumOfParcels, name: 'number_of_parcels'},
					{fieldLabel: this.labelDelCost, name: 'delivery_cost'},
					{fieldLabel: this.labelTtlCost, name: 'total_cost'},
					{fieldLabel: this.labelComments, name: 'comments'}
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
	tabMain: 'Состояние',
	tabClient: 'Описание заказа',
	tabItems: 'Список товаров',

	labelAdmComments: 'Комментарий',
	labelUserName: 'Пользователь',
	labelCrtdDate: 'Дата заказа',
	labelStatus: 'Статус',
	labelCountry: 'Страна',
	labelRegion: 'Регион',
	labelAddress: 'Адрес',
	labelMetOfPay: 'Способ оплаты',
	labelDiscount: 'Скидка',
	labelDelCost: 'Стоимость доставки',
	labelTtlItems: 'Кол-во товаров',
	labelTtlItemsCost: 'Общая стоимость товаров',
	labelNumOfParcels: 'Кол-во посылок',
	labelTtlCost: 'Общая стоимость заказа',
	labelComments: 'Доп. инфо',

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
