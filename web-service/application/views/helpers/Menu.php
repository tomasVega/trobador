<?php
class Application_View_Helper_Menu extends Zend_View_Helper_Abstract
{
    public function menu()
    {
        $menu = "<ul class='nav'>";
        $menu .= "<li><a id='menu-Pag1' href='/proyectos/proyectos/verproyectostotales'>".Zend_Registry::get('Zend_Translate')->translate('m000')."</a></li>";

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $menu .= "<li><a id='menu-Pag2' href='/proyectos/proyectos/verproyectos'>".Zend_Registry::get('Zend_Translate')->translate('m003')."</a></li>";
        }

        $menu .= "</ul>";

        return $menu;
    }
}
