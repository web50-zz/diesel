ui.catalogue.styles = function(config){
	Ext.apply(this, config);
	var proxy = new Ext.data.HttpProxy({
		api: {
			read: 'di/guide_style/styles_in_item.json'
		}
	});
	// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
	var reader = new Ext.data.JsonReader({
			totalProperty: 'total',
			successProperty: 'success',
			idProperty: 'id',
			root: 'records',
			messageProperty: 'errors'
		},
		[{name: 'id', type: 'int'}, 'name']
	);
	var getItemID = function(){
		return this.store.baseParams.iid;
	}.createDelegate(this);
	// The data store
	this.store = new Ext.data.Store({
		proxy: proxy,
		reader: reader,
		autoLoad: true,
		remoteSort: true,
		sortInfo: {field: 'name'}
	});
	this.reload = function(full){
		this.store.load();
		//if (full == true){
		//	var bb = this.getBottomToolbar();
		//	bb.doLoad(0);
		//}else{
		//	var bb = this.getBottomToolbar();
		//	bb.doLoad(bb.cursor);
		//}
	};
	this.addStyles = function(ddSource, e, data){
		var iid = getItemID();
		if (!(iid > 0)) return false;
		var ss = ddSource.dragData.selections;
		var sids = new Array();
		for (el in ss){
			var sid = parseInt(ss[el].id);
			if (sid > 0) sids.push(sid);
		}
		if (sids.length > 0){
			Ext.Ajax.request({
				url: 'di/catalogue_style/add_styles_to_item.do',
				params: {iid: iid, sids: sids.join(",")},
				disableCaching: true,
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (!(success && d.success))
						showError(this.errDoSync);
					else{
						this.fireEvent('styles_added');
					}
				},
				scope: this
			});
		}
		return true
	}.createDelegate(this);
	this.removeStyles = function(ddSource, e, data){
		var iid = getItemID();
		if (!(iid > 0)) return false;
		var ss = ddSource.dragData.selections;
		var sids = new Array();
		for (el in ss){
			var sid = parseInt(ss[el].id);
			if (sid > 0) sids.push(sid);
		}
		if (sids.length > 0){
			Ext.Ajax.request({
				url: 'di/catalogue_style/remove_styles_from_item.do',
				params: {iid: iid, sids: sids.join(",")},
				disableCaching: true,
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (!(success && d.success))
						showError(this.errDoSync);
					else{
						this.fireEvent('styles_removed');
					}
				},
				scope: this
			});
		}
		return true
	}.createDelegate(this);
	var srchField = new Ext.form.TextField();
	var srchBttOk = new Ext.Toolbar.Button({
		text: 'Найти',
		iconCls:'find',
		handler: function search_submit(){
			Ext.apply(this.store.baseParams, {_sname: '%'+srchField.getValue()+'%'});
			this.reload();
		},
		scope: this
	});
	var srchBttCancel = new Ext.Toolbar.Button({
		text: 'Сбросить',
		iconCls:'cancel',
		handler: function search_submit(){
			srchField.setValue('');
			Ext.apply(this.store.baseParams, {_sname: ''});
			this.reload();
		},
		scope: this
	});
	ui.catalogue.styles.superclass.constructor.call(this,{
		columns: [
			{id: 'id', dataIndex: 'id', hidden: true},
			{id: 'name', header:  this.labelName, dataIndex: 'name', width: 200, sortable: true}
		],
		tbar: [
			new Ext.Toolbar.TextItem ("Найти:"), srchField, srchBttOk, srchBttCancel
		],
		loadMask: true,
		autoExpandColumn: 'name'
	});
	this.addEvents(
		"styles_added",
		"styles_removed"
	);
	this.on({
		scope: this
	})
};
Ext.extend(ui.catalogue.styles, Ext.grid.GridPanel, {
	labelName: 'Тэг',

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Записи с {0} по {1}. Всего: {2}'
});
