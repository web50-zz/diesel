ui.user.user_list = function(config){
	Ext.apply(this, config);
	var Attach = function(){
		var s = this.getSelectionModel().getSelected();
		this.fireEvent('user_selected', s.get('id'), s.get('name'));
	}.createDelegate(this);
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'user_go', text: this.bttAttach, handler: Attach}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
	ui.user.user_list.superclass.constructor.call(this, {
		tbar: ['->', {iconCls: 'help', handler: function(){showHelp('text')}}]
	});
	this.addEvents(
		'user_selected'
	);
	this.on({
		rowcontextmenu: onCmenu,
		scope: this
	});
};
Ext.extend(ui.user.user_list, ui.user.grid, {
	bttAttach: 'Выбрать',

	applyID: function(id){
		if (id > 0){
			Ext.each(this.getTopToolbar().find('disabled', true), function(el){el.enable()});
			Ext.apply(this.store.baseParams, {_nid: id});
			this.store.load({params:{start: 0, limit: this.limit}});
		}
	}
});
