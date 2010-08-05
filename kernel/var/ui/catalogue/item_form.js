ui.catalogue.item_form = function(config){
	Ext.apply(this, config);
	var tpl = new Ext.XTemplate(
		'<center><img src="/storage/{real_name}" title="{name}" width="200"/></center>'
	);
	var pnlPrvw = new Ext.Panel({html: '<center>No preview</center>'});
	var files = new ui.catalogue.files({});
	var preview = new Ext.form.ComboBox({fieldLabel: this.labelPreview, hiddenName: 'preview',
		store: new Ext.data.JsonStore({url: 'di/catalogue_file/preview_combo.json', root: 'records', fields: ['real_name', 'name']}),
		valueField: 'real_name', displayField: 'name', triggerAction: 'all', editable: false
	});
	preview.on({
		select: function(combo, record){
			tpl.overwrite(pnlPrvw.body, record.data);
		}
	});
	var picture = new Ext.form.ComboBox({fieldLabel: this.labelPicture, hiddenName: 'picture',
		store: new Ext.data.JsonStore({url: 'di/catalogue_file/picture_combo.json', root: 'records', fields: ['real_name', 'name']}),
		valueField: 'real_name', displayField: 'name', triggerAction: 'all', editable: false
	});
	files.on({
		changes: function(){
			preview.store.reload();
			picture.store.reload();
		},
		deleted: function(){
			picture.store.reload();
		}
	});
	var style_out = new ui.catalogue.styles({title: 'Доступные', region: 'east', width: 300, split: true,
		ddGroup: 'style_out',
		enableDragDrop: true});
	style_out.store.baseParams = {iid: 0, _siid: 'null'};
	var style_in = new ui.catalogue.styles({title: 'Выбранные', region: 'center',
		ddGroup: 'style_in',
		enableDragDrop: true});
	style_in.store.baseParams = {iid: 0, _niid: 'null'};
	style_out.on({
		styles_removed: function(){
			style_out.reload();
			style_in.reload();
		},
		afterrender: function(){
			new Ext.dd.DropTarget(style_out.getView().scroller.dom , {ddGroup: 'style_in', notifyDrop: style_out.removeStyles});
		}
	});
	style_in.on({
		styles_added: function(){
			style_out.reload();
			style_in.reload();
		},
		afterrender: function(){
			new Ext.dd.DropTarget(style_in.getView().scroller.dom , {ddGroup: 'style_out', notifyDrop: style_in.addStyles});
		}
	});
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/catalogue_item/get.json',
			params: {_sid: id},
			waitMsg: this.loadText,
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success)
					tpl.overwrite(pnlPrvw.body, {real_name: d.data.preview, name: d.data.title});
				else
					showError(d.errors);
				
			}
		});
		f.setValues([{id: '_sid', value: id}]);
		files.setItemId(id);

		preview.store.baseParams = {_sciid: id};
		preview.store.reload();

		picture.store.baseParams = {_sciid: id};
		picture.store.reload();

		Ext.apply(style_in.store.baseParams, {iid: id});

		Ext.apply(style_out.store.baseParams, {iid: id});
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
				{id: 'item-main', title: this.tabMain, autoScroll: true, layout: 'column', items: [
					{columnWidth: .6, layout: 'form', labelAlign: 'top', defaults: {xtype: 'textfield', width: '100', anchor: '100%'}, items: [
						{fieldLabel: this.labelType, hiddenName: 'type_id', xtype: 'combo', emptyText: this.blankTypeText, valueNotFoundText: this.blankTypeText,
							store: new Ext.data.JsonStore({url: 'di/guide_type/combolist.json', root: 'records', fields: ['id', 'name'], autoLoad: true}),
							valueField: 'id', displayField: 'name', triggerAction: 'all', editable: false
						},
						{fieldLabel: this.labelName, name: 'title', allowBlank: false, blankText: this.blankText, maxLength: 256, maxLengthText: this.maxLengthText},
						{fieldLabel: this.labelDate, name: 'income_date', xtype: 'datefield', format: 'Y-m-d'},
						preview, picture,
						{hideLabel: true, xtype: 'compositefield', items: [
							{xtype: 'displayfield', value: this.labelRecomended},
							{hiddenName: 'recomended', xtype: 'combo', width: 50, value: 0,
								store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Нет'], [1, 'Да']] }),
								valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
							},
							{xtype: 'displayfield', value: this.labelExist},
							{hiddenName: 'on_offer', xtype: 'combo', width: 50, value: 0,
								store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Нет'], [1, 'Да']] }),
								valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
							}
						]},
						{fieldLabel: this.labelPrice, hiddenName: 'price_id', xtype: 'combo', width: 200,
							store: new Ext.data.JsonStore({url: 'di/guide_price/combolist.json', root: 'records', fields: ['id', 'title'], autoLoad: true}),
							valueField: 'id', displayField: 'title', triggerAction: 'all', editable: false
						},
						{hideLabel: true, xtype: 'compositefield', items: [
							{xtype: 'displayfield', value: this.labelPrepay},
							{name: 'prepayment', width: 70, xtype: 'numberfield', decimalPrecision: 2},
							{xtype: 'displayfield', value: this.labelPayfwd},
							{name: 'payment_forward', width: 70, xtype: 'numberfield', decimalPrecision: 2}
						]}
					]},
					{columnWidth: .4, bodyStyle: 'margin: 0 0 0 5px', items: [pnlPrvw]}
				]},
				{id: 'item-style', title: this.tabStyle, frame: false, layout: 'border', items: [
					style_in,
					style_out
				]},
				{id: 'item-extend', title: this.tabExtend, layout: 'form', defaults: {xtype: 'textfield', width: '100', anchor: '100%'}, items: [
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

			preview.store.baseParams = {_sciid: data.id};
			preview.store.reload();

			picture.store.baseParams = {_sciid: data.id};
			picture.store.reload();

			Ext.apply(style_in.store.baseParams, {iid: data.id});

			Ext.apply(style_out.store.baseParams, {iid: data.id});
		},
		scope: this
	})
}
Ext.extend(ui.catalogue.item_form , Ext.form.FormPanel, {
	loadText: 'Загрузка данных формы',

	tabMain: 'Общая информация',
	tabStyle: 'Стили',
	tabExtend: 'Дополнительно',
	tabDescr: 'Описание',
	tabFiles: 'Файлы',

	labelName: 'Наименование',
	labelDate: 'Дата поступления',
	labelExist: 'В продаже',
	labelRecomended: 'Рекомендовано',
	labelPrice: 'Прайс-цена',
	labelPrepay: "Цена по предоплата",
	labelPayfwd: "Цена нал. плат.",
	labelType: 'Тип товара',
	labelProducer: 'Производитель',
	labelCollection: 'Коллекция',
	labelGroup: 'Группа',
	labelStyle: 'Стиль',
	labelPreview: 'Preview',
	labelPicture: 'Изображение',

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
