<?php

class Proyectos_Form_Nuevoproyecto extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        $name=$this->createElement('text', 'nombreProyecto');
        $name->setLabel('Nome: ');
        $name->setRequired(true);
        $name->setAttrib('size', 18);
        $name->setAttrib('maxlength', 300);
        $name->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));

        $this->addElement($name);


        $version=$this->createElement('text', 'nombreVersion');
        $version->setLabel('Version: ');
        $version->setRequired(true);
        $version->setAttrib('size', 18);
        $version->setAttrib('maxlength', 300);
        $version->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));

        $this->addElement($version);

        $this->addElement('submit','submit',array('label'=>'Crear Proyecto'));        
        
    }
    
}
?>
