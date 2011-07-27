<?php

class Proyectos_Model_DbTable_Versions extends Zend_Db_Table_Abstract
{

    protected $_name = 'versions';


    public function almacenarVersion($nombre, $idUsuario, $idProyecto) {

        $data=array('version_name'=>$nombre,
                    'user_id'=>$idUsuario,
                    'project_id'=>$idProyecto,);

        $this->_db->insert($this->_name, $data);
    }

    public function getListaVersionesProyectoPorId($idProyecto) {

        $select=$this->select();
        $select->from($this->_name)->where("project_id = ?", $idProyecto);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    public function existeVersion($nombre, $idProyecto) {

        $select=$this->select();
        $select->from($this->_name)
                ->where("version_name = ?", $nombre)
                ->where("project_id = ?", $idProyecto);

        $rows = $this->fetchAll($select);
        $count=$rows->count();

        if($count>0){
            return true;
        }else{
            return false;
        }

    }

}
