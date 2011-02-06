ui.market_types.main = function(config){
	var self = this;
	var tree = new ui.market_types.tree({
		region:'center',
		listeners: {
			changenode: function(id, node){
			}
		}
	});
	Ext.apply(this, config, {});
	ui.market_types.main.superclass.constructor.call(this,{
		title: 'Типы товаров',
		layout: 'border',
		items: [tree]
	});
};
Ext.extend(ui.market_types.main, Ext.Panel, {});
