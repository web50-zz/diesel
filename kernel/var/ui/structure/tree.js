var Tree = function(config){
	var self = this;
	this.pid = 0;
	this.loader = new Ext.tree.TreeLoader({url: 'di/structure/slice.json'});
	this.root = new Ext.tree.AsyncTreeNode({id: '0', draggable: false, expanded: true});
	this.rootVisible = false;
	this.autoScroll = true;
	this.loadMask = new Ext.LoadMask(Ext.getBody(), {msg: "Загрузка данных..."});
	Ext.apply(this, config, {});
	this.reload = function(id){
		if (id){
			var node = this.getNodeById(id);
			if (node){
				if (!node.expanded)
					node.expand()
				else
					node.reload();
			}
		}else if (this.root.rendered == true)
			this.root.reload();
	}
	var Save = function(id, data){
		Ext.Ajax.request({
			url: 'di/structure/set.do',
			params: Ext.apply(data, {_sid: id}),
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (success)
					self.fireEvent('saved', data, d.data);
				else
					showError('Во время сохранения возникли ошибки.');
			}
		});
	}
	var Submit = function(f){
		f.submit({
			url: 'di/structure/set.do',
			waitMsg: 'Сохранение...',
			success: function(form, action){
				var d = Ext.util.JSON.decode(action.response.responseText);
				if (d.success)
					self.fireEvent('saved', f.getValues(), d.data);
				else
					showError(d.errors);
			},
			failure: function(form, action){
				showError('Во время сохранения возникли ошибки.');
			}
		});
	}
	var Delete = function(id){
		Ext.Ajax.request({
			url: 'di/structure/unset.do',
			params: {_sid: id},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (d.success)
					self.fireEvent('deleted', id);
				else
					showError('Во время удаления возникли ошибки.');
			}
		})
	}
	var getForm = function(data){
		return new Ext.form.FormPanel({
			frame: true, 
			labelWidth: 200,
			defaults: {xtype: 'textfield', width: 202},
			items: [
				{name: '_sid', xtype: 'hidden', value: (data.id || 0)},
				{name: 'pid', xtype: 'hidden', value: (data.pid || 0)},
				{fieldLabel: 'Наименование', name: 'title', value: data.title, allowBlank: false, blankText: 'Необходимо заполнить', maxLength: 64, maxLengthText: 'Не больше 64 символов'},
				new Ext.form.ComboBox({
					store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Да'], [1, 'Нет']] }),
					fieldLabel: 'Видимый',
					hiddenName: 'hidden',
					valueField: 'value',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					selectOnFocus: true,
					editable: false,
					value: 0
				}),
				{fieldLabel: 'Имя', name: 'name'},
				{fieldLabel: 'URI', name: 'uri', disabled: true},
				{fieldLabel: 'Перенаправить', name: 'redirect'},
				new Ext.form.ComboBox({
					store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
						['text', 'Текст'],
						['news', 'Новости'],
						['article', 'Статьи']
					]}),
					fieldLabel: 'Модуль',
					hiddenName: 'module',
					valueField: 'value',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					selectOnFocus: true,
					editable: false,
					value: 'text'
				}),
				{fieldLabel: 'Параметры', name: 'params', readOnly: true},
				new Ext.form.ComboBox({
					store: new Ext.data.JsonStore({
						url: 'ui/structure/templates.do',
						fields: ['template']
					}),
					fieldLabel: 'Шаблон',
					hiddenName: 'template',
					valueField: 'template',
					displayField: 'template',
					emptyText: 'Выберите шаблон...',
					typeAhead: true,
					triggerAction: 'all',
					selectOnFocus: true,
					editable: false
				}),
				new Ext.form.ComboBox({
					store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Нет'], [1, 'Да']]}),
					fieldLabel: 'Требует авторизацию',
					hiddenName: 'private',
					valueField: 'value',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					selectOnFocus: true,
					editable: false,
					value: 0
				}),
				{fieldLabel: 'Модуль авторизации', name: 'auth_module'}
			],
			buttonAlign: 'right'
		});
		
	}
	this.addNode = function(pid){
		var fp = getForm({pid: pid});
		var w = new Ext.Window({title: 'Добавить страницу', modal: true, layout: 'fit', width: 440, height: 377, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid()) Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Добавить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy()}, this, {single: true});
		w.show();
	}
	this.editNode = function(id){
		var fp = getForm({id: id})
		var w = new Ext.Window({title: 'Редактировать страницу', modal: true, layout: 'fit', width: 440, height: 377, items: fp});
		var submit = function(){
			var f = fp.getForm();
			if (f.isValid()) Submit(f);
		}
		fp.addButton({iconCls: 'disk', text: 'Сохранить', handler: submit, scope: this});
		fp.addButton({iconCls: 'cancel', text: 'Отмена', handler: function(){w.destroy()}});
		this.on('saved', function(){w.destroy()}, this, {single: true});
		w.show(null, function(){
			fp.getForm().load({
				url: 'di/structure/item.json',
				params: {_sid: id},
				waitMsg: 'Загрузка...'
			});
		});
	}
	this.saveNode = function(id, data){
		Save(id, data);
	}
	this.deleteNode = function(id, name){
		Ext.Msg.confirm('Подтверждение.', 'Вы действительно хотите удалить страницу "'+(name || id)+'"?', function(btn){if (btn == "yes") Delete(id)});
	}
	var onCmenu = function(node, e){
		var id = node.id;
		var cmenu = new Ext.menu.Menu({items: [
			{iconCls: 'add', text: 'Добавить', handler: this.addNode.createDelegate(this, [id])},
			{iconCls: 'pencil', text: 'Редактировать', handler: this.editNode.createDelegate(this, [id])},
			{iconCls: 'delete', text: 'Удалить', handler: this.deleteNode.createDelegate(this, [id, node.text])}
		]});
		e.stopEvent();
		cmenu.showAt(e.getXY());
	}
	var onNodeClick = function(node, e){
		self.fireEvent('changenode', node.id, node);
	}
	Tree.superclass.constructor.call(this,{
		tbar: [
			{id: 'add', iconCls: 'add', text: 'Добавить', handler: this.addNode.createDelegate(this, [0]), scope: this},
			'->', {iconCls: 'help', handler: function(){showHelp('test')}}
		]
	});
	var afterSave = function(data, response){
		if (data._sid > 0){
			var node = this.getNodeById(data._sid);
			if (node.attributes.ui != data.module){
				node.attributes.ui = data.module;
				this.fireEvent('changemodule', data._sid, node);
			}
			node.setText(data.title);
		}else{
			var node = new Ext.tree.AsyncTreeNode({id: response.id, text: data.title, expanded: true});
			node.attributes.ui = data.module;
			this.getNodeById(data.pid).appendChild(node);
		}
	}
	var afterDelete = function(id){
		var node = this.getNodeById(id);
		node.remove();
		this.fireEvent('removenode', id);
	}
	this.addEvents({
		loaded: true,
		changenode: true,
		removenode: true,
		changemodule: true,
		saved: true,
		deleted: true
	});
	this.on({
		contextmenu: onCmenu,
		click: onNodeClick,
		saved: afterSave,
		deleted: afterDelete,
		scope: this
	});
};
Ext.extend(Tree, Ext.tree.TreePanel, {});
