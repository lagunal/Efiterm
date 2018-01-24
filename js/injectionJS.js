window.onload=function(e){
	cargarFunciones(e);
}

function cargarFunciones(e){
	form = document.forms[0];

	c = form.elements.length;
	activos = true;
			
	for(var i=0; i<c; i++){
		var e = form.elements[i];
		
		//if((e.type=='text' || e.type=='password') && (!e.disabled)){
		if((e.type=='text') && (!e.disabled)){
			//e.onkeypress = function (e){
			e.onkeydown = function (e){
								var e=window.event || e
								
								//alert(e.keyCode);
								
								ret = true;
								//var caracteres = new Array('<', '>', ';', '&', '!', '?', '#', '%', '\"', '\'', '/', '\\', '$');
								var caracteres = new Array(226, 16, 18, 188, 219, 219);
								
								//var letra = String.fromCharCode(e.keyCode);
								//posicion = caracteres.IndexOf(e.keyCode);
								//posicion = Array.IndexOf(caracteres, e.keyCode);
								
								c = caracteres.length;
								posicion = -1;
								
								for(i=0; i<c; i++)
									if(caracteres[i]==e.keyCode)
										posicion = 1;
								
								if(posicion>=0) ret = false;
								
								return ret;
							};
		}
	}
}