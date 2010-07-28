ui.catalogue.item_form = function(config){
	Ext.apply(this, config);
	var files = new ui.catalogue.files({});
	var preview = new Ext.form.ComboBox({fieldLabel: this.labelPreview, hiddenName: 'preview',
		store: new Ext.data.JsonStore({url: 'di/catalogue_file/preview_combo.json', root: 'records', fields: ['real_name', 'name']}),
		valueField: 'real_name', displayField: 'name', triggerAction: 'all', editable: false
	});
	files.on({
		changes: function(){
			preview.store.reload();
		}
	})
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/catalogue_item/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
		files.setItemId(id);
		preview.store.baseParams = {_sciid: id};
		preview.store.reload();
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
		border: false,
		items: [
			{name: '_sid', xtype: 'hidden'},
			{xtype: 'tabpanel', activeItem: 0, border: false, anchor: '100% 100%', defferedRender: false,
			defaults: {hideMode: 'offsets', frame: true, layout: 'form'}, items: [
			{id: 'item-main', title: this.tabMain, defaults: {xtype: 'textfield', width: '100', anchor: '100%'}, items: [
				{fieldLabel: this.labelName, name: 'title', allowBlank: false, blankText: this.blankText, maxLength: 256, maxLengthText: this.maxLengthText},
				preview,
				{fieldLabel: this.labelPrepay, name: 'prepayment', width: 100, xtype: 'numberfield', decimalPrecision: 2},
				{fieldLabel: this.labelPayfwd, name: 'payment_forward', width: 100, xtype: 'numberfield', decimalPrecision: 2},
				{fieldLabel: this.labelExist, hiddenName: 'on_offer', xtype: 'combo', width: 50, anchor: null, value: 0,
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
				{fieldLabel: this.labelCollection, hiddenName: 'collection_id', xtype: 'combo', emptyText: this.blankCollectionText, valueNotFoundText: this.blankCollectionText,
					store: new Ext.data.JsonStore({url: 'di/guide_collection/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
					valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.labelGroup, hiddenName: 'group_id', xtype: 'combo',  emptyText: this.blankGroupText, valueNotFoundText: this.blankGroupText,
					store: new Ext.data.JsonStore({url: 'di/guide_group/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
					valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.labelStyle, hiddenName: 'style_id', xtype: 'combo', emptyText: this.blankStyleText, valueNotFoundText: this.blankStyleText,
					store: new Ext.data.JsonStore({url: 'di/guide_style/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
					valueField: 'id', displayField: 'name', triggerAction: 'all', selectOnFocus: true, editable: false
				}
			]},
			{id: 'item-descr', title: this.tabDescr, frame: false, defaults: {width: '200', anchor: '100% 100%'}, items: [
				{hideLabel: true, name: 'description', xtype: 'htmleditor'}
			]},
			{id: 'item-files', title: this.tabFiles, frame: false, layout: 'fit', items: [files]}
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
			files.setItemId(data.id);
		},
		scope: this
	})
}
Ext.extend(ui.catalogue.item_form , Ext.form.FormPanel, {
	loadText: 'Загрузка данных формы',

	tabMain: 'Общая информация',
	tabDescr: 'Описание',
	tabFiles: 'Файлы',

	labelName: 'Наименование',
	labelExist: 'В продаже',
	labelPrepay: "Предоплата",
	labelPayfwd: "Нал. плат.",
	labelType: 'Тип товара',
	labelProducer: 'Производитель',
	labelCollection: 'Коллекция',
	labelGroup: 'Группа',
	labelStyle: 'Стиль',
	labelPreview: 'Изображение',

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
