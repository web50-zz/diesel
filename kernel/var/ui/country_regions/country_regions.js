ui.country_regions.main = function(config, vp){
	Ext.apply(this, config);
	//if (vp && vp.ui_configure) store.baseParams = vp.ui_configure;
	var cl = new ui.country_regions.country_list({region:'west', split: true, width: 300});
	var rl = new ui.country_regions.region_list({region: 'center'}, vp);
	rl.store.baseParams = {_scr_regions_part_id: 0};
	cl.on({
		rowclick: function(cl, rowIndex, ev){
		rl.store.baseParams = {_scr_regions_part_id: this.getSelectionModel().getSelected().get('id')};
		rl.store.reload(true);
		}
	});

	ui.country_regions.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [cl, rl]
	});
};
Ext.extend(ui.country_regions.main, Ext.Panel, {
});
