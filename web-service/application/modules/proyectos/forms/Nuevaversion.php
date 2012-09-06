<?php

class Proyectos_Form_Nuevaversion extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        //Config del formulario
        $this->setAction('/proyectos/versiones/crearversion');
        $this->setMethod('post');
        $this->setName('frmNuevaVersion');

        
        //Elementos del formulario
        $name = $this->createElement('text', 'nombreVersion');
        $name->setLabel(Zend_Registry::get('Zend_Translate')->translate('m049').': ');
        $name->setRequired(true);
        $name->setAttrib('size', 18);
        $name->setAttrib('maxlength', 300);
        $name->addFilters(array(
            new Zend_Filter_StringTrim(),
        ));

        
        $proyectos = new Zend_Form_Element_Select('proyecto');
        $proyectos->setLabel(Zend_Registry::get('Zend_Translate')->translate('m029').': ');
        $proyectos->setRequired(true);

        $auth = Zend_Auth::getInstance();
        $data = $auth->getStorage()->read();
        $tablaProyectos = new Proyectos_Model_DbTable_Projects();

        $proyectos->addMultiOption(NULL,'['.Zend_Registry::get('Zend_Translate')->translate('m048').']');
        //Lee los proyectos de la BBDD segun el usuario
        foreach ($tablaProyectos->getListaProyectos() as $p) {
            $proyectos->addMultiOption($p->project_id, $p->project_name);
        }
        

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m050')));


        //Añadir elementos creados al formulario
        $this->addElements(array($name, $proyectos, $submit));

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
