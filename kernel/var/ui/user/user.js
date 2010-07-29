ui.user.main = function(config){
	var frmW = 350;
	var frmH = 270;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/user/list.js',
			create: 'di/user/set.js',
			update: 'di/user/set.js',
			destroy: 'di/user/unset.js'
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
		[{name: 'id', type: 'int'}, 'login', 'name', 'email', 'lang']
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
	var strLang = new Ext.data.SimpleStore({
		fields: ['value', 'title'],
		data: [
			['en_EN', 'English'],
			['ru_RU', 'Русский']
		]
	});
	var setLang = function(val){
		return strLang.query('value', val).items[0].data.title;
	}.createDelegate(this);
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50},
		{id: 'login', dataIndex: 'login', header: this.labelLogin, width: 150},
		{id: 'email', dataIndex: 'email', header: this.labelEMail, width: 100},
		{id: 'lang', dataIndex: 'lang', header: this.labelLang, renderer: setLang, width: 100},
		{id: 'name', dataIndex: 'name', header:  this.labelName}
	];
	var Add = function(){
		var f = new ui.user.editForm({strLang: strLang});
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.user.editForm({strLang: strLang});
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
				this.getTopToolbar().findById("bttDel").disable();
				this.getTopToolbar().findById("bttEdt").disable();
			}
		}, this);
	}.createDelegate(this);
	ui.user.main.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'name',
		tbar: [
			{text: this.bttAdd, iconCls: 'user_add', handler: Add},
			{text: this.bttEdit, iconCls: "user_edit", handler: Edit, id: "bttEdt", disabled: true},
			{text: this.bttDelete, iconCls: "user_delete", handler: Delete, id: "bttDel", disabled: true},
			'->', {iconCls: 'help', handler: function(){showHelp('user')}}
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
		rowclick: function(grid, rowIndex, ev){
			grid.getTopToolbar().findById("bttEdt").enable();
			grid.getTopToolbar().findById("bttDel").enable();
		},
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.user.main, Ext.grid.GridPanel, {
	limit: 20,

	labelName: 'Имя',
	labelLogin: 'Login',
	labelEMail: 'e-mail',
	labelLang: 'Язык',

	addTitle: "Добавление пользователя",
	editTitle: "Изменение пользователя",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(ого|их) пользовател(я|ей)?",

	pagerEmptyMsg: 'Нет пользователей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
