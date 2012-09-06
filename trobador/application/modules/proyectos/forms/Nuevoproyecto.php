<?php

class Proyectos_Form_Nuevoproyecto extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        //Config del formulario
        $this->setName('frmNuevaProyecto');
        $this->setAction('/proyectos/proyectos/crearproyecto');
        $this->setMethod('post');

        //Elementos del formulario
        $name = $this->createElement('text', 'nombreProyecto');
        $name->setLabel(Zend_Registry::get('Zend_Translate')->translate('m049').': ');
        $name->setRequired(true);
        $name->setAttrib('size', 18);
        $name->setAttrib('maxlength', 300);
        $name->addFilters(array(
            new Zend_Filter_StringTrim(),
        ));

        $version = $this->createElement('text', 'nombreVersion');
        $version->setLabel(Zend_Registry::get('Zend_Translate')->translate('m030').': ');
        $version->setRequired(true);
        $version->setAttrib('size', 18);
        $version->setAttrib('maxlength', 300);
        $version->addFilters(array(
            new Zend_Filter_StringTrim(),
        ));

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m051')));

        //Añadir elementos creados al formulario
        $this->addElements(array($name, $version, $submit));


        //Eliminar decoradores (Formatear form)
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
        $submit->setDecorators(array(
            'ViewHelper',
            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
            array(array('emptyrow' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element', 'placement' => 'PREPEND')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form',
        ));
    }
    
}
?>
