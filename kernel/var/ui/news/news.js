ui.news.main = function(config){
	var self = this;
	var fm = Ext.form;
	this.pid = 0;
	this.changes = false;
	this.cm = new Ext.grid.ColumnModel([
		{id: 'id', dataIndex: 'id', hidden: true},
		{id: 'release_date', dataIndex: 'release_date', header: "Дата", width: 150, sortable: true, renderer: formatDate, editor: new fm.DateField({allowBlank: false, format: 'Y-m-d'})},
		{id: 'title', dataIndex: 'title', header: "Заголовок", sortable: true, editor: new fm.TextField({maxLength: 255, maxLengthText: 'Не больше 255 символов'})},
		{id: 'author', dataIndex: 'author', header: "Автор", width: 150, sortable: true, editor: new fm.TextField({maxLength: 255, maxLengthText: 'Не больше 255 символов'})},
		{id: 'source', dataIndex: 'source', header: "Источник", width: 150, sortable: true, editor: new fm.TextField({maxLength: 64, maxLengthText: 'Не больше 64 символов'})}
	]);
	this.autoExpandColumn = 'title';
	this.stripeRows = true;
	this.autoScroll = true;
	this.viewConfig = new Ext.grid.GridView({emptyText: 'На данной странице нет новостей'});
	this.loadMask = new Ext.LoadMask(Ext.getBody(),{msg: 'Загрузка данных...'});
	this.selModel = new Ext.grid.RowSelectionModel({singleSelect: true});
	this.limit = 30;
	Ext.apply(this, config, {});
	this.store = new Ext.data.JsonStore({
		url: 'di/news/list.json',
		baseParams: {_spid: this.pid},
		autoLoad: true,
		id: 'id',
		root: 'records',
		totalProperty: 'total',
		fields: [{name: 'id', type: 'int'}, {name: 'release_date', type: 'date', dateFormat: 'Y-m-d'}, 'title', 'author', 'source'],
		remoteSort: true,
		listeners: {
			beforeload: function(store, options){
				if (this.changes){
					Ext.Msg.confirm('Подтверждение.', 'Имеются несохранённые данные, вы действительно хотите перегрузить данные?', function(btn){
						if (btn == "yes"){
							this.setAsChanged(false);
							store.load(options);
						}
					}, this);
					return false;
				}else
					return true;
			},
			scope: this
		}
	});
	this.reload = function(){
		var bb = this.getBottomToolbar();
		bb.doLoad(bb.cursor);
	};
	var Save = function(data){
		Ext.Ajax.request({
			url: 'di/news/set.do',
			params: data,
			waitMsg: 'Сохранение...',
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
			url: 'di/news/set.do',
			waitMsg: 'Сохранение...',
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success)
					self.fireEvent('saved');
				else
					showError(d.errors);
			},
			failure: function(form, response){
				showError('Ошибка сохранения.');
			}
		});
	}
	var Delete = function(id){
		Ext.Ajax.request({
			url: 'di/news/unset.do',
			params: {_sid: id, _spid: self.pid},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (success && d.success)
					self.fireEvent('deleted');
				else
					showError(d.error || "Ошибка удаления");
			}
		})
	}
	var getForm = function(data){
		return new Ext.FormPanel({
			frame: true, 
			defaults: {xtype: 'textfield', anchor: '100%'},
			buttonAlign: 'right',
			items: [
				{name: '_sid', inputType: 'hidden', value: (data.id || 0)},
				{name: 'pid', inputType: 'hidden', value: self.pid},
				{fieldLabel: 'Название', name: 'title', value: (data.title || ''), maxLength: 255, maxLengthText: 'Не больше 255 символов'},
				{fieldLabel: 'Дата', name: 'release_date', format: 'Y-m-d', allowBlank: false, xtype: 'datefield'},
				{fieldLabel: 'Источник', name: 'source', value: (data.source || ''), maxLength: 64, maxLengthText: 'Не больше 64 символов'},
				{fieldLabel: 'Автор', name: 'author', value: (data.author || ''), maxLength: 255, maxLengthText: 'Не больше 255 символов'},
				{hideLabel: true, name: 'content', value: (data.content || ''), xtype: 'ckeditor', CKConfig: {
					height: 260,
					filebrowserImageBrowseUrl: 'ui/file_manager/browser.html'
				}}
			]
		});
		
	}
	this.addNews = function(){
		var fp = getForm({});
		var w = new Ext.Window({title: 'Добавить новость', modal: true, layout: 'fit', width: 800, height: 620, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid())
				Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Добавить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy(); this.reload()}, this, {single: true});
		w.show();
	}
	this.editNews = function(id){
		var fp = getForm({id: id});
		var w = new Ext.Window({title: 'Редактировать новость', modal: true, layout: 'fit', width: 800, height: 620, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid())
				Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Сохранить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy(); this.reload()}, this, {single: true});
		w.show(null, function(){
			fp.getForm().load({
				url: 'di/news/item.json',
				params: {_sid: id},
				waitMsg: 'Загрузка...'
			});
		}, this);
	}
	this.saveNews = function(id, data){
		Save(Ext.apply({_sid: id}, data));
	}
	this.deleteNews = function(id, name){
		Ext.Msg.confirm('Подтверждение.', 'Вы действительно хотите удалить новость "'+(name || id)+'"?', function(btn){
			this.on('deleted', this.reload(), this, {single: true});
			if (btn == "yes") Delete(id)
		}, this);
	}
	this.setAsChanged = function(bool){
		var tb = this.getTopToolbar();
		tb.items.item('msave').setDisabled(!bool);
		this.changes = bool;
	}
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'pencil', text: 'Редактировать', handler: this.editNews.createDelegate(this, [id])},
			{iconCls: 'delete', text: 'Удалить', handler: this.deleteNews.createDelegate(this, [id, row.get('title')])}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	var multiSave = function(){
		var mr = this.store.getModifiedRecords();
		for(n = 0; n < mr.length; n++)
			this.saveNews(mr[n].get('id'), mr[n].getChanges());
		this.setAsChanged(false);
		this.reload();
	}
	ui.news.main.superclass.constructor.call(this, {
		tbar: new Ext.Toolbar({items:[
			{iconCls: 'add', text: 'Добавить', handler: this.addNews, scope: this},
			'-',
			{id: 'msave', iconCls: 'disk', text: 'Сохранить', disabled: true, handler: multiSave, scope: this}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Новости с {0} по {1} из {2}',
			emptyMsg: 'Нет новостей'
		})
	});
	this.addEvents({
		loaded: true,
		saved: true,
		deleted: true
	});
	this.on({
		rowcontextmenu: onCmenu,
		afteredit: this.setAsChanged.createDelegate(this, [true]),
		deleted: this.reload,
		scope: this
	});
};
Ext.extend(ui.news.main, Ext.grid.EditorGridPanel, {});
