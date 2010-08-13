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
		Ext.each(Ext.query(".rem2cart"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.rem2cart(item.getAttribute('cid'));
				},
				scope: this
			})
		}, this);
	}
	this.add2cart = function(id, el){
		Ext.Ajax.request({
			url: '/ui/catalogue/add2cart.do',
			params: {id: id},
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				var fld = Ext.fly('catalogue_item_'+id);
				fld.set({value: obj.count});
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
	this.rem2cart = function(id, el){
		Ext.Ajax.request({
			url: '/ui/catalogue/rem2cart.do',
			params: {id: id},
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				var fld = Ext.fly('catalogue_item_'+id);
				fld.set({value: obj.count});
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
