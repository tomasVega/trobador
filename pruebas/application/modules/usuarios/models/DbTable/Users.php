<?php

class Usuarios_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    //Generar cadena de caracteres aleatoria para realizar la activacion de la cuenta
    protected function generarValidationToken($numero=60)
    {

        $caracter= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        for($i=0; $i<$numero; $i++) {
            $rand.= $caracter[rand()%strlen($caracter)];
        }
        return $rand;
        
    }

    protected function generarPasswordAleatorio()
    {
        $long=12;
        $cadena="[^A-Za-z0-9]";
        return substr(eregi_replace($cadena, "", md5(rand())) .
            eregi_replace($cadena, "", md5(rand())) .
            eregi_replace($cadena, "", md5(rand())),
            0, $long);
    }

    //comprobar que el usuario y contraseña introducidos son válidos
    public function comprobarLogin($email, $pass){

        $select = $this->select()->where('email = ?', $email)
            ->where('password = ?', hash("md5",$pass));

        $row = $this->fetchRow($select);
        
        if($row) {
            return $row;
        }
        
        return false;
    }

    //comprobar que el usuario y contraseña introducidos son válidos y el usuario está activo
    public function comprobarLoginUsuarioActivado($email, $pass){

        $select = $this->select()->where('email = ?', $email)
            ->where('password = ?', hash("md5",$pass))
            ->where('activated = 1');

        $row = $this->fetchRow($select);

        if($row) {
            return $row;
        }

        return false;
    }
    
    //Introducir un nuevo usuario en la BBDD
    public function almacenarUsuario($email, $nombre, $pass) {

        $passEncriptado=hash("md5", $pass);
        $validationToken=$this->generarValidationToken();

        $data=array('email'=>$email,
                    'password'=>$passEncriptado,
                    'name'=>$nombre,
                    'validation_token'=>$validationToken,);

        $this->_db->insert($this->_name, $data);
    }

    //Devuelve la lista completa de usuarios de la BBDD
    public function getListaUsuarios() {

        $select=$this->select();
        $select->from($this->_name);
        
        $rows = $this->fetchAll($select);
        
        return $rows;
        
    }

    //Comprueba si una dirección de email ya está en uso
    public function existeEmail($email) {

        $select=$this->select();
        $select->from($this->_name)->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $count=$rows->count();

        if($count>0){
            return true;
        }else{
            return false;
        }

    }

    //Devuelve el validation_token que concuerda con un determinado email
    public function getValidationTokenPorEmail($email) {

        $select=$this->select();
        $select->from($this->_name,'validation_token')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $validationToken = $row['validation_token'];

        return $validationToken;

    }

    //Devuelve el user_id que concuerda con un determinado email
    public function getUserIdPorEmail($email) {

        $select=$this->select();
        $select->from($this->_name,'user_id')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $userId = $row['user_id'];

        return $userId;

    }

    //Devuelve el nombre que concuerda con un determinado email
    public function getNamePorEmail($email) {

        $select=$this->select();
        $select->from($this->_name,'name')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $nombre = $row['name'];

        return $nombre;

    }

    //Comprueba que el email y el validation_token concuerdan con los datos de BBDD
    public function comprobarActivacion($email,$validationToken) {

        $select=$this->select();
        $select->from($this->_name,'*');
        $select->where("email = ?", $email);
        $select->where("validation_token = ?", $validationToken);

        $rows = $this->fetchAll($select);
        $count=$rows->count();

        if($count>0){
            return true;
        }else{
            return false;
        }

    }

    //Pone a 1 el campo activated indicando que la cuenta se ha dado de alta
    public function realizarActivacion($email) {

        $data = array('activated' => '1');
        $this->_db->update($this->_name, $data, $this->_db->quoteInto('email = ?', $email));
        
    }

    public function setPasswordPorEmail($email){

        $password=$this->generarPasswordAleatorio();
        $passwordEncriptado=hash("md5", $password);

        $data= array('password' => $passwordEncriptado);
        $this->_db->update($this->_name, $data, $this->_db->quoteInto('email = ?', $email));

        return $password;

    }

}
