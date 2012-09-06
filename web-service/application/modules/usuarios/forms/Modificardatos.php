<?php

class Usuarios_Form_Modificardatos extends Zend_Form
{
    public function __construct($options)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setAction('/usuarios/usuarios/modificardatos');
        $this->setMethod('post');
        $this->setName('frmModificarDatos');
        $this->setOptions(array('class'=>'frm'));

        //Elementos del formulario
        $email=$this->createElement('text', 'email');
        $email->setLabel(Zend_Registry::get('Zend_Translate')->translate('m064').': ');
        $email->setRequired(true);
        $email->setAttrib('size', 20);
        $email->setAttrib('maxlength', 200);
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));
        $email->setValue($options['email']);

        $nombre=$this->createElement('text', 'name');
        $nombre->setLabel(Zend_Registry::get('Zend_Translate')->translate('m049').': ');
        $nombre->setRequired(true);
        $nombre->setAttrib('size', 20);
        $nombre->setAttrib('maxlength', 100);
        $nombre->setValue($options['name']);

        $pass = $this->createElement('password', 'password');
        $pass->setLabel(Zend_Registry::get('Zend_Translate')->translate('m061').': ');
        //$pass->setRequired(true);
        $pass->setAttrib('size', 20);
        $pass->setAttrib('maxlength', 200);

        $pass1 = $this->createElement('password', 'password1');
        $pass1->setLabel(Zend_Registry::get('Zend_Translate')->translate('m062').': ');
        //$pass1->setRequired(true);
        $pass1->addValidator('Identical', false, array('token' => 'password'));
        $pass1->setAttrib('size', 20);
        $pass1->setAttrib('maxlength', 200);

        $submit=$this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m063')));

        //Añadir elementos creados al formulario
        $this->addElements(array($email, $nombre ,$submit));

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
