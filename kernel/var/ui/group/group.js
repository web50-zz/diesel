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
			{iconCls: 'page_white_edit', text: this.bttEdit, handler: Edit},
			{iconCls: 'page_white_delete', text: this.bttDelete, handler: Delete},
			{iconCls: 'group_key', text: this.bttFaces, handler: Permissions}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}

	var reload = function(){
		store.load({params: {start: 0, limit: this.limit}});
	}.createDelegate(this);

	var srchType = new Ext.form.ComboBox({
		width: 100,
		store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
			['name', 'Название'],
			['id', 'GID']
		]}), value: 'name',
		valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
	});

	var srchField = new Ext.form.TextField();
	var srchBttOk = new Ext.Toolbar.Button({
		text: 'Найти',
		iconCls:'find',
		handler: function search_submit(){
			Ext.apply(store.baseParams, {field: srchType.getValue(), query: srchField.getValue()});
			reload();
		}
	})
	var srchBttCancel = new Ext.Toolbar.Button({
		text: 'Сбросить',
		iconCls:'cancel',
		handler: function search_submit(){
			srchField.setValue('');
			Ext.apply(store.baseParams, {field: '', query: ''});
			reload();
		}
	})

	ui.group.main.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		autoExpandColumn: 'name',
		tbar: [
			{text: this.bttAdd, iconCls: "group_add", handler: Add},
			{text: this.bttDelete, iconCls: "group_delete", handler: Delete, id: "bttDel", disabled: true},
			srchType,srchField, srchBttOk, srchBttCancel,
			'->', {iconCls: 'help', handler: function(){showHelp('group')}}
		],
		bbar: new Ext.PagingToolbar({pageSize: this.limit, store: store, displayInfo: true})
	});
	this.addEvents(
	);
	this.on({
		rowclick: function(grid, rowIndex, ev){
			grid.getTopToolbar().findById("bttDel").enable();
		},
		rowcontextmenu: onCmenu,
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
