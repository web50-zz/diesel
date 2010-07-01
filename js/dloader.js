var dLoader = function(config){
	var self = this;
	var loadMask;
	Ext.apply(this, config);
	var Load = function(config){
		var ui = config.ui;
		var cll = Ext.value(config.cll, 'js');
		if (Ext.value(ui, false) == false){
			showError('Не указано имя приложения');
		}else{
			loadMask = new Ext.LoadMask(Ext.getBody(), {msg: 'Загрузка приложения'});
			loadMask.show();
			var j = Ext.DomHelper.append(Ext.DomQuery.selectNode('head'),
				{tag: 'script', id: '_ui_'+ui+'_'+cll, type: 'text/javascript', src: 'ui/'+ui+'/'+cll+'.js'}, true);
			if (typeof(j.onreadystatechange) == 'object')
				j.on({readystatechange: _loaded_.createDelegate(self, [ui])});
			else
				j.on({load: _loaded_.createDelegate(self, [ui])});
		}
	}
	var _loaded_ = function(ui) {
		loadMask.hide();
		self.fireEvent('uiLoaded', {ui: ui});
	}
	dLoader.superclass.constructor.call(this, {});
	this.addEvents({
		uiLoaded: true
	});
	this.isLoaded = function(config){
		var ui = config.ui;
		var cll = Ext.value(config.cll, 'js');
		var x = Ext.value(Ext.get('_ui_'+ui+'_'+cll), false);
		return x;
	}
	this.load = function(config){
		var callback = Ext.value(config.callback, false);
		if (this.isLoaded(config)){
			if (callback)
				eval(callback);
		}else{
			if (callback)
				this.on({uiLoaded: {fn: callback, single: true}});
			Load(config);
		}
	}
}
Ext.extend(dLoader, Ext.util.Observable);
var dLoader = new dLoader();
