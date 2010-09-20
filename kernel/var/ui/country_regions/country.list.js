ui.country_regions.country_list = function(config){
	var frmW = 400;
	var frmH = 200;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/guide_country/list.js',
			create: 'di/guide_country/set.js',
			update: 'di/guide_country/set.js',
			destroy: 'di/guide_country/unset.js'
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
		[{name: 'id', type: 'int'}, 'title', 'code', 'cost', 'ccy', 'ccy_string']
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
		remoteSort: true
	});
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50, sortable: true},
		{id: 'title', dataIndex: 'title', header:  this.labelTitle, sortable: true},
		{id: 'code', dataIndex: 'code', header:  this.labelCode, width: 50, sortable: true},
		{id: 'cost', dataIndex: 'cost', header:  this.labelCost, width: 50, align: 'right', sortable: true},
		{id: 'ccy_string', dataIndex: 'ccy_string', header:  this.labelCCY, width: 50, sortable: true}
	];
	var Add = function(){
		var f = new ui.country_regions.country_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.country_regions.country_form();
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
				this.getTopToolbar().findById("bttDel-ggg").disable();
				this.getTopToolbar().findById("bttEdt-ggg").disable();
			}
		}, this);
	}.createDelegate(this);
	ui.country_regions.country_list.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'title',
		tbar: [
			{text: this.bttAdd, iconCls: 'world_add', handler: Add},
			{text: this.bttEdit, iconCls: "world_edit", handler: Edit, id: "bttEdt-ggg", disabled: true},
			{text: this.bttDelete, iconCls: "world_delete", handler: Delete, id: "bttDel-ggg", disabled: true},
			'->', {iconCls: 'help', handler: function(){showHelp('faq')}}
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
			grid.getTopToolbar().findById("bttEdt-ggg").enable();
			grid.getTopToolbar().findById("bttDel-ggg").enable();
		},
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.country_regions.country_list, Ext.grid.GridPanel, {
	limit: 20,

	labelTitle: 'Страна',
	labelCode: 'Код',
	labelCost: 'Стоимость',
	labelCCY: 'Валюта',

	addTitle: "Добавление страны",
	editTitle: "Изменение страны",

	bttAdd: "Добавить",
	bttEdit: "Изменить",
	bttDelete: "Удалить",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить эт(у|и) стран(у|ы)?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
