Ext.namespace("ui.cart");
ui.cart = function(conf){
	this.collectButtons = function(){
		Ext.each(Ext.query(".delCart"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.delCart(item.getAttribute('cid'));
				},
				scope: this
			})
		}, this);
		Ext.each(Ext.query(".cart_btt_recalc"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					
				},
				scope: this
			})
		}, this);
	}
	this.delCart = function(id, el){
		Ext.Ajax.request({
			url: '/ui/cart/del.do',
			params: {id: id},
			success: function(resp, opts){
				Ext.fly('cart_record_'+id).remove();
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
}
Ext.onReady(function(){
	var c = new ui.cart();
	c.collectButtons();
});
