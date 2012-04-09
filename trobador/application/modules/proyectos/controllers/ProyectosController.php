<?php

class Proyectos_ProyectosController extends Zend_Controller_Action {

    protected $_tablaProyectos = null;
    protected $_tablaVersiones = null;
    
    protected $_tablaUsuariosProyectos = null;
    protected $_acl = null;
    protected $_userData = null;

    public function init() {
        
        $this->_tablaProyectos = new Proyectos_Model_DbTable_Projects();
        $this->_tablaVersiones = new Proyectos_Model_DbTable_Versions();
        $this->_tablaUsuariosProyectos = new Proyectos_Model_DbTable_Usersprojects();
        $this->_acl = new Usuarios_Model_Acl();
        
        $auth = Zend_Auth::getInstance();
        $this->_userData = $auth->getStorage()->read();
        
    }

    // Formulario que permite crear un nuevo proyecto
    public function crearproyectoAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'proyectos', 'crear')){
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m006'));

            $formularioNuevoProyecto = new Proyectos_Form_Nuevoproyecto();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioNuevoProyecto->isValid($_POST)){

                    $data=$formularioNuevoProyecto->getValues();

                    if($this->_tablaProyectos->existeProyecto($data['nombreProyecto'])==false){

                        $auth = Zend_Auth::getInstance();
                        $userData = $auth->getStorage()->read();
                        $this->_tablaProyectos->almacenarProyecto($data['nombreProyecto'], $userData['user_id']);

                        $idProyecto = $this->_tablaProyectos->getIdProyectoPorNombre($data['nombreProyecto']);

                        $this->_tablaVersiones->almacenarVersion($data['nombreVersion'], $userData['user_id'], $idProyecto);

                        $this->_tablaUsuariosProyectos->setProyectoUsuario($userData['user_id'], $idProyecto);

                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m040');
                        $this->_helper->FlashMessenger($mensaje);

                    }else{
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err010');
                        $this->_helper->FlashMessenger($mensaje);
                    }
                }
            }

            $this->view->form = $formularioNuevoProyecto;
            //$this->view->mensaje = $mensaje;
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }

    // Permite ver la lista de proyectos de un usuario
    public function verproyectosAction() {
        
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'proyectos', 'ver')){

            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m041'));
            $mensaje = "";

            $auth = Zend_Auth::getInstance();
            $userData = $auth->getStorage()->read();

            $numProyectos = $this->_tablaUsuariosProyectos->getNumProyectosUsuario($userData['user_id']);
            if($numProyectos > 0) {
                $misProyectos = $this->_tablaProyectos->getListaProyectosPorIdUsuario($userData['user_id']);
                $this->view->proyectos = $misProyectos;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err011');
            }

            $this->view->mensaje = $mensaje;

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }

    public function verproyectostotalesAction() {

        $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m042'));
        $mensaje = "";

        $numProyectos = $this->_tablaProyectos->getNumProyectosTotal();
        if($numProyectos > 0) {
            $proyectos = $this->_tablaProyectos->getListaProyectos();
            $this->view->proyectos = $proyectos;
        } else {
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err012');
        }

        $this->view->mensaje = $mensaje;

    }

    // Permite ver las características de un proyecto determinado
    public function verproyectoAction() {
        
        $mensaje = "";
        // Si se reciben datos por get
        if($this->getRequest()->isGet() && $this->getRequest()->getParam("idProyecto") != null){
            
            $idProyecto = $this->getRequest()->getParam("idProyecto");
            $proyecto = $this->_tablaProyectos->getProyectoPorId($idProyecto);
            $this->view->proyecto = $proyecto;
            
            // Título pestaña navegador
            $this->view->headTitle($proyecto['project_name']);
            

            if($this->_tablaVersiones->getNumVersionesPorId($proyecto['project_id']) > 0) {
                $versionesProyecto = $this->_tablaVersiones->getListaVersionesProyectoPorId($proyecto['project_id']);
                $this->view->versiones=$versionesProyecto;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err013');
            }  
        } else {
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err009');
        }
        // Se asignan los datos del proyecto a la vista
        
        $this->view->mensaje = $mensaje;
    }

    // Elimina el proyecto seleccionado
    public function eliminarproyectoAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'proyectos', 'eliminar')){
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m043'));
            
            // Si se reciben datos por get
            if($this->getRequest()->isGet()){

                $idProyecto = $this->getRequest()->getParam("idProyecto");
                // Al borrar un proyecto elimino sus versionesy las cadenas de estas
                // y las referencias en la tabla users_projects
                $this->_tablaUsuariosProyectos->eliminarPorIdProyecto($idProyecto);
                $this->_tablaVersiones->eliminarVersiones($idProyecto);
                $this->_tablaProyectos->eliminarProyecto($idProyecto);
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m044');
                $this->_helper->FlashMessenger($mensaje);

            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err014');
                $this->_helper->FlashMessenger($mensaje);
            }

            $this->_redirect('/proyectos/proyectos/verproyectos');
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }


}


