<?php

class Application_View_Helper_Footer extends Zend_View_Helper_Abstract
{
    public function footer()
    {
        return "<ul class='pie pull-right'>
                    <li><a href='#'>".Zend_Registry::get('Zend_Translate')->translate('m013')."</a></li>
                    <li><a href='#'>".Zend_Registry::get('Zend_Translate')->translate('m014')."</a></li>
                </ul>";
    }

}
