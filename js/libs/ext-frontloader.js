var front = function(config){
	this.load = function(caller,id){
		var d = document;
		if(!d.getElementById(id))
		{
			var j = d.createElement('script');
			var h = d.getElementsByTagName('head')[0];
			j.id = id;
			j.src = caller;
			j.type = 'text/javascript';
			h.appendChild(j);
		}
	}
/* CSS loader for future use
var $ = document; 
var cssId = 'myCss';  
if (!$.getElementById(cssId))
{
var head  = $.getElementsByTagName('head')[0];
var link  = $.createElement('link');
link.id   = cssId;
link.rel  = 'stylesheet';
link.type = 'text/css';
link.href = 'http://website.com/css/stylesheet.css';
link.media = 'all';
head.appendChild(link);
}
*/

}
Ext.onReady(function(){
	FRONTLOADER = new front();
});
