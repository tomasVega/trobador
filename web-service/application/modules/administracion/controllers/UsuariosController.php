<?php

class Administracion_UsuariosController extends Zend_Controller_Action
{

    protected $_tablaUsuarios = null;
    protected $_tablaUsuariosProyectos = null;
    protected $_userData = null;

    public function init() {
        
        $this->_tablaUsuarios = new Usuarios_Model_DbTable_Users();
        $this->_tablaUsuariosProyectos = new Proyectos_Model_DbTable_Usersprojects();
        $this->_acl = new Usuarios_Model_Acl();
        
        $auth = Zend_Auth::getInstance();
        $this->_userData = $auth->getStorage()->read();
        
    }

    public function gestionusuariosAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'ver') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'crear')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'editar')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'eliminar')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m008'));
            $mensaje = "";

            $mensajeUsuarioRegistrado = $this->getRequest()->getParam('usuarioRegistrado');
            $this->view->usuarioRegistrado = $mensajeUsuarioRegistrado;

            $formularioActualizarUsuario = new Administracion_Form_Editarusuario();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioActualizarUsuario->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioActualizarUsuario->getValues();

                    $this->_tablaUsuarios->actualizarUsuario($data['email'], $data['name'], $data['roles'], $data['activo'], $data['user_id']);

                }
            }



            if ($this->_tablaUsuarios->getNumUsuarios() > 0) {
                $usuarios = $this->_tablaUsuarios->getListaUsuariosEditar();

                //$formEdicionUsuarios = new Administracion_Form_Editarusuario();
                //$this->view->form = $formEdicionUsuarios;
                $this->view->usuarios = $usuarios;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err031');
                $this->view->mensaje = $mensaje;
            }

            $formularioNuevoUsuario = new Administracion_Form_Nuevousuario();
            $this->view->formNuevoUsuario = $formularioNuevoUsuario;
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
    }

