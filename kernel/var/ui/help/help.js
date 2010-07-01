ui.help.main = function(config){
	var self = this;
	this.changes = false;
	this.cm = new Ext.grid.ColumnModel([
		{id: 'id', dataIndex: 'id', hidden: true},
		{id: 'name', dataIndex: 'name', header: "Имя", sortable: true},
		{id: 'title', dataIndex: 'title', header: "Заголовок", sortable: true}
	]);
	this.autoExpandColumn = 'title';
	this.stripeRows = true;
	this.autoScroll = true;
	this.viewConfig = new Ext.grid.GridView({emptyText: 'Нет записей'});
	this.loadMask = 'Загрузка данных...';
	this.selModel = new Ext.grid.RowSelectionModel({singleSelect: true});
	this.limit = 50;
	Ext.apply(this, config, {});
	this.store = new Ext.data.JsonStore({
		url: 'di/help/list.json',
		autoLoad: true,
		totalProperty: 'total',
		root: 'records',
		id: 'id',
		fields: [{name: 'id', type: 'int'},'name','title'],
		listeners: {
			loadexception: function(a, b, c, d){
				var r = Ext.util.JSON.decode(c.responseText);
				if (!r.success) showError(r.errors);
			}
		},
		remoteSort: true
	});
	this.reload = function(){
		var bb = this.getBottomToolbar();
		bb.doLoad(bb.cursor);
	};
	var Save = function(id, data){
		Ext.Ajax.request({
			url: 'di/help/set.do',
			params: Ext.apply(data, {_sid: id}),
			callback: function(options, success, response){
				if (success)
					self.fireEvent('saved');
				else
					showError('Во время сохранения возникли ошибки.');
			}
		});
	}
	var Submit = function(f){
		f.submit({
			url: 'di/help/set.do',
			waitMsg: 'Сохранение...',
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success)
					self.fireEvent('saved');
				else
					showError(d.errors);
			},
			failure: function(form, action){
				showError('Во время сохранения возникли ошибки.');
			}
		});
	}
	var Delete = function(id){
		Ext.Ajax.request({
			url: 'di/help/unset.do',
			params: {_sid: id},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (d.success)
					self.fireEvent('deleted');
				else
					showError('Во время удаления возникли ошибки.');
			}
		})
	}
	var getForm = function(data){
		return new Ext.form.FormPanel({
			frame: true, 
			defaults: {xtype: 'textfield'},
			items: [
				{name: '_sid', inputType: 'hidden', value: data.id},
				{fieldLabel: 'Имя', name: 'name', value: data.name, width: '98%', maxLength: 64, maxLengthText: 'Не больше 64 символов'},
				{fieldLabel: 'Заголовок', name: 'title', value: data.title, width: '98%', maxLength: 255, maxLengthText: 'Не больше 255 символов'},
				{hideLabel: true, name: 'description', xtype: 'ckeditor', CKConfig: {
					height: 300,
					filebrowserImageBrowseUrl: 'ui/file_manager/browser.html'
				}}
			],
			buttonAlign: 'right'
		});
		
	}
	this.addItem = function(){
		var fp = getForm({});
		var w = new Ext.Window({title: 'Добавить страницу помощи', modal: true, layout: 'form', width: 800, height: 600, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid()) Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Добавить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy(); this.reload()}, this, {single: true});
		w.show();
	}
	this.editItem = function(id){
		var fp = getForm({id: id})
		var w = new Ext.Window({title: 'Редактировать страницу помощи', modal: true, layout: 'form', width: 800, height: 600, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid()) Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Сохранить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy(); this.reload({})}, this, {single: true});
		w.show(null, function(){
			fp.getForm().load({
				url: 'di/help/item.json',
				params: {_sid: id},
				waitMsg: 'Загрузка...'
			});
		});
	}
	this.saveItem = function(id, data){
		Save(id, data);
	}
	this.deleteItem = function(id, name){
		Ext.Msg.confirm('Подтверждение.', 'Вы действительно хотите удалить запись "'+(name || id)+'"?', function(btn){if (btn == "yes") Delete(id)});
	}
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var title = row.get('title');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'pencil', text: 'Изменить', handler: this.editItem.createDelegate(this, [id])},
			{iconCls: 'delete', text: 'Удалить', handler: this.deleteItem.createDelegate(this, [id, title])},
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	var onRowselect = function(sm, ri, re){
		this.fireEvent('changeitem', re.get('id'), re);
	}
	ui.help.main.superclass.constructor.call(this,{
		tbar: new Ext.Toolbar({
			items:[
			{id: 'add', iconCls: 'add', text: 'Добавить', handler: this.addItem, scope: this}
			]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Записи с {0} по {1} из {2}',
			emptyMsg: 'Нет записей'
		})
	});
	this.addEvents({
		changeitem: true,
		loaded: true,
		saved: true,
		deleted: true
	});
	this.getSelectionModel().on({
		rowselect: onRowselect,
		scope: this
	});
	this.on({
		rowcontextmenu: onCmenu,
		saved: this.reload,
		deleted: this.reload,
		scope: this
	});
};
Ext.extend(ui.help.main, Ext.grid.GridPanel, {});
