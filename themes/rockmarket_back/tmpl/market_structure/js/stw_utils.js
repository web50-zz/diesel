var stwUtils = {
	image: {
		setSwapping: function(image) {
			if (image.stwSwap) return;

			image.onmouseover = function() { stwUtils.image.swap(image) };
			image.onmouseout = function() { stwUtils.image.swap(image) };

//			if (window.event && image == window.event.srcElement) stwUtils.image.swap(image);
		},

		swap: function(image) {
			if (image.stwSwap)
			{
				var tmp = image.src;
				image.src = image.stwSwap;
				image.stwSwap = tmp;
				return true;
			}

			var path = image.src;
			image.stwSwap = path;
			var ext = stwUtils.file.ext( path );
			path = stwUtils.string.substr( path, 0, -ext.length);
			if ('_over' == stwUtils.string.substr(path, -5)) path = stwUtils.string.substr( path, 0, -5 );
			else path += '_over';
			image.src = path + ext;
		},

		open: function(src, w, h, title) {
			var x = (window.screen.width - w) / 2;
			if (x < 0) { x = 0; }
		 	var y = (window.screen.height - h) / 2;
		 	if (y < 0) { y = 0; }
		 	var myWindow = window.open("", "full", 'left=' + x + ',' + 'top=' + y + ',toolbar=no,menubar=no,resizable=no,status=no,titlebar=no,width=' + w +',height=' + h);
			myWindow.document.write('<html><head><title>' + title + '</title></head><body style="padding: 0; margin: 0;"><img src="' + src + '" width="' + w + '" height="' + h + '" alt="' + title + '" title="' + title + '" style="margin: 0;"></body></html>');
			return false;
		}

	},

	string: {
		substr: function(str, begin, end) {
			if (begin < 0) begin = str.length + begin;
			if (!end) end = str.length;
			if (end < 0) end = str.length + end;
			return str.substr( begin, end );
		},

		escapeRegExp: function( str ) {
			['|', '^', '*', '(', ')', '+', '.'].each( function(s) {
				str = str.replace( s, '\\' + s );
			});

			return str;
		},

		closeTags: function( str ) {
			return str;
		}

	},

	number: {
		format: function( number, delimiter ) {
			number += '';
			x = number.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var exp = /(\d+)(\d{3})/;
			while (exp.test(x1)) {
				x1 = x1.replace(exp, '$1' + delimiter + '$2');
			}
			return x1 + x2;
		},

		formatCommas: function( number ) {
			return this.format( number, ',' )
		},

		ending: function(cnt, wimed, wroded, wrodmn) {
			if (cnt > 100) cnt = ( cnt % 100 );
			if ( cnt > 20 ) cnt = parseInt( String(cnt).substr(-1) );

			switch (cnt)
			{
				case 1:
					return wimed;

				case 2:
				case 3:
				case 4:
					return wroded;

				default:
					return wrodmn;
			}
		}
	},

	form: {
		input: {
			setInnerTitle: function( input ) {
				input = $( input );
				if (!input.value)
				{
					stwUtils.form.input.toggleInnerTitle( input );
					Event.observe( input, 'focus', function() { stwUtils.form.input.toggleInnerTitle( input ); } );
					Event.observe( input, 'blur', function() { stwUtils.form.input.toggleInnerTitle( input ); } );
					Event.observe( input.up('FORM'), 'submit', function() {
						if (input.hasClassName('disabled')) input.clear();
					} );
				}
			},

			toggleInnerTitle: function( input ) {
				if (input.hasClassName('disabled'))
				{
					input.clear();
					input.removeClassName('disabled');
				}
				else
				{
					if (!input.value)
					{
						input.value = input.readAttribute('title');
						input.addClassName('disabled');
					}
				}
			}
		},

		textarea: {
			makeFlexible: function(selector) {
				var c = ('string' == typeof selector ? $$(selector) : [selector]);
				var flex = function() {
					while (this.getHeight() < this.scrollHeight) {
				    	this.writeAttribute('rows', parseInt(this.readAttribute('rows')) + 1);
				    }

				    while (parseInt(this.readAttribute('rows')) > parseInt(this.readAttribute('stwInitRows')) && this.getHeight() > (this.scrollHeight + 20)) {
				    	this.writeAttribute('rows', parseInt(this.readAttribute('rows')) - 1);
					}
				};

				c.each(function(txt) {
					txt.setStyle({'overflow': 'hidden'});
					txt.writeAttribute('stwInitRows', parseInt(txt.readAttribute('rows')));
					txt.observe('keyup', flex);
					flex.bind(txt).call();
				});
			}
		}
	},

	file: {
		ext: function(filename) {
			return stwUtils.string.substr( filename, filename.lastIndexOf('.') );
		}
	},

	window: {
		open: function(href, w, h) {
			var x = (window.screen.width - w) / 2;
			if (x < 0) { x = 0; }
		 	var y = (window.screen.height - h) / 2;
		 	if (y < 0) { y = 0; }
		 	var myWindow = window.open(href, "full", 'left=' + x + ',' + 'top=' + y + ',toolbar=no,menubar=no,resizable=no,status=no,titlebar=no,width=' + w +',height=' + h);
			return false;
		},

		openWithCode: function(code, w, h, title) {
			var x = (window.screen.width - w) / 2;
			if (x < 0) { x = 0; }
			var y = (window.screen.height - h) / 2;
			if (y < 0) { y = 0; }
			var myWindow = window.open("", "code", 'left=' + x + ',' + 'top=' + y + ',toolbar=no,menubar=no,resizable=no,status=no,titlebar=no,width=' + w +',height=' + h);
			myWindow.document.write('<html><head><title>' + title + '</title></head><body style="padding: 0; margin: 0;">' + code + '</body></html>');
			return false;
		},

		getSelection: function() {
			var txt = '';
			if (document.selection && document.selection.createRange) {
				txt = document.selection.createRange().text;
			} else if (window.getSelection) {
				txt = window.getSelection();
			} else if (document.getSelection) {
				txt = document.getSelection();
			};
			return new String( txt );
		}
	},

	sleep: function( msec ) {
		var tm = new Date();
		tm = tm.getTime();
		do {
			var d = new Date();
		} while ( d.getTime() - tm < msec );
	},

	cursor: {
		insert: function( obj, text ) {
			if(document.selection) {
				obj.focus();
				var orig = obj.value.replace(/\r\n/g, "\n");
				var range = document.selection.createRange();

				if(range.parentElement() != obj) {
					return false;
				}

				range.text = text;

				var actual = tmp = obj.value.replace(/\r\n/g, "\n");

				for(var diff = 0; diff < orig.length; diff++) {
					if(orig.charAt(diff) != actual.charAt(diff)) break;
				}

				for(var index = 0, start = 0;
					tmp.match(text)
						&& (tmp = tmp.replace(text, ""))
						&& index <= diff;
					index = start + text.length
				) {
					start = actual.indexOf(text, index);
				}
			} else if(obj.selectionStart) {
				var start = obj.selectionStart;
				var end   = obj.selectionEnd;

				obj.value = obj.value.substr(0, start)
					+ text
					+ obj.value.substr(end, obj.value.length);
			}

			if(start != null) {
				stwUtils.cursor.set(obj, start + text.length);
			} else {
				obj.value += text;
			}
		},

		set: function( obj, pos ) {
			if(obj.createTextRange) {
				var range = obj.createTextRange();
				range.move('character', pos);
				range.select();
			} else if(obj.selectionStart) {
				obj.focus();
				obj.setSelectionRange(pos, pos);
			}
		}
	},

	pseudoSelect: function(toggler, select) {
		var t = this;
		document.observe('dom:loaded', function() {
			t.toggler = $(toggler);
			t.select = $(select);

			t.toggler.setStyle({'cursor': 'hand'});
			t.select.setStyle({'cursor': 'hand'});

			var bf = function(e) {
				var id = Event.element(e).id;
				if (id != t.select.id && id != t.toggler.id) {
					t.select.hide();
					Event.stopObserving(t.toggler, 'blur', nf);
					Event.stopObserving(document.body, 'click', bf);
					t.tryBad = true;
				}
			};

			var nf = function() {
				t.select.hide();
				Event.stopObserving(document.body, 'click', bf);
			};

			t.toggler.observe('click', function() {
				t.select.toggle();
				if (t.tryBad) {
					t.tryBad =  false;
					Event.observe(document.body, 'click', bf);
				}
				if (t.tryNice) {
					t.tryNice = false;
					Event.observe(t.toggler, 'blur', nf);
				}
			});
		});
	},

	debug: {
		showSource: function( what, where ) {
			if (!where) where = 'debug';

			$( where ).update( $( what ).innerHTML.escapeHTML().gsub(/\n/, '<br><br>').gsub(/\t/, '&nbsp;&nbsp;&nbsp;&nbsp;') );
		}
	}
}

stwUtils.pseudoSelect.prototype = {
	toggler: null,
	select: null,
	tryNice: true,
	tryBad: true
}