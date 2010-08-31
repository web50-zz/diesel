ui.news.main = function(config){
	var frmW = 800;
	var frmH = 480;
	var fm = Ext.form;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/news/list.js',
			create: 'di/news/set.js',
			update: 'di/news/mset.js',
			destroy: 'di/news/unset.js'
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
		[{name: 'id', type: 'int'}, {name: 'release_date', type: 'date', dateFormat: 'Y-m-d'}, 'title', 'author', 'source']
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
		writer: writer
	});
	columns = [
		{id: 'id', dataIndex: 'id', hidden: true},
		{header: this.clmnDate, id: 'release_date', dataIndex: 'release_date', width: 150, sortable: true, renderer: formatDate, editor: new fm.DateField({allowBlank: false, format: 'Y-m-d'})},
		{header: this.clmnAuthor, id: 'author', dataIndex: 'author', width: 150, sortable: true, editor: new fm.TextField({maxLength: 255, maxLengthText: 'Не больше 255 символов'})},
		{header: this.clmnTitle, id: 'title', dataIndex: 'title', sortable: true, editor: new fm.TextField({maxLength: 255, maxLengthText: 'Не больше 255 символов'})},
		{header: this.clmnSource, id: 'source', dataIndex: 'source', width: 150, sortable: true, editor: new fm.TextField({maxLength: 64, maxLengthText: 'Не больше 64 символов'})}
	];
	var Add = function(){
		var f = new ui.news.item_form();
		var w = new Ext.Window({title: this.addTitle, maximizable: true, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.news.item_form();
		var w = new Ext.Window({title: this.editTitle, maximizable: true, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show(null, function(){f.Load(id)});
	}.createDelegate(this);
	var multiSave = function(){
		this.store.save();
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
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'newspaper_go', text: 'Редактировать', handler: Edit},
			{iconCls: 'newspaper_delete', text: 'Удалить', handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	ui.news.main.superclass.constructor.call(this, {
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'title',
		stripeRows: true,
		autoScroll: true,
		selModel: new Ext.grid.RowSelectionModel({singleSelect: true}),
		tbar: [
			{iconCls: 'add', text: 'Добавить', handler: Add},
			'-', {id: 'msave', iconCls: 'disk', text: 'Сохранить', disabled: true, handler: multiSave},
			'->', {iconCls: 'help', handler: function(){showHelp('news')}}
		],
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
		rowcontextmenu: onCmenu,
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	});
};
Ext.extend(ui.news.main, Ext.grid.EditorGridPanel, {
	limit: 10,

	addTitle: "Добавление новости",
	editTitle: "Редактирование новости",

	clmnDate: "Дата",
	clmnTitle: "Заголовок",
	clmnAuthor: "Автор",
	clmnSource: "Источник",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эту новость?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
