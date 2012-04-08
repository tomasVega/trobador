<?php

class Email_EmailController extends Zend_Controller_Action
{

    // Datos para el envío de emails
    private $_hostName = 'http://zf.local';
    private $_smtpUser = 'traduccionOSL@gmail.com';
    private $_smtpUserPassword = '12345osl';
    private $_smtpPort = '465';
    private $_smtpName = 'smtp.gmail.com';
    
    protected $_tablaUsuarios;

    public function init()
    {
       $this->_tablaUsuarios=new Usuarios_Model_DbTable_Users();
    }

    protected function caracteres_html($texto)
    {
        $texto = htmlentities($texto, ENT_NOQUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades
        $texto = htmlspecialchars_decode($texto, ENT_NOQUOTES); // Dejar <, & y > como estaban
        return $texto;
    }

    // Configura el smtp a través del cual se envian los emails
    private function configurarSMTP(){
        
        $config=array(
            'auth' => 'login',
            'ssl'=>'ssl',
            'port'=>$this->_smtpPort,
            'username'=>$this->_smtpUser,
            'password'=>$this->_smtpUserPassword,
        );

        $transport=new Zend_Mail_Transport_Smtp($this->_smtpName, $config);
        return $transport;
        
    }

    public function enviaremailAction()
    {

        $this->view->headTitle("Rexistro realizado");
        // Recoger parámetros del formulario de registro
        $mail=$this->getRequest()->getParam('email');
        $name=$this->getRequest()->getParam('name');
        $name=$this->caracteres_html($name);
        $validationToken=$this->getRequest()->getParam('validation_token');

        // Configuración
        $transport=$this->configurarSMTP();

        $to=$mail;
        // Creación del email
        $MailObj= new Zend_Mail();
        $subject="Bienvenido a Traduccion";
        $emailMessage="<h2>Bienvenido a Traducci&oacute;n</h2>".
                    "<p>Usuario: "."$name"."<br />".
                    "E-mail: ".$mail."</p>".
                    "<p>Para activar su cuenta ".
                    "pulse el siguiente enlace:</p>".
                    "<p>".$this->_hostName."/email/email/activaremail?email=".$mail."&name=".str_replace(" ","/",$name)."&validation=".$validationToken."</p>";
        $fromEmail="traduccionOSL@gmail.com";
        $fromFullName="Traduccion.com";

        $MailObj->setBodyHtml($emailMessage);
        $MailObj->setFrom($fromEmail, $fromFullName);
        $MailObj->addTo($to);
        $MailObj->setSubject($subject);
        $MailObj->send($transport);

        $mensaje="Revise o seu email, debe activar a súa conta para acceder";
        $this->view->mensaje=$mensaje;

    }

    //Envia password aleatorio para acceder a la cuenta en caso de olvidar la contraseña
    public function enviarpasswordAction()
    {
        $mail=$this->getRequest()->getParam('email');

        if($this->_tablaUsuarios->existeEmail($mail)){

            $pass=$this->_tablaUsuarios->setPasswordPorEmail($mail);
            // Configuración
            $transport=$this->configurarSMTP();

            $to=$mail;
            // Creación del email
            $MailObj= new Zend_Mail();
            $subject="Cambio de password";
            $emailMessage="<h2>Bienvenido a Traducci&oacute;n</h2>".
                        "<p>Su nuevo password es: ".
                        $pass."</p>";
            $fromEmail="traduccionOSL@gmail.com";
            $fromFullName="Traduccion.com";

            $MailObj->setBodyHtml($emailMessage);
            $MailObj->setFrom($fromEmail, $fromFullName);
            $MailObj->addTo($to);
            $MailObj->setSubject($subject);
            $MailObj->send($transport);

            $mensaje="Revise o seu email para consultar o novo contrasinal";
        }else{
            $mensaje="O email non existe";
        }
        $this->view->mensaje=$mensaje;
    }

    // Realiza la activación de la cuenta de un usuario
    public function activaremailAction()
    {

        $this->view->headTitle("Activación da conta");

        $email=$this->getRequest()->getParam('email');
        $token=$this->getRequest()->getParam('validation');

        if($this->_tablaUsuarios->comprobarActivacion($email, $token)==true){
            //se realiza el update
            $this->_tablaUsuarios->realizarActivacion($email);
            $mensaje="A súa conta foi activa con éxito, xa poder acceder á aplicación";
        }else{
            //No se realiza el update, mensaje de error
            $mensaje="Erro na activación";
        }
        $this->view->mensaje=$mensaje;

    }
    
}
