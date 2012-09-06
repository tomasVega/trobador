<?php

class Proyectos_Model_DbTable_Usersprojects extends Zend_Db_Table_Abstract
{

    protected $_name = 'users_projects';

    // Asigna un proyecto a un usuario
    public function setProyectoUsuario($idUsuario, $idProyecto) {

        $data = array('user_id' => $idUsuario,
                    'project_id' => $idProyecto);

        $this->_db->insert($this->_name, $data);

    }
    
    
    public function getNumProyectosUsuario($idUsuario){

        $select = $this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"))->where("user_id = ?", $idUsuario);

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];
    }
    
    
    public function getProyectosPorId($idUsuario) {

        $select = $this->select();
        $select->from($this->_name)->where('user_id = ?', $idUsuario);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    public function getProyectosNoAsignadosPorId($idUsuario) {

        $sql = 'SELECT project_id FROM projects where project_id NOT IN (
	select project_id from users_projects where user_id='.$idUsuario.');';

        $rows = $this->_db->fetchAll($sql);

        return $rows;

    }

    //Desvincula los usuarios de un proyecto
    public function eliminarPorIdProyecto($idProyecto){


        $this->_db->delete($this->_name, 'project_id = '.$idProyecto);

    }

    public function eliminarPorIdUsuario($idUsuario) {

        $this->_db->delete($this->_name, 'user_id = '.$idUsuario);

    }

    public function eliminarPorIdProyectoIdUsuario($idProyecto, $idUsuario) {

        $this->_db->delete($this->_name, array('project_id = ?' =>$idProyecto, 'user_id = ?' => $idUsuario));

    }



}


