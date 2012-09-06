<?php

class Proyectos_VersionesController extends Zend_Controller_Action
{
    protected $_tablaVersiones = null;
    protected $_acl = null;
    protected $_userData = null;

    public function init()
    {
        $this->_tablaVersiones= new Proyectos_Model_DbTable_Versions();
        $this->_acl = new Usuarios_Model_Acl();

        $auth = Zend_Auth::getInstance();
        $this->_userData = $auth->getStorage()->read();

    }

    // Formulario que permite crear una versión dentro de un proyecto
    public function crearversionAction()
    {
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'versiones', 'crear')) {
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m007'));

            $formularioNuevaVersion = new Proyectos_Form_Nuevaversion();

            //Si se reciben datos por post
            if ($this->getRequest()->isPost()) {
                //Si los datos recibidos son válidos
                if ($formularioNuevaVersion->isValid($_POST)) {

                    $data=$formularioNuevaVersion->getValues();

                    if ($this->_tablaVersiones->existeVersion($data['nombreVersion'], $data['proyecto'])==false) {

                        $auth=Zend_Auth::getInstance();
                        $userData = $auth->getStorage()->read();
                        $this->_tablaVersiones->almacenarVersion($data['nombreVersion'], $userData['user_id'], $data['proyecto']);

                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m045');
                        $this->_helper->FlashMessenger($mensaje);

                    } else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err015');
                        $this->_helper->FlashMessenger($mensaje);
                    }
                }
            }

            $this->view->form=$formularioNuevaVersion;
            //$this->view->mensaje=$mensaje;

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    // Borra una versión
    public function eliminarversionAction()
    {
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'versiones', 'eliminar')) {
            $mensaje="";
            // Si se reciben datos por get
            if ($this->getRequest()->isGet()) {

                $idVersion=$this->getRequest()->getParam("idVersion");

                $tablaCadenas = new Proyectos_Model_DbTable_Translationunits();

                $tablaCadenas->eliminarCadenasVersion($idVersion);
                $this->_tablaVersiones->eliminarVersion($idVersion);
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m046');

            }
            //$this->view->mensaje=$mensaje;
            $this->_helper->FlashMessenger($mensaje);
            $this->_redirect('/proyectos/proyectos/verproyectos');

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    // Borra las versiones de un proyecto
    public function eliminarversionesAction()
    {
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'versiones', 'eliminar')) {
            if ($this->getRequest()->isGet()) {
                $idProyecto=$this->getRequest()->getParam("idProyecto");

                $this->_tablaVersiones->eliminarVersiones($idProyecto);
            }
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

}
