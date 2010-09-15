ui.order.main = function(config, vpu){
	var vp = {ui_configure: {}};
	Ext.apply(vp, vpu);
	Ext.apply(this, config);
	var filter = new ui.order.filter_form({region: 'west', split: true, width: 200});
	var grid = new ui.order.order_list({region: 'center'});
	//grid.applyStore((vp.ui_configure || {}));
	filter.on({
		submit: grid.applyStore,
		reset: grid.applyStore,
		afterrender: function(){filter.Load((vp.ui_configure || {}))},
		scope: grid
	});
	ui.order.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [filter, grid]
	});
};
Ext.extend(ui.order.main, Ext.Panel, {
});
