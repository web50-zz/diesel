ui.market_soon.latest_list = function(config, vp){
	var formW = 700;
	var formH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/market_soon/list.json',
			create: 'di/market_soon/set.do',
			update: 'di/market_soon/set.do',
			destroy: 'di/market_soon/unset.do'
		}
	});
	// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
	var reader = new Ext.data.JsonReader({
			totalProperty: 'total',
			successProperty: 'success',
			idProperty: 'id',
			root: 'records',
			messageProperty: 'errors'
		}, [
			{name: 'id', type: 'int'},
			{name: 'm_soon_product_id', type: 'int'},
			'p_title',
			'p_type',
			'p_collection',
			'p_group'
		]
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
		Ext.apply(store.baseParams, data);
		var bb = this.getBottomToolbar();
		if (bb){
			bb.changePage(1);
			bb.doRefresh();
		}
	}
	var ynFormat = function(value){
		return (value == 1) ? 'Да' : 'Нет';
	}
	var priceFormat = function(value){
		return Ext.util.Format.number(value, '0.00');
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{header: this.colPid, width: 50, sortable: true, dataIndex: 'm_soon_product_id', id: 'm_soon_product_id'},
		{header: this.colPtype, width: 50, sortable: true, dataIndex: 'p_type', id: 'p_type'},
		{header: this.colPgroup, width: 150, sortable: true, dataIndex: 'p_group', id: 'p_group'},
		{header: this.colPtitle, width: 200, sortable: true, dataIndex: 'p_title', id: 'p_title'},
		{header: this.colPcollection, width: 150, sortable: true, dataIndex: 'p_collection', id: 'p_collection'}
	];
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
			{iconCls: 'delete', text: this.bttDelete, handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}.createDelegate(this);
	ui.market_soon.latest_list.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		tbar: [
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

	this.Add = function(id){
		Ext.Ajax.request({
			url: 'di/market_soon/set.do',
			success:function(response,opts){
					var d = Ext.util.JSON.decode(response.responseText);
					if (d.success)
						store.reload();
					else
						showError(d.errors);
				},
			failure: function(response,opts ){
						showError(this.errText);
					},
			params:{ m_soon_product_id: id }
			});
	}.createDelegate(this);

};
Ext.extend(ui.market_soon.latest_list, Ext.grid.GridPanel, {
	limit: 20,
	colPid: "Id товара",
	colPtitle: "Наименование",
	colPtype: "Тип",
	colPgroup: "Группа",
	colPcollection: "Коллекция",

	errText:'Ошибка соединения c сервером',
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) элемент(ы|у)?"
});
