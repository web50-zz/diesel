ui.util_db.main = function(config){
	Ext.apply(this, config);
	var dump_form =  new ui.util_db.dump_form({region: 'center',width:'200'});
	ui.util_db.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [dump_form]
	});
	this.on({
		scope: this
	});
};
Ext.extend(ui.util_db.main, Ext.Panel, {
});
