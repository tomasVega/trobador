<?php

class Buscador_Form_Buscar extends Zend_Form
{
    public function __construct($options=null)
    {
        parent::__construct($options);
        //Config del formulario
        $this->setName('frmBuscador');
        $this->setAction('/buscador/buscador/index');
        $this->setMethod('post');

        //Elementos del formulario
        $cadena = $this->createElement('text','cadena');
        $cadena->setAttrib("placeholder", Zend_Registry::get('Zend_Translate')->translate('m020'));
        $cadena->setRequired(false);
        $cadena->addValidator(new Zend_Validate_StringLength(2));
        $cadena->class = "xxlarge";

        $idiomaOrigen = $this->createElement('select','idiomaOrigen');
        $idiomaOrigen->setRequired(false);

        $tablaIdiomas = new Buscador_Model_DbTable_Languages();

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $auth = Zend_Auth::getInstance();
            $data = $auth->getStorage()->read();
            $idioma = $data['lang'];
        } elseif (isset($_COOKIE['lang'])) {
            $idioma = $_COOKIE['lang'];
        } else {
            $idioma = 'gl_GL';
        }

        $idiomaOrigen->addMultiOption(NULL,'['.Zend_Registry::get('Zend_Translate')->translate('m021').']');
        //Lee los idiomas de la BBDD
        foreach ($tablaIdiomas->getListaIdiomas($idioma) as $i) {
            $idiomaOrigen->addMultiOption($i['unit_language'], $i['language_name']);
        }

        $boton = new Zend_Form_Element_Button('btnCambiarIdioma');
        $boton->setLabel("");
        $boton->setDecorators(array('ViewHelper'));
        $boton->setAttrib('onClick', 'buttonElegirIdioma();');
        $boton->class="btn cambiarIdioma";

        $idiomaDestino = $this->createElement('select','idiomaDestino');
        $idiomaDestino->setRequired(false);
        $idiomaDestino->addMultiOption(NULL,'['.Zend_Registry::get('Zend_Translate')->translate('m022').']');

        //Lee los idiomas de la BBDD
        foreach ($tablaIdiomas->getListaIdiomas($idioma) as $i) {
            $idiomaDestino->addMultiOption($i['unit_language'], $i['language_name']);
        }

        $submit = $this->createElement('submit','submit',array('label'=>Zend_Registry::get('Zend_Translate')->translate('m018')));
        $submit->class="submit button";

        $hidden = $this->createElement('hidden','oculto');

        //Añadir elementos creados al formulario
        $this->addElements(array($cadena, $submit, $hidden, $idiomaOrigen, $boton, $idiomaDestino));

        //Eliminar decoradores (Formatear form)
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
        ));
        $submit->setDecorators(array(
            'ViewHelper',
        ));

        $hidden->setDecorators(array(
            array(array('espacio2' => 'HtmlTag'), array('tag' => 'br')),
        ));

        $idiomaOrigen->setDecorators(array(
            'ViewHelper',
        ));

        $idiomaDestino->setDecorators(array(
            'ViewHelper',
        ));

        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));

    }

}
