<?php
class directorioActivo {
    public $nomina = "pdvsacom-ad-payrollclass";
    public $email = "mail";
    public $id = "uid";
    public $area = "pdvsacom-ad-area";
    public $telefono = "ipphone";
    public $compania = "company";
    public $nombre = "displayname";
    public $organization = "pdvsacom-ad-organization";
    public $localidad = "pdvsacom-ad-physicallocality";
    public $cedula = "pdvsacom-ad-cedula";
    public $oficina = "physicaldeliveryofficename";
    public $supervisor = "pdvsacom-ad-functionalsupervisor";
    public $cargo = "title";
    public $celular = "mobile";
    public $nacionalidad = "pdvsacom-ad-nationid";
    public $tipoempleado = "employeetype";
    public $primernombre = "givenname";
    public $segundonombre = "middlename";
    public $apellido = "sn";
    public $nombrered = "name";
    public $fechaingreso = "pdvsacom-ad-hiredate";
    public $fechaegreso = "pdvsacom-ad-firedate";
    public $numeroempleado = "employeeid";
    private $criterioBusqueda = "(|(UserAccountControl=512)(UserAccountControl=66048)(UserAccountControl=544)(UserAccountControl=66080))";
    
    private function conectar(){
        return @ldap_connect("ldap://CCSCAM17.pdvsa.com", "389");
    }

    private function obtenerResultados($datos){
        $c = count($datos) - 1;
        $res = array();
        
        for($i = 0; $i < $c; $i++)
            $res[] = @array($this->id => $datos[$i][$this->id][0], $this->fechaingreso => $datos[$i][$this->fechaingreso][0]);
        
        return $res;
    }

    public function validarCuenta($usuario, $clave){
        $con = $this->conectar();
        
        if($con)
            if(trim($usuario)!="" AND trim($clave)!=""){
                if(@ldap_bind($con, trim($usuario) . "@pdvsa.com", trim($clave))){
                    ldap_unbind($con);
                    return true;
                }
            }

        return false;
    }

    public function obtenerUsuariosID($usuario){
        $nombres = split(" ", $usuario);
        $c = count($nombres);
        $criterio = "(&";
        
        for($i = 0; $i  < $c; $i ++)
            $criterio .= "(displayname=*$nombres[$i]*)";
        
        $criterio .= ")";
         
        $con = $this->conectar();
        $res = ldap_search($con, "OU=Usuarios, DC=pdvsa, DC=com", "(&$this->criterioBusqueda(|(sAMAccountName=$usuario*)$criterio))");
        $info = ldap_get_entries($con, $res);
        
        return $this->obtenerResultados($info);
    }

    public function obtenerUsuarioCedula($cedula){
        if(isset($cedula)){
            $c = count($cedula);
            $criterio = "(&$this->criterioBusqueda(|";
            
            for($i = 0; $i  < $c; $i ++)
                $criterio .= "(pdvsacom-ad-cedula=$cedula[$i])";
            
            $criterio .= "))";
            
            $con = $this->conectar();
            $res = ldap_search($con, "OU=Usuarios, DC=pdvsa, DC=com", $criterio);
            $info = ldap_get_entries($con, $res);
            
            return $this->obtenerResultados($info);
        }
    }

    public function obtenerUsuarioSinCedula($cedula){
        if(isset($cedula)){
            $c = count($cedula);
            $criterio = "(&$this->criterioBusqueda(!(|";
            
            for($i = 0; $i  < $c; $i ++)
                $criterio .= "(pdvsacom-ad-cedula=$cedula[$i])";
            
            $criterio .= ")))";
            
            $con = $this->conectar();
            $res = ldap_search($con, "OU=Usuarios, DC=pdvsa, DC=com", $criterio);
            $info = ldap_get_entries($con, $res);
            
            return $this->obtenerResultados($info);
        }
    }

    public function obtenerUsuarioID($id){
        if(isset($id)){
            $c = count($id);
            $criterio = "(&$this->criterioBusqueda(|";
            
            for ($i = 0; $i  < $c; $i ++)
                $criterio .= "(sAMAccountName=$id[$i])";
            
            $criterio .= "))";

            $con = $this->conectar();
            $res = ldap_search($con, "OU=Usuarios, DC=pdvsa, DC=com", $criterio);
            $info = ldap_get_entries($con, $res);
            
            return $this->obtenerResultados($info);
        }
    }

    public function obtenerUsuarioNombre($nombre, $apellido){
        if(isset($nombre)){
            $c = count($nombre);

            $criterio = "(&$this->criterioBusqueda(|";
            for ($i = 0; $i < $c; $i ++)
                $criterio .= "(&(displayname=$apellido[$i]*)(givenname=$nombre[$i]*))";
            $criterio .= "))";

            $con = $this->conectar();
            $res = ldap_search($con, "OU=Usuarios, DC=pdvsa, DC=com", $criterio);
            $info = ldap_get_entries($con, $res);
            
            return $this->obtenerResultados($info);
        }
    }

    
    public function obtenerValor($usuario, $campos){
        $con = $this->conectar();
        $res = @ldap_search($con, "OU=Usuarios, DC=pdvsa, DC=com", "sAMAccountName=" . $usuario);
        $info = @ldap_get_entries($con, $res);
        
        $val = array();

        if(isset($campos) && $campos!=""){
            for($i = 0; $i < count($campos); $i++)
                $val[] = @$info[0][$campos[$i]][0];
        }else{
            $val = $info[0];
        }

        return $val;
    }
}
?>