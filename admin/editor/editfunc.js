var gSetColorType = ""; 
var gIsIE = document.all; 
var gIEVer = fGetIEVer();
var gLoaded = false;
var ev = null;

function fGetEv(e){
	ev = e;
}
function fGetIEVer(){
	var iVerNo = 0;
	var sVer = navigator.userAgent;
	if(sVer.indexOf("MSIE")>-1){
		var sVerNo = sVer.split(";")[1];
		sVerNo = sVerNo.replace("MSIE","");
		iVerNo = parseFloat(sVerNo);
	}
	return iVerNo;
}
function fSetEditable(){
	var f = window.frames["HtmlEditor"];
	f.document.designMode="on";
	if(!gIsIE)
		f.document.execCommand("useCSS",false, true);
}
function fSetFrmClick(){
	var f = window.frames["HtmlEditor"];
	f.document.onmousemove = function(){
		window.onblur();
	}
	f.document.onclick = function(){
		fHideMenu();
	}
}
function fSetHtmlContent(){
	var html = oLinkField.value	
	if (html)
	{
		var header = "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"editorArea.css\" /></head><body MONOSPACE>" ;
		var f = window.frames["HtmlEditor"];
		f.document.open();
		f.document.write(header + oLinkField.value);
		f.document.close();
	}
}
function fSetColor(){
	var dvForeColor =document.getElementById("dvForeColor");
	if(dvForeColor.getElementsByTagName("TABLE").length == 1){
		dvForeColor.innerHTML = drawCube() + dvForeColor.innerHTML;
	}
}
var oURL = location.href;
var offset = oURL.lastIndexOf("ID=");
if (offset == -1)
{
	alert("请传入调用参数ID，即隐藏的内容表单项ID！");
} else {
	offset = offset + 3
}
var sLinkFieldName = oURL.substring(offset)
var oLinkField = parent.document.getElementsByName(sLinkFieldName)[0];
var oForm = oLinkField.form ;

/*
var addEvent=(function(){
	if(document.addEventListener)
	{
		return function(el,type,fn)
		{
			if(el.length)
			{
				for(var i=0;i<el.length;i++)
				{
					addEvent(el[i],type,fn);
				}
			}
			else
			{
				el.addEventListener(type,fn,false);
			}
		};
	}
	else
	{
		return function(el,type,fn)
		{
			if(el.length)
			{
				for(var i=0;i<el.length;i++)
				{
					addEvent(el[i],type,fn);
				}
			}
			else
			{
				el.attachEvent('on'+type,function(){
					return fn.call(el,window.event);
				});
			}
		};
	}
})();
*/
window.onload = function(){
	try{
		gLoaded = true;
		fSetEditable();
		fSetFrmClick();
		fSetHtmlContent();
		setLinkedField()
	}catch(e){
		// window.location.reload();
	}
}
window.onblur =function(){
	var dvForeColor =document.getElementById("dvForeColor");
	var dvPortrait =document.getElementById("dvPortrait");
	dvForeColor.style.display = "none";
	dvPortrait.style.display = "none";
	if(!gIsIE || 1==1){
		fHideMenu();
	}
}
window.onerror = function(){return true;};
document.onmousemove = function(e){
	if(gIsIE) var el = event.srcElement;
	else var el = e.target;
	var tdView = document.getElementById("tdView");
	var tdColorCode = document.getElementById("tdColorCode");
	var dvForeColor =document.getElementById("dvForeColor");
	var dvPortrait =document.getElementById("dvPortrait");
	var fontsize =document.getElementById("fontsize");
	var fontface =document.getElementById("fontface");
	if(el.tagName == "IMG"){
		try{
			if(fCheckIfColorBoard(el)){
				tdView.bgColor = el.parentNode.bgColor;
				tdColorCode.innerHTML = el.parentNode.bgColor
			}
		}catch(e){}
	}else{
		dvForeColor.style.display = "none";
		if(!fCheckIfPortraitBoard(el)) dvPortrait.style.display = "none";
		if(!fCheckIfFontFace(el)) fontface.style.display = "none";
		if(!fCheckIfFontSize(el)) fontsize.style.display = "none";
	}
}
document.onclick = function(e){
	if(gIsIE) var el = event.srcElement;
	else var el = e.target;
	var dvForeColor =document.getElementById("dvForeColor");
	var dvPortrait =document.getElementById("dvPortrait");
	if(el.id == "imgFontface" || el.id == "imgFontsize"){
	
	}else{
		fHideMenu();
	}
	if(el.tagName == "IMG"){
		try{
			if(fCheckIfColorBoard(el)){
				format(gSetColorType, el.parentNode.bgColor);
				dvForeColor.style.display = "none";
				return;
			}
		}catch(e){}
		try{
			if(fCheckIfPortraitBoard(el)){
				format("InsertImage", el.src);
				dvPortrait.style.display = "none";
				return;
			}
		}catch(e){}
	}
}

