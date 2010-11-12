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
		
		Ext.each(Ext.query(".add2sel"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.add2sel(item.getAttribute('cid'));
				},
				scope: this
			})
		}, this);

	}
	this.add2cart = function(id, el){
		Ext.Ajax.request({
			url: '/ui/cart/add.do',
			params: {id: id},
			scope: this,
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				var text = 'Товар добавлен в корзину';
				AlertBox.show("Внимание", text, 'none', {dock: 'top',timeout:2});
					try{
						Diesel.market_basket.fireEvent('brefresh');
					}
					catch(err){
						//shut up
					}
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
	
	this.add2sel = function(id, el){
		Ext.Ajax.request({
			url: '/ui/market_selected/add.do',
			params: {id: id},
			scope: this,
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				var text = 'Товар добавлен в избранное';
				AlertBox.show("Внимание", text, 'none', {dock: 'top',timeout:2});
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}

}
Ext.onReady(function(){
	FRONTLOADER.load('/min/?f=/js/ux/alertbox/js/Ext.ux.AlertBox.js','alertbox');
	this.c = new ui.catalogue();
	this.c.collectButtons();
});
