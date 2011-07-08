ui.administrate.main = function(config){
	Ext.apply(this, config);
	var LogOut = function(){
		document.location = '/xxx/login/?cll=logout';
	}
	var home = new ui.administrate.home({iconCls: 'home', title: this.tabHome});
	var ws = new Ext.TabPanel({region: 'center', activeTab: 0, items: [home]});
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
	var appLauncher = function(item){
		if (item.appName && item.appFace){
			var appId = 'app-'+item.appName+'-'+item.appFace;
			var appClass = 'ui.'+item.appName+'.'+item.appFace;
			var app = new App({
				waitMsg: 'Загрузка приложения "'+item.text+'"'
			});
			app.on({
				apploaded: function(){
					var tab = ws.getComponent(appId);
					if (tab != undefined){
						ws.setActiveTab(tab);
					}else{
						var cfg = Ext.apply({region: 'center'}, {
							id: appId,
							title: item.text,
							iconCls: item.iconCls,
							closable: true
						});
						tab = eval('new '+appClass+'(cfg)');
						ws.add(tab);
						ws.setActiveTab(tab);
					}
				},
				apperror: function(msg){
					showError(msg);
				}
			});
			app.Load(item.appName, item.appFace);
		}else if(item.href){
			document.location = item.href;
		}else{
			showError('Unknown action');
		}
	}.createDelegate(this);
	menu.on('menuclick', appLauncher);
	ui.administrate.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [menu, ws, {region: 'south', baseCls: 'x-panel-header', html: '<div style="text-align: right">SBIN Diesel</div>'}]
	});
};
Ext.extend(ui.administrate.main, Ext.Viewport, {
	tabHome: 'Home'
});
