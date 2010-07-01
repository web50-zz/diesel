ui.file_manager.main = function(config){
	{__include(folders.js)__}
	{__include(files.js)__}

	var self = this;
	this.fck = false;
	this.baseURL = '/xxx/';
	Ext.apply(this, config);
	var folders = new Folders({
		baseURL: this.baseURL,
		fck: this.fck,
		region: 'west',
		title: 'Папки',
		width: 250,
		split: true,
		enableDD: true,
		ddGroup: 'filemanager'
	});
	var files = new Files({
		baseURL: this.baseURL,
		fck: this.fck,
		region: 'center',
		title: 'Файлы',
		enableDD: true,
		ddGroup: 'filemanager'
	});
	folders.on({
		click: function(node, e){
			files.setFolder(node.id);
		}
	});
	ui.file_manager.main.superclass.constructor.call(this, {
		layout: 'border',
		items: [folders, files]
	});
}
Ext.extend(ui.file_manager.main, Ext.Panel, {});
