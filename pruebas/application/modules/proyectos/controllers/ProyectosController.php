<?php

class Proyectos_ProyectosController extends Zend_Controller_Action
{

    protected $_tablaProyectos;
    protected $_tablaVersiones;

    public function init()
    {
        $this->_tablaProyectos= new Proyectos_Model_DbTable_Projects();
        $this->_tablaVersiones= new Proyectos_Model_DbTable_Versions();
    }

    public function crearproyectoAction()
    {
        $this->view->headTitle("Novo proxecto");
        
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

                    echo "proyecto creado";

                }else{
                    echo "el proyecto ya existe";
                }
            }
        }
        
        $this->view->form=$formularioNuevoProyecto;
        
    }

    public function verproyectosAction()
    {

        $this->view->headTitle("Lista de proxectos");

        $auth=Zend_Auth::getInstance();
        $userData = $auth->getStorage()->read();
        $misProyectos=$this->_tablaProyectos->getListaProyectosPorIdUsuario($userData['user_id']);


        $this->view->proyectos=$misProyectos;
        
    }

    public function crearversionAction()
    {

        $this->view->headTitle("Nova version");
        
        $formularioNuevaVersion = new Proyectos_Form_Nuevaversion();
        $formularioNuevaVersion->setAction('crearversion');
        $formularioNuevaVersion->setMethod('post');

        //Si se reciben datos por post
        if($this->getRequest()->isPost()){
            //Si los datos recibidos son válidos
            if($formularioNuevaVersion->isValid($_POST)){

                $data=$formularioNuevaVersion->getValues();
                

                if($this->_tablaVersiones->existeVersion($data['nombreVersion'], $data['proyecto'])==false){

                    $auth=Zend_Auth::getInstance();
                    $userData = $auth->getStorage()->read();
                    $this->_tablaVersiones->almacenarVersion($data['nombreVersion'], $userData['user_id'], $data['proyecto']);

                    echo "version creada";

                }else{
                    echo "la version ya existe";
                }
            }
        }

        $this->view->form=$formularioNuevaVersion;
    }

    public function verproyectoAction()
    {

        // Si se reciben datos por get
        if($this->getRequest()->isGet()){

            $idProyecto=$this->getRequest()->getParam("idProyecto");
            $proyecto=$this->_tablaProyectos->getProyectoPorId($idProyecto);
            // Título pestaña navegador
            $this->view->headTitle($proyecto['project_name']);

            $versionesProyecto=$this->_tablaVersiones->getListaVersionesProyectoPorId($proyecto['project_id']);

            

            // Se asignan los datos del proyecto a la vista
            $this->view->proyecto=$proyecto;
            $this->view->versiones=$versionesProyecto;
            
        }

    }


}
