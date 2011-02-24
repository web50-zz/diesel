ui.group.grid = function(config){
	var fm = Ext.form;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/group/list.js',
			create: 'di/group/set.js',
			update: 'di/group/mset.js',
			destroy: 'di/group/unset.js'
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
		[{name: 'id', type: 'int'}, 'name']
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
		return ui.group.languages.getById(v).get('title');
	}
	ui.group.grid.superclass.constructor.call(this, {
		store: store,
		columns: [
			{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50, sortable: true},
			{header: this.colNameTitle, dataIndex: 'name', id: 'name', width: 200, sortable: true}
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
Ext.extend(ui.group.grid, Ext.grid.EditorGridPanel, {
	limit: 50,

	colNameTitle: "Наименование",

	addTitle: "Добавление группы",
	editTitle: "Изменение группы",
	permTitle: "Права доступа",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(и|у) групп(ы|у)?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}',

	reload: function(){
		this.store.load({params: {start: 0, limit: this.limit}});
	}
});