function format(type, para){
	var f = window.frames["HtmlEditor"];
	var sAlert = "";
	if(!gIsIE){
		switch(type){
			case "Cut":
				sAlert = "你的浏览器安全设置不允许编辑器自动执行剪切操作,请使用键盘快捷键(Ctrl+X)来完成";
				break;
			case "Copy":
				sAlert = "你的浏览器安全设置不允许编辑器自动执行拷贝操作,请使用键盘快捷键(Ctrl+C)来完成";
				break;
			case "Paste":
				sAlert = "你的浏览器安全设置不允许编辑器自动执行粘贴操作,请使用键盘快捷键(Ctrl+V)来完成";
				break;
		}
	}
	if(sAlert != ""){
		alert(sAlert);
		return;
	}
	f.focus();
	if(!para)
		if(gIsIE)
			f.document.execCommand(type)
		else
			f.document.execCommand(type,false,false)
	else
		f.document.execCommand(type,false,para)
	f.focus();
}
function setMode(bStatus){
	var sourceEditor = document.getElementById("sourceEditor");
	var HtmlEditor = document.getElementById("HtmlEditor");
	var divEditor = document.getElementById("divEditor");
	var f = window.frames["HtmlEditor"];
	var body = f.document.getElementsByTagName("BODY")[0];
	if(sourceEditor.style.display){
		sourceEditor.style.display = "";
		HtmlEditor.style.height = "0px";
		divEditor.style.height = "0px";
		sourceEditor.value = body.innerHTML;
	}else{
		sourceEditor.style.display = "none";
		if(gIsIE){
			HtmlEditor.style.height = "286px";
			divEditor.style.height = "286px";
		}else{
			HtmlEditor.style.height = "283px";
			divEditor.style.height = "283px";
		}
		body.innerHTML = sourceEditor.value;
		//fSetEditable();
	}
}
function foreColor(e) {
	var sColor = fDisplayColorBoard(e);
	gSetColorType = "foreColor";
	if(gIsIE) format(gSetColorType, sColor);
}
function backColor(e){
	var sColor = fDisplayColorBoard(e);
	if(gIsIE)
		gSetColorType = "backcolor";
	else
		gSetColorType = "backcolor";
	if(gIsIE) format(gSetColorType, sColor);
}
function fDisplayColorBoard(e){
	if(gIsIE){
		var e = window.event;
	}
	if(gIEVer<=5.01 && gIsIE){
		var arr = showModalDialog("ColorSelect.htm", "", "font-family:Verdana; font-size:12; status:no; dialogWidth:21em; dialogHeight:21em");
		if (arr != null) return arr;
		return;
	}
	var dvForeColor =document.getElementById("dvForeColor");
	fSetColor();
	var iX = e.clientX;
	var iY = e.clientY;
	dvForeColor.style.display = "";
	dvForeColor.style.left = (iX-140) + "px";
	dvForeColor.style.top = (iY-10) + "px";
	return true;
}
function createLink() {
	var sURL=window.prompt("Enter link location (e.g. http://bbs.gope.cn/):", "http://");
	if ((sURL!=null) && (sURL!="http://")){
		format("CreateLink", sURL);
	}
}
function createImg()	{
	var sPhoto=prompt("请输入图片位置:", "http://");
	if ((sPhoto!=null) && (sPhoto!="http://")){
		format("InsertImage", sPhoto);
	}
}
function fCheckIfColorBoard(obj){
	if(obj.parentNode){
		if(obj.parentNode.id == "dvForeColor") return true;
		else return fCheckIfColorBoard(obj.parentNode);
	}else{setMode
		return false;
	}
}
function fCheckIfPortraitBoard(obj){
	if(obj.parentNode){
		if(obj.parentNode.id == "dvPortrait") return true;
		else return fCheckIfPortraitBoard(obj.parentNode);
	}else{
		return false;
	}
}
function fCheckIfFontFace(obj){
	if(obj.parentNode){
		if(obj.parentNode.id == "fontface") return true;
		else return fCheckIfFontFace(obj.parentNode);
	}else{
		return false;
	}
}
function fCheckIfFontSize(obj){
	if(obj.parentNode){
		if(obj.parentNode.id == "fontsize") return true;
		else return fCheckIfFontSize(obj.parentNode);
	}else{
		return false;
	}
}

