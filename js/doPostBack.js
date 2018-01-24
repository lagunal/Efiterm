function doPostBack(form) {
	form = document.getElementById(form);
	
	form.submit();
}

function doPostBackValor(form, control, valor) {
	form = document.getElementById(form);
	control = document.getElementById(control);

	if(control)
		control.value = valor;
	form.submit();
}

function eliminarCampo(control, valor, form, controlform, valorform) {
	if(confirm("¿Realmente desea eliminar el registro?")){
		establecerValor(control, valor);
		doPostBackValor(form, controlform, valorform);
	}
}

function establecerValor(control, valor) {
	control = document.getElementById(control);
	
	control.value = valor;
}