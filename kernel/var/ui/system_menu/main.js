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
	var Master = function(){
			var app = new App({waitMsg: 'Presets grid loading'});
			app.on({
				apploaded: function(){
					var f = new ui.system_menu_branch_master.main();
					var w = new Ext.Window({iconCls: this.iconCls, title: this.titlePresets, maximizable: true, modal: true, layout: 'fit', width: 600, height: 500, items: f});
					f.on({
						branchLoaded: function(data){
									w.destroy();
									this.operation.Reload(this,1);
									var node = this.getNodeById(1);
									if(data.sync == true)
									{
										node.setText(data.root_title);
									}
								},
						cancelled: function(){w.destroy()},
						scope: this
					});
					w.show(null, function(){});
				},
				apperror: showError,
				scope: this
			});
			app.Load('system_menu_branch_master', 'main');
	}
	var saveBranch = function(id){
			var app = new App({waitMsg: 'Presets grid loading'});
			app.on({
				apploaded: function(){
					var f = new ui.system_menu_branch_master.item_form();
					var w = new Ext.Window({iconCls: this.iconCls, title: this.titlePresets, maximizable: true, modal: true, layout: 'fit', width: 400, height: 100, items: f});
					f.on({
						saved: function(){w.destroy()},
						cancelled: function(){w.destroy()},
						scope: this
					});
					w.show(null, function(){f.setPid(id)});
				},
				apperror: showError,
				scope: this
			});
			app.Load('system_menu_branch_master', 'main');
	} 
	var loadBranch = function(id){
			var app = new App({waitMsg: 'Presets grid loading'});
			app.on({
				apploaded: function(){
					var f = new ui.system_menu_branch_master.selector();
					f.attachToId = id;
					var w = new Ext.Window({iconCls: this.iconCls, title: this.titlePresets, maximizable: true, modal: true, layout: 'fit', width: 600, height: 500, items: f});
					f.on({
						branchLoaded: function(data){
									w.destroy();
									this.operation.Reload(this,id);
									var node = this.getNodeById(id);
									if(data.sync == true)
									{
										node.setText(data.root_title);
									}
								},
						cancelled: function(){w.destroy()},
						scope: this
					});
					w.show(null, function(){});
				},
				apperror: showError,
				scope: this
			});
			app.Load('system_menu_branch_master', 'selector');
	} 

	var onCmenu = function(node, e){
		var id = node.id;
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'add', text: this.bttAdd, handler: Add.createDelegate(this, [node])},
			{iconCls: 'pencil', text: this.bttEdit, handler: Edit.createDelegate(this, [node])},
			{iconCls: 'pencil', text: this.bttSaveBranch, handler: saveBranch.createDelegate(this, [id])},
			{iconCls: 'pencil', text: this.bttLoadBranch, handler: loadBranch.createDelegate(this, [id])},
			{iconCls: 'delete', text: this.bttDelete, handler: Delete.createDelegate(this, [node])}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	ui.system_menu.main.superclass.constructor.call(this, {
		enableDD: true,
		tbar: [
			{iconCls: 'add', text: this.bttAdd, handler: Add.createDelegate(this, [])},
			{iconCls: 'add', text: this.bttConfig, handler: Master.createDelegate(this, [])},
			{iconCls: 'add', text: this.bttSaveConfig, handler: saveBranch.createDelegate(this, [1])},
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

	bttSaveBranch:'Сохранить ветку',
	bttLoadBranch:'Загрузить ветку',
	bttAdd: 'Добавить',
	bttEdit: 'Редактировать',
	bttConfig: 'Конфиги',
	bttSaveConfig: 'Сохранить структуру полностью',
	bttDelete: 'Удалить'
});
