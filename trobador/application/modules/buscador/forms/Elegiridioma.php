<?php

class Buscador_Form_Elegiridioma extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);
        //Config del formulario
        $this->setName('frmElegirIdioma');
        $this->setAction('/buscador/buscador/cambiaridioma');
        $this->setMethod('post');


        //Elementos del formulario
        $idioma = $this->createElement('select','idiomaApp');
        $idioma->setLabel(Zend_Registry::get('Zend_Translate')->translate('m019').":");
        $idioma->setRequired(true);
        $idioma->addMultiOption('gl_GL', Zend_Registry::get('Zend_Translate')->translate('m016'));
        $idioma->addMultiOption('es_ES', Zend_Registry::get('Zend_Translate')->translate('m015'));
        $idioma->addMultiOption('en_EN', Zend_Registry::get('Zend_Translate')->translate('m017'));
        $idioma->setAttrib('onChange', 'submitFormElegirIdioma();');
        
        if(Zend_Auth::getInstance()->hasIdentity()){
            $auth = Zend_Auth::getInstance();
            $data = $auth->getStorage()->read();
            $idioma->setValue($data['lang']);
        } else if(isset($_COOKIE['lang'])){
            $idioma->setValue($_COOKIE['lang']); 
        } else {
            $idioma->setValue('gl_GL');
        }
        
        $idioma->class = "medium";
        
        $ultimaUrl = $this->createElement('hidden','url');
        $ultimaUrl->setValue(Zend_Controller_Front::getInstance()->getRequest()->getRequestUri());

        //Añadir elementos creados al formulario
        $this->addElements(array($idioma, $ultimaUrl));

        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
        ));
        
        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));
        
    }
        
}

?>
