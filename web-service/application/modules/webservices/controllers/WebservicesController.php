<?php

class Webservices_WebservicesController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

    }

    public function indexAction()
    {
        $server = new Zend_Rest_Server();
        $server->setClass('Webservices_Service_Tmxservices');
        $server->handle();

    }

}
