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
		[{name: 'id', type: 'int'}, 'created_datetime', 'status', 'method_of_payment', 'discount', 'delivery_cost', 'str_user_name']
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
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50},
		{id: 'created_datetime', dataIndex: 'created_datetime', header: 'Дата создания', width: 100},
		{id: 'status', dataIndex: 'status', header: 'Статус', width: 100},
		{id: 'method_of_payment', dataIndex: 'method_of_payment', header: 'Способ оплаты', width: 100},
		{id: 'discount', dataIndex: 'discount', header: 'Скидка', width: 100},
		{id: 'delivery_cost', dataIndex: 'delivery_cost', header: 'Соимость доставки', width: 100},
		{id: 'str_user_name', dataIndex: 'str_user_name', header: 'Пользователь', width: 100}
	];
	var Add = function(){
		var f = new ui.order.order_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
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
	ui.order.main.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		tbar: [
			{text: this.bttAdd, iconCls: 'book_add', handler: Add},
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

	addTitle: "Добавление группы",
	editTitle: "Изменение группы",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(у|и) групп(у|ы)?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
