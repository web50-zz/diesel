ui.order.main = function(config){
	var frmW = 640;
	var frmH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/order/list.js',
			create: 'di/order/set.js',
			update: 'di/order/set.js',
			destroy: 'di/order/unset.js'
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
		[{name: 'id', type: 'int'},
			{name: 'created_datetime', type: 'date', dateFormat: 'Y-m-d H:i:s'},
			'status',
			'pt_string',
			'discount',
			'total_items',
			'total_items_cost',
			'delivery_cost',
			'total_cost',
			'str_user_name']
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
		writer: writer,
		remoteSort: true,
		sortInfo: {field: 'id', direction: 'DESC'}
	});
	function formatDate(value){
		return value ? value.dateFormat('d M Y H:i:s') : '';
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50, sortable: true},
		{id: 'created_datetime', dataIndex: 'created_datetime', header: 'Дата создания', renderer: formatDate, width: 130, sortable: true},
		{id: 'str_user_name', dataIndex: 'str_user_name', header: 'Пользователь', width: 100, sortable: true},
		{id: 'status', dataIndex: 'status', header: 'Статус', width: 100, sortable: true},
		{id: 'pt_string', dataIndex: 'pt_string', header: 'Способ оплаты', width: 100, sortable: true},
		{id: 'discount', dataIndex: 'discount', header: 'Скидка', width: 100, sortable: true},
		{id: 'total_items', dataIndex: 'total_items', header: 'Кол-во элементов', width: 100, sortable: true},
		{id: 'total_items_cost', dataIndex: 'total_items_cost', header: 'Общая стоимость товаров', width: 100, sortable: true},
		{id: 'delivery_cost', dataIndex: 'delivery_cost', header: 'Соимость доставки', width: 100, sortable: true},
		{id: 'total_cost', dataIndex: 'total_cost', header: 'Общая стоимость заказов', width: 100, sortable: true}
	];
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.order.order_form();
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
	var srchField = new Ext.form.TextField();
	var srchType = new Ext.form.ComboBox({
		width: 100,
		store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
			['str_user_name', 'Пользователь']
		]}), value: 'str_user_name',
		valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
	});
	var srchBttOk = new Ext.Toolbar.Button({
		text: 'Найти',
		iconCls:'find',
		handler: function search_submit(){
			Ext.apply(store.baseParams, {field: srchType.getValue(), query: srchField.getValue()});
			store.load({params: {start: 0, limit: this.limit}});
		},
		scope: this
	})
	var srchBttCancel = new Ext.Toolbar.Button({
		text: 'Сбросить',
		iconCls:'cancel',
		handler: function search_submit(){
			srchField.setValue('');
			Ext.apply(store.baseParams, {field: '', query: ''});
			store.load({params: {start: 0, limit: this.limit}});
		},
		scope: this
	})
	ui.order.main.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		tbar: [
			new Ext.Toolbar.TextItem ("Найти:"),
			srchType, srchField, srchBttOk, srchBttCancel,
			'->', {iconCls: 'help', handler: function(){showHelp('order')}}
		],
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: store,
			displayInfo: true,
			displayMsg: this.pagerDisplayMsg,
			emptyMsg: this.pagerEmptyMsg
		})
	});
	this.addEvents(
	);
	this.on({
		rowcontextmenu: onCmenu,
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.order.main, Ext.grid.GridPanel, {
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
