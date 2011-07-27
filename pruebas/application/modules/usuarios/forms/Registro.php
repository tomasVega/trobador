<?php

class Usuarios_Form_Registro extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        //Config del formulario
        $this->setAction('registro');
        $this->setMethod('post');

        
        //Elementos del formulario
        $email=$this->createElement('text', 'email');
        $email->setLabel('E-mail: ');
        $email->setRequired(true);
        $email->setAttrib('size', 20);
        $email->setAttrib('maxlength', 200);
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));


        $pass=$this->createElement('password', 'password');
        $pass->setLabel('Contrasinal: ');
        $pass->setRequired(true);
        $pass->setAttrib('size', 20);
        $pass->setAttrib('maxlength', 200);

        
        $pass1=$this->createElement('password', 'password1');
        $pass1->setLabel('Repita o contrasinal: ');
        $pass1->setRequired(true);
        $pass1->addValidator('Identical', false, array('token' => 'password'));
        $pass1->setAttrib('size', 20);
        $pass1->setAttrib('maxlength', 200);


        $nombre=$this->createElement('text', 'name');
        $nombre->setLabel('Nome: ');
        $nombre->setRequired(true);
        $nombre->setAttrib('size', 20);
        $nombre->setAttrib('maxlength', 100);
       

        $submit=$this->createElement('submit','submit',array('label'=>'Rexistrar'));


        //Añadir elementos creados al formulario
        $this->addElements(array($email, $pass, $pass1, $nombre, $submit));
 
    }  
}

?>