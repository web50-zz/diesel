ui.structure.page_view = function(config){
	var appFace = 'main';
	Ext.apply(this, config, {});
	this.newPage = function(pid, node, recreate){
		if (Ext.isEmpty(node.attributes.ui)) return;
                var appClass = 'ui.'+node.attributes.ui+'.'+appFace;
		var pageId = 'page_'+pid;
		var app = new App();
		app.on('apploaded', function(){
			var page = this.getComponent(pageId);
			if (page){
				if (recreate){
					var active = (this.getLayout().activeItem == page);
					this.delPage(pid);
					this.insert(0, eval('new '+appClass+'({id: pageId, pid: pid, title: node.text})'));
					if (active) this.getLayout().setActiveItem(pageId)
				}else{
					this.getLayout().setActiveItem(pageId)
				}
			}else{
				this.insert(0, eval('new '+appClass+'({id: pageId, pid: pid, title: node.text})'));
				this.getLayout().setActiveItem(pageId)
			}
		}, this);
		app.Load(node.attributes.ui, appFace);
	}
	this.delPage = function(pid){
		var page = this.getComponent('page_'+pid);
		if (page) page.destroy();
	}
	ui.structure.page_view.superclass.constructor.call(this,{
		layout: 'card'
	});
};
Ext.extend(ui.structure.page_view, Ext.Panel, {});
