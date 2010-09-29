ui.administrate.menu = function(config){
	Ext.apply(this, config);
	var LogOut = function(){
		document.location = '/xxx/login/?cll=logout';
	}
	var onMenuClick = function(item){
		this.fireEvent('menuclick', item);
	}.createDelegate(this);
	ui.administrate.menu.superclass.constructor.call(this, {items: [
		{text: this.menuStructure, iconCls: 'chart_organisation', appName: 'structure', appFace: 'main', handler: onMenuClick},
		{text: this.menuCatalogue, iconCls: 'layout', appName: 'catalogue', appFace: 'main', handler: onMenuClick},
		{text: "Заказы", iconCls: 'coins', appName: 'order', appFace: 'main', handler: onMenuClick},
		{text: this.menuGuide, iconCls: 'book', menu:[
			{text: "Производители", iconCls: 'book_open', appName: 'guide', appFace: 'producer', handler: onMenuClick},
			{text: "Коллекции", iconCls: 'book_open', appName: 'guide', appFace: 'collection', handler: onMenuClick},
			{text: "Группы", iconCls: 'book_open', appName: 'guide', appFace: 'group', handler: onMenuClick},
			{text: "Стили", iconCls: 'book_open', appName: 'guide', appFace: 'style', handler: onMenuClick},
			{text: "Типы", iconCls: 'book_open', appName: 'guide', appFace: 'type', handler: onMenuClick},
			{text: "Цены", iconCls: 'book_open', appName: 'guide', appFace: 'price', handler: onMenuClick},
			{text: "Страны и регионы", iconCls: 'world', appName: 'country_regions', appFace: 'main', handler: onMenuClick},
			{text: "Валюты", iconCls: 'money', appName: 'guide', appFace: 'currency', handler: onMenuClick},
			{text: "Почтовые зоны", iconCls: 'map', appName: 'guide', appFace: 'post_zone', handler: onMenuClick},
			{text: "Способы оплаты", iconCls: 'book_open', appName: 'guide', appFace: 'pay_type', handler: onMenuClick}
		]},	
		{text: this.menuApps, iconCls: 'application_double', menu:[
			{text: this.menuFileManager, iconCls: 'application_view_tile', appName: 'file_manager', appFace: 'main', handler: onMenuClick},
			{text: "Новости", iconCls: 'newspaper', appName: 'news', appFace: 'main', handler: onMenuClick},
			{text: "Текст", iconCls: 'page_white', appName: 'text', appFace: 'main', handler: onMenuClick},
			{text: "FAQ", iconCls: 'book_open', appName: 'faq', appFace: 'main', handler: onMenuClick},
			{text: "Рекомендуемое", iconCls: 'book_open', appName: 'market_recomendations', appFace: 'main', handler: onMenuClick},
			{text: "Новинки расширенно", iconCls: 'book_open', appName: 'market_latest_long', appFace: 'main', handler: onMenuClick},
			{text: "Новинки", iconCls: 'book_open', appName: 'market_latest', appFace: 'main', handler: onMenuClick},
			{text: "Гостевая", iconCls: 'book_open', appName: 'guestbook', appFace: 'main', handler: onMenuClick},
			{text: "Рассылки", iconCls: 'book_open', appName: 'subscribe', appFace: 'main', handler: onMenuClick},
			{text: "Клиенты", iconCls: 'book_open', appName: 'market_clients', appFace: 'main', handler: onMenuClick}
		]},

		{text: this.menuSecurity, iconCls: 'shield', menu:[
			{text: this.menuUsers, iconCls: 'user', appName: 'user', appFace: 'main', handler: onMenuClick},
			{text: this.menuGroups, iconCls: 'group', appName: 'group', appFace: 'main', handler: onMenuClick},
			{text: this.menuSecurity, iconCls: 'shield', appName: 'security', appFace: 'main', handler: onMenuClick}
		]},
		{text: this.menuHelpPages, iconCls: 'help', appName: 'help', appFace: 'main', handler: onMenuClick},
		'->',
		{text: this.menuLogout, iconCls: 'logout', handler: LogOut}
	]});
	this.addEvents('menuclick');
};
Ext.extend(ui.administrate.menu, Ext.Toolbar, {
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
