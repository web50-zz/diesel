ui.registry.grid = Ext.extend(Ext.grid.EditorGridPanel, {
	lblName: 'Имя',
	lblType: 'Тип',
	lblValue: 'Значение',
	lblCmmnt: 'Комментарий',

	formWidth: 350,
	formHeight: 270,

	loadMask: true,
	autoExpandColumn: 'comment',
	stripeRows: true,
	autoScroll: true,
	selModel: new Ext.grid.RowSelectionModel({singleSelect: true}),
	store: new Ext.data.Store({
		proxy: new Ext.data.HttpProxy({
			api: {
				read: 'di/registry/list.js',
				create: 'di/registry/set.js',
				update: 'di/registry/mset.js',
				destroy: 'di/registry/unset.js'
			}
		}),
		reader: new Ext.data.JsonReader({
				totalProperty: 'total',
				successProperty: 'success',
				idProperty: 'id',
				root: 'records',
				messageProperty: 'errors'
			},
			[{name: 'id', type: 'int'}, {name: 'type', type: 'int'}, 'name', 'value', 'comment']
		),
		writer: new Ext.data.JsonWriter({
			encode: true,
			listful: true,
			writeAllFields: false
		}),
		remoteSort: true,
		sortInfo: {field: 'name', direction: 'ASC'}
	}),
	Add: function(){
		var f = new ui.registry.item_form();
		var w = new Ext.Window({title: this.addTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: function(){this.store.reload()},
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show();
	},
	Edit: function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.registry.item_form();
		var w = new Ext.Window({title: this.editTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: function(){this.getStore().reload()},
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show(null, function(){f.Load(id)});
	},
	Delete: function(){
		var record = this.getSelectionModel().getSelections();
		if (!record) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				this.store.remove(record);
			}
		}, this);
	},

	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		config = config || {};
		Ext.apply(this, config, {
			columns: [
				{id: 'id', dataIndex: 'id', header: 'ID', align: 'right', sortable: true, width: 150, hidden: true},
				{id: 'name', dataIndex: 'name', header:  this.lblName, sortable: true, width: 200},
				{id: 'type', dataIndex: 'type', header: this.lblType, sortable: true, width: 50, renderer: function(v){return ui.registry.type.getById(v).get('title')}},
				{id: 'value', dataIndex: 'value', header:  this.lblValue, sortable: true, width: 400},
				{id: 'comment', dataIndex: 'comment', header:  this.lblCmmnt, sortable: true}
			]
		});
		ui.registry.grid.superclass.constructor.call(this, config);
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config)
	{
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o)
	{
	}
});
