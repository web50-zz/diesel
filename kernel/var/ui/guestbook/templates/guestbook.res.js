
Ext.namespace("ui.guestbook");
ui.guestbook = function(conf){

	this.collectButtons = function(){
		Ext.each(Ext.query(".newrecord"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.getFrm();
				},
				scope: this
			})
		}, this);
	}

	this.getFrm = function(cid)
	{
		Ext.Ajax.request({
			url: '/ui/guestbook/getfrm.do',
			scope: this,
			params:{cid:cid},
			success: function(response, opts) {
				var obj = Ext.decode(response.responseText);
				if(obj.code == '400')
				{
					AlertBox.show("Внимание", obj.error, 'none', {dock: 'top'});
				}
				if(obj.code == '200')
				{
					this.makeFrmWindow(obj.form);
				}
			},
			 failure: function(response, opts) {
					 console.log(' Error ' + response.status);
			}
		});
	};

	this.handleSubmit = function(){
		Ext.each(Ext.query(".req",Ext.fly('.guestbook_form')), function(item, index, allItems){
			var el = Ext.get(item);
			if(el.getValue() == '')
			{
				var elt = Ext.fly(el.getAttribute('fldttlid'));
				el.replaceClass('field','field_error');
				elt.replaceClass('field_name','field_name_error');
			}
			else
			{
				var elt = Ext.fly(el.getAttribute('fldttlid'));
				el.replaceClass('field_error','field');
				elt.replaceClass('field_name_error','field_name');
			}
		}, this);
		Ext.Ajax.on('beforerequest', this.showSpinner, this);
		Ext.Ajax.on('requestcomplete', this.hideSpinner, this);
		Ext.Ajax.on('requestexception', this.hideSpinner, this);
		Ext.Ajax.request({
			url: '/ui/guestbook/save_form.do',
			form: 'guestbook_form',
			scope: this,
			success: function(response, opts) {
				var obj = Ext.decode(response.responseText);
				if(obj.code == '400')
				{
					AlertBox.show("Внимание", obj.error, 'none', {dock: 'top'});
				}
				if(obj.code == '200')
				{
					this.authism();
				}
			},
			 failure: function(response, opts) {
					 console.log(' Error ' + response.status);
			}
		});
	};

	this.showSpinner =  function(){
		el = Ext.fly('guestbook').insertFirst({
		tag: 'div',
		cls: 'spinner',
		id:  'spinner',
		html: 'cоединение'
		});
		el.setLeft(document.documentElement.clientWidth/2);
		el.setTop(document.documentElement.clientHeight/2.5);
	};
	this.hideSpinner =  function(){
		Ext.fly('spinner').remove();
	};

	this.makeFrmWindow = function(resp)
	{	
		if(this.frm == true){
			return;
		}
		var dh = Ext.DomHelper; 
		var spec ={
		id:'frmwrap',
		tag:'div',
		cls:'frmwrap'
		};
		var newel = dh.append(document.body,spec);

		newel.innerHTML = resp;
		this.frm = true;	
		Ext.each(Ext.query(".closebt"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
				  var el1 = Ext.fly('frmwrap');
				  el1.remove();
				  this.frm = false;
				},
				scope: this
			})
		},this);
		
		Ext.each(Ext.query(".sbbt"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.handleSubmit();
				},
				scope: this
			})
		}, this);
	};

	this.authism = function()
	{
		window.location="";
	}
}

Ext.onReady(function(){
	FRONTLOADER.load('/js/ux/alertbox/js/Ext.ux.AlertBox.js','alertbox');
	var guestbook = new ui.guestbook();
	guestbook.collectButtons();
});
