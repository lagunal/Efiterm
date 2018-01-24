	function fechasIniciales(cantidad){
		if(!cantidad)
			cantidad = 6;
			
		fechai = document.getElementById('txtFechaInicio');
		fechaf = document.getElementById('txtFechaFin');

		var f = new Date();

		fechaf.value = convertirFechaString(f);
		fechai.value = convertirFechaString(sumaDias(f, -cantidad));
	}
	
	function convertirFechaObjeto(fecha){
		arr = fecha.split("/");

		var f=new Date();
		
		f.setFullYear(arr[2], arr[1]-1, arr[0]);
		f.setHours(0);
		f.setMinutes(0);
		f.setSeconds(0);
		
		return f;
	}

	function convertirFechaString(fecha){
		f = '';
		ano = fecha.getYear();

		if(ano < 1000) ano = ano + 1900;

		f = fecha.getDate() + '/' + (fecha.getMonth() + 1) + '/' + ano;
		
		return f;
	}

	function sumaDias(fecha,dias){
	    return new Date(fecha.getTime() + (dias*86400000));
	}
	
	function buscarCombo(combo, valor){
		combo = document.getElementById(combo);
		c = combo.length;

		for(i=0;i<c;i++){
			if(combo.options[i].value == valor)
				return i;
		}
		return 0;
	}