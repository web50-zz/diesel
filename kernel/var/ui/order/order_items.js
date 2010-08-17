ui.order.order_items = function(config){
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/order_item/list.json'
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
		[{name: 'id', type: 'int'}, 'count', 'cost', 'str_title', 'str_type']
	);
	// The data store
	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader
	});
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: "ID", width: 50, sortable: true, dataIndex: 'id', id: 'id'},
		{header: this.colType, width: 200, sortable: true, dataIndex: 'str_type', id: 'type'},
		{header: this.colTitle, width: 200, sortable: true, dataIndex: 'str_title', id: 'title'},
		{header: this.colCount, width: 200, sortable: true, dataIndex: 'count', id: 'count'},
		{header: this.colCost, width: 200, sortable: true, dataIndex: 'cost', id: 'cost'}
	];
	this.applyStore = function(data){
		Ext.apply(store.baseParams, data);
		/*
		var bb = this.getBottomToolbar();
		if (bb){
			bb.changePage(1);
			bb.doRefresh();
		}
		*/
	}
	ui.order.order_items.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		autoExpandColumn: 'title',
		tbar: [
			'->', {iconCls: 'help', handler: function(){showHelp('order-item')}}
		],
		bbar: new Ext.PagingToolbar({pageSize: this.limit, store: store, displayInfo: true})
	});
	this.on({
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.order.order_items, Ext.grid.GridPanel, {
	limit: 20,

	colType: "Тип",
	colTitle: "Наименование",
	colCount: "Кол-во",
	colCost: "Стоимость"
});