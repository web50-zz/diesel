Ext.namespace("ui.market_basket");


ui.market_basket = Ext.extend(Ext.util.Observable, {
	constructor: function(config)
	{
		config = config || {};
		Ext.apply(this, config);
		ui.market_basket.superclass.constructor.call(this, config);
		this.on('brefresh',this.refresh,this);
	},

	init: function(){
	},

	refresh: function(){
		Ext.Ajax.request({
			url: '/ui/market_basket/basket_json.do',
			scope: this,
			success: function(response, opts) {
				var obj = Ext.decode(response.responseText);
				if(obj.success == false){
					return;
				}
				if(obj.success == true){	
					var el = Ext.fly('basket_body');
						
					el.remove();
					Ext.DomHelper.insertFirst('basket_wrap',obj.payload);
				}
			},
			 failure: function(response, opts) {
					 console.log(' Error ' + response.status);
			}
		});
	},

});

Ext.onReady(function(){
	this.ui_market_basket = new ui.market_basket();
	this.ui_market_basket.init();
});

