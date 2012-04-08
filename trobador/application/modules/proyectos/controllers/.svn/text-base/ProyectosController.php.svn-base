<?php

class Proyectos_ProyectosController extends Zend_Controller_Action
{

    protected $_tablaProyectos = null;
    protected $_tablaVersiones = null;

    public function init()
    {
        $this->_tablaProyectos= new Proyectos_Model_DbTable_Projects();
        $this->_tablaVersiones= new Proyectos_Model_DbTable_Versions();
    }

    // Formulario que permite crear un nuevo proyecto
    public function crearproyectoAction()
    {
        $this->view->headTitle("Novo proxecto");
        $mensaje="";
        
        $formularioNuevoProyecto = new Proyectos_Form_Nuevoproyecto();
        $formularioNuevoProyecto->setAction('crearproyecto');
        $formularioNuevoProyecto->setMethod('post');

        //Si se reciben datos por post
        if($this->getRequest()->isPost()){
            //Si los datos recibidos son válidos
            if($formularioNuevoProyecto->isValid($_POST)){

                $data=$formularioNuevoProyecto->getValues();

                if($this->_tablaProyectos->existeProyecto($data['nombreProyecto'])==false){

                    $auth=Zend_Auth::getInstance();
                    $userData = $auth->getStorage()->read();
                    $this->_tablaProyectos->almacenarProyecto($data['nombreProyecto'], $userData['user_id']);

                    $idProyecto=$this->_tablaProyectos->getIdProyectoPorNombre($data['nombreProyecto']);

                    $this->_tablaVersiones->almacenarVersion($data['nombreVersion'], $userData['user_id'], $idProyecto);

                    $mensaje = "Proxecto creado";

                }else{
                    $mensaje = "O proxecto xa existe";
                }
            }
        }
        
        $this->view->form=$formularioNuevoProyecto;
        $this->view->mensaje=$mensaje;
        
    }

    // Permite ver la lista de proyectos de un usuario
    public function verproyectosAction()
    {
        $this->view->headTitle("Listado de proxectos");
        $mensaje="";

        $auth=Zend_Auth::getInstance();
        $userData = $auth->getStorage()->read();

        $numProyectos=$this->_tablaProyectos->getNumProyectosPorIdUsuario($userData['user_id']);
        if($numProyectos > 0) {
            $misProyectos=$this->_tablaProyectos->getListaProyectosPorIdUsuario($userData['user_id']);
            $this->view->proyectos=$misProyectos;
        } else {
            $mensaje="Non ten ningún proxecto";
        }
        
        $this->view->mensaje=$mensaje;
        
    }

    // Permite ver las características de un proyecto determinado
    public function verproyectoAction()
    {
        $mensaje="";
        // Si se reciben datos por get
        if($this->getRequest()->isGet()){

            $idProyecto=$this->getRequest()->getParam("idProyecto");
            $proyecto=$this->_tablaProyectos->getProyectoPorId($idProyecto);
            // Título pestaña navegador
            $this->view->headTitle($proyecto['project_name']);

            if($this->_tablaVersiones->getNumVersionesPorId($proyecto['project_id']) > 0) {
                $versionesProyecto=$this->_tablaVersiones->getListaVersionesProyectoPorId($proyecto['project_id']);
                $this->view->versiones=$versionesProyecto;
            } else {
                $mensaje="O proxecto non ten versións";
            }  
        }
        // Se asignan los datos del proyecto a la vista
        $this->view->proyecto=$proyecto;
        $this->view->mensaje=$mensaje;
    }

    // Elimina el proyecto seleccionado
    public function eliminarproyectoAction()
    {
        $this->view->headTitle("Eliminar proxecto");

        $mensaje="";
        // Si se reciben datos por get
        if($this->getRequest()->isGet()){

            $idProyecto=$this->getRequest()->getParam("idProyecto");
            // Al borrar un proyecto elimino sus versiones @todo (revisar)
            $this->_tablaVersiones->eliminarVersiones($idProyecto);
            $this->_tablaProyectos->eliminarProyecto($idProyecto);
            $mensaje="Proxecto e versións eliminados con éxito";

        }
        $this->view->mensaje=$mensaje;
    }


}


