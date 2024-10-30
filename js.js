function cbads_addListener(obj,type,listener){if (obj.addEventListener){obj.addEventListener(type,listener,false);return true;}else if(obj.attachEvent){obj.attachEvent("on"+type,listener);return true;}return false;}
cbads_addListener(window,"DOMContentLoaded",function(){cbads_show();})
cbads_addListener(window,"resize",function(){cbads_show();})
cbads_addListener(window,"scroll",function(){cbads_show();})
function cbads_isVisible(elem){
	var coords=cbads_getCoords(elem);
	coords.bottom=coords.top+elem.offsetHeight;
	var windowTop=(window.pageYOffset || document.documentElement.scrollTop)-100;
	var windowBottom=windowTop+document.documentElement.clientHeight+300;
	return (coords.top<windowBottom && coords.bottom>windowTop);
}
function cbads_getCoords(elem){
	var box=elem.getBoundingClientRect();
	var body=document.body;
	var docElem=document.documentElement;
	var scrollTop=window.pageYOffset || docElem.scrollTop || body.scrollTop;
	var scrollLeft=window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
	var clientTop=docElem.clientTop || body.clientTop || 0;
	var clientLeft=docElem.clientLeft || body.clientLeft || 0;
	return {top: Math.round(box.top+scrollTop-clientTop),left: Math.round(box.left+scrollLeft-clientLeft)};
}
var cbads_t=0;
function cbads_show2(){
	var tgs=document.getElementsByTagName("iframe");cbads_t=0;
	for(var i=0;i<tgs.length;i++){tgt=tgs[i];var src1=tgt.getAttribute("data-src");if(src1!=undefined && src1!="" && cbads_isVisible(tgt)){tgt.src=src1;tgt.setAttribute("data-src","");}}
}
function cbads_show(){if(cbads_t==0){cbads_t=setTimeout("cbads_show2()",200)}}
