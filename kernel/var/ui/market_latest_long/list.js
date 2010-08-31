ui.market_latest_long.list = function(config, vp){
	var formW = 800;
	var formH = 600;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/market_latest_long/list.json',
			create: 'di/market_latest_long/set.do',
			update: 'di/market_latest_long/set.do',
			destroy: 'di/market_latest_long/unset.do'
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
			{name: 'm_latest_l_issue_datetime', type: 'date', dateFormat: 'Y-m-d H:i:s'},
			'm_latest_l_title'
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
		{header: this.colId, width: 50, sortable: true, dataIndex: 'id', id: 'id'},
		{id: 'm_latest_l_issue_datetime', dataIndex: 'm_latest_l_issue_datetime', header: this.colIssueDate , renderer: formatDate, width: 130},
		{width:400, header: this.colTitle, sortable: true, dataIndex: 'm_latest_l_title', id: 'm_latest_l_title'}
	];

	function formatDate(value){
		return value ? value.dateFormat('d M Y H:i:s') : '';
	}

	var Add = function(){
		var f = new ui.market_latest_long.form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', maximizable: true, width: formW, height: formH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(0, this.pid)}, this);
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.market_latest_long.form();
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

	ui.market_latest_long.list.superclass.constructor.call(this, {
		store: store,
		columns: columns,
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

	this.applyStore = function(data){
		Ext.apply(store.baseParams, data);
		var bb = this.getBottomToolbar();
		if (bb){
			bb.changePage(1);
			bb.doRefresh();
		}
	}

};
Ext.extend(ui.market_latest_long.list, Ext.grid.GridPanel, {
	limit: 20,
	colId: "Id",
	colTitle: "Заголовок",
	colIssueDate: "Датировано",
	bttEdit:"Редактировать",
	bttAdd:"Добавить",

	errText:'Ошибка соединения c сервером',
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) элемент(ы|у)?"
});
