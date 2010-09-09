ui.guide.currency = function(config){
	var frmW = 300;
	var frmH = 200;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/guide_currency/list.js',
			create: 'di/guide_currency/set.js',
			update: 'di/guide_currency/set.js',
			destroy: 'di/guide_currency/unset.js'
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
		[{name: 'id', type: 'int'}, 'name', 'title']
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
		{id: 'name', dataIndex: 'name', header:  this.labelName, align: 'right', width: 100},
		{id: 'title', dataIndex: 'title', header:  this.labelTitle}
	];
	var Add = function(){
		var f = new ui.guide.currency_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.guide.currency_form();
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
		grid.getSelectionModel().selectRow(rowIndex);
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'money', text: 'Редактировать', handler: Edit},
			{iconCls: 'money_delete', text: 'Удалить', handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	ui.guide.currency.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'title',
		tbar: [
			{text: this.bttAdd, iconCls: 'money_add', handler: Add},
			'->', {iconCls: 'help', handler: function(){showHelp('guide-currency')}}
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
Ext.extend(ui.guide.currency, Ext.grid.GridPanel, {
	limit: 20,

	labelName: 'Валюта',
	labelTitle: 'Расшифровка',

	addTitle: "Добавление валюты",
	editTitle: "Изменение валюты",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эту валюту?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
