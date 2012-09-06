<?php

class Application_View_Helper_Login extends Zend_View_Helper_Abstract {

    public function login($form) {

        return $form.
                "<p><a href='/usuarios/usuarios/registro'>".Zend_Registry::get('Zend_Translate')->translate('m002')."</a>
                 <a class='pull-right' href='/usuarios/usuarios/recuperarpassword'>".Zend_Registry::get('Zend_Translate')->translate('m010')."</a></p>";
    }

}

?>
