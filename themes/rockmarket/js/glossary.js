var glossary = {
	counter: 0,

	show: function( link ) {
		var did = 'glossary_termin_' + this.counter++;
		new Insertion.After( link, '<div id="' + did + '" class="termin" title="Нажмите, чтобы скрыть"><img src="/images/spinner.gif"></div>' );
		new Ajax.Updater( did, link.href + 'ajax/' );
		$(did).onclick = $(did).toggle;
		link.onclick = function () {
			$(did).toggle();
			return false;
		};
	}
}