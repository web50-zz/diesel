ui.registry.main = Ext.extend(ui.registry.grid, {
	bttAdd: 'Добавить',
	bttEdit: 'Редактировать',
	bttDelete: 'Удалить',
	bttSearch: 'Найти',
	bttCancle: 'Сбросить',

	limit: 50,
	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}',

	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		var srchField = new Ext.form.TextField({text:'Имя', width: 200});
		var srchBttOk = new Ext.Toolbar.Button({
			text: this.bttSearch,
			iconCls: 'find',
			handler: function search_submit(){
				var s = this.getStore();
				Ext.apply(s.baseParams, {query: srchField.getValue()});
				s.reload();
			},
			scope: this
		});
		var srchBttCancel = new Ext.Toolbar.Button({
			text: this.bttCancle,
			iconCls: 'cancel',
			handler: function search_submit(){
				var s = this.getStore();
				srchField.setValue('');
				Ext.apply(s.baseParams, {query: ''});
				s.reload();
			},
			scope: this
		});
		config = config || {};
		Ext.apply(this, config, {
			tbar: [
				{iconCls: 'cog_add', text: this.bttAdd, handler: this.Add, scope: this},
				'-',
				srchField, srchBttOk, srchBttCancel,
				'->', {iconCls: 'help', handler: function(){showHelp('registry')}}
			],
			bbar: new Ext.PagingToolbar({
				pageSize: this.limit,
				store: this.store,
				displayInfo: true,
				displayMsg: this.pagerDisplayMsg,
				emptyMsg: this.pagerEmptyMsg
			})
		});
		ui.registry.main.superclass.constructor.call(this, config);
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
	init: function(o){
		this.on({
			rowcontextmenu: function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var row = grid.getSelectionModel().getSelected();
				var id = row.get('id');
				var cmenu = new Ext.menu.Menu({items: [
					{iconCls: 'cog_edit', text: this.bttEdit, handler: this.Edit, scope: this},
					{iconCls: 'cog_delete', text: this.bttDelete, handler: this.Delete, scope: this}
				]});
				e.stopEvent();  
				cmenu.showAt(e.getXY());
			},
			render: function(){
				this.getStore().load({params:{start:0, limit: this.limit}})
			},
			scope: this
		});
	}
});