//    public function eliminarusuarioAction() {
//        
//        // Comprobación de permisos
//        if($this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'eliminar')) {
//
//            $formularioEliminarUsuario = new Administracion_Form_Eliminarusuario();
//
//            //Si se reciben datos por post
//            if($this->getRequest()->isPost()){
//                //Si los datos recibidos son válidos
//                if($formularioEliminarUsuario->isValid($_POST)){
//                    //Datos recibidos del formulario
//                    $data = $formularioEliminarUsuario->getValues();
//                    // Se eliminan los grupos a los que pertenece el usuario y luego el user
//                    $this->_tablaUsuariosProyectos->eliminarPorIdUsuario($data['user_id']);
//                    $this->_tablaUsuarios->eliminarUsuario($data['user_id']);
//                    $mensaje = Zend_Registry::get('Zend_Translate')->translate('m087');
//                    $this->_helper->FlashMessenger($mensaje);
//
//                }
//            }
//
//            $this->_redirect('/administracion/usuarios/gestionusuarios');
//            
//        } else {
//            $this->_redirect('administracion/recursos/errorpermisos');
//        }
//        
//    }
    
    
    public function eliminarusuarioAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'eliminar')) {

            //$formularioEliminarUsuario = new Administracion_Form_Eliminarusuario();

            //Si se reciben datos por post
            if($this->getRequest()->isGet()){
                //Si los datos recibidos son válidos
                //if($formularioEliminarUsuario->isValid($_POST)){
                    //Datos recibidos del formulario
                    //$data = $formularioEliminarUsuario->getValues();
                    $idUsuario = $this->getRequest()->getParam("idUsuario");
                    // Se eliminan los grupos a los que pertenece el usuario y luego el user
                    $this->_tablaUsuariosProyectos->eliminarPorIdUsuario($idUsuario);
                    $this->_tablaUsuarios->eliminarUsuario($idUsuario);
                    $mensaje = Zend_Registry::get('Zend_Translate')->translate('m087');
                    $this->_helper->FlashMessenger($mensaje);

                //}
            }

            $this->_redirect('/administracion/usuarios/gestionusuarios');
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }

    public function nuevousuarioAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'usuarios', 'crear')) {
            
            $formularioNuevoUsuario = new Administracion_Form_Nuevousuario();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioNuevoUsuario->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioNuevoUsuario->getValues();

                    if (!$this->_tablaUsuarios->existeEmail($data['email'])) {
                        // Genero estos datos para almacenarlos en BD
                        $validationToken = $this->_tablaUsuarios->generarValidationToken();
                        $password = $this->_tablaUsuarios->generarPasswordAleatorio();
                        $passEncriptado = hash("md5", $password);

                        $this->_tablaUsuarios->almacenarUsuarioAdmin($data['email'], $data['name'], $data['roles'], $data['activo'], $validationToken, $passEncriptado);

                        //Datos que van a ser enviados
                        $params = array(
                            'email' => $data['email'],
                            'password' => $password,
                        );

                        //$this->_helper->viewRenderer->setNoRender(true);
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m088');
                        $this->_helper->FlashMessenger($mensaje);
                        $this->_helper->redirector('enviarpassgestionadmin', 'email', 'email', $params);
                    } else {
                        // Mensaje para indicar que el email está en uso
//                        $params = array(
//                            'usuarioRegistrado' => "O email xa está rexistrado",
//                        );
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err020');
                        $this->_helper->FlashMessenger($mensaje);
                        $this->_redirect('/administracion/usuarios/gestionusuarios');

                        //$this->_helper->redirector('gestionusuarios', 'usuarios', 'administracion', $params);
                    }
                }
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err009');
            }

            $this->_helper->redirector('gestionusuarios', 'usuarios', 'administracion');
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
    }

    public function asignarproxectosusuarioAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'ver') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'crear') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'editar') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'eliminar') ) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m089'));
            $mensaje = "";

            $idUsuario = $this->getRequest()->getParam('idUsuario');

            $formularioAsignarProyectos = new Administracion_Form_Asignarproxectosusuario($idUsuario);
            $this->view->formAsignarProyectos = $formularioAsignarProyectos;    

            $tablaUsuariosProyectos = new Proyectos_Model_DbTable_Usersprojects();
            $tablaProyectos = new Proyectos_Model_DbTable_Projects();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioAsignarProyectos->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioAsignarProyectos->getValues();
                    $proyectos = $data['proyectos'];
                    if ($proyectos != "-1") {

                        $tablaUsuariosProyectos->setProyectoUsuario($data['idUsuario'], $proyectos['project_id']);
                        $idUsuario = $data['idUsuario'];
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m090');
                        $this->_helper->FlashMessenger($mensaje);
                    } else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err032');
                        $this->_helper->FlashMessenger($mensaje);
                    }
                }
            }

            //Lee los proyectos de la BBDD
            $proyectosUsuario[] = null;
            $i = 0;
            foreach ($tablaUsuariosProyectos->getProyectosPorId($idUsuario) as $proyecto) {
                $pro = $tablaProyectos->getProyectoPorId($proyecto['project_id']);
                $proyectosUsuario[$i] = $pro;
                $i = $i +1;
            }

            $this->view->proyectos = $proyectosUsuario;
            $this->view->idUsuario = $idUsuario;
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }

    public function eliminarproxectoasignadousuarioAction() {
        
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'ver') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'crear') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'editar') 
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'grupos', 'eliminar') ) {
        
            $mensaje = "";
            $idProyecto = $this->getRequest()->getParam('idProyecto');
            $idUsuario = $this->getRequest()->getParam('idUsuario');



            if($idUsuario != null && $idProyecto != null){
                
                $this->_tablaUsuariosProyectos->eliminarPorIdProyectoIdUsuario($idProyecto, $idUsuario);
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m091');
                $this->_helper->FlashMessenger($mensaje);

            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err033');
                $this->_helper->FlashMessenger($mensaje);
            }
            $params = array('idUsuario' => $idUsuario);
            $this->_helper->redirector('asignarproxectosusuario', 'usuarios', 'administracion', $params);
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }


}
