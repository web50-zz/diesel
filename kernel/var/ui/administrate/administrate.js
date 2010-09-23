ui.administrate.main = function(config){
	Ext.apply(this, config);
	var LogOut = function(){
		document.location = '/xxx/login/?cll=logout';
	}
	var ws = new Ext.TabPanel({region: 'center', items: []});
	var menu = new ui.administrate.menu({region: 'north', xtype: 'toolbar', height: 27});
	this.Launch = function(appName, appFace, tabText)
	{
		var appId = 'app-'+appName+'-'+appFace;
                var appClass = 'ui.'+appName+'.'+appFace;
		var app = new App();
		app.on({
			apploaded: function(){
				var tab = ws.getComponent(appId);
				if (tab != undefined){
					ws.setActiveTab(tab);
				}else{
					var cfg = Ext.apply({region: 'center'}, {
						id: appId,
						title: tabText,
						closable: true
					});
					tab = eval('new '+appClass+'(cfg)');
					ws.add(tab);
					ws.setActiveTab(tab);
				}
			},
			apperror: showError
		});
		app.Load(appName, appFace);
	}
	var appLauncher = function(config){
		var appId = 'app-'+config.appName+'-'+config.appFace;
                var appClass = 'ui.'+config.appName+'.'+config.appFace;
		var app = new App();
		app.on({
			apploaded: function(){
				var tab = ws.getComponent(appId);
				if (tab != undefined){
					ws.setActiveTab(tab);
				}else{
					var cfg = Ext.apply({region: 'center'}, {
						id: appId,
						title: config.text,
						iconCls: config.iconCls,
						closable: true
					});
					tab = eval('new '+appClass+'(cfg)');
					ws.add(tab);
					ws.setActiveTab(tab);
				}
			},
			apperror: showError
		});
		app.Load(config.appName, config.appFace);
	}.createDelegate(this);
	menu.on('menuclick', appLauncher);
	ui.administrate.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [menu, ws, {region: 'south', baseCls: 'x-panel-header', html: '<div style="text-align: right">SBIN Diesel</div>'}]
	});
};
Ext.extend(ui.administrate.main, Ext.Viewport, {
	menuStructure: 'Structure',
	menuFileManager: 'File manager',
	menuUsers: 'Users',
	menuGuide: 'Reference Books',
	menuApps: 'Applications',
	menuCatalogue: 'Catalogue',
	menuGroups: 'Groups',
	menuSecurity: 'Security',
	menuHelpPages: 'Help pages',
	menuLogout: 'Logout',
	errUILoad: 'Can`t load UI'
});
