<?php
include "postgresqlHelper.php";
include_once "../config.php";
  
class clad{
	private $pgHelper;

	function clad($conString=""){
        if($conString==""){
            $conf = new config();
            $conString = $conf->queryString;
        }
        
        $this->pgHelper = new postgresqlHelper($conString);
    }

    public function setConectionString($conString){
        $this->conectionString=$conString;
    }

//SESIONES
    public function consultarBloqueoUsuario($id){
    	return $this->pgHelper->obtenerEscalar("SELECT count(*) FROM t_usuarios SET WHERE id='$id' AND bloqueado=1;");
    }

    public function bloquearUsuario($id, $bloqueo){
    	return $this->pgHelper->actualizarDatos("UPDATE t_usuarios SET bloqueado=$bloqueo WHERE id='$id';");
    }

    public function crearSesion($id, $nombre){
    	if($this->consultarSesion($id)!=0){
    		$this->pgHelper->actualizarDatos("DELETE FROM t_sesion WHERE id='$id';");
    	}

       	return $this->pgHelper->actualizarDatos("INSERT INTO t_sesion (id, nombre_sesion) VALUES ('$id', '$nombre');");
    }

    public function consultarSesion($id){
        $query = "SELECT count(*) FROM t_sesion WHERE id='$id';";

        return $this->pgHelper->obtenerEscalar($query);
    }

    public function consultarSesionNombre($id, $nombre){
        $query = "SELECT count(*) FROM t_sesion WHERE id='$id' AND nombre_sesion='$nombre';";

        return $this->pgHelper->obtenerEscalar($query);
    }

//Logging de errores
    public function agregarError($num_err, $cadena_err, $archivo_err, $linea_err, $errcontext){
        return $this->pgHelper->actualizarDatos("SELECT func_insert_error($1, $2, $3, $4, $5);", array($num_err, $cadena_err, $archivo_err, $linea_err, $errcontext));
    }

    
//Opciones
    public function obtenerConfiguracion($id){
        $query = "SELECT DISTINCT cp.codigo_usuario, cp.actualizar, cp.rangografico, ce.codigo_equipo, mostrar FROM t_configuracion_personal cp, t_configuracion_equipos ce WHERE UPPER(cp.codigo_usuario)=UPPER(ce.codigo_usuario) AND UPPER(cp.codigo_usuario)=UPPER('$id');";

        return $this->pgHelper->obtenerDatos($query);
    }
    
