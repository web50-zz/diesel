ui.market_clients.main = function(config){
	var frmW = 640;
	var frmH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/market_clients/list.js',
			create: 'di/market_clients/set.js',
			update: 'di/market_clients/set.js',
			destroy: 'di/market_clients/unset.js'
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
		[{name: 'id', type: 'int'}, {name: 'clnt_created_datetime', type: 'date', dateFormat: 'Y-m-d H:i:s'}, 'clnt_name', 'clnt_mname', 'clnt_lname', 'clnt_email']
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
	function formatDate(value){
		return value ? value.dateFormat('d M Y H:i:s') : '';
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50},
		{id: 'clnt_created_datetime', dataIndex: 'clnt_created_datetime', header: 'Дата регистрации', renderer: formatDate, width: 130},
		{id: 'clnt_name', dataIndex: 'clnt_name', header: 'Имя', width: 100},
		{id: 'clnt_mname', dataIndex: 'clnt_mname', header: 'Отчество', width: 100},
		{id: 'clnt_lname', dataIndex: 'clnt_lname', header: 'Фамилия', width: 100},
		{id: 'clnt_email', dataIndex: 'clnt_email', header: 'E-mail', width: 100}
	];
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.market_clients.market_client_form();
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
			{iconCls: 'coins', text: this.bttEdit, handler: Edit},
			{iconCls: 'coins_delete', text: this.bttDelete, handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}.createDelegate(this);
	ui.market_clients.main.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		tbar: [
			'->', {iconCls: 'help', handler: function(){showHelp('order')}}
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
Ext.extend(ui.market_clients.main, Ext.grid.GridPanel, {
	limit: 20,

	labelName: 'Имя',
	labelLogin: 'Login',
	labelEMail: 'e-mail',
	labelLang: 'Язык',

	editTitle: "Просмотр заказа",

	bttEdit: "Просмотреть",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить этот заказ?",

	pagerEmptyMsg: 'Нет заказов',
	pagerDisplayMsg: 'Заказы с {0} по {1}. Всего: {2}'
});
