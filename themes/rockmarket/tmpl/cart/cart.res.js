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
					this.recCart();
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
				var obj = Ext.decode(resp.responseText);
				Ext.fly('cart_record_'+id).remove();
				Ext.fly('cart_form_total').update(obj.summ);
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
	this.recCart = function(){
		Ext.Ajax.request({
			url: '/ui/cart/recalc.do',
			form: 'cart_form',
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				Ext.fly('cart_form_total').update(obj.summ);
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
