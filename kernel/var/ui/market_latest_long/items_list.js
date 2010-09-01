ui.market_latest_long.items_list = function(config, vp){
	var formW = 700;
	var formH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/market_latest_long_list/list.json',
			create: 'di/market_latest_long_list/set.do',
			update: 'di/market_latest_long_list/set.do',
			destroy: 'di/market_latest_long_list/unset.do'
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
			{name: 'm_latest_ls_product_id', type: 'int'},
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
		{header: this.colPid, width: 50, sortable: true, dataIndex: 'm_latest_ls_product_id', id: 'm_latest_product_id'},
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


	var addForm = function()
	{
		var vp = {ui_configure: {}};
		var grid = new ui.market_latest_long.catalogue_list({region: 'center'});
		var filter = new ui.catalogue.filter_form({region: 'west', split: true, width: 200});
		grid.setBack(this);
		filter.on({
			submit: grid.applyStore,
			reset: grid.applyStore,
			afterrender: function(){filter.Load((vp.ui_configure || {}))},
			scope: grid
			});

		var panel1 = new Ext.Panel({
			title: 'Поиск по каталогу',
			layout:'border',
			items: [filter,grid]
		});

		var w = new Ext.Window({title: this.editTitle, modal: true, layout: 'fit', width: 700, height: 400,items:panel1});
		w.show(null, function(){});
	}.createDelegate(this);

	this.oops = function(id){
		this.Add(id);
	}.createDelegate(this);

	ui.market_latest_long.items_list.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		tbar: [
			{text: this.bttAdd, iconCls: "layout_add", handler: addForm},
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
			url: 'di/market_latest_long_list/set.do',
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
			params:{ m_latest_ls_product_id: id }
			});
	}.createDelegate(this);

};
Ext.extend(ui.market_latest_long.items_list, Ext.grid.GridPanel, {
	limit: 20,
	colPid: "Id товара",
	colPtitle: "Наименование",
	colPtype: "Тип",
	colPgroup: "Группа",
	colPcollection: "Коллекция",

	bttAdd:'Добавить',
	errText:'Ошибка соединения c сервером',
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) элемент(ы|у)?"
});
