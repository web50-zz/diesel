ui.administrate.home = function(config){
	Ext.apply(this, config);
	ui.administrate.home.superclass.constructor.call(this, {
		layout: 'column',
		autoScroll: true,
		items: [
			{columnWidth: .6,
			items: [
				//new ui.order.order_list({iconCls: 'coins', title: 'Заказы', height: 300})
			]},
			{columnWidth: .2,
			items: [
			]},
			{columnWidth: .2,
			items: [
			]}
		]
	});
};
Ext.extend(ui.administrate.home, Ext.Panel, {
});
