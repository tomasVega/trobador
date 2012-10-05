<?php

class Proyectos_Form_Nuevamemoria extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setName('frmNuevaMemoria');
        $this->setAction('/proyectos/memorias/subirmemoria');
        $this->setMethod('post');

        //Elementos del formulario
        $file = new Zend_Form_Element_File('archivo');
        $file->setLabel(Zend_Registry::get('Zend_Translate')->translate('m047').': ');
        $file->setRequired(true);
        $file->setDestination(UPLOAD_PATH);
        $file->addValidator('Count',false,100);
        $file->addValidator('Extension',false,'tmx, zip');
        $file->addValidator('Size', false, 22102400);
        $file->setMaxFileSize(22102400);

        $proyectos = new Zend_Form_Element_Select('proyecto');
        $proyectos->setLabel(Zend_Registry::get('Zend_Translate')->translate('m029').': ');
        $proyectos->setRequired(true);
        $proyectos->setAttrib('onChange', 'cargarVersiones();');

        $auth = Zend_Auth::getInstance();
        $data = $auth->getStorage()->read();
        $tablaProyectos = new Proyectos_Model_DbTable_Projects();

        $proyectos->addMultiOption(NULL,'['.Zend_Registry::get('Zend_Translate')->translate('m048').']');
        //Lee los proyectos de la BBDD segun el usuario
        foreach ($tablaProyectos->getListaProyectosPorIdUsuario($data['user_id']) as $p) {
            $proyectos->addMultiOption($p['project_id'], $p['project_name']);
        }

        $versiones = $this->createElement('select', 'version', array(
            'required'                 => true,
            'label'                    => 'Version: ',
            'registerInArrayValidator' => false,//Necesario para ajax
            'multiOptions'             => array(NULL => '['.Zend_Registry::get('Zend_Translate')->translate('m048').']')
        ));

        $submit = $this->createElement('submit','submitMemoria',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m039')));

        //Añadir elementos creados al formulario
        $this->addElements(array($file, $proyectos, $versiones, $submit));

    }

}
