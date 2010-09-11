Ext.namespace("ui.market_latest_long");
ui.market_latest_long = function(conf){

	this.got = function()
	{
	var c = new ui.catalogue();
	c.collectButtons();
	alert('eee');
	}
}


Ext.onReady(function(){
	FRONTLOADER.load('/min/?f=/kernel/var/ui/catalogue/templates/catalogue.res.js','catalogue');
	r = new ui.market_latest_long();
});
