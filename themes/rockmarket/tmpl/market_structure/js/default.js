function changeFormAction(id, action)
{
	var form = document.getElementById(id);
	form.action = action;
}

function changeFormPage( form, input )
{
	var form = document.getElementById(id);
	var inputField = document.getElementById(input);
	alert ( inputField );
	form.action = 'page_'.inputField.value;
}

function confirmLink( message )
{
	if ( true != confirm( message ))
	{
		return false;
	}
	else return true;
}

function openImage(src, w, h, title)
{
	var x = (self.screen.width - w) / 2;
	if (x < 0) { x = 0; }
 	var y = (self.screen.height - h) / 2;
 	if (y < 0) { y = 0; }
 	var myWindow = window.open("", "full", 'left=' + x + ',' + 'top=' + y + ',toolbar=no,menubar=no,resizable=no,status=no,titlebar=no,width=' + w +',height=' + h);
	myWindow.document.write('<html><head><title>' + title + '</title></head><body style="padding: 0; margin: 0;"><img src="' + src + '" width="' + w + '" height="' + h + '" alt="' + title + '" title="' + title + '" style="margin: 0;"></body></html>');
	return false;
}

function number_format(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';

	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

// Nifty Corners
function NiftyCheck()
{
if(!document.getElementById || !document.createElement)
    return(false);
var b=navigator.userAgent.toLowerCase();
if(b.indexOf("msie 5")>0 && b.indexOf("opera")==-1)
    return(false);
return(true);
}

function Rounded(selector,bk,color,size){
var i;
var v=getElementsBySelector(selector);
var l=v.length;
for(i=0;i<l;i++){
    var elem = v[i];
    var pElem = elem.parentElement;
    var outerDiv = document.createElement("div");
	elem = pElem.replaceChild( outerDiv, elem );
    outerDiv.appendChild(elem);

    AddTop(outerDiv,bk,color,size);
    AddBottom(outerDiv,bk,color,size);
    }
}

function RoundedTop(selector,bk,color,size){
var i;
var v=getElementsBySelector(selector);
for(i=0;i<v.length;i++)
    AddTop(v[i],bk,color,size);
}

function RoundedBottom(selector,bk,color,size){
var i;
var v=getElementsBySelector(selector);
for(i=0;i<v.length;i++)
    AddBottom(v[i],bk,color,size);
}

function AddTop(el,bk,color,size){
var i;
var d=document.createElement("b");
var cn="r";
var lim=4;
if(size && size=="small"){ cn="rs"; lim=2}
d.className="rtop";
d.style.backgroundColor=bk;
for(i=1;i<=lim;i++){
    var x=document.createElement("b");
    x.className=cn + i;
    x.style.backgroundColor=color;
    d.appendChild(x);
    }
el.insertBefore(d,el.firstChild);
}

function AddBottom(el,bk,color,size){
var i;
var d=document.createElement("b");
var cn="r";
var lim=4;
if(size && size=="small"){ cn="rs"; lim=2}
d.className="rbottom";
d.style.backgroundColor=bk;
for(i=lim;i>0;i--){
    var x=document.createElement("b");
    x.className=cn + i;
    x.style.backgroundColor=color;
    d.appendChild(x);
    }
el.appendChild(d,el.firstChild);
}

function getElementsBySelector(selector){
	return $$( selector );
}

function setRounded( selector, forecolor, backcolor )
{
	if(!NiftyCheck()) return;

	if(!selector) selector = 'div.odd';
	if(!backcolor) backcolor = 'transparent';
	if(!forecolor) forecolor = '#f0f0f0';

	Rounded( selector, backcolor, forecolor );
}

// / Nifty Corners

function stripe()
{
	var trs = $$('table.striped tr');
	var l = trs.length;
	if (l <= 0) return;
	var odd = 1;
	for (i = 0; i < l; i++)
	{
		if (odd) trs[i].addClassName('odd');
		odd = 1 - odd;
	}
}