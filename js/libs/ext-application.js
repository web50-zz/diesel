var App = function(config){
        var self = this;
	Ext.apply(this, config);
	App.superclass.constructor.call(this, {});
	var loadDependencies = function(ui){
		var ab = ui.split('.');
		var app = new App();
		app.on({
			apploaded: function(){
				this.fireEvent('deploaded');
			},
			apperror: function(errMsg){
				this.fireEvent('deperror', errMsg);
			},
			scope: this
		});
		app.Load(ab[0], ab[1], true);
	}.createDelegate(this);
	var checkForDependencies = function(appName, appFace){
		Ext.Ajax.request({
			url: 'ui/'+appName+'/dependencies.get',
			params: {face: appFace},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (success && d.success){
					if (!Ext.isEmpty(d.dependencies)){
						var deps = d.dependencies;
						var ui = deps.pop();
						this.on({
							deploaded: function(){
								var ui = deps.pop();
								if (typeof ui == 'string')
									loadDependencies(ui);
								else	
									this.Load(appName, appFace, true);
							},
							scope: this
						});
						loadDependencies(ui);
					}else
						this.Load(appName, appFace, true);
				}else{
					this.fireEvent('deperror', d.errors);
				}
			},
			failure: function(response, options){
				showError("Server-side failure with status code: "+response.status+"<br/>"+response.statusText);
			},
			scope: this
		});
	}.createDelegate(this);
	this.Load = function(appName, appFace, depChecked){
		Ext.namespace("ui."+appName);

		if (!(classExists("ui."+appName+"."+appFace) || eval("typeof(ui."+appName+"."+appFace+")") == 'object')){
			if (this.waitMsg && typeof(this.loadMask) != 'object'){
				this.loadMask = new Ext.LoadMask(Ext.getBody(), {msg: this.waitMsg});
				this.loadMask.show();
			}

			if (!depChecked){
				checkForDependencies(appName, appFace);
			}else{
				var id = 'ui-'+appName+'-'+appFace;
				var el = Ext.fly(id);
				if (undefined == el){
					//var loadMask = new Ext.LoadMask(Ext.getBody(), {msg: 'Загрузка приложения "'+appName+'.'+appFace+'"'});
					//loadMask.show();
					// append script to the head
					var h = Ext.fly(document.getElementsByTagName('head')[0]);
					var j = document.createElement('script');
					j.id = id;
					j.src = 'ui/'+appName+'/'+appFace+'.js';
					j.type = 'text/javascript';

					if (typeof(j.onreadystatechange) == 'object'){
						j.onreadystatechange = function(e){
							if (this.readyState == 'loaded'){
								self.fireEvent('scriptloaded');
							}
						};
					}else{
						Ext.get(j).on({
							load: function(){
								this.fireEvent('scriptloaded');
							},
							scope: this
						});
					}
					
					this.on({
						scriptloaded: function(){
							try{
								var x = "ui."+appName+"."+appFace;
								if (classExists(x) || eval("typeof("+x+")") == 'object'){
									if (ApplyLocale) ApplyLocale();
									this.fireEvent('apploaded', [appName, appFace])
								}else
									this.fireEvent('apperror', this.appErrorMsg);
							}catch(e){
								this.fireEvent('apperror', new Ext.Template(
									"fileName: {fileName}<br/>",
									"lineNumber: {lineNumber}<br/>",
									"errorName: {name}<br/>",
									"errorMessage: {message}<br/>"
								).apply(e));
							}
						},
						deperror: function(errMsg){
							if (typeof(this.loadMask) == 'object') this.loadMask.hide();
							this.fireEvent('apperror', errMsg);
						},
						apploaded: function(appName, appFace){
							if (typeof(this.loadMask) == 'object') this.loadMask.hide();
							//loadMask.hide();
						},
						apperror: function(msg){
							if (typeof(this.loadMask) == 'object') this.loadMask.hide();
							showError(new Ext.Template(msg).apply({name: appName, face: appFace}));
							//loadMask.hide();
						},
						scope: this
					});
					document.getElementsByTagName('head')[0].appendChild(j);
				}
			}
		}else{
			if (typeof(this.loadMask) == 'object') this.loadMask.hide();
			this.fireEvent('apploaded', [appName, appFace]);
		}
	}
	this.addEvents({
		scriptloaded: true,
		apploaded: true,
		apperror: true,
		deploaded: true,
		deperror: true
	});
	this.on({
		deperror: function(errMsg){
			if (typeof this.loadMask == 'object') this.loadMask.hide();
			this.fireEvent('apperror', errMsg);
		},
		scope: this
	})
}
Ext.extend(App, Ext.util.Observable, {
	appErrorMsg: 'Не удалось загрузить приложение ui.{name}.{face}.'
});
