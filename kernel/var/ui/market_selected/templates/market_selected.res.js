Ext.namespace("ui.market_selected");
ui.market_selected = function(conf){
	this.collectButtons = function(){
		Ext.each(Ext.query(".delSel"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.delSel(item.getAttribute('cid'));
				},
				scope: this
			})
		}, this);
	}
	this.delSel = function(id, el){
		Ext.Ajax.request({
			url: '/ui/market_selected/del.do',
			params: {id: id},
			success: function(resp, opts){
				var obj = Ext.decode(resp.responseText);
				Ext.fly('market_selected_record_'+id).remove();
			},
			failure: function(resp, opts){
				alert(resp.status);
			}
		});
	}
}
Ext.onReady(function(){
	FRONTLOADER.load('/min/?f=/kernel/var/ui/catalogue/templates/catalogue.res.js','catalogue');
	var c = new ui.market_selected();
	c.collectButtons();
});
