ui.market_recomendations.main = function(config, vpu){
	var vp = {ui_configure: {}};
	Ext.apply(vp, vpu);
	Ext.apply(this, config);
	var filter = new ui.catalogue.filter_form({region: 'west', split: true, width: 150});
	var grid = new ui.market_recomendations.catalogue_list({region: 'center'});
	var grid2 = new ui.market_recomendations.recomend_list({region: 'center',title:'Рекомендуемое'});
		panel1 = new Ext.Panel({
			title: 'Поиск по каталогу',
			layout:'border',
			region:'west',
			width: 600,
			collapsible:true,
			collapsed:true,
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
	ui.market_recomendations.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [grid2,panel1]
	});
};
Ext.extend(ui.market_recomendations.main, Ext.Panel, {
});
