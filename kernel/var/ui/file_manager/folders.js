var Folders = function(config){
	var self = this;
	this.rootVisible = false;
	this.autoScroll = true;
	this.root = new Ext.tree.AsyncTreeNode({id: '0', draggable: false, expanded: true});
	this.loadMask = new Ext.LoadMask(Ext.getBody(), {msg: "Загрузка данных..."});
	Ext.apply(this, config, {});
	this.pid = 0;
	this.loader = new Ext.tree.TreeLoader({url: self.baseURL+'di/fm_folders/slice.json'});
	this.reload = function(id){
		if (id){
			var node = this.getNodeById(id);
			if (node){
				if (!node.expanded)
					node.expand()
				else
					node.reload();
			}
		}else if (this.root.rendered == true)
			this.root.reload();
	}
	var Save = function(id, data){
		Ext.Ajax.request({
			url: self.baseURL+'di/fm_folders/set.do',
			params: Ext.apply(data, {_sid: id}),
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (success)
					self.fireEvent('saved', data, d.data);
				else
					showError('Во время сохранения возникли ошибки.');
			}
		});
	}
	var Submit = function(f){
		f.submit({
			url: self.baseURL+'di/fm_folders/set.do',
			waitMsg: 'Сохранение...',
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success)
					self.fireEvent('saved', f.getValues(), d.data);
				else
					showError(d.errors);
			},
			failure: function(form, action){
				showError('Во время сохранения возникли ошибки.');
			}
		});
	}
	var Move = function(tree, node, oldParent, newParent, index){
		Ext.Ajax.request({
			url: self.baseURL+'di/fm_folders/move.do',
			params:{
				id: node.id,
				pid: newParent.id,
				pos: index
			},
			failure: function(result, request){
				showError(result.responseText)
			}
		});
	}
	var Delete = function(id){
		Ext.Ajax.request({
			url: self.baseURL+'di/fm_folders/unset.do',
			params: {_sid: id},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (d.success)
					self.fireEvent('deleted', id);
				else
					showError('Во время удаления возникли ошибки.');
			}
		})
	}
	var getForm = function(data){
		return new Ext.form.FormPanel({
			layout: 'form',
			frame: true, 
			labelWidth: 200,
			defaults: {xtype: 'textfield', width: '97%'},
			items: [
				{xtype: 'hidden', name: '_sid', value: data.id},
				{xtype: 'hidden', name: 'pid', value: data.pid},
				{hideLabel: true, name: 'title', allowBlank:false}
			],
			buttonAlign: 'right'
		});
		
	}
	this.addNode = function(pid){
		var fp = getForm({pid: pid});
		var w = new Ext.Window({title: 'Добавить папку', modal: true, layout: 'fit', width: 300, height: 110, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid()) Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Добавить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy()}, this, {single: true});
		w.show();
	}
	this.editNode = function(id){
		var fp = getForm({id: id})
		var w = new Ext.Window({title: 'Редактировать папку', modal: true, layout: 'fit', width: 300, height: 110, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid()) Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Сохранить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy()}, this, {single: true});
		w.show(null, function(){
			fp.getForm().load({
				url: self.baseURL+'di/fm_folders/item.json',
				params: {_sid: id},
				waitMsg: 'Загрузка...'
			});
		});
	}
	this.saveNode = function(id, data){
		Save(id, data);
	}
	this.deleteNode = function(id, name){
		Ext.Msg.confirm('Подтверждение.', 'Вы действительно хотите удалить папку "'+(name || id)+'"?', function(btn){if (btn == "yes") Delete(id)});
	}
	var onCmenu = function(node, e){
		var id = node.id;
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'add', text: 'Добавить', handler: this.addNode.createDelegate(this, [id])},
			{iconCls: 'pencil', text: 'Редактировать', handler: this.editNode.createDelegate(this, [id])},
			{iconCls: 'delete', text: 'Удалить', handler: this.deleteNode.createDelegate(this, [id, node.text]), disabled: (id == 1)}
		]});
		e.stopEvent();
		cmenu.showAt(e.getXY());
	}
	Folders.superclass.constructor.call(this,{
		tbar: [
			'->', {iconCls: 'help', handler: function(){showHelp('test')}}
		]
	});
	var afterSave = function(data, response){
		if (data._sid > 0){
			var node = this.getNodeById(data._sid);
			node.setText(data.title);
		}else{
			var node = new Ext.tree.AsyncTreeNode({id: response.id, text: data.title, expanded: true});
			this.getNodeById(data.pid).appendChild(node);
		}
	}
	var afterDelete = function(id){
		var node = this.getNodeById(id);
		node.remove();
		this.fireEvent('removenode', id);
	}
	this.addEvents({
		loaded: true,
		changenode: true,
		removenode: true,
		saved: true,
		deleted: true
	});
	this.on({
		movenode: Move,
		contextmenu: onCmenu,
		saved: afterSave,
		deleted: afterDelete,
		scope: this
	});
};
Ext.extend(Folders, Ext.tree.TreePanel, {});
