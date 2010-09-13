
Ext.namespace("ui.guestbook");
ui.guestbook = function(conf){

	this.collectButtons = function(){
		var el = Ext.get('guestbook_btn');
		el.on('click', function(e,t) {
		if (Ext.get('gb_author_name').getValue() != '' && Ext.get('gb_author_location').getValue() != '' && Ext.get('gb_record').getValue() != ''){
				var req = 'gb_author_email=' + Ext.get('gb_author_email').getValue() + '&gb_author_name=' + Ext.get('gb_author_name').getValue() + '&gb_author_location=' + Ext.get('gb_author_location').getValue() + '&gb_record=' + Ext.get('gb_record').getValue();
				Ext.Ajax.request({
					url: '/ui/guestbook/save_record.html?' + req,
					form: 'guestbook_form',
					success: function(response, opts) {
						reset_fields();
					},
					failure: function(response, opts) {
						console.log('server-side failure with status code ' + response.status);
					}
				});
		}else{
			var els = Ext.select('.req');
			els.each(function(el){
				var prt = el.parent();
				if(el.getValue() == ''){
					prt.addClass('error');
					//	alert(el.getvalue());
					error = true;
				}else{
					prt.removeClass('error');
				}
			});
		}
		// e is a normalized event object (Ext.EventObject)
		// t the target that was clicked, this is an Ext.Element.
		// this also points to t.			
		});

	}

	var reset_fields  = function reset_fields(){
		var els = Ext.select('.input');
		els.each(function(el){
			el.dom.value='';
		});

		var els = Ext.select('.textarea');
		els.each(function(el){
			el.dom.value='';
		});
	}
}


Ext.onReady(function(){
	var guestbook = new ui.guestbook();
	guestbook.collectButtons();
});






