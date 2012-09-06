<?php
class Usuarios_Model_Acl extends Zend_Acl
{
    public function __construct() {

        $tablaRecursos = new Administracion_Model_DbTable_Resources();
        $tablaRoles = new Administracion_Model_DbTable_Roles();
        $tablaPrivilegios = new Administracion_Model_DbTable_Privileges();
        $tablaTupla = new Administracion_Model_DbTable_Rolesresourcesprivileges();

        $this->setRoles();
        $this->setRecursos();
        $this->setPrivilegios();
        
    }


    protected function setRoles() {

        $tablaRoles = new Administracion_Model_DbTable_Roles();
        
        $roles = $tablaRoles->getRoles();
        foreach($roles as $rol){
            $this->addRole(new Zend_Acl_Role($rol['role_name']));
        }

    }

    protected function setRecursos() {

        $tablaRecursos = new Administracion_Model_DbTable_Resources();

        $recursos = $tablaRecursos->getRecursos();
        foreach($recursos as $recurso){
            $this->add(new Zend_Acl_Resource($recurso['resource_name']));
        }

    }

    protected function setPrivilegios() {

        $tablaRecursos = new Administracion_Model_DbTable_Resources();
        $tablaRoles = new Administracion_Model_DbTable_Roles();
        $tablaPrivilegios = new Administracion_Model_DbTable_Privileges();
        $tablaTupla = new Administracion_Model_DbTable_Rolesresourcesprivileges();

        $tuplas = $tablaTupla->getRolesRecursosPrivilegios();

        foreach ($tuplas as $tupla) {

            $rol = $tablaRoles->getRolPorId($tupla['role_id']);
            $recurso = $tablaRecursos->getRecursoPorId($tupla['resource_id']);
            $privilegio = $tablaPrivilegios->getPrivilegioPorId($tupla['privilege_id']);

            $this->allow($rol['role_name'], $recurso['resource_name'], $privilegio['privilege_name']);

        }

    }

    public function tienePermiso($rol, $recurso, $privilegio){

        if ($rol == null) {
            $rol = 'invitado';
        }

        if ( $this->isAllowed($rol, $recurso, $privilegio) ) {
            return true;
        } else {
            return false;
        }

    }

    public function esAdmin($rol) {

        if ($rol == 'administrador') {
            return true;
        } else {
            return false;
        }

    }

    public function esUsuarioSinRegistrar($rol){

        if ($rol == 'invitado') {
            return true;
        } else {
            return false;
        }

    }
    
}
