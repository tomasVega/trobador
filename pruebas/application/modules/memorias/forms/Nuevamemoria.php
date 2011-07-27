<?php

class Memorias_Form_Nuevamemoria extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setName('nuevaMemoriaForm');
        $this->setAction('subirmemoria');
        $this->setMethod('post');

        $file=new Zend_Form_Element_File('archivo');
        $file->setLabel('Subir: ');
        $file->setRequired(true);
        $file->setDestination(UPLOAD_PATH);
        $file->addValidator('Count',false,1);
        $file->addValidator('Extension',false,'tmx');
        $this->addElement($file);
        
        
        $proyectos= new Zend_Form_Element_Select('proyecto');
        $proyectos->setLabel('Proxecto: ');
        $proyectos->setRequired(true);
        $proyectos->setAttrib('onChange', 'cargarVersiones();');

        $auth=Zend_Auth::getInstance();
        $data = $auth->getStorage()->read();
        $tablaProyectos= new Proyectos_Model_DbTable_Projects();
        
        $proyectos->addMultiOption(NULL,'[Seleccione]');
        //Lee los proyectos de la BBDD segun el usuario
        foreach ($tablaProyectos->getListaProyectosPorIdUsuario($data['user_id']) as $p) {
            $proyectos->addMultiOption($p->project_id, $p->project_name);
        }

        

        $this->addElement($proyectos);

        $this->addElement('select', 'version', array(
            'required' => true,
            'label' => 'Version: ',
            'registerInArrayValidator' => false,//Necesario para ajax
            'multiOptions' => array(NULL => '[Seleccione]')
        ));

        $this->addElement('submit','submit',array('label'=>'Subir memoria'));
        
    }
    
}

?>
