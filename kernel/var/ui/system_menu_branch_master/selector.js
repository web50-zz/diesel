ui.system_menu_branch_master.selector = Ext.extend(ui.system_menu_branch_master.grid, {
	bttAdd: 'Импорт',
	bttEdit: 'Редактировать',
	bttDelete: 'Удалить',
	bttSearch: 'Найти',
	bttCancle: 'Сбросить',
	lblLoad: 'Добавить только вложенные',
	lblLoadAll: 'Добавить с замещением ( текущая нода будет изменена )',
	lblLoadRoot: 'Добавить ниже как отдельную папку',

	limit: 50,
	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}',

	constructor: function(config)
	{
		config = config || {};
		var tbar = this.initTbar();
		var bbar = this.initBbar();
		Ext.apply(this, config, {
			tbar: tbar,
			bbar: bbar
			});
		ui.system_menu_branch_master.selector.superclass.constructor.call(this, config);
		this.on({
			rowcontextmenu: this.rowCmenu, 
			dblclick: this.attachTo,
			render: this.initGrid,
			scope: this
		});

	},
	initTbar:function(){
		var tbar=  [
				'-',
				'->', {iconCls: 'help', handler: function(){showHelp('registry')}}
			]
		return tbar;
	},
	initBbar:function(){
		var bbar = new Ext.PagingToolbar({
				pageSize: this.limit,
				store: this.store,
				displayInfo: true,
				displayMsg: this.pagerDisplayMsg,
				emptyMsg: this.pagerEmptyMsg
			});
		return bbar;
	},
	rowCmenu:function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var row = grid.getSelectionModel().getSelected();
				var id = row.get('id');
				var cmenu = new Ext.menu.Menu({items: [
					{iconCls: 'cog_add', text: this.lblLoad, handler: this.attachTo, scope: this},
					{iconCls: 'cog_add', text: this.lblLoadAll, handler: this.attachToFull, scope: this},
					{iconCls: 'cog_add', text: this.lblLoadRoot, handler: this.attachToDown, scope: this}
				]});
				e.stopEvent();  
				cmenu.showAt(e.getXY());
	},
	initGrid:function(){
		this.getStore().load({params:{start:0, limit: this.limit}})
	},
	configure: function(config)
	{
		config = config || {};
		Ext.apply(this, config, config);
	},
	init: function(o){
	}
});
