ui.market_latest_long.main = function(config, vpu){
	var vp = {ui_configure: {}};
	Ext.apply(vp, vpu);
	Ext.apply(this, config);
	//if (vp && vp.ui_configure) store.baseParams = vp.ui_configure;
	var filter = new ui.market_latest_long.filter_form({region:'center'});
	var grid = new ui.market_latest_long.list({region: 'center'});
	grid.applyStore((vp.ui_configure || {}));
	panel1 = new Ext.Panel({
			title: 'Поиск',
			layout:'border',
			region:'west',
			width: 200,
			collapsible:true,
			collapsed:true,
			items: [filter]
		});
	filter.on({
		submit: grid.applyStore,
		reset: grid.applyStore,
		afterrender: function(){filter.Load((vp.ui_configure || {}))},
		scope: grid
	});
	ui.market_latest_long.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [panel1, grid]
	});
};
Ext.extend(ui.market_latest_long.main, Ext.Panel, {
});
