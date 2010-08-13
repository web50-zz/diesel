Ext.namespace("ui.catalogue");
ui.catalogue = function(conf){
	this.collectButtons = function(){
		Ext.each(Ext.query(".add2cart"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.add2cart(item.getAttribute('cid'));
				},
				scope: this
			})
		}, this);
	}
	this.add2cart = function(id, el){
		Ext.Ajax.request({
			url: '/ui/cart/add.do',
			params: {id: id},
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
}
Ext.onReady(function(){
	var c = new ui.catalogue();
	c.collectButtons();
});
