function ver(control){
	control = parent.document.getElementById(control);
	control = control.toString();
	eval("parent." + control.substr(11));
}
