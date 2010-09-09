ui.administrate = function(config){
	var self = this;
        var appLoaded = false;
	Ext.apply(this, config);
	var LogOut = function(){
		document.location = '/xxx/login/?cll=logout';
	}
	var mainWS = new Ext.TabPanel({
		region: 'center',
		activeTab: 0,
		items: [
			{id: 'tab-home', title: 'Home', iconCls: 'home', html: ''}
		]
	});
	this.Launch = function(appName, appFace, tabText)
	{
		var appId = 'app-'+appName+'-'+appFace;
                var appClass = 'ui.'+appName+'.'+appFace;
		var app = new App();
		app.on('apploaded', function(){
			var tab = mainWS.getComponent(appId);
			if (tab != undefined){
				mainWS.setActiveTab(tab);
			}else{
				var cfg = Ext.apply({region: 'center'}, {
					id: appId,
					title: tabText,
					closable: true
				});
				tab = eval('new '+appClass+'(cfg)');
				mainWS.add(tab);
				mainWS.setActiveTab(tab);
			}
		});
		app.Load(appName, appFace);
	}
	var appLauncher = function(config){
		var appId = 'app-'+config.appName+'-'+config.appFace;
                var appClass = 'ui.'+config.appName+'.'+config.appFace;
		var app = new App();
		app.on('apploaded', function(){
			var tab = mainWS.getComponent(appId);
			if (tab != undefined){
				mainWS.setActiveTab(tab);
			}else{
				var cfg = Ext.apply({region: 'center'}, {
					id: appId,
					title: config.text,
					iconCls: config.iconCls,
					closable: true
				});
				tab = eval('new '+appClass+'(cfg)');
				mainWS.add(tab);
				mainWS.setActiveTab(tab);
			}
		});
		app.Load(config.appName, config.appFace);
	}.createDelegate(this);
	ui.administrate.superclass.constructor.call(this, {
		layout: 'border',
		items: [
			{region: 'north', xtype: 'toolbar', height: 27, items: [
				{text: this.menuStructure, iconCls: 'chart_organisation', appName: 'structure', appFace: 'main', handler: appLauncher},
				{text: this.menuFileManager, iconCls: 'application_view_tile', appName: 'file_manager', appFace: 'main', handler: appLauncher},
				{text: this.menuCatalogue, iconCls: 'layout', appName: 'catalogue', appFace: 'main', handler: appLauncher},
				{text: this.menuGuide, iconCls: 'book', menu:[
					{text: "Производители", iconCls: 'book_open', appName: 'guide', appFace: 'producer', handler: appLauncher},
					{text: "Коллекции", iconCls: 'book_open', appName: 'guide', appFace: 'collection', handler: appLauncher},
					{text: "Группы", iconCls: 'book_open', appName: 'guide', appFace: 'group', handler: appLauncher},
					{text: "Стили", iconCls: 'book_open', appName: 'guide', appFace: 'style', handler: appLauncher},
					{text: "Типы", iconCls: 'book_open', appName: 'guide', appFace: 'type', handler: appLauncher},
					{text: "Цены", iconCls: 'book_open', appName: 'guide', appFace: 'price', handler: appLauncher},
					{text: "Страны и регионы", iconCls: 'world', appName: 'country_regions', appFace: 'main', handler: appLauncher},
					{text: "Валюты", iconCls: 'money', appName: 'guide', appFace: 'currency', handler: appLauncher},
					{text: "Почтовые зоны", iconCls: 'map', appName: 'guide', appFace: 'post_zone', handler: appLauncher}
				]},	
				{text: this.menuApps, iconCls: 'book', menu:[
					{text: "Новости", iconCls: 'newspaper', appName: 'news', appFace: 'main', handler: appLauncher},
					{text: "Текст", iconCls: 'page_white', appName: 'text', appFace: 'main', handler: appLauncher},
					{text: "FAQ", iconCls: 'book_open', appName: 'faq', appFace: 'main', handler: appLauncher},
					{text: "Рекомендуемое", iconCls: 'book_open', appName: 'market_recomendations', appFace: 'main', handler: appLauncher},
					{text: "Новинки расширенно", iconCls: 'book_open', appName: 'market_latest_long', appFace: 'main', handler: appLauncher},
					{text: "Новинки", iconCls: 'book_open', appName: 'market_latest', appFace: 'main', handler: appLauncher},
					{text: "Гостевая", iconCls: 'book_open', appName: 'guestbook', appFace: 'main', handler: appLauncher},
					{text: "Рассылки", iconCls: 'book_open', appName: 'subscribe', appFace: 'main', handler: appLauncher},
					{text: "Заказы", iconCls: 'coins', appName: 'order', appFace: 'main', handler: appLauncher},
					{text: "Клиенты", iconCls: 'book_open', appName: 'market_clients', appFace: 'main', handler: appLauncher}
				]},

				{text: this.menuSecurity, iconCls: 'shield', menu:[
					{text: this.menuUsers, iconCls: 'user', appName: 'user', appFace: 'main', handler: appLauncher},
					{text: this.menuGroups, iconCls: 'group', appName: 'group', appFace: 'main', handler: appLauncher},
					{text: this.menuSecurity, iconCls: 'shield', appName: 'security', appFace: 'main', handler: appLauncher}
				]},
				{text: this.menuHelpPages, iconCls: 'help', appName: 'help', appFace: 'main', handler: appLauncher},
				'->',
				{text: this.menuLogout, iconCls: 'logout', handler: LogOut, scope: this}
			]},
			mainWS,
			{region: 'south', baseCls: 'x-panel-header', html: '<div style="text-align: right">SBIN Diesel 8==></div>'}
		]
	});
};
Ext.extend(ui.administrate, Ext.Viewport, {
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
