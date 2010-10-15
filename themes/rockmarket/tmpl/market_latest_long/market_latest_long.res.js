Ext.namespace("ui.market_latest_long");
ui.market_latest_long = function(conf){

}

Ext.onReady(function(){
	FRONTLOADER.load('/min/?f=/kernel/var/ui/catalogue/templates/catalogue.res.js','catalogue');
	r = new ui.market_latest_long();
});
