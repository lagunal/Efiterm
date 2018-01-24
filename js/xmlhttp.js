function Ajax(){
	this.http = null;
	
	this.iniciarHTTPObject = iniciarHTTPObject;
	
	this.working = false;

	this.enviar = enviar;
	
	this.setWorkingOn = setWorkingOn;
	
	this.setWorkingOff = setWorkingOff;
	
	this.isWorking = isWorking;
}

function iniciarHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  //return xmlhttp;
  this.http = xmlhttp;
  this.working = false;
}
function setWorkingOn(){
	document.body.style.cursor = 'wait';
	this.working = true;
}
function setWorkingOff(){
	document.body.style.cursor = 'default';
	this.working = false;
}
function isWorking(){
	return this.working;
}

function enviar(metodo, url, datos, funcion){
	var postData = null;
	if (this.http==null){
		alert('Objeto Ajax no iniciado.');
		return false;
	}

	if (!this.isWorking() && this.http) {
		metodo = metodo.toUpperCase();
		if (metodo=="GET"){
			var uriGet = url;
			this.http.open("GET", uriGet , true);
		}else{
			this.http.open("POST", url , true);
			try
			{
				this.http.setRequestHeader("Method", "POST "+ url + " HTTP/1.1");
				this.http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				postData = datos;
			}
			catch(e)
			{
				alert("Su navegador no soporta requerimientos asíncronos usando POST.");
				return false;
			}
		}
		
		if(funcion){
			this.http.onreadystatechange = funcion; //handleHttpResponse;
			this.setWorkingOn();
		}else{
			this.setWorkingOff();
		}
		
		this.http.send(postData);
	}
}