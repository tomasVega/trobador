<?php

class Usuarios_Form_Nuevopassword extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        //Config del formulario
        $this->setAction('recuperarpassword');
        $this->setMethod('post');

        //Elementos del formulario
        $email=$this->createElement('text', 'email');
        $email->setLabel('E-mail: ');
        $email->setRequired(true);
        $email->setAttrib('size', 18);
        $email->setAttrib('maxlength', 200);
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));

        $submit=$this->createElement('submit','submit',array('label'=>'Enviar'));

        //Añadir elementos creados al formulario
        $this->addElements(array($email, $submit));

    }
}
?>