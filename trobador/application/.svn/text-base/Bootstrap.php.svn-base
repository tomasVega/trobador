<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{
    

    /*protected function _initViewHelpers(){

        //$this->bootstrap('layout');
        
        $view = new Zend_View();
        $view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        $view->addHelperPath(APPLICATION_PATH."/views/helpers", 'Application_View_Helper');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

    }*/

    protected function _initNavigation(){

        /*$this->bootstrap('layout');
        $layout=$this->getResource('layout');
        $view=$layout->getView();

        $config=new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml','nav');
        $navigation=new Zend_Navigation($config);
        $view->navigation($navigation);*/


        $this->bootstrap('view');
        $view = $this->getResource('view');

        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);

    }

    protected function _initTranslation(){

        $translator = new Zend_Translate(
            'array',
            '../resources/languages',
            'es',
            array('scan' => Zend_Translate::LOCALE_DIRECTORY)
        );

        Zend_Validate_Abstract::setDefaultTranslator($translator);

    }

}

?>