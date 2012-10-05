<?php

class Proyectos_Model_DbTable_Versions extends Zend_Db_Table_Abstract
{

    protected $_name = 'versions';

    public function getByVersionId($versionId)
    {
        $select = $this->select();
        $select->from($this->_name)->where("version_id = ?", $versionId);

        $rows = $this->fetchAll($select);

        return $rows;
    }

    // Guarda una versión de un proyecto
    public function almacenarVersion($nombre, $idUsuario, $idProyecto)
    {
        $data = array('version_name' => $nombre,
                    'user_id' => $idUsuario,
                    'project_id' => $idProyecto,);

        $this->_db->insert($this->_name, $data);
    }

    // Devuelve las versiones asociadas a un proyecto
    public function getListaVersionesProyectoPorId($idProyecto)
    {
        $select = $this->select();
        $select->from($this->_name)->where("project_id = ?", $idProyecto);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    // Devuelve el nombre de version segun su id
    public function getNombreVersionPorId($idVersion)
    {
        $select = $this->select();
        $select->from($this->_name)->where("version_id = ?", $idVersion);

        $rows = $this->fetchAll($select);

        $row = $rows->getRow(0);
        $nombre = $row['version_name'];

        return $nombre;

    }

    // Devuelve la version asociada a un proyecto
    public function getIdVersionProyectoPorNombre($nombreVersion, $idProyecto)
    {
        $select = $this->select();
        $select->from($this->_name)
                ->where("project_id = ?", $idProyecto)
                ->where("version_name = ?", $nombreVersion);

        $rows = $this->fetchAll($select);

        $row = $rows->getRow(0);
        $versionId = $row['version_id'];

        return $versionId;

    }

    // Devuelve el número de versiones de un proyecto
    public function getNumVersionesPorId($idProyecto)
    {
        $select=$this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"))->where("project_id = ?", $idProyecto);

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];
    }

    // Comprueba si existe una determinada versión de un proyecto
    public function existeVersion($nombre, $idProyecto)
    {
        $select=$this->select();
        $select->from($this->_name)
                ->where("version_name = ?", $nombre)
                ->where("project_id = ?", $idProyecto);

        $rows = $this->fetchAll($select);
        $count=$rows->count();

        if ($count>0) {
            return true;
        } else {
            return false;
        }

    }

    // Elimina una versión
    public function eliminarVersion($idVersion)
    {
        $this->_db->delete($this->_name, 'version_id = '.$idVersion);

    }

    // Se eliminan las versiones correspodientes al proyecto y las cadenas de las versiones
    public function eliminarVersiones($idProyecto)
    {
        $tablaCadenas = new Proyectos_Model_DbTable_Translationunits();
        $versiones = $this->getListaVersionesProyectoPorId($idProyecto);

        foreach ($versiones as $version) {
            $tablaCadenas->eliminarCadenasVersion($version['version_id']);
        }

        $this->_db->delete($this->_name, 'project_id = '.$idProyecto);

    }

}
