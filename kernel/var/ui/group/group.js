ui.group.main = function(config){
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/group/list.js',
			create: 'di/group/set.js',
			update: 'di/group/set.js',
			destroy: 'di/group/unset.js'
		}
	});
	// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
	var reader = new Ext.data.JsonReader({
			totalProperty: 'total',
			successProperty: 'success',
			idProperty: 'id',
			root: 'records',
			messageProperty: 'errors'
		},
		[{name: 'id', type: 'int'}, 'name']
	);
	// Typical JsonWriter
	var writer = new Ext.data.JsonWriter({
		encode: true,
		writeAllFields: false
	});
	// The data store
	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader,
		writer: writer
	});
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: "ID", width: 50, sortable: true, dataIndex: 'id'},
		{header: this.colNameTitle, width: 200, sortable: true, dataIndex: 'name', id: 'name'}
	];
	var Add = function(){
		var f = new ui.group.editForm();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: 400, height: 150, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.group.editForm();
		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', width: 400, height: 150, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(id)});
	}.createDelegate(this);
	var Delete = function(){
		var record = this.getSelectionModel().getSelections();
		if (!record) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				this.store.remove(record);
				this.getTopToolbar().findById("bttDel").disable();
				this.getTopToolbar().findById("bttEdt").disable();
				this.getTopToolbar().findById("bttPerm").disable();
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
					var iids = new Array();
					for (el in ss){
						var iid = parseInt(ss[el].id);
						if (iid > 0) iids.push(iid);
					}
					if (iids.length > 0){
						Ext.Ajax.request({
							url: 'di/interface_group/remove_interfaces_from_group.do',
							params: {gid: gid, iids: iids.join(",")},
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
					var iids = new Array();
					for (el in ss){
						var iid = parseInt(ss[el].id);
						if (iid > 0) iids.push(iid);
					}
					if (iids.length > 0){
						Ext.Ajax.request({
							url: 'di/interface_group/add_interfaces_to_group.do',
							params: {gid: gid, iids: iids.join(",")},
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
	ui.group.main.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		autoExpandColumn: 'name',
		tbar: [
			{text: this.bttAdd, iconCls: "group_add", handler: Add},
			{text: this.bttEdit, iconCls: "group_edit", handler: Edit, id: "bttEdt", disabled: true},
			{text: this.bttDelete, iconCls: "group_delete", handler: Delete, id: "bttDel", disabled: true},
			'-', {text: this.bttFaces, iconCls: "group_key", handler: Permissions, id: "bttPerm", disabled: true},
			'->', {iconCls: 'help', handler: function(){showHelp('group')}}
		],
		bbar: new Ext.PagingToolbar({pageSize: this.limit, store: store, displayInfo: true})
	});
	this.addEvents(
	);
	this.on({
		rowclick: function(grid, rowIndex, ev){
			grid.getTopToolbar().findById("bttEdt").enable();
			grid.getTopToolbar().findById("bttDel").enable();
			grid.getTopToolbar().findById("bttPerm").enable();
		},
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.group.main, Ext.grid.GridPanel, {
	limit: 20,
	colNameTitle: "Наименование",

	addTitle: "Добавление группы",
	editTitle: "Изменение группы",
	permTitle: "Права доступа",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",
	bttFaces: "Права доступа",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) групп(ы|у)?"
});
