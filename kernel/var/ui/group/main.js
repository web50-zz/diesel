ui.group.main = function(config){
	Ext.apply(this, config);
	var Add = function(){
		var f = new ui.group.item_form();
		var w = new Ext.Window({title: this.addTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: function(){this.store.reload()},
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.group.item_form();
		var w = new Ext.Window({title: this.editTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: function(){this.store.reload()},
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show(null, function(){f.Load(id)});
	}.createDelegate(this);
	var multiSave = function(){
		this.store.save();
	}.createDelegate(this);
	var Delete = function(){
		var record = this.getSelectionModel().getSelections();
		if (!record) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				this.store.remove(record);
			}
		}, this);
	}.createDelegate(this);
	var Permissions = function(){
		var gid = this.getSelectionModel().getSelected().get('id');
		var i1 = new ui.security.interfaces({title: 'Доступные', region: 'east', width: 512, split: true,
			ddGroup: 'available',
			enableDragDrop: true});
		var i2 = new ui.security.interfaces({title: 'Назначенные', region: 'center',
			ddGroup: 'enabled',
			enableDragDrop: true});
		var w = new Ext.Window({title: this.permTitle, modal: true, layout: 'border', width: 1024, height: 600,
			tbar: ['->', {iconCls: 'help', handler: function(){showHelp('add-user-to-group')}}],
			items: [i2, i1]});
		i1.store.baseParams = {gid: gid, _sgid: 'null'};
		i2.store.baseParams = {gid: gid, _ngid: 'null'};
		i2.addEvents('interfaces_added', 'interfaces_removed');
		i2.on('interfaces_added', function(){
			i1.reload();
			i2.reload();
		});
		i2.on('interfaces_removed', function(){
			i1.reload();
			i2.reload();
		});
		w.show(null, function(){
			// This will make sure we only drop to the  view scroller element
			var i1DTEl =  i1.getView().scroller.dom;
			var i1DT = new Ext.dd.DropTarget(i1DTEl , {
				ddGroup: 'enabled',
				notifyDrop: function(ddSource, e, data){
					var ss = ddSource.dragData.selections;
					var epids = new Array();
					for (el in ss){
						var iid = parseInt(ss[el].id);
						if (iid > 0) epids.push(iid);
					}
					if (epids.length > 0){
						Ext.Ajax.request({
							url: 'di/entry_point_group/remove_entry_points_from_group.do',
							params: {gid: gid, epids: epids.join(",")},
							disableCaching: true,
							callback: function(options, success, response){
								var d = Ext.util.JSON.decode(response.responseText);
								if (!(success && d.success))
									showError(this.errDoSync);
								else{
									i2.fireEvent('interfaces_removed');
								}
							},
							scope: this
						});
					}
					return true
				}
			});
			var i2DTEl =  i2.getView().scroller.dom;
			var i2DT = new Ext.dd.DropTarget(i2DTEl , {
				ddGroup: 'available',
				notifyDrop: function(ddSource, e, data){
					var ss = ddSource.dragData.selections;
					var epids = new Array();
					for (el in ss){
						var iid = parseInt(ss[el].id);
						if (iid > 0) epids.push(iid);
					}
					if (epids.length > 0){
						Ext.Ajax.request({
							url: 'di/entry_point_group/add_entry_points_to_group.do',
							params: {gid: gid, epids: epids.join(",")},
							disableCaching: true,
							callback: function(options, success, response){
								var d = Ext.util.JSON.decode(response.responseText);
								if (!(success && d.success))
									showError(this.errDoSync);
								else{
									i2.fireEvent('interfaces_added');
								}
							},
							scope: this
						});
					}
					return true
				}
			});
		});
	}.createDelegate(this);
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'group_edit', text: this.bttEdit, handler: Edit},
			{iconCls: 'group_key', text: this.bttFaces, handler: Permissions},
			'-',
			{iconCls: 'group_delete', text: this.bttDelete, handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	var srchType = new Ext.form.ComboBox({
		width: 100,
		store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
			['name', 'Наименование'],
			['id', 'GID']
		]}), value: 'name',
		valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
	});
	var srchField = new Ext.form.TextField({text:'Имя'});
	var srchBttOk = new Ext.Toolbar.Button({
		text: 'Найти',
		iconCls:'find',
		handler: function search_submit(){
			Ext.apply(this.store.baseParams, {field: srchType.getValue(), query: srchField.getValue()});
			this.reload();
		},
		scope: this
	})
	var srchBttCancel = new Ext.Toolbar.Button({
		text: 'Сбросить',
		iconCls:'cancel',
		handler: function search_submit(){
			srchField.setValue('');
			Ext.apply(this.store.baseParams, {field: '', query: ''});
			this.reload();
		},
		scope: this
	})
	ui.group.main.superclass.constructor.call(this, {
		tbar: [
			{iconCls: 'group_add', text: this.bttAdd, handler: Add},
			'-',
			srchType,srchField, srchBttOk, srchBttCancel,
			'->', {iconCls: 'help', handler: function(){showHelp('group')}}
		]
	});
	this.addEvents({
	});
	this.on({
		rowcontextmenu: onCmenu,
		render: function(){this.store.load({params:{start:0, limit: this.limit}})},
		scope: this
	});
};
Ext.extend(ui.group.main, ui.group.grid, {
	formWidth: 350,
	formHeight: 100,

	permTitle: "Права доступа",

	bttAdd: 'Добавить',
	bttEdit: 'Редактировать',
	bttFaces: "Права доступа",
	bttDelete: 'Удалить'
});
