Ext.namespace("ui.market_basket");


ui.market_basket = Ext.extend(Ext.util.Observable, {
	constructor: function(config)
	{
		config = config || {};
		Ext.apply(this, config);
		ui.market_basket.superclass.constructor.call(this, config);
		this.collectButtons();
		this.on('brefresh',this.refresh,this);
	},

	collectButtons:function(){
		Ext.each(Ext.query(".iremove"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.delCart(item.getAttribute('cid'));
				},
				scope: this
			})
		}, this);
	
	},

	delCart:function(id){
		Ext.Ajax.request({
			url: '/ui/cart/del.do',
			params: {id: id},
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				this.fireEvent('brefresh');
			},
			failure: function(resp, opts){
				alert(resp.status);
			},
			scope:this
		});

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
				this.collectButtons();
			},
			 failure: function(response, opts) {
					 console.log(' Error ' + response.status);
			},
			scope:this
		});
	},

});

Ext.onReady(function(){
	this.ui_market_basket = new ui.market_basket();
});

