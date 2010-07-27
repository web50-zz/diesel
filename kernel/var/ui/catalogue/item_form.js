ui.catalogue.item_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/catalogue_item/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/catalogue_item/set.do',
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
	ui.catalogue.item_form.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield', width: '100', anchor: '100%'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{fieldLabel: this.labelName, name: 'title', allowBlank: false, blankText: this.blankText, maxLength: 256, maxLengthText: this.maxLengthText},
			{fieldLabel: this.labelPrepay, name: 'prepayment', width: 100, xtype: 'numberfield', decimalPrecision: 2},
			{fieldLabel: this.labelPayfwd, name: 'payment_forward', width: 100, xtype: 'numberfield', decimalPrecision: 2},
			{fieldLabel: this.labelExist, hiddenName: 'on_offer', xtype: 'combo', width: 50, value: 0,
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Нет'], [1, 'Да']] }),
				valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
			},
			{fieldLabel: this.labelType, hiddenName: 'type_id', xtype: 'combo', emptyText: this.blankTypeText, valueNotFoundText: this.blankTypeText,
				store: new Ext.data.JsonStore({url: 'di/guide_type/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				valueField: 'id', displayField: 'name', triggerAction: 'all', editable: false
			},
			{fieldLabel: this.labelProducer, hiddenName: 'producer_id', xtype: 'combo', emptyText: this.blankProducerText, valueNotFoundText: this.blankProducerText,
				store: new Ext.data.JsonStore({url: 'di/guide_producer/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
				valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
			},
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
Ext.extend(ui.catalogue.item_form , Ext.form.FormPanel, {
	loadText: 'Загрузка данных формы',

	labelName: 'Наименование',
	labelExist: 'В продаже',
	labelPrepay: "Предоплата",
	labelPayfwd: "Нал. плат.",
	labelType: 'Тип товара',
	labelProducer: 'Производитель',
	labelCollection: 'Коллекция',
	labelGroup: 'Группа',
	labelStyle: 'Стиль',

	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',
	blankTypeText: 'Выберите тип...',
	blankProducerText: 'Выберите производителя...',
	blankCollectionText: 'Выберите коллекцию...',
	blankGroupText: 'Выберите группу...',
	blankStyleText: 'Выберите стиль...',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
