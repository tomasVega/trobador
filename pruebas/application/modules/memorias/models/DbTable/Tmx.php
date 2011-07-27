<?php

class Memorias_Model_DbTable_Tmx extends Zend_Db_Table_Abstract
{

    protected $_name = 'tmx';
    

    public function almacenarTmx($nombre, $idUsuario, $idVersion) {

        $data=array('name'=>$nombre,
                    'user_id'=>$idUsuario,
                    'version_id'=>$idVersion,);

        $this->_db->insert($this->_name, $data);
    }

    public function getListaTmxPorIdUsuario($idUsuario) {

        $select=$this->select();
        $select->from($this->_name)->where("user_id = ?", $idUsuario);
        
        $rows = $this->fetchAll($select);
        
        return $rows;
        
    }

    public function getIdTmxPorNombre($nombreTmx){

        $select=$this->select();
        $select->from($this->_name,'tmx_id')
                ->where("name = ?", $nombreTmx)
                ->order("upload_date DESC");

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $tmxId = $row['tmx_id'];

        return $tmxId;

    }

}
