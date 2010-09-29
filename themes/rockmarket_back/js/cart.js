var cart = {
	config: {
		div: 'cart',
		formHighlight: '#569B21',
		formBackground: '#f2f2f2'
	},

	add: function( product, count, size, form ) {
		if (undefined == count) count = 1;
		new Ajax.Updater( this.config.div, '/cart/add/ajax/', {
			parameters: { product: product, c: count, 'size': size },
			method: "POST",
			onComplete: function() {
				cart.alert();
				if (undefined != form) cart.alertForm( form );
			},
			asynchronous:true,
			evalScripts:true
		});
	},

	refresh: function() {
		new Ajax.Updater( this.config.div, '/cart/block/ajax/', {
			onComplete: cart.alert,
			asynchronous:true,
			evalScripts:true
		});
	},

	submit: function( form ) {
		var size = ((undefined === form.size) ? 0 : $F(form.size));
		this.add( form.product.value, form.c.value, size, form );
	},

	calc: function ( price, count )	{
		return this.format( parseFloat(price) * count );
	},

	calc_item: function( id, price ) {
		new Form.Element.Observer(
  			$(id),
  			0.2,
  			function(el, value){
    			$(id + '_sum').style.display="inline";
  				$(id + '_sum').innerHTML = "на " + cart.calc( price, value);
  			}
		)
	},

	format: function( summa ) {
		summa += '';
		x = summa.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1].truncate(2, '') : '';

		if (x2.length > 0 && x2.length < 3) x2 += '0';

		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	},

	alertForm: function( form ) {
		new Effect.Highlight( form, {endcolor: this.config.formHighlight, restorecolor: this.config.formHighlight});
		new Effect.Highlight( form, {startcolor:this.config.formHighlight, endcolor: this.config.formBackground, restorecolor: this.config.formBackground, duration: 1, queue: 'end'});
	},

	alert: function() {
		new Effect.Pulsate( cart.config.div, { duration: .5, pulses: 3 } );
	}
}