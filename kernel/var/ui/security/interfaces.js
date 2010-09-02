ui.security.interfaces = function(config){
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/entry_point/in_group.json'
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
		[{name: 'id', type: 'int'}, 'type', 'name', 'interface_name']
	);
	// The data store
	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader
	});
	var columns = [
		{id: 'id', dataIndex: 'id', hidden: true},
		{id: 'type', header:  this.labelType, dataIndex: 'type', width: 30},
		{id: 'name', header:  this.labelName, dataIndex: 'interface_name', width: 200},
		{id: 'face', header:  this.labelFace, dataIndex: 'name', width: 200}
	];
	this.reload = function(full){
		if (full == true){
			var bb = this.getBottomToolbar();
			bb.doLoad(0);
		}else{
			var bb = this.getBottomToolbar();
			bb.doLoad(bb.cursor);
		}
	};
	var srchField = new Ext.form.TextField();
	var srchBttOk = new Ext.Toolbar.Button({
		text: 'Найти',
		iconCls:'find',
		handler: function search_submit(){
			Ext.apply(store.baseParams, {query: srchField.getValue()});
			this.reload();
		},
		scope: this
	})
	var srchBttCancel = new Ext.Toolbar.Button({
		text: 'Сбросить',
		iconCls:'cancel',
		handler: function search_submit(){
			srchField.setValue('');
			Ext.apply(store.baseParams, {query: ''});
			this.reload();
		},
		scope: this
	})
	ui.security.interfaces.superclass.constructor.call(this,{
		store: store,
		columns: columns,
		loadMask: true,
		autoExpandColumn: 'face',
		tbar: [new Ext.Toolbar.TextItem ("Найти:"), srchField, srchBttOk, srchBttCancel],
		bbar: new Ext.PagingToolbar({
			pageSize: this.limit,
			store: store,
			displayInfo: true,
			displayMsg: this.pagerDisplayMsg,
			emptyMsg: this.pagerEmptyMsg
		})
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
