ui.registry.item_form = Ext.extend(Ext.form.FormPanel, {
	lblName: 'Имя',
	lblType: "Тип",
	lblValue: 'Значение',
	lblCmmnt: 'Комментарий',

	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",

	Load: function(id){
		var f = this.getForm();
		f.load({
			url: 'di/registry/get.json',
			params: {_sid: id},
			waitMsg: this.loadText
		});
		f.setValues([{id: '_sid', value: id}]);
	},
	Save: function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/registry/set.do',
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
	},
	Cancel: function(){
		this.fireEvent('cancelled');
	},

	frame: true, 
	defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
	buttonAlign: 'right',

	/**
	 * @constructor
	 */
	constructor: function(config){
		config = config || {};
		Ext.apply(this, config, {
			items: [
				{name: '_sid', xtype: 'hidden'},
				{fieldLabel: this.lblName, name: 'name', allowBlank: false, blankText: this.blankText, maxLength: 64, maxLengthText: this.maxLengthText},
				{fieldLabel: this.lblType, hiddenName: 'type', xtype: 'combo', value: '0',
					valueField: 'value', displayField: 'title', allowBlank: false,
					mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false,
					store: ui.registry.type
				},
				{fieldLabel: this.lblValue, name: 'value', allowBlank: false, blankText: this.blankText},
				{fieldLabel: this.lblCmmnt, name: 'comment', xtype: 'textarea', maxLength: 255, maxLengthText: this.maxLengthText}
			],
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			]
		});
		ui.registry.grid.superclass.constructor.call(this, config);
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config){
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o){
		this.addEvents(
			"saved",
			"cancelled"
		);
		this.on({
			saved: function(data){
				this.getForm().setValues([{id: '_sid', value: data.id}]);
			},
			scope: this
		});
	}
});
