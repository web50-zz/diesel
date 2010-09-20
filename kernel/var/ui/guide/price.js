ui.guide.price = function(config){
	var frmW = 640;
	var frmH = 480;
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/guide_price/list.js',
			create: 'di/guide_price/set.js',
			update: 'di/guide_price/set.js',
			destroy: 'di/guide_price/unset.js'
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
		[{name: 'id', type: 'int'}, 'title', 'cost']
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
	var priceFormat = function(value){
		return Ext.util.Format.number(value, '0.00');
	}
	// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
	var columns = [
		{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', width: 50},
		{id: 'cost', dataIndex: 'cost', header:  this.labelCost, align: 'right', width: 100, renderer: priceFormat},
		{id: 'title', dataIndex: 'title', header:  this.labelName}
	];
	var Add = function(){
		var f = new ui.guide.price_form();
		var w = new Ext.Window({title: this.addTitle, modal: true, layout: 'fit', width: frmW, height: frmH, items: f});
		f.on({
			saved: function(){store.reload()},
			cancelled: function(){w.destroy()}
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.guide.price_form();
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
				this.getTopToolbar().findById("bttDel-gp").disable();
				this.getTopToolbar().findById("bttEdt-gp").disable();
			}
		}, this);
	}.createDelegate(this);
	ui.guide.price.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'title',
		tbar: [
			{text: this.bttAdd, iconCls: 'book_add', handler: Add},
			{text: this.bttEdit, iconCls: "book_edit", handler: Edit, id: "bttEdt-gp", disabled: true},
			{text: this.bttDelete, iconCls: "book_delete", handler: Delete, id: "bttDel-gp", disabled: true},
			'->', {iconCls: 'help', handler: function(){showHelp('guide-price')}}
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
			grid.getTopToolbar().findById("bttEdt-gp").enable();
			grid.getTopToolbar().findById("bttDel-gp").enable();
		},
		render: function(){store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.guide.price, Ext.grid.GridPanel, {
	limit: 20,

	labelName: 'Наименование',
	labelLang: 'Цена',

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
