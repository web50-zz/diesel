ui.structure.page_view_points = function(config){
	var frmW = 800;
	var frmH = 480;
	var fm = Ext.form;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/ui_view_point/list.js',
			create: 'di/ui_view_point/set.js',
			update: 'di/ui_view_point/mset.js',
			destroy: 'di/ui_view_point/unset.js'
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
		[{name: 'id', type: 'int'}, {name: 'view_point', type: 'int'}, 'title', 'ui_name', 'human_name', 'ui_call', 'ui_configure']
	);
	// Typical JsonWriter
	var writer = new Ext.data.JsonWriter({
		encode: true,
		listful: true,
		writeAllFields: false
	});
	// The data store
	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader,
		writer: writer
	});
	this.applyStore = function(data){
		Ext.apply(store.baseParams, data);
		store.load();
	}
	columns = [
		{id: 'id', dataIndex: 'id', hidden: true},
		{header: this.clmnVPoint, id: 'view_point', dataIndex: 'view_point', sortable: true, width: 50},
		{header: this.clmnUIName, id: 'human_name', dataIndex: 'human_name', sortable: true, width: 150},
		{header: this.clmnUICall, id: 'ui_call', dataIndex: 'ui_call', sortable: true, width: 100},
		{header: this.clmnTitle, id: 'title', dataIndex: 'title', sortable: true, editor: new fm.TextField({maxLength: 255, maxLengthText: 'Не больше 255 символов'})}
	];
	var Add = function(){
		var f = new ui.structure.page_view_point_form();
		var w = new Ext.Window({title: this.addTitle, maximizable: true, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(0, store.baseParams._spid)});
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.structure.page_view_point_form();
		var w = new Ext.Window({title: this.editTitle, maximizable: true, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(id, store.baseParams._spid)});
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
	var Launch = function(){
		var record = this.getSelectionModel().getSelected();
	}
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'layout_edit', text: 'Редактировать', handler: Edit},
			{iconCls: 'layout_delete', text: 'Удалить', handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	var reload = function(){
		store.load({params: {start: 0, limit: this.limit}});
	}.createDelegate(this);
	ui.structure.page_view_points.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'title',
		stripeRows: true,
		autoScroll: true,
		selModel: new Ext.grid.RowSelectionModel({singleSelect: true}),
		tbar: [
			{iconCls: 'layout_add', text: 'Добавить', handler: Add},
			'->', {iconCls: 'help', handler: function(){showHelp('view-points')}}
		]
	});
	this.addEvents({
	});
	this.on({
		rowcontextmenu: onCmenu,
		scope: this
	});
};
Ext.extend(ui.structure.page_view_points, Ext.grid.EditorGridPanel, {
	limit: 20,

	addTitle: "Добавление ViewPoint",
	editTitle: "Редактирование ViewPoint",

	clmnVPoint: "VP Num.",
	clmnTitle: "Наименование",
	clmnUIName: "Модуль",
	clmnUICall: "Вызов",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить этот ViewPoint?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
