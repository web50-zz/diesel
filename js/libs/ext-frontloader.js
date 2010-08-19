var front = function(config){
	this.load = function(caller,id){
		var j = document.createElement('script');
		j.id = id;
		j.src = caller;
		j.type = 'text/javascript';
		document.getElementsByTagName('head')[0].appendChild(j);
	}
}
Ext.onReady(function(){
	FRONTLOADER = new front();
});
