Ext.namespace("ui.registration");

ui.registration = function(conf){

	this.collectButtons = function(){
		Ext.each(Ext.query(".submbutt"), function(item, index, allItems){
			Ext.get(item).on({
				click: function(ev, el, opt){
					this.handleSubmit();
				},
				scope: this
			})
		}, this);

	}

	this.handleSubmit = function(){
		Ext.each(Ext.query(".req",Ext.fly('.regform')), function(item, index, allItems){
			el = Ext.get(item);
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
			url: '/ui/registration/register.do',
			form: 'regform',
			scope: this,
			success: function(response, opts) {
				var obj = Ext.decode(response.responseText);
				if(obj.code == '400')
				{
					Ext.fly('errortext').dom.innerHTML = obj.error;
				}
				else
				{
					Ext.fly('errortext').dom.innerHTML = '';
				}
				if(obj.code == '200')
				{
					Ext.fly('report').dom.innerHTML = obj.report;
					this.authism();
					//window.location="?secret="+Ext.fly('passwd').getValue()+"&user="+Ext.fly('email').getValue();
				}
				else
				{
					Ext.fly('report').dom.innerHTML = '';
				}
			},
			 failure: function(response, opts) {
					 console.log(' Error ' + response.status);
			}
		});
	}
	
	this.showSpinner =  function(){
		Ext.fly('registr').insertFirst({
		tag: 'div',
		cls: 'spinner',
		id: 'spinner',
		html: 'cоединение'
		});
	}
	this.hideSpinner =  function(){
		Ext.fly('spinner').remove();
	}

	this.authism = function()
	{
		Ext.Ajax.request({
			url: '/ui/registration/register.do',
			scope:this,
			success: function(response,opts){
						window.location="";
						},
					failure: function(response,opts){
							alert('failure');
						},
					params: {user: Ext.fly('email').getValue(),secret: Ext.fly('passwd').getValue()}
				});
	}
}

Ext.onReady(function(){
	var c = new ui.registration();
	c.collectButtons();
});
