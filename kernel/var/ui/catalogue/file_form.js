ui.catalogue.file_form = function(config){
	Ext.apply(this, config);
	this.Load = function(id, ciid){
		var f = this.getForm();
		f.load({
			url: 'di/catalogue_file/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}, {id: 'ciid', value: ciid}]);
	}
	var Save = function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/catalogue_file/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					//var j = Ext.query('json', action.response.responseXML)[0].textContent.trim();
					//var d = Ext.util.JSON.decode(j);
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
	ui.catalogue.file_form.superclass.constructor.call(this, {
		frame: true,
		layout: 'form',
		fileUpload: true,
		defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
		items: [
			{name: '_sid', xtype: 'hidden'},
			{name: 'ciid', xtype: 'hidden'},
			{fieldLabel: this.labelFile, name: 'file', xtype: 'fileuploadfield', buttonCfg: {text: '', iconCls: 'folder'}},
			{fieldLabel: this.labelTitle, name: 'title'},
			{fieldLabel: this.labelType, hiddenName: 'item_type', xtype: 'combo', value: 0,
				store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [
					[0, 'Изображение'],
					[1, 'Аудио-файл'],
					[2, 'Другое']
				] }),
				valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
			},
			{fieldLabel: this.labelComment, name: 'comment', height: '100', xtype: 'htmleditor'}
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
Ext.extend(ui.catalogue.file_form , Ext.form.FormPanel, {
	loadText: 'Загрузка данных формы',

	labelFile: 'Файл',
	labelName: 'Наименование',
	labelType: 'Тип файла',
	labelComment: 'Коментарий',

	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером"
});
