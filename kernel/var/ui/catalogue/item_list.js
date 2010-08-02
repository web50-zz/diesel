ui.catalogue.item_list = function(config, vp){
	var formW = 700;
	var formH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/catalogue_item/list.json',
			create: 'di/catalogue_item/set.do',
			update: 'di/catalogue_item/set.do',
			destroy: 'di/catalogue_item/unset.do'
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
		[{name: 'prepayment', type: 'float'}, {name: 'payment_forward', type: 'float'}, {name: 'on_offer', type: 'int'}, {name: 'id', type: 'int'}, 'title', 'type']
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
	this.applyStore = function(data){
		store.baseParams = data;
		store.reload(true);
	}
	var existFormat = function(value){
		return (value == 1) ? 'Да' : 'Нет';
	}
	var priceFormat = function(value){
		return Ext.util.Format.number(value, '0.00');
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: "ID", width: 50, sortable: true, dataIndex: 'id', id: 'id'},
		{header: this.colNameType, width: 100, sortable: true, dataIndex: 'type', id: 'type'},
		{header: this.colNameExist, width: 50, sortable: true, dataIndex: 'on_offer', id:'on_offer', align: 'center', renderer: existFormat},
		{header: this.colNamePrepay, width: 100, sortable: true, dataIndex: 'prepayment', id: 'prepayment', align: 'right', renderer: priceFormat},
		{header: this.colNamePayfwd, width: 100, sortable: true, dataIndex: 'payment_forward', id: 'payment_forward', align: 'right', renderer: priceFormat},
		{header: this.colNameTitle, width: 200, sortable: true, dataIndex: 'title', id: 'title'}
	];
	var Add = function(){
		var f = new ui.catalogue.item_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', maximizable: true, width: formW, height: formH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(0, this.pid)}, this);
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.catalogue.item_form();
		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', maximizable: true, width: formW, height: formH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(id, this.pid)}, this);
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
	ui.catalogue.item_list.superclass.constructor.call(this, {
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
Ext.extend(ui.catalogue.item_list, Ext.grid.GridPanel, {
	limit: 20,
	colNameExist: "В продаже",
	colNamePrepay: "Предоплата",
	colNamePayfwd: "Нал. плат.",
	colNameType: "Тип",
	colNameTitle: "Наименование",

	addTitle: "Добавление элемента",
	editTitle: "Изменение элемента",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) элемент(ы|у)?"
});
