ui.catalogue.main = function(config){
	this.pid = 0;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/catalogue_item/list.js',
			create: 'di/catalogue_item/set.js',
			update: 'di/catalogue_item/set.js',
			destroy: 'di/catalogue_item/unset.js'
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
		[{name: 'id', type: 'int'}, {name: 'exist', type: 'int'}, 'title', {name: 'cost', type: 'float'}]
	);
	// Typical JsonWriter
	var writer = new Ext.data.JsonWriter({
		encode: true,
		writeAllFields: false
	});
	// The data store
	var store = new Ext.data.Store({
		baseParams: {_spid: this.pid},
		proxy: proxy,
		reader: reader,
		writer: writer
	});
	var existFormat = function(value){
		return (value == 1) ? 'Да' : 'Нет';
	}
	var priceFormat = function(value){
		return Ext.util.Format.number(value, '0.00');
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: "ID", width: 50, sortable: true, dataIndex: 'id'},
		{header: this.colNameExist, width: 50, sortable: true, dataIndex: 'exist', id: 'exist', align: 'center', renderer: existFormat},
		{header: this.colNameCost, width: 100, sortable: true, dataIndex: 'cost', id: 'cost', align: 'right', renderer: priceFormat},
		{header: this.colNameTitle, width: 200, sortable: true, dataIndex: 'title', id: 'title'}
	];
	var Add = function(){
		var f = new ui.catalogue.item_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: 400, height: 150, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(0, this.pid)}, this);
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.catalogue.item_form();
		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', width: 400, height: 150, items: f});
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
				this.getTopToolbar().findById("bttDel-ci").disable();
				this.getTopToolbar().findById("bttEdt-ci").disable();
			}
		}, this);
	}.createDelegate(this);
	ui.catalogue.main.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		autoExpandColumn: 'title',
		tbar: [
			{text: this.bttAdd, iconCls: "layout_add", handler: Add},
			{text: this.bttEdit, iconCls: "layout_edit", handler: Edit, id: "bttEdt-ci", disabled: true},
			{text: this.bttDelete, iconCls: "layout_delete", handler: Delete, id: "bttDel-ci", disabled: true},
			'->', {iconCls: 'help', handler: function(){showHelp('catalog')}}
		],
		bbar: new Ext.PagingToolbar({pageSize: this.limit, store: store, displayInfo: true})
	});
	this.addEvents(
	);
	this.on({
		rowclick: function(grid, rowIndex, ev){
			grid.getTopToolbar().findById("bttEdt-ci").enable();
			grid.getTopToolbar().findById("bttDel-ci").enable();
		},
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.catalogue.main, Ext.grid.GridPanel, {
	limit: 20,
	colNameExist: "В наличии",
	colNameCost: "Стоимость",
	colNameTitle: "Наименование",

	addTitle: "Добавление элемента",
	editTitle: "Изменение элемента",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) элемент(ы|у)?"
});
