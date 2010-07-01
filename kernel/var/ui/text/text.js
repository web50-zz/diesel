ui.text.main = function(config){
	var self = this;
	this.cid = 0;
	this.pid = 0;
	this.autoScroll = true;
	Ext.apply(this, config);
	var Save = function(data){
		Ext.Ajax.request({
			url: 'di/text/set.do',
			params: data,
			disableCaching: true,
			callback: function(options, success, response){
				if (success)
					self.fireEvent('saved');
				else
					showError("Ошибка сохранения");
			}
		});
	}
	var Submit = function(f){
		f.submit({
			url: 'di/text/set.do',
			waitMsg: 'Сохранение...',
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success){
					self.cid = d.data.id;
					self.fireEvent('saved');
				}else
					showError(d.errors);
			},
			failure: function(form, action){
				switch (action.failureType){
					case Ext.form.Action.CLIENT_INVALID:
						showError("Корректно заполните все необходимые поля.");
					break;
					case Ext.form.Action.CONNECT_FAILURE:
						showError("Ошибка связи с сервером");
					break;
					case Ext.form.Action.SERVER_INVALID:
						showError(action.result.errors);
				}
			}
		});
	}
	var getForm = function(data){
		return new Ext.FormPanel({
			frame: true, 
			defaults: {xtype: 'textfield', anchor: '100%'},
			buttonAlign: 'right',
			items: [
				{name: '_sid', inputType: 'hidden', value: self.cid},
				{name: 'pid', inputType: 'hidden', value: self.pid},
				{hideLabel: true, name: 'title', width: 200, allowBlank: false},
				{hideLabel: true, name: 'content', xtype: 'ckeditor', CKConfig: {
					height: 300,
					filebrowserImageBrowseUrl: 'ui/file_manager/browser.html'
				}}
			]
		});
		
	}
	this.editPage = function(){
		var fp = getForm();
		var w = new Ext.Window({title: 'Редактирование', modal: true, layout: 'fit', width: 800, height: 580, items: fp});
		var submit = function(){
			Submit(fp.getForm());
		}
		fp.addButton({iconCls: 'disk', text: 'Сохранить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy()}, this, {single: true});
		w.show(null, function(){
			fp.getForm().load({
				url: 'di/text/item.json',
				params: {_sid: this.cid},
				waitMsg: 'Загрузка...'
			});
		}, this);
	}
	this.savePage = function(data){
		Save(data);
	}
	this.loadPage = function(){
		Ext.Ajax.request({
			url: 'di/text/get.json',
			params: {_spid: this.pid},
			disableCaching: true,
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (success && d.success){
					if (d.data){
						this.cid = d.data.id;
						this.body.update(d.data.content, false, function(){
							this.syncSize();
							this.fireEvent('loaded');
						}.createDelegate(this));
					}
				}else
					showError("Ошибка во время загрузки данных");
			},
			scope: this
		});
	}
	ui.text.main.superclass.constructor.call(this, {
		tbar: new Ext.Toolbar({items:[
			{iconCls: 'page_edit', text: 'Изменить', handler: this.editPage, scope: this}
		]}),
	});
	this.addEvents({
		loaded: true,
		saved: true
	});
	this.on({
		render: this.loadPage,
		saved: this.loadPage,
		scope: this
	});
};
Ext.extend(ui.text.main, Ext.Panel, {});
