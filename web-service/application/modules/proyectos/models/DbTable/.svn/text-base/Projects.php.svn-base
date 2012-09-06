<?php

class Proyectos_Model_DbTable_Projects extends Zend_Db_Table_Abstract
{

    protected $_name = 'projects';
    
    // Guarda un proyecto
    public function almacenarProyecto($nombre, $idUsuario) {

        $data=array('project_name'=>$nombre,
                    'user_id'=>$idUsuario,);

        $this->_db->insert($this->_name, $data);
    }

    // Devuelve la lista de proyectos de un usuario
    public function getListaProyectosPorIdUsuario($idUsuario) {

        $select=$this->select();
        $select->from($this->_name)->where("user_id = ?", $idUsuario);
        
        $rows = $this->fetchAll($select);
        
        return $rows;
        
    }

    // Devuelve el id del proyecto según su nombre
    public function getIdProyectoPorNombre($nombreProyecto){

        $select=$this->select();
        $select->from($this->_name,'project_id')->where("project_name = ?", $nombreProyecto);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $projectId = $row['project_id'];

        return $projectId;

    }

    // Devuelve el proyecto según su id
    public function getProyectoPorId($idProyecto){

        $select=$this->select();
        $select->from($this->_name)->where("project_id = ?", $idProyecto);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);

        return $row;
        
    }

    // Devuelve el número de proyectos
    public function getNumProyectosPorIdUsuario($idUsuario){

        $select=$this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"))->where("user_id = ?", $idUsuario);

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];
    }

    // Comprueba si existe un determinado proyecto buscando por nombre
    public function existeProyecto($nombre) {

        $select=$this->select();
        $select->from($this->_name)->where("project_name = ?", $nombre);

        $rows = $this->fetchAll($select);
        $count=$rows->count();

        if($count>0){
            return true;
        }else{
            return false;
        }

    }

    // Elimina un proyecto
    public function eliminarProyecto($idProyecto){

        $this->_db->delete($this->_name, 'project_id = '.$idProyecto);

    }

}
