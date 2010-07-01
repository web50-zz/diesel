ui.structure.main = function(config){
	{__include(tree.js)__}
	{__include(view.js)__}
	var self = this;
	var tree = new Tree({
		region: 'west',
		width: 300,
		split: true
	});
	var view = new View({region: 'center'});
	tree.on({
		changenode: function(pid, node){
			view.Page(pid, node);
		},
		changemodule: function(pid, node){
			view.rePage(pid, node);
		},
		removenode: function(pid, node){
			view.delPage(pid);
		}
	});
	Ext.apply(this, config, {});
	ui.structure.main.superclass.constructor.call(this,{
		title: 'Структура сайта',
		layout: 'border',
		items: [tree, view]
	});
};
Ext.extend(ui.structure.main, Ext.Panel, {});
