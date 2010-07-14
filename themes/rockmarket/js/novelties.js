var noveltiesBlockRight = {
	config: {
		margin: 20,
		speed: 40
	},

	container: '',
	roller: '',
	step: -1,
	diff: 0,
	top: 0,
	interval: 0,
	paused: false,

	init: function() {
		this.container = $('novelties_block');

		this.roller = this.container.firstDescendant();
//		this.roller.addClassName = 'roller';

		this.roller.onmouseover = function() {
			noveltiesBlockRight.pause(true);
		};

		this.roller.onmouseout = function() {
			noveltiesBlockRight.pause(false);
		};

		Position.absolutize( this.roller );
		Position.relativize( this.roller );

		this.setInterval();

		this.top = parseInt( this.substr( this.roller.style.top, 0, -2) );

		this.run();
	},

	setInterval: function() {
		this.interval = this.roller.getHeight() - this.container.getHeight();
	},

	run: function() {
		if ( this.interval <= 0 ) return;
		interval = setInterval( this.doit, this.config.speed );
	},

	doit: function() {
		return noveltiesBlockRight.walk();
	},

	walk: function() {
		if (this.paused) return false;

		if ( this.diff >= (this.interval + this.config.margin) && this.step < 0) this.moveDown();
		if ( this.diff <= (0 - this.config.margin) && this.step > 0) this.moveTop();

		this.top += this.step;
		this.diff += -1 * this.step;
		this.roller.style.top = this.top + 'px';
	},

	pause: function( pause ) {
		if ('undefined' != pause) this.paused = pause;
		return this.paused;
	},

	moveDown: function() {
		this.step = Math.abs( this.step );
	},

	moveTop: function() {
		this.step = -1 * Math.abs( this.step );
	},

	substr: function( str, begin, end ) {
		if (end < 0) end = str.length + end;
		return str.substr( begin, end );
	}

};

document.observe('dom:loaded', function() { noveltiesBlockRight.init(); } );