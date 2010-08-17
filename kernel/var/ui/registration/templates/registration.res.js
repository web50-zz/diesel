Ext.onReady(function(){
Ext.EventManager.on('submbutt','click',handleSubmit);
});


function handleSubmit(){
	Ext.each(Ext.query(".req",Ext.fly('.regform')), function(item, index, allItems){
		el = Ext.get(item);
		if(el.getValue() == '')
		{
			var elt = Ext.fly(el.getAttribute('fldttlid'));
			el.setStyle({'background-color':'#FAA'});
			elt.setStyle({'color':'#F00'});
		}
		else
		{
			var elt = Ext.fly(el.getAttribute('fldttlid'));
			el.setStyle({'background-color':null});
			elt.setStyle({'color':null});
		}
	}, this);


Ext.Ajax.request({
   url: '/ui/registration/register.do',
   form: 'regform',
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
