Ext.namespace("ui.profile");

ui.profile = function(conf){

	this.collectButtons = function(){
		Ext.each(Ext.query(".ld"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					var el = Ext.fly('pd');
					if(!el)
					{
						this.getPinfo();
					}
				},
				scope: this
			})
		}, this);
		Ext.each(Ext.query(".sec"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
						this.getSecFrm();
				},
				scope: this
			})
		}, this);
		Ext.each(Ext.query(".zak"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					var el = Ext.fly('od');
					if(!el)
					{
						this.getZinfo();
					}
				},
				scope: this
			})
		}, this);
		Ext.each(Ext.query(".cpd"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
						this.getPform();
				},
				scope: this
			})
		}, this);
		Ext.each(Ext.query(".cpda"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
						var id  = el.getAttribute('cid');
						this.getOrder(id);
				},
				scope: this
			})
		}, this);


	};

	this.getPform = function(){
		SplForm.show({formUrl:'/ui/profile/get_pform.do',saveUrl:'/ui/profile/save_pform.do',width:500,height:600});
	}


	this.getOrder = function(id){
		SplForm.show({formUrl:'/ui/profile/get_order.do',saveUrl:'',params:{_sid:id},width:500,height:500});
	}


	this.getPinfo = function(){
			this.getData({url:'/ui/profile/client_info_part.get',callb:this.getPinfoAfter,current:'od'});	
		}

	this.getZinfo = function(){
			this.getData({url:'/ui/profile/client_orders_part.get',callb:this.getZinfoAfter,current:'pd'});	
		}

	this.getData = function(u)
	{
		Ext.Ajax.request({
			url: u.url,
			scope: this,
			success: function(response, opts) {
				var obj = Ext.decode(response.responseText);
				if(obj.code == '400'){
					AlertBox.show("Внимание", obj.report, 'none', {dock: 'top'});
				}
				if(obj.code == '200'){	
					var el = Ext.fly(u.current);
					el.remove();
					Ext.DomHelper.insertFirst('pcontent',obj.payload);
					this.collectButtons();
				}
			},
			 failure: function(response, opts) {
					 console.log(' Error ' + response.status);
			}
		});

	}





	this.getSecFrm = function(email)
	{
		SplForm.show({formUrl:'/ui/profile/get_passform.do',saveUrl:'/ui/profile/save_passform.do'});
	}
}

Ext.onReady(function(){
	FRONTLOADER.load('/js/ux/alertbox/js/Ext.ux.AlertBox.js','alertbox');
	FRONTLOADER.loadCss('/js/ux/alertbox/alertbox.css','alertboxcss');
	FRONTLOADER.load('/js/ux/splform/Ext.ux.SplForm.js','splform');
	FRONTLOADER.loadCss('/js/ux/splform/splform.css','splformcss');
	var ui_profile = new ui.profile();
	ui_profile.collectButtons();
});

