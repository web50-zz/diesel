ui.subscribe.accounts_list = function(config){
	var frmW = 640;
	var frmH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/subscribe_accounts/list.js',
			create: 'di/subscribe_accounts/set.js',
			update: 'di/subscribe_accounts/set.js',
			destroy: 'di/subscribe_accounts/unset.js'
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
		[{name: 'id', type: 'int'},'email','name']
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
		{id: 'email', dataIndex:'email', header:  this.labelEmail, width: 200},
		{id: 'name', dataIndex:'name', header:  this.labelName, width:150}
	];
	var Add = function(){
		var f = new ui.subscribe.account_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.subscribe.account_form();
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
				this.getTopToolbar().findById("bttDel-gg").disable();
				this.getTopToolbar().findById("bttEdt-gg").disable();
			}
		}, this);
	}.createDelegate(this);
	ui.subscribe.accounts_list.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'name',
		tbar: [
			{text: this.bttAdd, iconCls: 'book_add', handler: Add},
			{text: this.bttEdit, iconCls: "book_edit", handler: Edit, id: "bttEdt-gg", disabled: true},
			{text: this.bttDelete, iconCls: "book_delete", handler: Delete, id: "bttDel-gg", disabled: true},
			'->', {iconCls: 'help', handler: function(){showHelp('guestbook')}}
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
		rowclick: function(grid, rowIndex, ev){
			grid.getTopToolbar().findById("bttEdt-gg").enable();
			grid.getTopToolbar().findById("bttDel-gg").enable();
		},
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.subscribe.accounts_list, Ext.grid.GridPanel, {
	limit: 20,

	labelName: 'Имя',
	labelEmail: 'E-mail',
	labelRecord: 'Запись',
	labelAnswer: 'Ответ',
	labelCreated: 'Создано',
	labelLocation: 'Местоположение',

	addTitle: "Добавление запись",
	editTitle: "Изменение записи",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(у|и) групп(у|ы)?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
