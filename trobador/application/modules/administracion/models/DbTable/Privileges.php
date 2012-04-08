<?php

class Administracion_Model_DbTable_Privileges extends Zend_Db_Table_Abstract
{

    protected $_name = 'privileges';

    public function getPrivilegios() {

        $select = $this->select();
        $select->from($this->_name);

        $rows = $this->fetchAll($select);

        return $rows;

    }


    public function getPrivilegioPorId($privilegioId) {

        $select = $this->select();
        $select->from($this->_name)->where("privilege_id = ?", $privilegioId);

        $rows = $this->fetchAll($select);
        $row = $rows->getRow(0);

        return $row;

    }

    public function guardarPrivilegio($nombrePrivilegio){

        $data = array('privilege_name' => $nombrePrivilegio);

        $this->_db->insert($this->_name, $data);

    }

    public function existePrivilegio($nombrePrivilegio){

        $select = $this->select();
        $select->from($this->_name)->where("privilege_name = ?", $nombrePrivilegio);

        $rows = $this->fetchAll($select);
        $count = $rows->count();

        if($count > 0){
            return true;
        }else{
            return false;
        }

    }

    // Devuelve el número de privilegios
    public function getNumPrivilegios(){

        $select = $this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"));

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];
    }

    // Elimina un privilegio
    public function eliminarPrivilegio($idPrivilegio){

        $this->_db->delete($this->_name, 'privilege_id = '.$idPrivilegio);

    }



}