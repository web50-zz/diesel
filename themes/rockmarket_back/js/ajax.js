function ajax_update(url, container, options) {
    // todo
    var check = 0;
    for (var i in options) { check++ };

    if (check == 0) options = {asynchronous:true, evalScripts:true};
	new Ajax.Updater(container, url, options);
}

function ajax_request(url, options) {
    var check = 0;
    for (var i in options) { check++ };

    if (check == 0) options = {asynchronous:true, evalScripts:true};
	new Ajax.Request(url, options);
}