var View = function(config){
	var self = this;
	Ext.apply(this, config, {});
	this.Page = function(pid, node, appLoad){
		var pageId = 'page_'+pid;
		if (Ext.isEmpty(node.attributes.ui)) return;
		var appName = node.attributes.ui;
                var appFace = 'main';
		// Create namespace
		Ext.namespace('ui.'+appName);
                var appClass = 'ui.'+appName+'.'+appFace;
		var page = this.getComponent(pageId);
		if (page){
			this.getLayout().setActiveItem(pageId)
		}else{
                        if (classExists(appClass)){
                                this.insert(0, eval('new '+appClass+'({id: pageId, pid: pid, title: node.text})'));
                                this.getLayout().setActiveItem(pageId)
                        }else if(appLoad == undefined){
                                app.on('apploaded', this.Page.createDelegate(this, [pid, node, true]), this, {single: true});
                                app.Load(appName, appFace);
                        }else{
                                showError('Не удалось загрузить UI "'+appName+'"');
                        }
		}

	}
	this.rePage = function(pid, node, appLoad){
		var pageId = 'page_'+pid;
		if (Ext.isEmpty(node.attributes.ui)) return;
                var uiName = 'ui.'+node.attributes.ui;

		if (classExists(uiName)){
			var page = this.getComponent(pageId);
			var active = (this.getLayout().activeItem == page);
			if (page) page.destroy();
			this.insert(0, eval('new '+uiName+'({id: pageId, pid: pid, title: node.text})'));
			if (active) this.getLayout().setActiveItem(pageId)
		}else if(appLoad == undefined){
			app.on('apploaded', this.Page.createDelegate(this, [pid, node, true]), this, {single: true});
			app.Load(node.attributes.ui);
		}else{
			showError('Не удалось загрузить UI "'+uiName+'"');
		}

	}
	this.delPage = function(pid){
		var pageId = 'page_'+pid;
		var page = this.getComponent(pageId);
		if (page) page.destroy();
	}
	View.superclass.constructor.call(this,{
		layout: 'card'
	});
};
Ext.extend(View, Ext.Panel, {});
