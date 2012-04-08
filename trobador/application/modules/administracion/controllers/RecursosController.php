<?php

class Administracion_RecursosController extends Zend_Controller_Action
{

    protected $_tablaRoles = null;
    protected $_tablaRecursos = null;
    protected $_tablaPrivilegios = null;
    protected $_tablaUsuarios = null;
    protected $_tablaUsuariosProyectos = null;
    protected $_tablaRolesRecursosPrivilegios = null;
    protected $_userData = null;

    public function init() {
        $this->_tablaRoles = new Administracion_Model_DbTable_Roles();
        $this->_tablaRecursos = new Administracion_Model_DbTable_Resources();
        $this->_tablaPrivilegios = new Administracion_Model_DbTable_Privileges();
        $this->_tablaUsuarios = new Usuarios_Model_DbTable_Users();
        $this->_tablaUsuariosProyectos = new Proyectos_Model_DbTable_Usersprojects();
        $this->_tablaRolesRecursosPrivilegios = new Administracion_Model_DbTable_Rolesresourcesprivileges();
        $this->_acl = new Usuarios_Model_Acl();
        
        $auth = Zend_Auth::getInstance();
        $this->_userData = $auth->getStorage()->read();
        
    }

    public function indexAction(){
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'recursos', 'ver')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'roles', 'ver')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'ver')) {
            
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m009'));
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }

    public function crearprivilegioAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'crear')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m072'));
            $formularioCrearPrivilegio = new Administracion_Form_Crearprivilegio();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioCrearPrivilegio->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioCrearPrivilegio->getValues();

                    if(!$this->_tablaPrivilegios->existePrivilegio($data['nombre'])){

                        $this->_tablaPrivilegios->guardarPrivilegio($data['nombre']);
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m073');
                        $this->_helper->FlashMessenger($mensaje);

                    }else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err023');
                        $this->_helper->FlashMessenger($mensaje);
                    }

                }
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err009');
                $this->_redirect('administracion/recursos/verprivilegios');
            }

            $this->view->form = $formularioCrearPrivilegio;
        
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
            
    }

    public function verrolesAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'roles', 'ver')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'roles', 'crear')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'roles', 'editar')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'roles', 'eliminar')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m074'));
            $formularioCrearRol = new Administracion_Form_Crearrol();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioCrearRol->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioCrearRol->getValues();

                    if(!$this->_tablaRoles->existeRol($data['nombre'])){

                        $this->_tablaRoles->guardarRol($data['nombre'], $data['descripcion']);
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m075');
                        $this->_helper->FlashMessenger($mensaje);

                    }else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err024');
                        $this->_helper->FlashMessenger($mensaje);
                    }

                }
            }

            $this->view->form = $formularioCrearRol;
            //$this->view->mensajeFrm = $mensajeFrm;

            // Obtener listado de roles
            $numRoles = $this->_tablaRoles->getNumRoles();
            if($numRoles > 0) {
                $roles = $this->_tablaRoles->getListaRoles();
                $this->view->roles = $roles;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err025');
                $this->_helper->FlashMessenger($mensaje);
            }

            //$this->view->mensaje = $mensaje;
        
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    public function verrecursosAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'recursos', 'ver')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'recursos', 'crear')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'recursos', 'editar')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'recursos', 'eliminar')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m076'));
            $formularioCrearRecurso = new Administracion_Form_Crearrecurso();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioCrearRecurso->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioCrearRecurso->getValues();

                    if(!$this->_tablaRecursos->existeRecurso($data['nombre'])){

                        $this->_tablaRecursos->guardarRecurso($data['nombre'], $data['descripcion']);
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m077');
                        $this->_helper->FlashMessenger($mensaje);

                    }else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err026');
                        $this->_helper->FlashMessenger($mensaje);
                    }

                }
            }

            $this->view->form = $formularioCrearRecurso;
            //$this->view->mensajeFrm = $mensajeFrm;

            // Obtener listado de recursos
            $numRecursos = $this->_tablaRecursos->getNumRecursos();
            if($numRecursos > 0) {
                $recursos = $this->_tablaRecursos->getRecursos();
                $this->view->recursos = $recursos;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err027');
                $this->_helper->FlashMessenger($mensaje);
            }

            //$this->view->mensaje = $mensaje;
        
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }  
            
    }

    public function verprivilegiosAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'ver')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'crear')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'editar')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'eliminar')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m078'));
            // Formulario crear rol
            $mensajeFrm = "";
            $formularioCrearPrivilegio = new Administracion_Form_Crearprivilegio();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioCrearPrivilegio->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioCrearPrivilegio->getValues();

                    if(!$this->_tablaPrivilegios->existePrivilegio($data['nombre'])){

                        $this->_tablaPrivilegios->guardarPrivilegio($data['nombre']);
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m079');
                        $this->_helper->FlashMessenger($mensaje);

                    }else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err028');
                        $this->_helper->FlashMessenger($mensaje);
                    }

                }
            }

            $this->view->form = $formularioCrearPrivilegio;

            // Obtener listado de privilegios
            $numPrivilegios = $this->_tablaPrivilegios->getNumPrivilegios();
            if($numPrivilegios > 0) {
                $privilegios = $this->_tablaPrivilegios->getPrivilegios();
                $this->view->privilegios = $privilegios;
            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err029');
                $this->_helper->FlashMessenger($mensaje);
            }

            //$this->view->mensaje = $mensaje;
        
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
            
    }

    public function eliminarrolAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'roles', 'eliminar')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m080'));
            
            // Si se reciben datos por get
            if($this->getRequest()->isGet()){

                $idRol = $this->getRequest()->getParam("idRol");
                // Al borrar un rol se eliminan los usuarios con ese rol
                // así como los recursos que referenciaban a dicho rol

                $usuarios = $this->_tablaUsuarios->getListaUsuariosPorRol($idRol);
                foreach($usuarios as $usuario){
                    $this->_tablaUsuariosProyectos->eliminarPorIdUsuario($usuario['user_id']);
                }
                $this->_tablaUsuarios->eliminarUsuariosRol($idRol);
                $this->_tablaRolesRecursosPrivilegios->eliminarRolRecursosPrivilegiosPorIdRol($idRol);
                $this->_tablaRoles->eliminarRol($idRol);
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m081');
                $this->_helper->FlashMessenger($mensaje);
            }

            $this->_redirect('/administracion/recursos/verroles');
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
            
    }

    
    public function eliminarrecursoAction() {

        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'recursos', 'eliminar')) {
            
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m082'));
            
            // Si se reciben datos por get
            if($this->getRequest()->isGet()){

                $idRecurso = $this->getRequest()->getParam("idRecurso");
                // Al borrar un recurso se borra también las referencias
                // en la tabla rolesRecursosPrivilegios
                $this->_tablaRolesRecursosPrivilegios->eliminarRolRecursosPrivilegiosPorIdRecurso($idRecurso);
                $this->_tablaRecursos->eliminarRecurso($idRecurso);
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m083');
                $this->_helper->FlashMessenger($mensaje);

            }

            $this->_redirect('/administracion/recursos/verrecursos');
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    public function eliminarprivilegioAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'privilegios', 'eliminar')) {
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m084'));
            
            // Si se reciben datos por get
            if($this->getRequest()->isGet()){

                $idPrivilegio = $this->getRequest()->getParam("idPrivilegio");
                // Al borrar un privilegio se borra también las referencias
                // en la tabla rolesRecursosPrivilegios
                $this->_tablaRolesRecursosPrivilegios->eliminarRolRecursosPrivilegiosPorIdPrivilegio($idPrivilegio);
                $this->_tablaPrivilegios->eliminarPrivilegio($idPrivilegio);
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m085');
                $this->_helper->FlashMessenger($mensaje);

            }

            $this->_redirect('/administracion/recursos/verprivilegios');
            
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }
        
    }

    public function asignarAction() {
        
        // Comprobación de permisos
        if($this->_acl->tienePermiso($this->_userData['role_name'], 'acl', 'ver')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'acl', 'crear')
                && $this->_acl->tienePermiso($this->_userData['role_name'], 'acl', 'eliminar')) {
        
        
            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m086'));
            $mensaje = "";

            $roles = $this->_tablaRoles->getRoles();
            $recursos = $this->_tablaRecursos->getRecursos();
            $privilegios = $this->_tablaPrivilegios->getPrivilegios();

            $formularioAsignarRecursos = new Administracion_Form_Asignarrecursos();

            //Si se reciben datos por post
            if($this->getRequest()->isPost()){
                //Si los datos recibidos son válidos
                if($formularioAsignarRecursos->isValid($_POST)){
                    //Datos recibidos del formulario
                    $data = $formularioAsignarRecursos->getValues();

                    if($this->_tablaRolesRecursosPrivilegios->existeRolRecursoPrivilegio($data['role_id'], $data['resource_id'], $data['privilege_id'])){
                        $this->_tablaRolesRecursosPrivilegios->eliminarRolRecursoPrivilegio($data['role_id'], $data['resource_id'], $data['privilege_id']);
                    } else {
                        $this->_tablaRolesRecursosPrivilegios->guardarRolRecursoPrivilegio($data['role_id'], $data['resource_id'], $data['privilege_id']);
                    }

                }
            } 

            $this->view->roles = $roles;
            $this->view->recursos = $recursos;
            $this->view->privilegios = $privilegios;
            $this->view->mensaje = $mensaje;
        
        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }
    
    
    
    public function errorpermisosAction(){
        
        $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('err030'));
        
    }


}




















