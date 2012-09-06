<?php

class Administracion_Form_Nuevousuario extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        //Config del formulario
        $this->setName('frmEditarUsuario');
        $this->setAction('/administracion/usuarios/nuevousuario');
        $this->setMethod('post');

        //Elementos del formulario
        $nombre = $this->createElement('text', 'name');
        $nombre->setRequired(true);
        $nombre->setAttrib('size', 20);
        $nombre->setAttrib('maxlength', 100);
        $nombre->setValue($options['name']);
        $nombre->class="input-small";

        $email = $this->createElement('text', 'email');
        $email->setRequired(true);
        $email->setAttrib('size', 20);
        $email->setAttrib('maxlength', 200);
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addFilters(array(
            new Zend_Filter_StringTrim(),
            new Zend_Filter_StringToLower(),
        ));
        $email->setValue($options['email']);
        $email->class="input-small";

        $roles = new Zend_Form_Element_Select('roles');
        $roles->setRequired(true);

        $tablaRoles = new Administracion_Model_DbTable_Roles();

        //Lee los roles de la BBDD
        foreach ($tablaRoles->getRoles() as $rol) {
            if($rol['role_name'] != 'invitado'){
                $roles->addMultiOption($rol['role_id'], $rol['role_name']);
            }
        }
        $roles->setValue($options['role_id']);
        $roles->class="small";

        $activo = new Zend_Form_Element_Select('activo');
        $activo->setRequired(true);
        $activo->addMultiOption(1, Zend_Registry::get('Zend_Translate')->translate('m096'));
        $activo->setValue($options['activated']);
        $activo->class="small";


        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m093')));
        
        //Añadir elementos creados al formulario
        $this->addElements(array($nombre, $email, $roles, $activo, $submit));

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
        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));

    }

}

?>


