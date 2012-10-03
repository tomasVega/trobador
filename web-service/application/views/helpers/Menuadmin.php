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
            $menuAdmin .= "<ul class='nav'>";
            $menuAdmin .= "<li class='dropdown'>";
            $menuAdmin .= "<a class='dropdown-toggle' data-toggle=\"dropdown\" href='#'>".Zend_Registry::get('Zend_Translate')->translate('m005')."<b class=\"caret\"></b></a>";
            $menuAdmin .= "<ul class='dropdown-menu'>
                            <li><a href='/proyectos/proyectos/crearproyecto'>".Zend_Registry::get('Zend_Translate')->translate('m006')."</a></li>
                            <li><a href='/proyectos/versiones/creaversion'>".Zend_Registry::get('Zend_Translate')->translate('m007')."</a></li>";
            $menuAdmin .= "<li><a id='menu-Pag3' href='/proyectos/memorias/subirmemoria'>".Zend_Registry::get('Zend_Translate')->translate('m004')."</a></li>";
            $menuAdmin .= "<li><a href='/administracion/usuarios/gestionusuarios'>".Zend_Registry::get('Zend_Translate')->translate('m008')."</a></li>
                            <li><a href='/administracion/recursos/index'>".Zend_Registry::get('Zend_Translate')->translate('m009')."</a></li>
                        </ul>
                    </li>
                </ul>";
        }

        return $menuAdmin;

    }

}