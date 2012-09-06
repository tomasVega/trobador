<?php

class Usuarios_UsuariosController extends Zend_Controller_Action
{

    protected $_flashMessenger;
    protected $_tablaUsuarios;
    protected $_tablaRoles;
    protected $_userData = null;

    public function init()
    {
        $this->_tablaUsuarios = new Usuarios_Model_DbTable_Users();
        $this->_tablaRoles = new Administracion_Model_DbTable_Roles();
        $this->_acl = new Usuarios_Model_Acl();

        $auth = Zend_Auth::getInstance();
        $this->_userData = $auth->getStorage()->read();

    }

    // Formulario de registro de usuario
    public function registroAction()
    {
        $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m059'));
        $formularioRegistro = new Usuarios_Form_Registro();

        //Si se reciben datos por post
        if ($this->getRequest()->isPost()) {
            //Si los datos recibidos son válidos
            if ($formularioRegistro->isValid($_POST)) {
                //Datos recibidos del formulario
                $data = $formularioRegistro->getValues();

                if ($this->_tablaUsuarios->existeEmail($data['email'])!=true) {

                    //Se inserta el usuario en la base de datos
                    $this->_tablaUsuarios->almacenarUsuario($data['email'], $data['name'], $data['password']);
                    $token = $this->_tablaUsuarios->getValidationTokenPorEmail($data['email']);

                    //Datos que van a ser enviados
                    $params=array(
                        'email' => $data['email'],
                        'name' => $data['name'],
                        'validation_token' => $token,
                    );

                    $this->_helper->viewRenderer->setNoRender(true);
                    $this->_helper->redirector('enviaremail', 'email', 'email', $params);

                } else {
                    $mensaje = Zend_Registry::get('Zend_Translate')->translate('err016');
                    $this->_helper->FlashMessenger($mensaje);
                }
            }
        }

        $this->view->form = $formularioRegistro;

    }

    // Iniciar sesión
    public function loginAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($this->getRequest()->isPost()
                && $this->getRequest()->getPost('email') != null
                && $this->getRequest()->getPost('password') != null) {

            $email = $this->getRequest()->getPost('email');
            $pwd = $this->getRequest()->getPost('password');

            $authAdapter = new Usuarios_Model_AuthAdapter($email, $pwd);

            $result = $auth->authenticate($authAdapter);

            if (!$result->isValid()) {

                switch ($result->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        $msg = Zend_Registry::get('Zend_Translate')->translate('err017');
                        break;
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        $msg = Zend_Registry::get('Zend_Translate')->translate('err018');
                        break;

                }
                $this->_helper->FlashMessenger($msg);
                $this->_redirect('/buscador/buscador/');

            } else {

                $auth = Zend_Auth::getInstance();
                $userId = $this->_tablaUsuarios->getUserIdPorEmail($email);
                $nombre = $this->_tablaUsuarios->getNamePorEmail($email);
                $rolId = $this->_tablaUsuarios->getRolPorEmail($email);
                $rol = $this->_tablaRoles->getRolPorId($rolId);
                $lang = $_COOKIE['lang'];
                $auth->getStorage()->write(array('email'=>$email, 'user_id' => $userId, 'name' => $nombre, 'role_name' => $rol['role_name'], 'lang' => $lang));

                $this->_redirect('/buscador/buscador/');

            }
        } else {
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err038');
            $this->_helper->FlashMessenger($mensaje);
            $this->_redirect('/buscador/buscador/');
        }

    }

    // Cerrar sesión
    public function logoutAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {

            // Antes de cerrar se mantiene el idioma seleccionado
            // creando la cookie
            $auth = Zend_Auth::getInstance();
            $data = $auth->getStorage()->read();
            setcookie('lang', $data['lang'], 0, "/");

            $auth->clearIdentity();
            $this->_helper->viewRenderer->setNoRender(true);

        } else {
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err019');
            $this->_helper->FlashMessenger($mensaje);
            $this->_redirect('/buscador/buscador/');
        }

        $this->_redirect('/buscador/buscador/');
    }

    // Formulario que se encarga de llamar a la función que envía el nuevo pass por email
    public function recuperarpasswordAction()
    {
        $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m037'));
        $formularioNuevoPass = new Usuarios_Form_Nuevopassword();

        //Si se reciben datos por post
        if ($this->getRequest()->isPost()) {
            //Si los datos recibidos son válidos
            if($formularioNuevoPass->isValid($_POST)
                    && $formularioNuevoPass->getValues() != null){

                $data = $formularioNuevoPass->getValues();

                $params=array(
                    'email' => $data['email'],
                );

                $this->_helper->viewRenderer->setNoRender(true);
                $this->_helper->redirector('enviarpassword', 'email', 'email', $params);

            }

        }

        $this->view->form = $formularioNuevoPass;

    }

    public function modificardatosAction()
    {

        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'editar')) {

            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m011'));

            if (Zend_Auth::getInstance()->hasIdentity()) {
                $auth=Zend_Auth::getInstance();
                $userData = $auth->getStorage()->read();

                $user = $this->_tablaUsuarios->getUserPorId($userData['user_id']);
                $formularioModificarDatos = new Usuarios_Form_Modificardatos($user);
                $formularioModificarPass = new Usuarios_Form_Cambiopassword();

                //Si se reciben datos por post
                if ($this->getRequest()->isPost()) {
                    //Si los datos recibidos son válidos
                    if ($formularioModificarDatos->isValid($_POST)) {
                        $data = $formularioModificarDatos->getValues();

                        if ( ($userData['email'] == $data['email']) || (!$this->_tablaUsuarios->existeEmail($data['email'])) ) {
                            $this->_tablaUsuarios->actualizarDatosUsuario($data['email'], $data['name']);
                        } else {
                            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err020');
                            $this->_helper->FlashMessenger($mensaje);
                        }
                    }
                }
                //$this->view->user = $user;
                $this->view->form = $formularioModificarDatos;
                $this->view->formCambiarPass = $formularioModificarPass;

            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err021');
                $this->_helper->FlashMessenger($mensaje);
            }

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    public function cambiarpasswordAction()
    {
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'editar')) {

            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m011'));

            if (Zend_Auth::getInstance()->hasIdentity()) {
                $auth=Zend_Auth::getInstance();
                $userData = $auth->getStorage()->read();

                $user = $this->_tablaUsuarios->getUserPorId($userData['user_id']);
                $formularioModificarDatos = new Usuarios_Form_Modificardatos($user);
                $formularioModificarPass = new Usuarios_Form_Cambiopassword();

                //Si se reciben datos por post
                if ($this->getRequest()->isPost()) {
                    //Si los datos recibidos son válidos
                    if ($formularioModificarPass->isValid($_POST)) {
                        $data = $formularioModificarPass->getValues();
                        $passActual = $data['passwordActual'];

                        if ($this->_tablaUsuarios->getPasswordPorId($userData['user_id']) == hash("md5", $passActual)) {
                            $this->_tablaUsuarios->actualizarPasswordUsuario($data['password']);
                        } else {
                            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err022');
                            $this->_helper->FlashMessenger($mensaje);
                        }

                    }
                }
                $this->view->form = $formularioModificarDatos;
                $this->view->formCambiarPass = $formularioModificarPass;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err021');
                $this->_helper->FlashMessenger($mensaje);
            }

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

}
