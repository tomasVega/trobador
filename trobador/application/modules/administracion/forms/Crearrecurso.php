<?php

class Administracion_Form_Crearrecurso extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        //Config del formulario
        $this->setName('frmCrearRecurso');
        $this->setAction('/administracion/recursos/verrecursos');
        $this->setMethod('post');

        //Elementos del formulario
        $nombre = $this->createElement('text', 'nombre');
        $nombre->setLabel(Zend_Registry::get('Zend_Translate')->translate('m049').': ');
        $nombre->setRequired(true);
        $nombre->setAttrib('size', 20);
        $nombre->setAttrib('maxlength', 100);
        $nombre->setValue($options['name']);
        $nombre->class="input-large";

        $descripcion = $this->createElement('textarea', 'descripcion');
        $descripcion->setLabel(Zend_Registry::get('Zend_Translate')->translate('m094').': ');
        $descripcion->setRequired(true);
        $descripcion->setAttrib('rows', 5);

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m093')));

        //Añadir elementos creados al formulario
        $this->addElements(array($nombre, $descripcion, $submit));

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
