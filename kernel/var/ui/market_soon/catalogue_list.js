ui.market_soon.catalogue_list = function(config, vp){
	Ext.apply(this, config);

	var shit = function(){
		var e ='ee';	
	}.createDelegate(this);

	var addToSelected  = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		this.father.oops(id);
	}.createDelegate(this);
	this.onCmenu = function(grid, rowIndex, e){
		this.getSelectionModel().selectRow(rowIndex);
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'pencil', text: this.bttMove, handler: addToSelected}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}.createDelegate(this);
	
	this.setBack = function(fth)
	{
		this.father = fth;
	}.createDelegate(this);
	ui.market_soon.catalogue_list.superclass.constructor.call(this,config,vp);
	this.purgeListeners();	
	this.on({
		rowcontextmenu: this.onCmenu
	});
};

Ext.extend(ui.market_soon.catalogue_list, ui.catalogue.item_list, {
bttMove:'Поместить в скоро в продаже'
});
