<?php

class Usuarios_Model_AuthAdapter implements Zend_Auth_Adapter_Interface{
    
    protected $email;
    protected $password;
    protected $user;

    public function __construct($email, $password) {

        $this->email = $email;
        $this->password = $password;
        $this->user = new Usuarios_Model_DbTable_Users();
        
    }

    //Comprueba que las credenciales de usuario son válidas
    public function  authenticate() {

        $match = $this->user->comprobarLogin($this->email, $this->password);

        if(!$match) {

            $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);

        } else {

            if($this->user->comprobarLoginUsuarioActivado($this->email, $this->password)){
                $user = current($match);
                $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
            }else{
                // Si el usuario no ha activado su cuenta
                $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null);
            }
            
        }

        return $result;

    }

}
