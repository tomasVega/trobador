<?php

class Administracion_Form_Asignarrecursos extends Zend_Form {

    public function __construct($options=null){

        parent::__construct($options);

        $tablaRolesRecursosPrivilegios = new Administracion_Model_DbTable_Rolesresourcesprivileges();
        if($options != null){
            $existe = $tablaRolesRecursosPrivilegios->existeRolRecursoPrivilegio($options['role_id'], $options['resource_id'], $options['privilege_id']);
        } else {
            $existe =  false;
        }


        //@TODO probar elementos como hidden

        //Config del formulario
        $this->setName('frmAsignarProxectosUsuario');
        $this->setAction('/administracion/recursos/asignar');
        $this->setMethod('post');

        //.$options['role_id'].$options['resource_id'].$options['privilege_id']

        //Elementos del formulario
        $permiso = new Zend_Form_Element_Checkbox('permiso');
        
        /*$this->setAttrib('role_id', $options['role_id']);
        $this->setAttrib('resource_id', $options['resource_id']);
        $this->setAttrib('privilege_id', $options['privilege_id']);*/
        //$permiso->setAttrib('id', 'permiso'.$options['role_id'].$options['resource_id'].$options['privilege_id']);
        if($existe){
            //$permiso->setValue("debur");
            $permiso->setChecked(true);
        } else {
            $permiso->setChecked(false);
        }
        $permiso->setAttrib('onChange', 'submit()');

        $idRol = new Zend_Form_Element_Hidden('role_id');
        $idRol->setValue($options['role_id']);
        $idRol->setDisableLoadDefaultDecorators(true);

        $idRecurso = new Zend_Form_Element_Hidden('resource_id');
        $idRecurso->setValue($options['resource_id']);
        $idRecurso->setDisableLoadDefaultDecorators(true);

        $idPrivilegio = new Zend_Form_Element_Hidden('privilege_id');
        $idPrivilegio->setValue($options['privilege_id']);
        $idPrivilegio->setDisableLoadDefaultDecorators(true);

        //$submit = $this->createElement('submit','submit',array('label'=>'Asignar'));

        //Añadir elementos creados al formulario
        $this->addElements(array($permiso, $idRol, $idRecurso, $idPrivilegio));

        //Eliminar decoradores (Formatear form)
        $this->setElementDecorators(array(
            'ViewHelper',
        ));
        $idRol->setDecorators(array('ViewHelper'));
        $idRecurso->setDecorators(array('ViewHelper'));
        $idPrivilegio->setDecorators(array('ViewHelper'));
        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));

    }

}

?>

