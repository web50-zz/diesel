Ext.namespace("ui","Diesel");
ui.market_viewed = Ext.extend(Ext.util.Observable, {

});

Ext.onReady(function(){
	Diesel.market_viewed = new ui.market_viewed();
});
