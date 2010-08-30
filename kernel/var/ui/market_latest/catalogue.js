ui.market_latest.main = function(config, vpu){
	var vp = {ui_configure: {}};
	Ext.apply(vp, vpu);
	Ext.apply(this, config);
	var filter = new ui.catalogue.filter_form({region: 'west', split: true, width: 150});
	var grid = new ui.market_latest.catalogue_list({region: 'center'});
	var grid2 = new ui.market_latest.latest_list({region: 'center',title:'Новинки'});
		panel1 = new Ext.Panel({
			title: 'Поиск по каталогу',
			layout:'border',
			region:'west',
			width: 600,
			items: [filter,grid]
		});

//	grid.applyStore((vp.ui_configure || {}));

	grid.setBack(this);
	this.oops = function(id)
	{
		grid2.Add(id);
	}

	filter.on({
		submit: grid.applyStore,
		reset: grid.applyStore,
		afterrender: function(){filter.Load((vp.ui_configure || {}))},
		scope: grid
	});
	ui.market_latest.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [grid2,panel1]
	});
};
Ext.extend(ui.market_latest.main, Ext.Panel, {
});
