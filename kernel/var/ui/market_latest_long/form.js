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
	
	ui.market_latest_long.form.superclass.constructor.call(this, {
		frame: true, 
		defaults: {xtype: 'textfield'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{xtype: 'tabpanel', activeItem: 0, border: false, anchor: '100% 100%', defferedRender: false,defaults: {hideMode: 'offsets', frame: true, layout: 'form'},
				items:[
					{id: 'item-main', title: this.labelTab1, autoScroll: true, layout: 'column', items: [
						{columnWidth: .99, layout: 'form', labelAlign: 'left', defaults: {xtype: 'textfield'}, 
						items: [
							{xtype: 'displayfield', fieldLabel:this.labelId , name: 'id'},
							{xtype: 'displayfield', fieldLabel:this.labelCreated , name: 'm_latest_l_created_datetime'},
							{xtype: 'displayfield', fieldLabel:this.labelChanged , name: 'm_latest_l_changed_datetime'},
							{fieldLabel: this.labelIssueDate, width:200, name: 'm_latest_l_issue_datetime',format: 'Y-m-d H:i:s', allowBlank: true, xtype: 'datefield'},
							{fieldLabel: this.labelTitle, name: 'm_latest_l_title', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, maxLength: 255, maxLengthText: this.maxLengthText},
							{xtype:'htmleditor', fieldLabel: this.labelText, name: 'm_latest_l_text', width: 100, anchor: '100%', allowBlank: false, blankText: this.blankText, height:300}
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
