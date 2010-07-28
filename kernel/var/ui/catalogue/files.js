ui.catalogue.files = function(config, vp){
	var formW = 640;
	var formH = 320;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/catalogue_file/list.json',
			create: 'di/catalogue_file/set.do',
			update: 'di/catalogue_file/set.do',
			destroy: 'di/catalogue_file/unset.do'
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
		[{name: 'id', type: 'int'}, 'title', 'item_type']
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
	if (vp && vp.ui_configure) store.baseParams = vp.ui_configure;
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: "ID", width: 50, sortable: true, dataIndex: 'id', id: 'id'},
		{header: this.colNameType, width: 100, sortable: true, dataIndex: 'item_type', id: 'item_type'},
		{header: this.colNameTitle, width: 200, sortable: true, dataIndex: 'title', id: 'title'}
	];
	var Add = function(){
		var id = this.getItemId();
		if (id > 0){
			var f = new ui.catalogue.file_form();
			var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', maximizable: true, width: formW, height: formH, items: f});
			f.on({
				saved: function(){store.reload()},
				cancelled: function(){w.destroy()}
			});
			w.show(null, function(){f.Load(0, id)}, this);
		}else{
			showError(this.errNoItemId);
		}
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.catalogue.file_form();
		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', maximizable: true, width: formW, height: formH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(id, this.getItemId())}, this);
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
	var onCmenu = function(grid, rowIndex, e){
		this.getSelectionModel().selectRow(rowIndex);
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'pencil', text: this.bttEdit, handler: Edit},
			{iconCls: 'delete', text: this.bttDelete, handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}.createDelegate(this);
	this.setItemId = function(id){
		this.store.baseParams = {_sciid: id};
		this.store.reload();
	}
	this.getItemId = function(){
		return this.store.baseParams._sciid;
	}
	ui.catalogue.files.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		autoExpandColumn: 'title',
		tbar: [
			{text: this.bttAdd, iconCls: "layout_add", handler: Add},
			'->', {iconCls: 'help', handler: function(){showHelp('catalog')}}
		],
		bbar: new Ext.PagingToolbar({pageSize: this.limit, store: store, displayInfo: true})
	});
	this.addEvents(
	);
	this.on({
		rowcontextmenu: onCmenu,
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.catalogue.files, Ext.grid.GridPanel, {
	limit: 20,
	errNoItemId: "Сначала сохраните данные",

	colNameTitle: "Наименование",
	colNameType: "Тип",

	addTitle: "Добавление файла",
	editTitle: "Изменение файла",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить этот файл?"
});
