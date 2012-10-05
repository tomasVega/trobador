<?php

class Application_View_Helper_Login extends Zend_View_Helper_Abstract
{
    public function login($form)
    {
        return $form.'<ul class="nav">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">X</a>
                <ul class="dropdown-menu">
                  <li><a href="/usuarios/usuarios/registro">'.Zend_Registry::get('Zend_Translate')->translate('m002').'</a></li>
                  <li><a href="/usuarios/usuarios/recuperarpassword">'.Zend_Registry::get('Zend_Translate')->translate('m010').'</a></li>
                </ul>
              </li>
            </ul>';
    }

}

