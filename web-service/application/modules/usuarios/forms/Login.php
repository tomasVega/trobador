<?php

class Usuarios_Form_Login extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setName('frmLogin');
        $this->setAction('/usuarios/usuarios/login');
        $this->setMethod('post');

        //Elementos del formulario
        $email=$this->createElement('text', 'email');
        $email->setAttrib("placeholder", Zend_Registry::get('Zend_Translate')->translate('m064'));
        $email->setRequired(true);
        $email->setAttrib('size', 18);
        $email->setAttrib('maxlength', 200);
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));
        $email->class="input-medium";

        $pass=$this->createElement('password', 'password');
        $pass->setAttrib("placeholder", Zend_Registry::get('Zend_Translate')->translate('m065'));
        $pass->setRequired(true);
        $pass->setAttrib('size', 15);
        $pass->setAttrib('maxlength', 15);
        $pass->addValidator(new Zend_Validate_StringLength(3,15));
        $pass->class="input-medium";

        $submit=$this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m066')));
        $submit->class="submit button";

        //Añadir elementos creados al formulario
        $this->addElements(array($email, $pass, $submit));

        //Eliminar decoradores (Formatear form)
        $this->setElementDecorators(
            array(
                'ViewHelper',
                'Label',
        ));
        $submit->setDecorators(array('ViewHelper'));
        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));

    }
}
