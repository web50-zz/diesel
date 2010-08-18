ui.guide.collection = function(config){
	var frmW = 640;
	var frmH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/guide_collection/list.js',
			create: 'di/guide_collection/set.js',
			update: 'di/guide_collection/set.js',
			destroy: 'di/guide_collection/unset.js'
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
		[{name: 'id', type: 'int'}, 'name', 'name_eng', 'discount']
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
	var prcnt = function(value){
		return value + ' %';
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50},
		{id: 'discount', dataIndex: 'discount', header:  this.colDiscount, width: 100, align: 'right', renderer: prcnt},
		{id: 'name', dataIndex: 'name', header:  this.colName, width: 200},
		{id: 'name_eng', dataIndex: 'name_eng', header:  this.colNameEng}
	];
	var Add = function(){
		var f = new ui.guide.collection_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.guide.collection_form();
		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
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
			}
		}, this);
	}.createDelegate(this);
	var onCmenu = function(grid, rowIndex, e){
		this.getSelectionModel().selectRow(rowIndex);
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'book_edit', text: this.bttEdit, handler: Edit},
			{iconCls: 'book_delete', text: this.bttDelete, handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}.createDelegate(this);
	ui.guide.collection.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'name_eng',
		tbar: [
			{text: this.bttAdd, iconCls: 'book_add', handler: Add},
			'->', {iconCls: 'help', handler: function(){showHelp('guide-collection')}}
		],
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: store,
			displayInfo: true,
			displayMsg: this.pagerDisplayMsg,
			emptyMsg: this.pagerEmptyMsg
		}),
	});
	this.addEvents(
	);
	this.on({
		rowcontextmenu: onCmenu,
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.guide.collection, Ext.grid.GridPanel, {
	limit: 20,

	colName: 'Наименование',
	colNameEng: 'Наименование Eng',
	colDiscount: 'Скидка',

	addTitle: "Добавление коллекции",
	editTitle: "Изменение коллекции",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(у|и) коллекци(ю|и)?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
