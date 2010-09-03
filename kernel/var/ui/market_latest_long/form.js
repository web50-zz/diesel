ui.market_latest_long.form = function(config){
	Ext.apply(this, config);
	var itemslist = new ui.market_latest_long.items_list({region:'center',height:400});
	this.Load = function(id){
		var f = this.getForm();
		f.load({
			url: 'di/market_latest_long/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
			f.setValues([{id: '_sid', value: id}]);
			itemslist.applyStore({'_sm_latest_ls_issue_id':id});
			itemslist.issueId = id;
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/market_latest_long/set.do',
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

	var setMproduct = function()
	{
		var vp = {ui_configure: {}};
		var grid = new ui.market_latest_long.catalogue_list({region: 'center'});
		var filter = new ui.catalogue.filter_form({region: 'west', split: true, width: 200});
		grid.setBack(this);
		filter.on({
			submit: grid.applyStore,
			reset: grid.applyStore,
			afterrender: function(){filter.Load((vp.ui_configure || {}))},
			scope: grid
			});

		var panel1 = new Ext.Panel({
			title: 'Поиск по каталогу',
			layout:'border',
			items: [filter,grid]
		});

		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', width: 700, height: 400,items:panel1});
		w.show(null, function(){});
	}.createDelegate(this);

	this.oops = function(id)
	{
		var f = this.getForm();
			Ext.Ajax.request({
			url: 'di/catalogue_item/list.do',
			success:function(response,opts){
					var d = Ext.util.JSON.decode(response.responseText);
					if (d.success){
						f.setValues([{id: 'pr_title', value: d.records[0].id + ': ' + d.records[0].str_group +' '+d.records[0].title + ' '+ d.records[0].str_type}]);
						f.setValues([{id: 'm_latest_l_product_id', value: id}]);
					}
					else{
						showError(d.errors);
					}
			},
			failure: function(response,opts ){
						showError(this.errText);
					},
			params:{_sid:id}
			});

	}.createDelegate(this);


	ui.market_latest_long.form.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{name: 'm_latest_l_product_id', xtype: 'hidden'},
			{xtype: 'tabpanel', activeItem: 0, border: false, anchor: '100% 100%', defferedRender: false,defaults: {hideMode: 'offsets', frame: true, layout: 'form'},
				items:[
					{id: 'item-main', title: this.labelTab1, autoScroll: true, layout: 'column',
						tbar: [
							{text: this.bttSetMproduct, iconCls: "layout_add", handler: setMproduct}
							],
						items: [
							{columnWidth: .99, layout: 'form', labelAlign: 'left', defaults: {xtype: 'textfield'}, 
							items: [
								{xtype: 'displayfield', fieldLabel:this.labelId , name: 'id'},
								{xtype: 'displayfield', fieldLabel:this.labelCreated , name: 'm_latest_l_created_datetime'},
								{xtype: 'displayfield', fieldLabel:this.labelChanged , name: 'm_latest_l_changed_datetime'},
								{xtype: 'displayfield', fieldLabel:this.labelProduct , name: 'pr_title'},
								{fieldLabel: this.labelIssueDate, width:200, name: 'm_latest_l_issue_datetime',format: 'Y-m-d H:i:s', allowBlank: true, xtype: 'datefield'},
								{fieldLabel: this.labelTitle, name: 'm_latest_l_title', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
								{xtype: 'ckeditor', fieldLabel: this.labelText, name: 'm_latest_l_text', height:300, CKConfig: {filebrowserImageBrowseUrl: 'ui/file_manager/browser.html'}}
							]}
						]},
					{id: 'item-files', title: this.labelTab2, frame: false, layout: 'border', items: [itemslist]}
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
			itemslist.issueId = data.id;	
		},
		scope: this
	});
}
Ext.extend(ui.market_latest_long.form, Ext.form.FormPanel, {
	labelTitle: 'Заголовок',
	labelText: 'Текст',
	labelId: 'ID',

	labelIssueDate: 'Датировано',
	labelCreated: 'Создано',
	labelChanged: 'Изменено',
	labelTab1:'Общее',
	labelTab2:'Новинки',
	labelProduct:'Рецензия по',
	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',
	bttSetMproduct:'Рецензия по',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
