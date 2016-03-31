//loading
var divObj=document.createElement("div");
var loadingText = '<div id="loding" style="width:100%; max-width: 640px; z-index: 99; position: fixed; top:50%; margin-top: -18px; text-align: center; ">';
	loadingText += '<img src ="'+getUrl()+'template/style/img/loading.gif" width="28"/></div>';
	divObj.innerHTML=loadingText;
function showLoading(){
	if(document.getElementById('loding')){
		document.getElementById('loding').style.display = 'block';
	}else{
		var first=document.body.firstChild; //得到第一个元素
		document.body.insertBefore(divObj,first); //在第原来的第一个元素之前插入
	}
}
function hideLoading(){
	if(document.getElementById('loding')){
		document.getElementById('loding').style.display = 'none';
	}
}
