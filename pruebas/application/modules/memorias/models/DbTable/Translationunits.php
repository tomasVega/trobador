<?php

class Memorias_Model_DbTable_Translationunits extends Zend_Db_Table_Abstract
{

    protected $_name = 'translation_units';


    public function almacenarTranslationUnit($cadenaOriginal, $idiomaCadenaOriginal, $cadenaTraducida, $idiomaCadenaTraducida, $idTmx) {

        $data=array('original_unit'=>$cadenaOriginal,
                    'original_unit_language'=>$idiomaCadenaOriginal,
                    'translated_unit'=>$cadenaTraducida,
                    'translated_unit_language'=>$idiomaCadenaTraducida,
                    'tmx_id'=>$idTmx,
        );

        $this->_db->insert($this->_name, $data);
    }

    public function getResultadosBusqueda($cadenaEntrada){

        $select=$this->select();
        $select->from($this->_name)->where("MATCH (original_unit, translated_unit) AGAINST (?)", $cadenaEntrada);

        $rows = $this->fetchAll($select);
        if($rows->count() > 0){
            $row=$rows->getRow(0);
            $resul = $row['translated_unit'];
        }else{
            return null;
        }
        
        return $resul;

    }

    /*
    public function getListaTmxPorIdUsuario($idUsuario) {

        $select=$this->select();
        $select->from($this->_name)->where("user_id = ?", $idUsuario);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    public function getIdTmxPorNombre($nombreTmx){

        $select=$this->select();
        $select->from($this->_name,'project_id')->where("name = ?", $nombreProyecto);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $tmxId = $row['project_id'];

        return $tmxId;

    }
*/
}

