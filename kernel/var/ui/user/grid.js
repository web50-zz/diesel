ui.user.grid = Ext.extend(Ext.grid.EditorGridPanel, {
	setParams: function(params, reload){
		var s = this.getStore();
		params = params || {};
		for (var i in params){if(params[i] === ''){delete params[i]}}
		this.getStore().baseParams = params;
		if (reload) s.load({params:{start: 0, limit: this.pagerSize}});
	},
	applyParams: function(params, reload){
		var s = this.getStore();
		params = params || {};
		for (var i in params){if(params[i] === ''){delete params[i]}}
		Ext.apply(s.baseParams, params);
		if (reload) s.load({params:{start: 0, limit: this.pagerSize}});
	},
	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		Ext.apply(this, {
			store: new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					api: {
						read: 'di/user/list.js',
						create: 'di/user/set.js',
						update: 'di/user/mset.js',
						destroy: 'di/user/unset.js'
					}
				}),
				reader: new Ext.data.JsonReader({
						totalProperty: 'total',
						successProperty: 'success',
						idProperty: 'id',
						root: 'records',
						messageProperty: 'errors'
					}, [
						{name: 'id', type: 'int'},
						'login',
						{name: 'type', type: 'int'},
						'server',
						'name',
						'email',
						'lang'
					]
				),
				writer: new Ext.data.JsonWriter({
					encode: true,
					listful: true,
					writeAllFields: false
				}),
				remoteSort: true,
				sortInfo: {field: 'id', direction: 'DESC'}
			})
		});
		Ext.apply(this, {
			pagerSize: 50,
			pagerEmptyMsg: 'Нет пользователей',
			pagerDisplayMsg: 'Пользователи с {0} по {1}. Всего: {2}',

			lblName: 'Имя',
			lblType: 'Тип',
			lblServer: 'Сервер',
			lblLogin: 'Login',
			lblEMail: 'e-mail',
			lblLang: 'Язык',

			addTitle: "Добавление пользователя",
			editTitle: "Изменение пользователя",

			cnfrmTitle: "Подтверждение",
			cnfrmMsg: "Вы действительно хотите удалить эт(ого|их) пользовател(я|ей)?",
		});
		Ext.apply(this, {
			loadMask: true,
			stripeRows: true,
			autoScroll: true,
			autoExpandColumn: 'expand',
			selModel: new Ext.grid.RowSelectionModel({singleSelect: true}),
			colModel: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true,
					width: 120
				},
				columns: [
					{dataIndex: 'id', header: 'ID', align: 'right', width: 50, sortable: true},
					{dataIndex: 'login', header: this.lblLogin, width: 150, sortable: true},
					{dataIndex: 'email', header: this.lblEMail, width: 150, sortable: true},
					{dataIndex: 'lang', header: this.lblLang, width: 70, sortable: true, renderer: function(v){return (v == '') ? 'Не указан' : ui.user.languages.getById(v).get('title')}},
					{dataIndex: 'name', id: 'expand', header:  this.labelName, sortable: true},
					{dataIndex: 'type', header: this.lblType, width: 70, sortable: true, renderer: function(v){switch(v){case 0: return 'MySQL'; break; case 1: return 'LDAP'; break;}}},
					{dataIndex: 'server', header: this.lblServer, width: 150, sortable: true}
				]
			}),
			bbar: new Ext.PagingToolbar({
				pageSize: this.pagerSize,
				store: this.store,
				displayInfo: true,
				displayMsg: this.pagerDisplayMsg,
				emptyMsg: this.pagerEmptyMsg
			})
		});

		config = config || {};
		Ext.apply(this, config);
		ui.user.grid.superclass.constructor.call(this, config);
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config)
	{
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o)
	{
	}
});
