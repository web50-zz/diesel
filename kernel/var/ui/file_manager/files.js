var Files = function(config){
	var self = this;
	var fm = Ext.form;
	this.pid = 0;
	this.changes = false;
	this.cm = new Ext.grid.ColumnModel([
		{id: 'id', header: "id", width: 70, sortable: true, dataIndex: 'id', resizable: false},
		{id: 'title', header: "Название", width: 150, sortable: true, dataIndex: 'title'},
		{id: 'name', header: "Файл", width: 150, sortable: true, dataIndex: 'name'},
		{id: 'type', header: "Тип", width: 100, sortable: true, dataIndex: 'type'},
		{id: 'size', header: "Размер", width: 70, sortable: true, renderer: formatSize, dataIndex: 'size'}
	]);
	this.autoExpandColumn = 'title';
	this.stripeRows = true;
	this.autoScroll = true;
	this.viewConfig = new Ext.grid.GridView({emptyText: 'На данной странице нет файлов'});
	this.loadMask = {msg: 'Загрузка данных...'};
	this.selModel = new Ext.grid.RowSelectionModel({singleSelect: true});
	this.limit = 30;
	Ext.apply(this, config, {});
	this.store = new Ext.data.JsonStore({
		url: self.baseURL+'di/fm_files/list.json',
		baseParams: {_spid: this.pid},
		id: 'id',
		root: 'records',
		totalProperty: 'total',
		fields: [{name: 'id', type: 'int'}, 'title', 'name', 'type', {name: 'size', type: 'int'},'real_name'],
		remoteSort: true,
		listeners: {
			beforeload: function(store, options){
				if (this.changes){
					Ext.Msg.confirm('Подтверждение.', 'Имеются несохранённые данные, вы действительно хотите перегрузить список?', function(btn){
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
	this.reload = function(full){
		if (full == true){
			var bb = this.getBottomToolbar();
			bb.doLoad(0);
		}else{
			var bb = this.getBottomToolbar();
			bb.doLoad(bb.cursor);
		}
	};
	var Save = function(data){
		Ext.Ajax.request({
			url: self.baseURL+'di/fm_files/set.do',
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
			url: self.baseURL+'di/fm_files/set.do',
			waitMsg: 'Сохранение...',
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success)
					self.fireEvent('saved', d);
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
			url: self.baseURL+'di/fm_files/unset.do',
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
			layout: 'form',
			frame: true,
			fileUpload: true,
			defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
			buttonAlign: 'right',
			items: [
				{xtype: 'hidden', name: '_sid', value: data.id},
				{xtype: 'hidden', name: 'pid', value: data.pid},
				{fieldLabel: 'Название', name: 'title'},
				{fieldLabel: 'Файл', name: 'file', xtype: 'fileuploadfield', buttonCfg: {text: '', iconCls: 'folder'}}
			]
		});
		
	}
	this.addFile = function(){
		var fp = getForm({pid: this.pid});
		var w = new Ext.Window({title: 'Добавить файл', modal: true, layout: 'fit', width: 400, height: 160, items: fp});
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
	this.editFile = function(id){
		var fp = getForm({id: id, pid: this.pid});
		var w = new Ext.Window({title: 'Редактировать файл', modal: true, layout: 'fit', width: 400, height: 160, items: fp});
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
				url: self.baseURL+'di/fm_files/item.json',
				params: {_sid: id},
				waitMsg: 'Загрузка...'
			});
			fp.getForm().setValues([{id: '_sid', value: id}]);
		}, this);
	}
	this.saveFile = function(id, data){
		Save(Ext.apply({_sid: id}, data));
	}
	this.deleteFile = function(id, name){
		Ext.Msg.confirm('Подтверждение.', 'Вы действительно хотите удалить файл "'+(name || id)+'"?', function(btn){
			this.on('deleted', this.reload(), this, {single: true});
			if (btn == "yes") Delete(id)
		}, this);
	}
	this.setAsChanged = function(bool){
		var tb = this.getTopToolbar();
		tb.items.item('msave').setDisabled(!bool);
		this.changes = bool;
	}
	this.showImage = function(id, title){
		new Ext.Window({
			title: '['+id+'] '+title,
			maximizable: true,
			resizable: true,
			width: 640,
			height: 480,
			autoScroll: true,
			html: '<center><img src="/file/?id='+id+'" border="0"/></center>'
		}).show();
	}
	var file2fck = function(id, title){
		window.opener.SetUrl('/file/?id='+id);
		window.close();
	}
	var file2ck = function(id, title,real_name){
	//	window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, '/files/?id='+id);
		window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, '/filestorage/'+real_name);//9* 16122013  а пусть будет реалнейм 
		window.close();
	}
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'image_link', text: 'Вставить в текст',
			handler: file2ck.createDelegate(this, [id, row.get('title'),row.get('real_name')]), disabled: !self.fck},
			{iconCls: 'image', text: 'Посмотреть',
			handler: this.showImage.createDelegate(this, [id, row.get('title')]), disabled: !/^image/.test(row.get('type'))},
			{iconCls: 'link', text: 'Скачать', handler: function(){document.location = '/file/?id='+id+'&download'}},
			'-',
			{iconCls: 'image_edit', text: 'Редактировать',
			handler: this.editFile.createDelegate(this, [id])},
			'-',
			{iconCls: 'image_delete', text: 'Удалить',
			handler: this.deleteFile.createDelegate(this, [id, row.get('title')])}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	var multiSave = function(){
		var mr = this.store.getModifiedRecords();
		for(n = 0; n < mr.length; n++)
			this.saveFile(mr[n].get('id'), mr[n].getChanges());
		this.setAsChanged(false);
		this.reload();
	}
	this.setFolder = function(pid){
		if (this.pid != pid){
			this.pid = pid;
			this.store.baseParams = {_spid: pid};
			this.fireEvent('setfolder', pid);
			
			var tb = this.getTopToolbar();
			tb.items.get('add').setDisabled(!(pid > 0));
		}
	}
	Files.superclass.constructor.call(this, {
		tbar: new Ext.Toolbar({items:[
			{id: 'add', iconCls: 'add', text: 'Добавить', disabled: true, handler: this.addFile, scope: this},
			'-',
			{id: 'msave', iconCls: 'disk', text: 'Сохранить', disabled: true, handler: multiSave, scope: this}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Файлы с {0} по {1} из {2}',
			emptyMsg: 'Нет файлов'
		})
	});
	this.addEvents({
		loaded: true,
		saved: true,
		deleted: true,
		setfolder: true
	});
	this.on({
		rowcontextmenu: onCmenu,
		afteredit: this.setAsChanged.createDelegate(this, [true]),
		deleted: this.reload,
		setFolder: this.reload.createDelegate(this, [true]),
		scope: this
	});
};
Ext.extend(Files, Ext.grid.EditorGridPanel, {});
