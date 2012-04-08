<?php

class Buscador_Form_Buscar extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);
        //Config del formulario
        $this->setAction('index');
        $this->setMethod('post');


        //Elementos del formulario
        $cadena = $this->createElement('text','cadena');
        $cadena->setLabel("Buscar: ");
        $cadena->setRequired(true);
        $cadena->addValidator(new Zend_Validate_StringLength(2));


        $submit=$this->createElement('submit','submit',array('label'=>'Buscar'));

        //Añadir elementos creados al formulario
        $this->addElements(array($cadena, $submit));
        
    }
        
}

?>
