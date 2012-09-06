<?php

class Usuarios_Form_Nuevopassword extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setAction('/usuarios/usuarios/recuperarpassword');
        $this->setMethod('post');
        $this->setName('frmModificarPassword');

        //Elementos del formulario
        $email = $this->createElement('text', 'email');
        $email->setLabel(Zend_Registry::get('Zend_Translate')->translate('m064').': ');
        $email->setRequired(true);
        $email->setAttrib('size', 18);
        $email->setAttrib('maxlength', 200);
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m067')));

        //Añadir elementos creados al formulario
        $this->addElements(array($email, $submit));

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
