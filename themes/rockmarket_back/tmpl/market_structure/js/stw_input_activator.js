var stwInputActivator = {
	config: {
		tip: 'свой вариант'
	},

	set: function( input ) {
		var trigger = this.triggerId(input);

		if ('checkbox' == $( trigger ).type)
		{
			Event.observe( trigger, 'change', function() {stwInputActivator.toggle( input );} );
			$( trigger ).onclick = function() { $( trigger ).blur(); };
			this.toggle( input );
		}
		else
		{
			try {
				var radios = $( trigger ).parentElement.parentElement.select('input[type="radio"]');
				for (var i = 0; i < radios.length; i++)
				{
					Event.observe( radios[i], 'change', function() {stwInputActivator.toggle( input );} );
				}
				this.toggle( input );
			} catch (err) {
				// IE6 not working, to do or not to do?
			}
		}
	},

	toggle: function(input) {
		var inp = $( input );
		if ( this.state( this.triggerId(input) ) )
		{
			if (this.config.tip == inp.value) inp.value = '';
			inp.enable();
		}
		else
		{
			inp.disable();
			if ('' == inp.value) inp.value = this.config.tip;
		}
	},

	triggerId: function(input) {
		return input + '_trigger';
	},

	state: function(trigger_id) {
		return ( $F( trigger_id ) == 'other' );
	}
}