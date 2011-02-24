ui.system_menu.tree = function(config){
	Ext.apply(this, config, {});
	ui.system_menu.tree.superclass.constructor.call(this, {
		rootVisible: false,
		autoScroll: true,
		root: {id: '1', pid: '0', draggable: false, expanded: true},
		loadMask: "Загрузка данных...",
		loader: new Ext.tree.TreeLoader({url: 'di/system_menu/slice.json'})
	});
	this.on({
		movenode: this.operation.Move,
		scope: this
	});
};
Ext.extend(ui.system_menu.tree, Ext.tree.TreePanel, {
	addTitle: "Добавление договора",
	editTitle: "Редактирование договора",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить этот договор?",
	operation: {
		Reload: function(id){
			if (id){
				var node = this.getNodeById(id);
				if (node){
					if (!node.expanded)
						node.expand();
					else
						node.reload();
				}
			}else if (this.root.rendered == true)
				this.root.reload();
		},
		Move: function(tree, node, oldParent, newParent, index){
			Ext.Ajax.request({
				url: 'di/system_menu/move.do',
				params:{
					_sid: node.id,
					pid: newParent.id,
					ind: index
				},
				failure: function(result, request){
					showError(result.responseText)
				}
			});
		},
		Delete: function(node){
			Ext.Ajax.request({
				url: 'di/system_menu/unset.do',
				params: {_sid: node.id},
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (d.success)
						node.remove();
					else
						showError('Во время удаления возникли ошибки.');
				},
				scope: this
			})
		},
		afterSave: function(data, response){
			if (data._sid > 0){
				var node = this.getNodeById(data._sid);
				node.setText(data.text);
				if (data.icon != '') node.setIconCls(data.icon);
			}else{
				var node = new Ext.tree.AsyncTreeNode({id: response.id, text: data.text, iconCls: (data.icon || null), expanded: true});
				this.getNodeById(data.pid).appendChild(node);
			}
		}
	}
});
