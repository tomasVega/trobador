<?php

class Buscador_BuscadorController extends Zend_Controller_Action{


    protected $_tablaMemorias;


    public function init()
    {
        $this->_tablaMemorias=new Memorias_Model_DbTable_Translationunits();
    }

    // Buscador, página de inicio de la aplicación
    public function indexAction(){

        $this->view->headTitle("Buscador");
        $formularioBusqueda=new Buscador_Form_Buscar();
        $cadenaTraducida="";

        // Si se reciben datos por post
        if($this->getRequest()->isPost()){
            // Si los datos recibidos son válidos
            if($formularioBusqueda->isValid($_POST)){
                //Datos recibidos del formulario
                $cadena=$formularioBusqueda->getValue('cadena');

                // Realizar busqueda
                $cadenaTraducida=$this->_tablaMemorias->getResultadosBusqueda($cadena);

                if($cadenaTraducida != null){
                    $this->view->resultado=$cadenaTraducida;
                }else{
                    $this->view->resultado="No se han encontrado coincidencias";
                }

            }
        }

        $this->view->form=$formularioBusqueda;
        
    }


}

