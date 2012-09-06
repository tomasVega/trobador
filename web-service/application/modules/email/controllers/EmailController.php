<?php

class Email_EmailController extends Zend_Controller_Action
{
    // Datos para el envío de emails
    private $_hostName = 'http://';

    protected $_tablaUsuarios;
    protected $_acl = null;

    public function init()
    {
       $this->_tablaUsuarios=new Usuarios_Model_DbTable_Users();
       $this->_acl = new Usuarios_Model_Acl();

    }

    protected function caracteres_html($texto)
    {
        $texto = htmlentities($texto, ENT_NOQUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades
        $texto = htmlspecialchars_decode($texto, ENT_NOQUOTES); // Dejar <, & y > como estaban

        return $texto;

    }

    // Configura el smtp a través del cual se envian los emails
    private function configurarSMTP()
    {
        $transport = new Zend_Mail_Transport_Smtp('localhost');

        return $transport;

    }

    public function enviaremailAction()
    {
        $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m032'));
        // Recoger parámetros del formulario de registro
        $mail = $this->getRequest()->getParam('email');
        $name = $this->getRequest()->getParam('name');
        $name = $this->caracteres_html($name);
        $validationToken = $this->getRequest()->getParam('validation_token');

        // Configuración
        $transport = $this->configurarSMTP();

        $to = $mail;
        // Creación del email
        $MailObj = new Zend_Mail();
        $subject = "Benvido a Trobador";
        $emailMessage = "<h2>Benvido a Trobador</h2>".
                    "<p>Usuario: "."$name"."<br />".
                    "E-mail: ".$mail."</p>".
                    "<p>Para activar a s&uacute;a conta ".
                    "prema no seguinte enlace:</p>".
                    "<p>".$this->_hostName.$_SERVER['HTTP_HOST']."/email/email/activaremail?email=".$mail."&name=".str_replace(" ","/",$name)."&validation=".$validationToken."</p>";
        //$fromEmail = "trobador@info.com";
        //$fromFullName = "Trobador.com";

        $MailObj->setBodyHtml($emailMessage);
        //$MailObj->setFrom($fromEmail, $fromFullName);
        $MailObj->addTo($to);
        $MailObj->setSubject($subject);
        $MailObj->send($transport);

        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err001');
        //$this->view->mensaje = $mensaje;
        $this->_helper->FlashMessenger($mensaje);
        $this->_redirect('/buscador/buscador/');

    }

    //Envia password aleatorio para acceder a la cuenta en caso de olvidar la contraseña
    public function enviarpasswordAction()
    {
        $mail = $this->getRequest()->getParam('email');

        if ($this->_tablaUsuarios->existeEmail($mail)) {

            $pass = $this->_tablaUsuarios->setPasswordPorEmail($mail);
            // Configuración
            $transport = $this->configurarSMTP();

            $to = $mail;
            // Creación del email
            $MailObj = new Zend_Mail();
            $subject = "Cambio de contrasinal";
            $emailMessage = "<h2>Benvido a Trobador</h2>".
                        "<p>O seu novo contrasinal &eacute;: ".
                        $pass."</p>";
            $fromEmail = "trobador@info.com";
            $fromFullName = "Trobador.com";

            $MailObj->setBodyHtml($emailMessage);
            $MailObj->setFrom($fromEmail, $fromFullName);
            $MailObj->addTo($to);
            $MailObj->setSubject($subject);
            $MailObj->send($transport);

            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err002');
            $this->_helper->FlashMessenger($mensaje);
            $this->_redirect('/buscador/buscador/');

        } else {

            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err003');
            $this->_helper->FlashMessenger($mensaje);
            $this->_redirect('/usuarios/usuarios/recuperarpassword');

        }

    }

    // Realiza la activación de la cuenta de un usuario
    public function activaremailAction()
    {
        $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m033'));

        $email = $this->getRequest()->getParam('email');
        $token = $this->getRequest()->getParam('validation');

        if ($this->_tablaUsuarios->comprobarActivacion($email, $token)==true) {
            //se realiza el update
            $this->_tablaUsuarios->realizarActivacion($email);
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('m034');
        } else {
            //No se realiza el update, mensaje de error
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err004');
        }
        //$this->view->mensaje=$mensaje;
        $this->_helper->FlashMessenger($mensaje);
        $this->_redirect('/buscador/buscador/');

    }

    //Envia password cuando la cuenta es creada por el admin
    public function enviarpassgestionadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userData = $auth->getStorage()->read();
        $mensaje = '';
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($userData['role_name'], 'usuarios', 'crear')) {

            $mail = $this->getRequest()->getParam('email');
            $pass = $this->getRequest()->getParam('password');

            if ($mail != null && $pass != null) {

                // Configuración
                $transport = $this->configurarSMTP();

                $to = $mail;
                // Creación del email
                $MailObj = new Zend_Mail();
                $subject = "Creación de conta";
                $emailMessage = "<h2>Benvido a Trobador</h2>".
                            "<p>O seu contrasinal &eacute;: ".
                            $pass."</p>";
                $fromEmail = "trobador@info.com";
                $fromFullName = "Trobador.com";

                $MailObj->setBodyHtml($emailMessage);
                $MailObj->setFrom($fromEmail, $fromFullName);
                $MailObj->addTo($to);
                $MailObj->setSubject($subject);
                $MailObj->send($transport);

                $this->_redirect('/administracion/usuarios/gestionusuarios');

            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err005');
                $this->_helper->FlashMessenger($mensaje);
            }

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

}
