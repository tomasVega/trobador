<?php

class Usuarios_Form_Cambiopassword extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setAction('/usuarios/usuarios/cambiarpassword');
        $this->setMethod('post');
        $this->setName('frmModificarPassword');

        //Elementos del formulario
        $passActual = $this->createElement('password', 'passwordActual');
        $passActual->setLabel(Zend_Registry::get('Zend_Translate')->translate('m060').': ');
        $passActual->setRequired(true);
        $passActual->setAttrib('size', 20);
        $passActual->setAttrib('maxlength', 200);

        $pass = $this->createElement('password', 'password');
        $pass->setLabel(Zend_Registry::get('Zend_Translate')->translate('m061').': ');
        $pass->setRequired(true);
        $pass->setAttrib('size', 20);
        $pass->setAttrib('maxlength', 200);

        $pass1 = $this->createElement('password', 'password1');
        $pass1->setLabel(Zend_Registry::get('Zend_Translate')->translate('m062').': ');
        $pass1->setRequired(true);
        $pass1->addValidator('Identical', false, array('token' => 'password'));
        $pass1->setAttrib('size', 20);
        $pass1->setAttrib('maxlength', 200);

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m063')));

        //Añadir elementos creados al formulario
        $this->addElements(array($passActual, $pass, $pass1, $submit));

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
