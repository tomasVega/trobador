<?php

class Proyectos_Form_Nuevaversion extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        $name=$this->createElement('text', 'nombreVersion');
        $name->setLabel('Nome: ');
        $name->setRequired(true);
        $name->setAttrib('size', 18);
        $name->setAttrib('maxlength', 300);
        $name->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));

        $this->addElement($name);

        $proyectos= new Zend_Form_Element_Select('proyecto');
        $proyectos->setLabel('Proxecto: ');
        $proyectos->setRequired(true);

        $auth=Zend_Auth::getInstance();
        $data = $auth->getStorage()->read();
        $tablaProyectos= new Proyectos_Model_DbTable_Projects();

        $proyectos->addMultiOption(NULL,'[Seleccione]');
        //Lee los proyectos de la BBDD segun el usuario
        foreach ($tablaProyectos->getListaProyectosPorIdUsuario($data['user_id']) as $p) {
            $proyectos->addMultiOption($p->project_id, $p->project_name);
        }

        $this->addElement($proyectos);

        $this->addElement('submit','submit',array('label'=>'Crear Version'));

    }

}
?>
