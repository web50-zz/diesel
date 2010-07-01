var App = function(config){
        var self = this;
	var loadMask = new Ext.LoadMask(Ext.getBody());
	Ext.apply(this, config);
	App.superclass.constructor.call(this, {});
	var loadDependencies = function(dependencies){
		Ext.each(dependencies, function(appName){
			var app = new App();
			appArr = appName.split('.');
			app.on('apploaded', function(){
				var loaded = true;
				Ext.each(dependencies, function(){
					if (!classExists("ui."+appArr[0]+"."+appArr[1]))
						loaded = false;
				});
				if (loaded == true)
					self.fireEvent('deploaded');
			});
			app.Load(appArr[0], appArr[1]);
		});
	}
	var checkForDependencies = function(appName, appFace){
		Ext.Ajax.request({
			url: 'ui/'+appName+'/dependencies.get',
			params: {face: appFace},
			callback: function(options, success, response){
				var d = Ext.util.JSON.decode(response.responseText);
				if (success && d.success){
					if (!Ext.isEmpty(d.dependencies)){
						this.on({
							deploaded: function(){
								this.Load(appName, appFace, true);
							},
							scope: this
						})
						loadDependencies(d.dependencies);
					}else
						this.Load(appName, appFace, true);
				}else
					showError(d.errors);
			},
			failure: function(response, options){
				switch (options.failureType){
					case Ext.response.Action.CONNECT_FAILURE:
						showError("Ошибка связи с сервером");
					break;
					case Ext.response.Action.SERVER_INVALID:
						showError(options.result.errors);
				}
			},
			scope: this
		});
	}.createDelegate(this);
	this.Load = function(appName, appFace, depChecked){
		Ext.namespace("ui."+appName);

		if (!classExists("ui."+appName+"."+appFace)){
			if (!depChecked){
				checkForDependencies(appName, appFace);
			}else{
				var id = 'ui-'+appName+'-'+appFace;
				var el = Ext.fly(id);
				if (undefined == el){
					loadMask.show();
					// append script to the head
					var h = Ext.fly(document.getElementsByTagName('head')[0]);
					var j = document.createElement('script');
					j.id = id;
					j.src = 'ui/'+appName+'/'+appFace+'.js';
					j.type = 'text/javascript';

					if (typeof(j.onreadystatechange) == 'object'){
						j.onreadystatechange = function(e){
							if (this.readyState == 'loaded'){
								self.fireEvent('apploaded', [appName, appFace]);
							}
						};
					}else{
						Ext.get(j).on({
							load: function(){
								this.fireEvent('apploaded', [appName, appFace]);
							},
							scope: this
						});
					}

					document.getElementsByTagName('head')[0].appendChild(j);
				}
			}
		}else{
			this.fireEvent('apploaded', [appName, appFace]);
		}
	}
	this.addEvents({
		apploaded: true,
		deploaded: true
	});
	this.on({
		apploaded: function(appName, appFace){
			loadMask.hide();
			if (ApplyLocale) ApplyLocale();
		}
	})
}
Ext.extend(App, Ext.util.Observable, {});
