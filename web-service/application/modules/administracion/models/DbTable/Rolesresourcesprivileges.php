<?php

class Administracion_Model_DbTable_Rolesresourcesprivileges extends Zend_Db_Table_Abstract
{

    protected $_name = 'roles_resources_privileges';

    public function getRolesRecursosPrivilegios()
    {
        $select = $this->select();
        $select->from($this->_name);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    // Elimina por rol
    public function eliminarRolRecursosPrivilegiosPorIdRol($idRol)
    {
        $this->_db->delete($this->_name, 'role_id = '.$idRol);

    }

    // Elimina por recurso
    public function eliminarRolRecursosPrivilegiosPorIdRecurso($idRecurso)
    {
        $this->_db->delete($this->_name, 'resource_id = '.$idRecurso);

    }

    // Elimina por privilegio
    public function eliminarRolRecursosPrivilegiosPorIdPrivilegio($idPrivilegio)
    {
        $this->_db->delete($this->_name, 'privilege_id = '.$idPrivilegio);

    }

    public function existeRolRecursoPrivilegio($idRol, $idRecurso, $idPrivilegio)
    {
        $select = $this->select();
        $select->from($this->_name)->where("role_id = ?", $idRol)
                ->where("resource_id = ?", $idRecurso)
                ->where("privilege_id = ?", $idPrivilegio);

        $rows = $this->fetchAll($select);
        $count = $rows->count();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function guardarRolRecursoPrivilegio($idRol, $idRecurso, $idPrivilegio)
    {
        $data = array('role_id' => $idRol,
                    'resource_id' => $idRecurso,
                    'privilege_id' => $idPrivilegio);

        $this->_db->insert($this->_name, $data);

    }

    public function eliminarRolRecursoPrivilegio($idRol, $idRecurso, $idPrivilegio)
    {
        $this->_db->delete($this->_name, 'role_id = '.$idRol.' AND resource_id = '.$idRecurso.' AND privilege_id = '.$idPrivilegio);

    }

}
