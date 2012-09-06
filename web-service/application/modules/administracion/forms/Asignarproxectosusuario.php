<?php

class Administracion_Form_Asignarproxectosusuario extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);

        //Config del formulario
        $this->setName('frmAsignarProxectosUsuario');
        $this->setAction('/administracion/usuarios/asignarproxectosusuario');
        $this->setMethod('post');

        //Elementos del formulario
        $proyectos = new Zend_Form_Element_Select('proyectos');
        $proyectos->setRequired(true);

        $tablaUsuariosProyectos = new Proyectos_Model_DbTable_Usersprojects();
        $tablaProyectos = new Proyectos_Model_DbTable_Projects();

        //Lee los proyectos de la BBDD
        $proyectos->addMultiOption("-1", "[".Zend_Registry::get('Zend_Translate')->translate('m048')."]");
        foreach ($tablaUsuariosProyectos->getProyectosNoAsignadosPorId($options['idUsuario']) as $proyecto) {
            $pro = $tablaProyectos->getProyectoPorId($proyecto['project_id']);
            $proyectos->addMultiOption($pro['project_id'], $pro['project_name']);
        }
        $proyectos->class="small";

        $idUsuario = new Zend_Form_Element_Hidden('idUsuario');
        $idUsuario->setValue($options['idUsuario']);
        $idUsuario->setDisableLoadDefaultDecorators(true);

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m092')));

        //Añadir elementos creados al formulario
        $this->addElements(array( $proyectos, $submit, $idUsuario));

        //Eliminar decoradores (Formatear form)
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
        ));
        $submit->setDecorators(array(
            'ViewHelper',
            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        ));
        $idUsuario->setDecorators(array('ViewHelper'));
        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));

    }

}
