ui.system_menu.main = function(config){
	Ext.apply(this, config);
	var Add = function(node){
		if (!node) node = this.getRootNode();
		var f = new ui.system_menu.item_form();
		var w = new Ext.Window({title: this.addTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: this.operation.afterSave,
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show(null, function(){f.Load({id: 0, pid: node.attributes.id})});
	}
	var Edit = function(node){
		var f = new ui.system_menu.item_form();
		var w = new Ext.Window({title: this.editTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: this.operation.afterSave,
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show(null, function(){f.Load({id: node.attributes.id, pid: 0})});
	}
	var Delete = function(node){
		this.operation.Delete(node);
	}
	var onCmenu = function(node, e){
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'add', text: this.bttAdd, handler: Add.createDelegate(this, [node])},
			{iconCls: 'pencil', text: this.bttEdit, handler: Edit.createDelegate(this, [node])},
			{iconCls: 'delete', text: this.bttDelete, handler: Delete.createDelegate(this, [node])}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	ui.system_menu.main.superclass.constructor.call(this, {
		enableDD: true,
		tbar: [
			{iconCls: 'add', text: this.bttAdd, handler: Add.createDelegate(this, [])},
			'->', {iconCls: 'help', handler: function(){showHelp('system-menu')}}
		]
	});
	this.on({
		contextmenu: onCmenu,
		scope: this
	});
};
Ext.extend(ui.system_menu.main, ui.system_menu.tree, {
	formWidth: 300,
	formHeight: 230,

	bttAdd: 'Добавить',
	bttEdit: 'Редактировать',
	bttDelete: 'Удалить'
});
