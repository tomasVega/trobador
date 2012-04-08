<?php

class Proyectos_Model_DbTable_Translationunits extends Zend_Db_Table_Abstract
{

    protected $_name = 'translation_units';

    public function almacenarTranslationUnit($cadenaOriginal, $idiomaCadenaOriginal, $cadenaTraducida, $idiomaCadenaTraducida, $idVersion, $idUsuario, $comentario) {

        $data=array('original_unit'=>$cadenaOriginal,
                    'original_unit_language'=>$idiomaCadenaOriginal,
                    'translated_unit'=>$cadenaTraducida,
                    'translated_unit_language'=>$idiomaCadenaTraducida,
                    'version_id'=>$idVersion,
                    'user_id' =>$idUsuario,
                    'comment' => $comentario,
        );

        $this->_db->insert($this->_name, $data);
    }
    
    public function getResultadosBusquedaIdiomas($cadenaEntrada, $idiomaOrigen, $idiomaDestino){

        $select = $this->select();
        $select->from($this->_name)
                ->where("MATCH (original_unit) AGAINST (?)", $cadenaEntrada);
        
        if($idiomaOrigen != null){
            $select->where("original_unit_language = ?", $idiomaOrigen);
        }
        
        if($idiomaDestino != null){
            $select->where("translated_unit_language = ?", $idiomaDestino);
        }       

        $rows = $this->fetchAll($select);
        if($rows->count() > 0){
            return $rows;
        }else{
            return null;
        }

    }

    public function getResultadosBusqueda($cadenaEntrada){

        $select = $this->select();
        $select->from($this->_name)->where("MATCH (original_unit) AGAINST (?)", $cadenaEntrada);

        $rows = $this->fetchAll($select);
        if($rows->count() > 0){
            return $rows;
        }else{
            return null;
        }

    }

    public function getResultadosBusquedaLimit($cadenaEntrada, $proyecto, $version){

        $select = $this->select();
        $select->from($this->_name)
                ->where("MATCH (original_unit) AGAINST (?)", $cadenaEntrada);
        
        if($proyecto != null && $version != null && $proyecto != '' && $version != '') {
            
            $tablaProyectos = new Proyectos_Model_DbTable_Projects();
            $tablaVersiones = new Proyectos_Model_DbTable_Versions();
            
            $idProyecto = $tablaProyectos->getIdProyectoPorNombre($proyecto);
            
            if($idProyecto != null) {
                if($tablaVersiones->existeVersion($version, $idProyecto)){
                    $idVersion = $tablaVersiones->getIdVersionProyectoPorNombre($version, $idProyecto);
                    $select->where("version_id = ?", $idVersion);
                    $select->limit(10, 0);
                } else {
                    $select->limit(5, 0);
                } 
            } else {
                $select->limit(5, 0);
            }
        } else {
            $select->limit(5, 0);
        }
        
        //$select->limit(5, 0);

        $rows = $this->fetchAll($select);
        if($rows->count() > 0){
            return $rows;
        }else{
            return null;
        }

    }
    

    public function getTranslationUnitsPorIdVersion($idVersion){

        $select = $this->select();
        $select->from($this->_name)->where("version_id = ?", $idVersion);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    // Elimina una versión
    public function eliminarCadenasVersion($idVersion){

        $this->_db->delete($this->_name, 'version_id = '.$idVersion);

    }
    
}