    public function guardarOpciones($id, $opciones){
        $equipos = array();
        $query = "DELETE FROM t_configuracion_personal WHERE codigo_usuario = '$id';";
        $query .= "DELETE FROM t_configuracion_equipos WHERE codigo_usuario = '$id';";
        
        $this->pgHelper->obtenerEscalar($query);
        
        $query = "";
        
        if(!isset($opciones["cmbActualizar"]))
            $opciones["cmbActualizar"] = 0;
            
        foreach($opciones as $llave => $valor)
            if(substr($llave,0,8) == "chkHorno")
                $equipos[] = array($id, substr($llave,8));
                    
        $query = "INSERT INTO t_configuracion_personal (codigo_usuario, actualizar, rangografico) VALUES ('$id', " . $opciones["cmbActualizar"] . ", " . $opciones["cmbRango"] . ");";
        
        $c = count($equipos);

        for($i=0;$i<$c;$i++)
            $query .= "INSERT INTO t_configuracion_equipos (codigo_usuario, codigo_equipo, mostrar) VALUES ('" . $equipos[$i][0] . "', " . $equipos[$i][1] . ", true);";
        
        return $this->pgHelper->obtenerDatos($query);
    }

//Datos XML
    public function obtenerEquiposTodos(){
        //$query = "SELECT * FROM t_datos_equipos ORDER BY equipo, indicador DESC, planta, codigo";
        $query = "SELECT eq.codigo_equipo, codigo, indicador, planta, equipo, re.nombre, re.siglas FROM t_equipos eq, t_plantas pl, t_tipos_equipos te, t_refinerias re WHERE eq.codigo_planta=pl.codigo_planta AND te.codigo_tipo=eq.codigo_tipo AND pl.codigo_refineria=re.codigo_refineria ORDER BY siglas, equipo, indicador DESC, codigo;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerTipoEquipo($codigo){
        $query = "SELECT eq.codigo_equipo, re.siglas, eq.codigo, eq.indicador, eq.eficiencia, pl.planta FROM t_equipos eq, t_plantas pl, t_refinerias re WHERE eq.codigo_tipo=$codigo AND eq.codigo_planta = pl.codigo_planta AND pl.codigo_refineria=re.codigo_refineria ORDER BY indicador DESC, pl.planta, codigo;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerEquipos($tipo, $refineira){
        $query = "SELECT eq.codigo_equipo, pl.planta || ', ' || codigo AS equipo " .
                "FROM t_equipos eq, t_refinerias re, t_plantas pl " .
                "WHERE eq.codigo_planta=pl.codigo_planta AND pl.codigo_refineria=re.codigo_refineria AND re.codigo_refineria=$refineira AND eq.codigo_tipo=$tipo;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerEquipo($codigo){
        $query = "SELECT eq.codigo_equipo, codigo, eficiencia, eficienciaradiaciongas, eficienciaradiacionliquido, eq.unidad, planta, re.codigo_refineria, equipo, combustible, siglas, ev.codigo_variable, ev.nombre, (SELECT unidad_gas_combustible FROM t_unidades_equipos WHERE codigo_equipo=eq.codigo_equipo) AS unidad_gas_combustible " .
                "FROM t_equipos eq, t_plantas pl, t_tipos_equipos te, t_combustibles co, t_refinerias re, t_equipo_variables ev " .
                "WHERE eq.codigo_equipo=$codigo AND eq.codigo_planta=pl.codigo_planta AND eq.codigo_tipo=te.codigo_tipo AND eq.codigo_combustible=co.codigo_combustible " .
                "AND re.codigo_refineria=pl.codigo_refineria AND eq.codigo_equipo=ev.codigo_equipo;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerTagsDescripcionEquipo($codigo){
        $query = "SELECT ev.nombre, ev.nombre || ' - ' || ' (' || td.nombre || ', ' || td.unidad || ')' AS texto " .
                "FROM t_equipos eq, t_equipo_variables ev, t_tags_descripcion td " .
                "WHERE eq.codigo_equipo=$codigo AND eq.codigo_equipo=ev.codigo_equipo AND ev.codigo_variable=td.codigo_tag;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerTagsEquipo($codigo){
        $query = "SELECT ev.codigo_variable, ev.nombre, pl.codigo_refineria, eq.codigo_tipo " .
                "FROM t_equipo_variables ev, t_tags_descripcion td, t_equipos eq, t_plantas pl " .
                "WHERE ev.codigo_equipo=$codigo AND td.codigo_tag=ev.codigo_variable AND ev.codigo_equipo=eq.codigo_equipo AND pl.codigo_planta=eq.codigo_planta;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerDataEquipos($codigo, $fecha){
        $query = "SELECT * FROM t_datos_predeterminados WHERE codigo_equipo = $codigo AND fecha = '$fecha';";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerDataCombustible($campo, $tipo){
        $query = "SELECT \"$campo\" FROM t_datos_combustible WHERE upper(\"TipoCombustible\") = upper('$tipo');";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerDataChimenea(){
        $query = "SELECT \"pesoMolecularCombustion\" FROM t_datos_combustible WHERE \"TipoCombustible\" = 'chimenea';";

        return $this->pgHelper->obtenerDatos($query);
    }


    public function obtenerAnalisisLaboratorio($refineira){
        $query = "SELECT * FROM t_analisis_refinerias ar, t_codigos_analisis ca WHERE ar.codigo_analisis = ca.codigo_analisis AND analisis<>'' AND ar.codigo_refineria=$refineira;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerTiposEquipoRefineria(){
        $query = "SELECT DISTINCT eq.codigo_tipo || ';' || re.codigo_refineria AS codigo, te.equipo || ' ' || re.siglas AS nombre " .
                "FROM t_equipos eq, t_refinerias re, t_plantas pl, t_tipos_equipos te " .
                "WHERE eq.codigo_planta=pl.codigo_planta AND pl.codigo_refineria=re.codigo_refineria AND eq.codigo_tipo=te.codigo_tipo;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerEquipoRefineria($equipo){
        $query = "SELECT re.*, eq.codigo_tipo FROM t_equipos eq, t_plantas pl, t_refinerias re WHERE eq.codigo_equipo=$equipo AND eq.codigo_planta=pl.codigo_planta AND pl.codigo_refineria=re.codigo_refineria;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerEquiposIndicador($codigos){
        $query = "SELECT eq.codigo_equipo, eq.codigo_tipo, eficiencia, re.codigo_refineria, equipo, combustible, ev.codigo_variable, ev.nombre " .
                "FROM t_equipos eq, t_plantas pl, t_tipos_equipos te, t_combustibles co, t_refinerias re, t_equipo_variables ev " .
                "WHERE eq.codigo_equipo in ($codigos) AND eq.codigo_planta=pl.codigo_planta AND eq.codigo_tipo=te.codigo_tipo AND eq.codigo_combustible=co.codigo_combustible " .
                "AND re.codigo_refineria=pl.codigo_refineria AND eq.codigo_equipo=ev.codigo_equipo;";
                
        return $this->pgHelper->obtenerDatos($query);
    }

    
//Acceso
    public function obtenerAcceso($id){
        $query = "SELECT acceso FROM t_lista_acceso WHERE codigo_usuario = '$id';";
        
        return $this->pgHelper->obtenerEscalar($query);
    }

//Visitas
    public function obtenerVisitasLog(){
        $query = "SELECT count(codigo_log) FROM t_log;";

        return $this->pgHelper->obtenerEscalar($query);
    }

    public function obtenerTotalVisitas(){
        $query = "SELECT sum(visitas) FROM t_usuario;";

        return $this->pgHelper->obtenerEscalar($query);
    }

    public function obtenerVisitas($id){
        return $this->pgHelper->obtenerEscalar("SELECT * FROM func_obtene_visitas($1) as visitas;", array($id));
    }

    public function agregarVisita($id){
        return $this->pgHelper->actualizarDatos("SELECT func_actual_visitas($1) AS visitas;", array($id));
    }

//Log
    public function obtenerLog(){
        $query = "SELECT codigo_usuario, to_char(fecha,'DD/MM/YYYY a las hh:mi:ss a.m.') as fecha_hora FROM t_log ORDER BY fecha DESC LIMIT 2000;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerLogIntervalo(){
        $query = "SELECT count(codigo_log) as visitas, to_char(fecha,'MM/YYYY') as periodo FROM t_log GROUP BY periodo ORDER BY periodo LIMIT 12;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerAutorizacion($id){
        return $this->pgHelper->obtenerEscalar("SELECT * FROM func_obtene_autorizacion($1);", array($id));
    }

    public function agregarLog($usuario){
        return $this->pgHelper->actualizarDatos("SELECT func_insert_log($1);", array($usuario));
    }

//INTEGRADOR
    public function obtenerEquiposEfiterm(){
        $query = "SELECT eq.codigo_equipo, re.siglas || ', ' || te.equipo || ' - ' || pl.planta || ', ' || codigo AS equipo FROM t_equipos eq, t_plantas pl, t_tipos_equipos te, t_refinerias re " .
                "WHERE indicador=1 AND eq.codigo_planta=pl.codigo_planta AND eq.codigo_tipo=te.codigo_tipo AND pl.codigo_refineria=re.codigo_refineria " .
                "ORDER BY indicador DESC, pl.planta, codigo;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerEquipoEfiterm($codigo){
        $query = "SELECT eq.codigo_equipo, re.siglas || ', ' || te.equipo || ' - ' || pl.planta || ', ' || codigo AS equipo FROM t_equipos eq, t_plantas pl, t_tipos_equipos te, t_refinerias re " .
                "WHERE eq.codigo_equipo=$codigo AND eq.codigo_planta=pl.codigo_planta AND eq.codigo_tipo=te.codigo_tipo AND pl.codigo_refineria=re.codigo_refineria;";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerDetallesEquipo($codigo){
        $query = "SELECT eq.codigo_equipo, codigo, eficiencia, eficienciaradiaciongas, eficienciaradiacionliquido, eq.unidad, planta, re.codigo_refineria, equipo, combustible, siglas " .
                "FROM t_equipos eq, t_plantas pl, t_tipos_equipos te, t_combustibles co, t_refinerias re " .
                "WHERE eq.codigo_equipo=$codigo AND eq.codigo_planta=pl.codigo_planta AND eq.codigo_tipo=te.codigo_tipo AND eq.codigo_combustible=co.codigo_combustible " .
                "AND re.codigo_refineria=pl.codigo_refineria;";

        return $this->pgHelper->obtenerDatos($query);
    }

//USUARIO
    public function obtenerRoles(){
        $query = "SELECT * FROM t_roles";

        return $this->pgHelper->obtenerDatos($query);
    }

    public function obtenerUsuariosRoles(){
        $query = "select * from t_usuarios us, t_roles ro WHERE us.codigo_rol = ro.codigo_rol;";

        return $this->pgHelper->obtenerDatos($query);
    }
    
    public function buscarUsuario($id){
    	$query = "SELECT count(id) FROM t_usuarios WHERE id=LOWER('$id');";
    	
    	return $this->pgHelper->obtenerEscalar($query);
    }

    public function buscarUsuarioAdministrador($id){
    	$query = "SELECT count(id) FROM t_usuarios WHERE id=LOWER('$id') AND codigo_rol=1;";
    	
    	return $this->pgHelper->obtenerEscalar($query);
    }

    public function buscarUsuariosAdministradores(){
    	$query = "SELECT count(id) FROM t_usuarios WHERE codigo_rol=1;";
    	
    	return $this->pgHelper->obtenerEscalar($query);
    }

    public function eliminarUsuarioRol($id){
    	$query = "DELETE FROM t_usuarios WHERE id=LOWER('$id');";
    	
        return $this->pgHelper->actualizarDatos($query);
    }

    public function actualizarUsuarioRol($id, $rol){
    	$id = strtolower($id);

    	$query = "";
    	
    	if($this->buscarUsuario($id)==0){
    		$query = "INSERT INTO t_usuarios (id, codigo_rol) VALUES ('$id', $rol);";
    	}else{
    		$query = "UPDATE t_usuarios SET codigo_rol=$rol WHERE id=LOWER('$id')";
    	}
    	 
        return $this->pgHelper->actualizarDatos($query);
    }

}
?>