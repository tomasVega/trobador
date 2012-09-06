<?php

class Application_View_Helper_Menuadmin extends Zend_View_Helper_Abstract
{
    public function menuAdmin()
    {
        $menuAdmin = '';

        $acl = new Usuarios_Model_Acl();
        $auth = Zend_Auth::getInstance();
        $data = $auth->getStorage()->read();
        if (Zend_Auth::getInstance()->hasIdentity() && $acl->esAdmin($data['role_name'])) {
            $menuAdmin .= "<div class='pull-left'>
                <ul class='nav secondary-nav'>
                    <li class='dropdown'>
                        <a class='dropdown-toggle' href='#'>".Zend_Registry::get('Zend_Translate')->translate('m005')."</a>
                        <ul class='dropdown-menu'>
                            <li><a href='/proyectos/proyectos/crearproyecto'>".Zend_Registry::get('Zend_Translate')->translate('m006')."</a></li>
                            <li><a href='/proyectos/versiones/crearversion'>".Zend_Registry::get('Zend_Translate')->translate('m007')."</a></li>
                            <li><a href='/administracion/usuarios/gestionusuarios'>".Zend_Registry::get('Zend_Translate')->translate('m008')."</a></li>
                            <li><a href='/administracion/recursos/index'>".Zend_Registry::get('Zend_Translate')->translate('m009')."</a></li>
                        </ul>
                    </li>
                </ul>
            </div>";
        }

        return $menuAdmin;

    }

}
