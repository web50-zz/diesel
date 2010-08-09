ui.catalogue.main = function(config, vp){
	Ext.apply(this, config);
	//if (vp && vp.ui_configure) store.baseParams = vp.ui_configure;
	var filter = new ui.catalogue.filter_form({region: 'west', split: true, width: 200});
	var grid = new ui.catalogue.item_list({region: 'center'});
	grid.applyStore(vp.ui_configure);
	filter.on({
		submit: grid.applyStore,
		reset: grid.applyStore,
		afterrender: function(){filter.Load(vp.ui_configure)},
		scope: grid
	});
	ui.catalogue.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [filter, grid]
	});
};
Ext.extend(ui.catalogue.main, Ext.Panel, {
});
