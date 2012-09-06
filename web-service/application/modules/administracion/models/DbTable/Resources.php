<?php

class Administracion_Model_DbTable_Resources extends Zend_Db_Table_Abstract
{

    protected $_name = 'resources';

    public function getRecursos()
    {
        $select = $this->select();
        $select->from($this->_name);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    public function getRecursoPorId($recursoId)
    {
        $select = $this->select();
        $select->from($this->_name)->where("resource_id = ?", $recursoId);

        $rows = $this->fetchAll($select);
        $row = $rows->getRow(0);

        return $row;

    }

    public function guardarRecurso($nombreRecurso, $descripcion)
    {
        $data = array('resource_name' => $nombreRecurso,
                    'resource_description' => $descripcion);

        $this->_db->insert($this->_name, $data);

    }

    public function existeRecurso($nombreRecurso)
    {
        $select = $this->select();
        $select->from($this->_name)->where("resource_name = ?", $nombreRecurso);

        $rows = $this->fetchAll($select);
        $count = $rows->count();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }

    }

    // Devuelve el número de recursos
    public function getNumRecursos()
    {
        $select = $this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"));

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];
    }

    // Elimina un recurso
    public function eliminarRecurso($idRecurso)
    {
        $this->_db->delete($this->_name, 'resource_id = '.$idRecurso);

    }

}
