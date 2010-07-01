ui.security.interfaces = function(config){
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/interface/intefaces_in_group.json'
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
		[{name: 'id', type: 'int'}, 'type', 'name', 'human_name', 'entry_point', 'human_entry_point']
	);
	// The data store
	this.store = new Ext.data.Store({
		proxy: proxy,
		reader: reader
	});
	this.reload = function(full){
		if (full == true){
			var bb = this.getBottomToolbar();
			bb.doLoad(0);
		}else{
			var bb = this.getBottomToolbar();
			bb.doLoad(bb.cursor);
		}
	};
	var getName = function(value, metaData, record){
		var hn = record.get('human_name');
		return (hn) ? hn : value;
	}.createDelegate(this);
	var getFace = function(value, metaData, record){
		var hn = record.get('human_entry_point');
		return (hn) ? hn : value;
	}.createDelegate(this);
	ui.security.interfaces.superclass.constructor.call(this,{
		columns: [
			{id: 'id', dataIndex: 'id', hidden: true},
			{id: 'type', header:  this.labelType, dataIndex: 'type', width: 30},
			{id: 'name', header:  this.labelName, renderer: getName, dataIndex: 'name', width: 200},
			{id: 'face', header:  this.labelFace, renderer: getFace, dataIndex: 'entry_point', width: 200}
		],
		loadMask: true,
		autoExpandColumn: 'face',
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: this.store,
			displayInfo: true,
			displayMsg: this.pagerDisplayMsg,
			emptyMsg: this.pagerEmptyMsg
		}),
	});
	this.on({
		render: function(){this.store.load({params:{start:0, limit: this.limit}})},
		scope: this
	})
};
Ext.extend(ui.security.interfaces, Ext.grid.GridPanel, {
	limit: 50,

	labelType: 'Тип',
	labelName: 'Интерфейс',
	labelFace: 'Точка входа',

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
