<?php

class Administracion_Form_Eliminarusuario extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setName('frmEliminarUsuario');
        $this->setAction('/administracion/usuarios/eliminarusuario');
        $this->setMethod('post');

        //Elementos del formulario
        $idUsuario = new Zend_Form_Element_Hidden('user_id');
        $idUsuario->setValue($options['user_id']);
        $idUsuario->setDisableLoadDefaultDecorators(true);

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m056')));

        //Añadir elementos creados al formulario
        $this->addElements(array($idUsuario, $submit));

        //Eliminar decoradores (Formatear form)
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
        ));
        $submit->setDecorators(array(
            'ViewHelper',
        ));
        $idUsuario->setDecorators(array('ViewHelper'));
        $this->setDecorators(array(
            'FormElements',
            'Form',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
        ));

    }

}
