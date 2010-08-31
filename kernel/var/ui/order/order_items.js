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
		[{name: 'id', type: 'int'}, 'count', 'price1', 'price2', 'discbool', 'discount', 'str_title', 'str_type']
	);
	// The data store
	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader
	});
	// The item`s summ renderer
	var itemSumm = function(value, metaData, record){
		return Ext.util.Format.number(value * record.get('count'), '0.00');
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: "ID", sortable: true, hidden: true, dataIndex: 'id', id: 'id', width: 50},
		{header: this.colType, dataIndex: 'str_type', id: 'type', width: 120, align: 'center'},
		{header: this.colTitle, dataIndex: 'str_title', id: 'title', width: 200},
		{header: this.colPrice1, dataIndex: 'price1', id: 'price1', width: 100, align: 'right', xtype: 'numbercolumn', format: '0.00'},
		{header: this.colDisc, dataIndex: 'discount', id: 'discount', width: 50, align: 'right', xtype: 'numbercolumn', format: '0.00 %'},
		{header: this.colPrice2, dataIndex: 'price2', id: 'price2', width: 100, align: 'right', xtype: 'numbercolumn', format: '0.00'},
		{header: this.colCount, dataIndex: 'count', id: 'count', width: 50, align: 'right'},
		{header: this.colSumm, dataIndex: 'price2', id: 'summ', width: 100, align: 'right', renderer: itemSumm}
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
	colDisc: "Скидка %",
	colPrice1: "Исх. стоимость",
	colPrice2: "Стоимость с %",
	colSumm: "Общая стоим."
});
