<?php

class Administracion_Model_DbTable_Roles extends Zend_Db_Table_Abstract
{

    protected $_name = 'roles';

    public function getRoles()
    {
        $select = $this->select();
        $select->from($this->_name);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    public function getRolPorId($rolId)
    {
        $select = $this->select();
        $select->from($this->_name)->where("role_id = ?", $rolId);

        $rows = $this->fetchAll($select);
        $row = $rows->getRow(0);

        return $row;

    }

    public function guardarRol($nombreRol, $descripcion)
    {
        $data = array('role_name' => $nombreRol,
                    'role_description' => $descripcion);

        $this->_db->insert($this->_name, $data);

    }

    public function existeRol($nombreRol)
    {
        $select = $this->select();
        $select->from($this->_name)->where("role_name = ?", $nombreRol);

        $rows = $this->fetchAll($select);
        $count = $rows->count();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }

    }

    // Devuelve el número de roles
    public function getNumRoles()
    {
        $select = $this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"));

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];
    }

    // Devuelve la lista de roles total
    public function getListaRoles()
    {
        $select = $this->select();
        $select->from($this->_name);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    // Elimina un rol
    public function eliminarRol($idRol)
    {
        $this->_db->delete($this->_name, 'role_id = '.$idRol);

    }

    public function getRoleNamePorId ($idRol)
    {
        $select = $this->select();
        $select->from($this->_name)->where("role_id = ?", $idRol);

        $nombreRol = $this->fetchRow($select);

        return $nombreRol['role_name'];

    }

}