/*function fImgOver(el){
	if(el.tagName == "IMG"){
		el.style.borderRight="1px #cccccc solid";
		el.style.borderBottom="1px #cccccc solid";
	}
}
function fImgMoveOut(el){
	if(el.tagName == "IMG"){
		el.style.borderRight="1px #F3F8FC solid";
		el.style.borderBottom="1px #F3F8FC solid";
	}
}*/

String.prototype.trim = function(){
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
function fSetBorderMouseOver(obj) {
	obj.style.borderRight="1px solid #aaa";
	obj.style.borderBottom="1px solid #aaa";
	obj.style.borderTop="1px solid #fff";
	obj.style.borderLeft="1px solid #fff";
} 

function fSetBorderMouseOut(obj) {
	obj.style.border="none";
}

function fSetBorderMouseDown(obj) {
	obj.style.borderRight="1px #F3F8FC solid";
	obj.style.borderBottom="1px #F3F8FC solid";
	obj.style.borderTop="1px #cccccc solid";
	obj.style.borderLeft="1px #cccccc solid";
}

function fDisplayElement(element,displayValue) {
	if(gIEVer<=5.01 && gIsIE){
		if(element == "fontface"){
			var sReturnValue = showModalDialog("FontFaceSelect.htm","", "font-family:Verdana; font-size:12; status:no; unadorned:yes; scroll:no; resizable:yes;dialogWidth:112px; dialogHeight:271px");;
			format("fontname",sReturnValue);
		}else{
			var sReturnValue = showModalDialog("FontSizeSelect.htm","", "font-family:Verdana; font-size:12; status:no; unadorned:yes; scroll:no; resizable:yes;dialogWidth:130px; dialogHeight:250px");;
			format("fontsize",sReturnValue);
		}
		return;
	}
	if(element == "fontface"){
		var fontsize = document.getElementById("fontsize");
		fontsize.style.display = "none";
	}else if(element == "fontsize"){
		var fontface = document.getElementById("fontface");
		fontface.style.display = "none";
	}
	if ( typeof element == "string" )
		element = document.getElementById(element);
	if (element == null) return;
	element.style.display = displayValue;
	if(gIsIE){
		var e = event;
	}else{
		var e = ev;
	}
	var iX = e.clientX;
	var iY = e.clientY;
	element.style.display = "";
	element.style.left = (iX-40) + "px";
	element.style.top = (iY-10) + "px";
	return true;
}

function fHideMenu(){
	var fontface = document.getElementById("fontface");
	var fontsize = document.getElementById("fontsize");
	fontface.style.display = "none";
	fontsize.style.display = "none";
}

// 设置所属表单的提交或reset事件
function setLinkedField() {
	
	if (! oLinkField) return ;
	var oForm = oLinkField.form ;
	if (!oForm) return ;
	
	//oForm.attachEvent("onsubmit", AttachSubmit);
	//alert(oLinkField.value);
	//oForm.attachEvent("onreset", AttachReset);
	//oForm.addEventListener("submit",AttachSubmit,false);
	//oForm.addEventListener("reset",AttachReset,false); 
	addEvent(oForm,"submit",AttachSubmit);
	addEvent(oForm,"reset",AttachReset); 
}

function addEvent(elm, evType, fn) {
	if (elm.addEventListener) {
		elm.addEventListener(evType, fn, false);//DOM2.0
		return true;
	}
	else if (elm.attachEvent) {
		var r = elm.attachEvent('on' + evType, fn);//IE5+
		return r;
	}
	else {
		elm['on' + evType] = fn;//DOM 0
	}
} 

// 提交表单
function AttachSubmit() {
	var sourceEditor = document.getElementById("sourceEditor");
	if(sourceEditor.style.display){
		var html = window.frames["HtmlEditor"].document.getElementsByTagName("BODY")[0].innerHTML;
	}else{
		var html = sourceEditor.value;
	}
	if ( (html.toLowerCase() == "<p>&nbsp;</p>") || (html.toLowerCase() == "<p></p>") ){
		html = "";
	}
	
	oLinkField.value = html;
} 
// 附加Reset事件
function AttachReset() {
	window.frames["HtmlEditor"].document.getElementsByTagName("BODY")[0].innerHTML = oLinkField.value;
}


