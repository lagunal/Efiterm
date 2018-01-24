var testObj;

function setOpacity(value){
	testObj.style.opacity = value/10;
	testObj.style.filter = 'alpha(opacity=' + value*10 + ')';
}

function ocultarFondo(){
	div = document.getElementById('divFondo');
	
	var altura = document.body.scrollHeight;
	var anchura = document.body.scrollWidth;
	div.style.height = altura+"px";
	div.style.width = anchura+"px";

	div.style.opacity = 0;
	div.style.filter = 'alpha(opacity=0)';

	div.style.display = 'block';
	
	initFade();
}

function initFade(){
	testObj = document.getElementById('divFondo');

	for(var i=0;i<7;i++)
		setTimeout('setOpacity('+i+')',100*i);
	return false;
}