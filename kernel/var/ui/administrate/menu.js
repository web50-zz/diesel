ui.administrate.menu = function(config){
	Ext.apply(this, config);
	var onMenuClick = function(item){
		this.fireEvent('menuclick', item);
	}.createDelegate(this);
	ui.administrate.menu.superclass.constructor.call(this, {items: [{__apply menu(/menu)__}]});
	this.addEvents('menuclick');
};
Ext.extend(ui.administrate.menu, Ext.Toolbar, {
	menuOrder: 'Заявки',
	menuServices: 'Услуги',
	menuContractor: 'Контрагенты',
	menuFileManager: 'File manager',
	menuUsers: 'Users',
	menuGuide: 'Reference Books',
	menuApps: 'Applications',
	menuGroups: 'Groups',
	menuSecurity: 'Security',
	menuHelpPages: 'Help pages',
	menuLogout: 'Logout',
	errUILoad: 'Can`t load UI'
});
{__(template menu(item)__}
{__(apply(@item)__}
	{__(if(is_array($menu) && !empty($menu))__}
		{text: '{__$text__}', iconCls: '{__$icon__}', menu: [{__apply menu($menu)__}]}
	{__elseif($ui != '' AND $ep != '')__}
		{text: '{__$text__}', iconCls: '{__$icon__}', appName: '{__$ui__}', appFace: '{__$ep__}', handler: onMenuClick}
	{__elseif($href)__}
		{text: '{__$text__}', iconCls: '{__$icon__}', href: '{__$href__}', handler: onMenuClick}
	{__else__}
		'{__$0__}'
	{__if)__}{__(if(@position < @last)__},{__if)__}
{__apply)__}
{__template)__}
