<?php

class Application_View_Helper_Loggedin extends Zend_View_Helper_Abstract
{
    public function loggedin()
    {
        $menuLoggedIn = '';

        $auth = Zend_Auth::getInstance();
        $data = $auth->getStorage()->read();
        $menuLoggedIn .= "<ul class='nav secondary-nav'>
            <li class='dropdown'>
                <a class='dropdown-toggle'  data-toggle=\"dropdown\" href='#'>".$data['name']."</a>
                <ul class='dropdown-menu'>
                    <li><a href='/usuarios/usuarios/modificardatos'>".Zend_Registry::get('Zend_Translate')->translate('m011')."</a></li>
                    <li><a href='#'>".Zend_Registry::get('Zend_Translate')->translate('m013')."</a></li>
                    <li class='divider'></li>
                    <li><a href='/usuarios/usuarios/logout'>".Zend_Registry::get('Zend_Translate')->translate('m012')."</a></li>
                </ul>
            </li>
        </ul>";

        return $menuLoggedIn;

    }

}
