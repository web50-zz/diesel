ui.user.main = function(config){
	Ext.apply(this, config);
	var Add = function(){
		var f = new ui.user.item_form();
		var w = new Ext.Window({title: this.addTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: function(){this.store.reload()},
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show();
	}.createDelegate(this);
	var Edit = function(){
		var id = this.getSelectionModel().getSelected().get('id');
		var f = new ui.user.item_form();
		var w = new Ext.Window({title: this.editTitle, maximizable: true, modal: true, layout: 'fit', width: this.formWidth, height: this.formHeight, items: f});
		f.on({
			saved: function(){this.store.reload()},
			cancelled: function(){w.destroy()},
			scope: this
		});
		w.show(null, function(){f.Load(id)});
	}.createDelegate(this);
	var multiSave = function(){
		this.store.save();
	}.createDelegate(this);
	var Delete = function(){
		var record = this.getSelectionModel().getSelections();
		if (!record) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				this.store.remove(record);
			}
		}, this);
	}.createDelegate(this);
	var onCmenu = function(grid, rowIndex, e){
		grid.getSelectionModel().selectRow(rowIndex);
		var row = grid.getSelectionModel().getSelected();
		var id = row.get('id');
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'user_edit', text: this.bttEdit, handler: Edit},
			{iconCls: 'user_delete', text: this.bttDelete, handler: Delete}
		]});
		e.stopEvent();  
		cmenu.showAt(e.getXY());
	}
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
			Ext.apply(this.store.baseParams, {field: srchType.getValue(), query: srchField.getValue()});
			this.reload();
		},
		scope: this
	})
	var srchBttCancel = new Ext.Toolbar.Button({
		text: 'Сбросить',
		iconCls:'cancel',
		handler: function search_submit(){
			srchField.setValue('');
			Ext.apply(this.store.baseParams, {field: '', query: ''});
			this.reload();
		},
		scope: this
	})
	ui.user.main.superclass.constructor.call(this, {
		tbar: [
			{iconCls: 'user_add', text: this.bttAdd, handler: Add},
			'-',
			srchType,srchField, srchBttOk, srchBttCancel,
			'->', {iconCls: 'help', handler: function(){showHelp('user')}}
		]
	});
	this.addEvents({
	});
	this.on({
		rowcontextmenu: onCmenu,
		render: function(){this.store.load({params:{start:0, limit: this.limit}})},
		scope: this
	});
};
Ext.extend(ui.user.main, ui.user.grid, {
	formWidth: 350,
	formHeight: 270,

	bttAdd: 'Добавить',
	bttEdit: 'Редактировать',
	bttDelete: 'Удалить'
});