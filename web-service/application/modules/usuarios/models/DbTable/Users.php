<?php

class Usuarios_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    // Genera cadena de caracteres aleatoria para realizar la activacion de la cuenta
    public function generarValidationToken($numero=60)
    {

        $caracter= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double) microtime()*1000000);
        for ($i=0; $i<$numero; $i++) {
            $rand.= $caracter[rand()%strlen($caracter)];
        }

        return $rand;

    }

    public function generarPasswordAleatorio()
    {
        $long=12;
        $cadena="[^A-Za-z0-9]";

        return substr(eregi_replace($cadena, "", md5(rand())) .
            eregi_replace($cadena, "", md5(rand())) .
            eregi_replace($cadena, "", md5(rand())),
            0, $long);
    }

    // Comprueba que el usuario y contraseña introducidos son válidos
    public function comprobarLogin($email, $pass)
    {
        $select = $this->select()->where('email = ?', $email)
            ->where('password = ?', hash("md5",$pass));

        $row = $this->fetchRow($select);

        if ($row) {
            return $row;
        }

        return false;
    }

    // Comprueba que el usuario y contraseña introducidos son válidos y el usuario está activo
    public function comprobarLoginUsuarioActivado($email, $pass)
    {
        $select = $this->select()->where('email = ?', $email)
                                ->where('password = ?', hash("md5",$pass))
                                ->where('activated = 1');

        $row = $this->fetchRow($select);

        if ($row) {
            return $row;
        }

        return false;
    }

    // Introduce un nuevo usuario en la BBDD
    public function almacenarUsuario($email, $nombre, $pass)
    {
        $passEncriptado = hash("md5", $pass);
        $validationToken = $this->generarValidationToken();

        $data = array('email' => $email,
                    'password' => $passEncriptado,
                    'name' => $nombre,
                    'validation_token' => $validationToken,);

        $this->_db->insert($this->_name, $data);
    }

    // Introduce un nuevo usuario en la BBDD
    public function almacenarUsuarioAdmin($email, $nombre, $role, $activo, $validationToken, $passEncriptado)
    {
        //$passEncriptado = hash("md5", $pass);
        //$validationToken = $this->generarValidationToken();

        $data = array('email' => $email,
                    'password' => $passEncriptado,
                    'name' => $nombre,
                    'validation_token' => $validationToken,
                    'activated' => $activo,
                    'role_id' => $role);

        $this->_db->insert($this->_name, $data);
    }

    // Devuelve la lista completa de usuarios de la BBDD
    public function getListaUsuarios()
    {
        $select = $this->select();
        $select->from($this->_name);

        $rows = $this->fetchAll($select);

        return $rows;

    }

    // Comprueba si una dirección de email ya está en uso
    public function existeEmail($email)
    {
        $select=$this->select();
        $select->from($this->_name)->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $count = $rows->count();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }

    }

    // Devuelve el validation_token que concuerda con un determinado email
    public function getValidationTokenPorEmail($email)
    {
        $select=$this->select();
        $select->from($this->_name,'validation_token')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $validationToken = $row['validation_token'];

        return $validationToken;

    }

    public function getUserPorId($userId)
    {
        $select=$this->select();
        $select->from($this->_name)->where("user_id = ?", $userId);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);

        return $row;

    }

    // Devuelve el nombre que concuerda con un determinado email
    public function getNamePorId($id)
    {
        $select=$this->select();
        $select->from($this->_name,'name')->where("user_id = ?", $id);

        $rows = $this->fetchAll($select);
        if (count($rows) > 0) {
            $row=$rows->getRow(0);
            $nombre = $row['name'];

            return $nombre;
        } else {
            return null;
        }
    }

    // Devuelve el user_id que concuerda con un determinado email
    public function getUserIdPorEmail($email)
    {
        $select=$this->select();
        $select->from($this->_name,'user_id')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $userId = $row['user_id'];

        return $userId;

    }

    public function getRolPorEmail($email)
    {
        $select=$this->select();
        $select->from($this->_name,'role_id')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $rolId = $row['role_id'];

        return $rolId;

    }

    // Devuelve el nombre que concuerda con un determinado email
    public function getNamePorEmail($email)
    {
        $select=$this->select();
        $select->from($this->_name,'name')->where("email = ?", $email);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $nombre = $row['name'];

        return $nombre;

    }

    public function getPasswordPorId($userId)
    {
        $select=$this->select();
        $select->from($this->_name, 'password')->where("user_id = ?", $userId);

        $rows = $this->fetchAll($select);
        $row=$rows->getRow(0);
        $pass = $row['password'];

        return $pass;

    }

    // Comprueba que el email y el validation_token concuerdan con los datos de BBDD
    public function comprobarActivacion($email,$validationToken)
    {
        $select=$this->select();
        $select->from($this->_name,'*');
        $select->where("email = ?", $email);
        $select->where("validation_token = ?", $validationToken);

        $rows = $this->fetchAll($select);
        $count=$rows->count();

        if ($count>0) {
            return true;
        } else {
            return false;
        }

    }

    // Pone a 1 el campo activated indicando que la cuenta se ha dado de alta
    public function realizarActivacion($email)
    {
        $data = array('activated' => '1');
        $this->_db->update($this->_name, $data, $this->_db->quoteInto('email = ?', $email));

    }

    // Inserta el pass generado tras la recuperación, en BBDD
    public function setPasswordPorEmail($email)
    {
        $password = $this->generarPasswordAleatorio();
        $passwordEncriptado = hash("md5", $password);

        $data = array('password' => $passwordEncriptado);
        $this->_db->update($this->_name, $data, $this->_db->quoteInto('email = ?', $email));

        return $password;

    }

    public function actualizarDatosUsuario($email, $nombre)
    {
        // Se seleccion el id de usuario de la sesión
        $auth = Zend_Auth::getInstance();
        $userData = $auth->getStorage()->read();
        //$userId = $this->getUserIdPorEmail($userData['email']);

        // Se insertan los nuevos valores en base de datos
        $data = array('email' => $email, 'name' => $nombre);
        $this->_db->update($this->_name, $data, $this->_db->quoteInto('user_id = ?', $userData['user_id']));

        // Se actualizan los datos de la sesión del usuario
        $this->actualizaSesionUsuario($userData['user_id'], $email, $nombre, $userData['role_name'], $userData['lang']);

    }

    public function actualizarPasswordUsuario($password)
    {
        // Se seleccion el id de usuario de la sesión
        $auth = Zend_Auth::getInstance();
        $userData = $auth->getStorage()->read();

        // Se insertan los nuevos valores en base de datos
        $data = array('password' => hash("md5", $password));
        $this->_db->update($this->_name, $data, $this->_db->quoteInto('user_id = ?', $userData['user_id']));

    }

    private function actualizaSesionUsuario($userId, $email, $nombre, $rol, $lang)
    {
        $auth = Zend_Auth::getInstance();
        $auth->getStorage()->write(array('email' => $email, 'user_id' => $userId, 'name' => $nombre, 'role_name' => $rol, 'lang' => $lang));

    }

    // Devuelve la lista de todos los usuarios para editar de la aplicación
    public function getListaUsuariosEditar()
    {
        $sql = 'SELECT u.user_id, u.email, u.name, u.activated, u.role_id, r.role_name
            FROM users u, roles r
            WHERE u.role_id = r.role_id';

        $rows = $this->_db->fetchAll($sql);

        return $rows;

    }

    // Devuelve el número de usuarios
    public function getNumUsuarios()
    {
        $select = $this->select();
        $select->from($this->_name, array("num"=>"COUNT(*)"));

        $rowsNum = $this->fetchRow($select);

        return $rowsNum['num'];

    }

    // Actualiza los datos de un usuario en la parte de administración
    public function actualizarUsuario($email, $nombre, $roles, $activo, $userId)
    {
        // Se insertan los nuevos valores en base de datos
        $data = array('email' => $email, 'name' => $nombre, 'role_id' => $roles, 'activated' => $activo);

        $this->_db->update($this->_name, $data, $this->_db->quoteInto('user_id = ?', $userId));

    }

    // Elimina un usuario
    public function eliminarUsuario($idUsuario)
    {
        $this->_db->delete($this->_name, 'user_id = '.$idUsuario);

    }

    // Elimina usuarios segun su rol
    public function eliminarUsuariosRol($idRol)
    {
        $this->_db->delete($this->_name, 'role_id = '.$idRol);

    }

    public function getListaUsuariosPorRol($idRol)
    {
        $select = $this->select();
        $select->from($this->_name)->where("role_id = ?", $idRol);

        $rows = $this->fetchAll($select);

        return $rows;

    }

}
