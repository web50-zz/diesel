ui.market_recomendations.catalogue_list = function(config, vp){
	Ext.apply(this, config);
	ui.market_recomendations.catalogue_list.superclass.constructor.call(this,{});

	var addToSelected  = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		this.father.oops(id);
	}.createDelegate(this);

	var onCmenu = function(grid, rowIndex, e){
		this.getSelectionModel().selectRow(rowIndex);
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'pencil', text: this.bttMove, handler: addToSelected}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}.createDelegate(this);

	this.purgeListeners();	
	this.on({
		rowcontextmenu: onCmenu
	});

	this.setBack = function(fth)
	{
		this.father = fth;
	}
};


Ext.extend(ui.market_recomendations.catalogue_list, ui.catalogue.item_list, {
bttMove:'Поместить в рекомендованное'
});
