Ext.BLANK_IMAGE_URL = '/images/s.gif';
function classExists(c){
	if (typeof(c) == "string")
		return eval('typeof('+c+') == "function" && typeof('+c+'.prototype) == "object" ? true : false');
	else
		return typeof(c) == "function" && typeof(c.prototype) == "object" ? true : false;
}
function showError(err, title){
	Ext.Msg.show({ title: (title || 'Ошибка'), msg: err, icon: Ext.Msg.WARNING, buttons: Ext.Msg.OK})
}
function showInfo(msg, title){
	Ext.Msg.show({ title: (title || 'Сообщение'), msg: msg, icon: Ext.Msg.INFO, buttons: Ext.Msg.OK})
}
function showHelp(name){
	Ext.Ajax.request({
		url: 'di/help/get.do',
		params: {_sname: name},
		callback: function(options, success, response){
			var d = Ext.util.JSON.decode(response.responseText);
			if (success && d.success && !Ext.isEmpty(d.data)){
				var w = new Ext.Window({
					title: d.data.title,
					resizable: true,
					minimazable: true,
					width: 640,
					height: 480,
					html: d.data.description
				});
				w.show();
			}else
				showError(d.errors || 'Не удалось найти страницу помощи.');
		}
	});
}
