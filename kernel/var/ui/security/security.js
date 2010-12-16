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
		width: 550
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
			var reload = function(){
				u.store.load({params: {start: 0, limit: 20}});
			}.createDelegate(this);

			var srchType = new Ext.form.ComboBox({
			width: 100,
			store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
				['name', 'Имя'],
				['login', 'Login'],
				['email', 'E-mail'],
				['id', 'UID']
			]}), value: 'login',
			valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
			});

			var srchField = new Ext.form.TextField({text:'Имя'});
			var srchBttOk = new Ext.Toolbar.Button({
				text: 'Найти',
				iconCls:'find',
				handler: function search_submit(){
					Ext.apply(u.store.baseParams, {field: srchType.getValue(), query: srchField.getValue()});
					reload();
				}
			})
			var srchBttCancel = new Ext.Toolbar.Button({
				text: 'Сбросить',
				iconCls:'cancel',
				handler: function search_submit(){
					srchField.setValue('');
					Ext.apply(u.store.baseParams, {field: '', query: ''});
					reload();
				}
			})

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
					srchType,srchField, srchBttOk, srchBttCancel,
					'->', {iconCls: 'help', handler: function(){showHelp('user-in-group')}}
				]
			});
			w.show();
		}else{
			showError(this.errGroupNotSelected);
		}
	}.createDelegate(this);
	
	

	var srchType = new Ext.form.ComboBox({
			width: 100,
			store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
				['name', 'Имя'],
				['login', 'Login'],
				['email', 'E-mail'],
				['id', 'UID']
			]}), value: 'login',
			valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
			});

	var srchField = new Ext.form.TextField({text:'Имя'});
	var srchBttOk = new Ext.Toolbar.Button({
				text: 'Найти',
				iconCls:'find',
				handler: function search_submit(){
					Ext.apply(user.store.baseParams, {field: srchType.getValue(), query: srchField.getValue()});
					reload1();
				}
			})
	var srchBttCancel = new Ext.Toolbar.Button({
				text: 'Сбросить',
				iconCls:'cancel',
				handler: function search_submit(){
					srchField.setValue('');
					Ext.apply(user.store.baseParams, {field: '', query: ''});
					reload1();
				}
	})


	var user = new ui.user.list({
		title: 'Пользователи',
		region: 'center',
		tbar: [{text: this.bttAddUsers, iconCls: 'user_add', handler: addUsers},
			srchType,srchField, srchBttOk, srchBttCancel
			]
	});
	var reload1 = function(){
				user.store.load({params: {start: 0, limit: 20}});
		}.createDelegate(this);
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
			{text: this.menuTitleMain, iconCls: 'shield', menu:[
				{text: this.menuTitleSync, iconCls: 'shield_go', handler: doSync}
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
