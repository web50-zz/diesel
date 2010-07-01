ui.security.main = function(config){
	var loadMask = new Ext.LoadMask(Ext.getBody());
	Ext.apply(this, config);
	var doSync = function(){
		loadMask.show();
		Ext.Ajax.request({
			url: 'di/interface/sync.do',
			disableCaching: true,
			callback: function(options, success, response){
				loadMask.hide();
				var d = Ext.util.JSON.decode(response.responseText);
				if (!(success && d.success))
					showError(this.errDoSync);
			},
			scope: this
		});
	}.createDelegate(this);
	var group = new ui.group.main({
		title: 'Группы',
		region: 'west',
		split: true,
		width: 300
	});
	var getSelectedGroup = function(){
		var s = group.getSelectionModel().getSelected();
		return (s) ? s.get('id') : 0;
	}
	var addUsers = function(){
		var gid = getSelectedGroup();
		if (gid > 0){
			var u = new ui.user.list();
			u.store.baseParams = {gid: gid, _sgid: 'null'};
			u.addEvents('users_added');
			u.on('users_added', u.reload);
			var w = new Ext.Window({title: "Choose users", modal: true, layout: 'fit', width: 640, height: 480, items: [u],
				tbar: [
					{text: this.bttAddUsers, iconCls: 'user_add', handler: function(){
						var sm = u.getSelectionModel();
						var ss = sm.getSelections();
						if (ss){
							var uids = new Array();
							for (el in ss){
								var uid = parseInt(ss[el].id);
								if (uid > 0) uids.push(uid);
							}
							if (uids.length > 0){
								Ext.Ajax.request({
									url: 'di/group_user/add_users_to_group.do',
									params: {gid: gid, uids: uids.join(",")},
									disableCaching: true,
									callback: function(options, success, response){
										var d = Ext.util.JSON.decode(response.responseText);
										if (!(success && d.success))
											showError(this.errDoSync);
										else{
											this.fireEvent('users_added');
											u.fireEvent('users_added');
										}
									},
									scope: this
								});
							}
						}else{
							showError(this.errUserNotSelected);
						}
					}, scope: this},
					'->', {iconCls: 'help', handler: function(){showHelp('user-in-group')}}
				]
			});
			w.show();
		}else{
			showError(this.errGroupNotSelected);
		}
	}.createDelegate(this);
	var user = new ui.user.list({
		title: 'Пользователи',
		region: 'center',
		tbar: [{text: this.bttAddUsers, iconCls: 'user_add', handler: addUsers}]
	});
	var delUsers = function(){
		var gid = getSelectedGroup();
		if (gid > 0){
			var ss = user.getSelectionModel().getSelections();
			if (ss){
				var uids = new Array();
				for (el in ss){
					var uid = parseInt(ss[el].id);
					if (uid > 0) uids.push(uid);
				}
				if (uids.length > 0){
					Ext.Ajax.request({
						url: 'di/group_user/remove_users_from_group.do',
						params: {gid: gid, uids: uids.join(",")},
						disableCaching: true,
						callback: function(options, success, response){
							var d = Ext.util.JSON.decode(response.responseText);
							if (!(success && d.success))
								showError(this.errDoSync);
							else
								this.fireEvent('users_deleted');
						},
						scope: this
					});
				}
			}else{
				showError(this.errUserNotSelected);
			}
		}
	}.createDelegate(this);
	user.getTopToolbar().add({text: this.bttRemoveUsers, iconCls: 'user_add', handler: delUsers});
	user.store.baseParams = {gid: 0, _ngid: 'null'};
	group.on({
		rowclick: function(grid, rowIndex, ev){
			user.store.baseParams = {gid: this.getSelectionModel().getSelected().get('id'), _ngid: 'null'};
			user.reload(true);
		}
	});
	ui.security.main.superclass.constructor.call(this, {
		layout: 'border',
		tbar: new Ext.Toolbar({items:[
			{text: this.menuTitleMain, iconCls: 'package', menu:[
				{text: this.menuTitleSync, iconCls: 'package_go', handler: doSync}
			]}
		]}),
		items: [group, user]
	});
	this.addEvents(
		'users_added',
		'users_deleted'
	);
	this.on({
		users_added: function(){user.reload()},
		users_deleted: function(){user.reload()},
		scope: this
	});
};
Ext.extend(ui.security.main, Ext.Panel, {
	menuTitleMain: 'Operations',
	menuTitleSync: 'Syncronization',
	bttAddUsers: 'Add users', 
	bttRemoveUsers: 'Remove users', 
	errDoSync: 'Error while modules syncronization',
	errGroupNotSelected: 'The group not selected',
	errUserNotSelected: 'The user(s) not selected'
});
