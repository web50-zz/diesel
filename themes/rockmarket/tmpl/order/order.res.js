Ext.namespace("ui.order");
ui.order = function(conf){
	this.collectButtons = function(){
		Ext.get("method_of_payment").on({
			change: function(ev, tar){
				this.recalcOrder(Ext.get(tar).getValue());
			},
			scope: this
		});
	}
	this.recalcOrder = function(mop){
		Ext.Ajax.request({
			url: '/ui/order/recalc.do',
			params: {method_of_payment: mop},
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				Ext.fly('order-table').update(obj.html);
				Ext.fly('order-parcels').update(obj.parcels);
				Ext.fly('order-delivery_cost').update(obj.delivery_cost);
				Ext.fly('order-total_cost').update(obj.total_cost);
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
}
Ext.onReady(function(){
	var o = new ui.order();
	o.collectButtons();
});
