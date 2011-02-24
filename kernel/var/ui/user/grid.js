ui.user.grid = function(config){
	var fm = Ext.form;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/user/list.js',
			create: 'di/user/set.js',
			update: 'di/user/mset.js',
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
		listful: true,
		writeAllFields: false
	});
	// The data store
	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader,
		writer: writer,
		remoteSort: true,
		sortInfo: {field: 'id', direction: 'ASC'}
	});
	var rndLang = function(v){
		return ui.user.languages.getById(v).get('title');
	}
	ui.user.grid.superclass.constructor.call(this, {
		store: store,
		columns: [
			{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50, sortable: true},
			{id: 'login', dataIndex: 'login', header: this.labelLogin, width: 150, sortable: true},
			{id: 'email', dataIndex: 'email', header: this.labelEMail, width: 100, sortable: true},
			{id: 'lang', dataIndex: 'lang', header: this.labelLang, renderer: rndLang, width: 100, sortable: true},
			{id: 'name', dataIndex: 'name', header:  this.labelName, sortable: true}
		],
		loadMask: true,
		autoExpandColumn: 'name',
		stripeRows: true,
		autoScroll: true,
		selModel: new Ext.grid.RowSelectionModel({singleSelect: true}),
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: store,
			displayInfo: true,
			displayMsg: this.pagerDisplayMsg,
			emptyMsg: this.pagerEmptyMsg
		})
	});
	this.addEvents({
	});
	this.on({
		scope: this
	});
};
Ext.extend(ui.user.grid, Ext.grid.EditorGridPanel, {
	limit: 50,

	labelName: 'Имя',
	labelLogin: 'Login',
	labelEMail: 'e-mail',
	labelLang: 'Язык',

	addTitle: "Добавление пользователя",
	editTitle: "Изменение пользователя",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(ого|их) пользовател(я|ей)?",

	pagerEmptyMsg: 'Нет пользователей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}',

	reload: function(){
		this.store.load({params: {start: 0, limit: this.limit}});
	}
});
