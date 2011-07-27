<?php

class Memorias_MemoriasController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function subirmemoriaAction()
    {

        $this->view->headTitle("Subir memoria");

        $formularioNuevaMemoria = new Memorias_Form_Nuevamemoria();

        //Si se reciben datos por post
        if($this->getRequest()->isPost()){
            //Si los datos recibidos son válidos          
            if($formularioNuevaMemoria->isValid($_POST)){
                //Valores del formulario
                $data=$formularioNuevaMemoria->getValues();
                $tablaTmx= new Memorias_Model_DbTable_Tmx();
                //Datos del usuario
                $auth=Zend_Auth::getInstance();
                $userData = $auth->getStorage()->read();
                //Almacenar referencia al archivo
                $tablaTmx->almacenarTmx($data['archivo'], $userData['user_id'], $data['version']);

                $idTmx=$tablaTmx->getIdTmxPorNombre($data['archivo']);

                $params=array(
                        'tmx_id'=>$idTmx,
                        'archivo'=>$data['archivo'],
                );
                //Recibir archivo
                $formularioNuevaMemoria->archivo->receive();

                $this->_helper->viewRenderer->setNoRender(true);
                $this->_helper->redirector('parseararchivo', 'memorias', 'memorias', $params);
                
            }
        }

        $this->view->form=$formularioNuevaMemoria;
        
    }

    public function parseararchivoAction()
    {
    
        $idTmx=$this->getRequest()->getParam('tmx_id');
        $nombreArchivo=$this->getRequest()->getParam('archivo');
        // Carga un archivo XML
        $tmx = new SimpleXMLElement(UPLOAD_PATH.$nombreArchivo, null, true);

        $body=$tmx->body;

        $tablaTranslationUnits=new Memorias_Model_DbTable_Translationunits();
        //Almacenar las cadenas de traduccion
        foreach($body->tu as $tu){

            $cadenaOriginal=$tu->tuv[0]->seg;
            $att_xmlO=$tu->tuv[0]->attributes('xml',1);
            $idiomaCadenaOriginal=$att_xmlO['lang'];
            $i=0;

            foreach($tu->tuv as $tuv){
                if(!$i==0){
                    $cadenaTraducida=$tuv->seg;
                    $att_xmlT=$tuv->attributes('xml',1);
                    $idiomaCadenaTraducida=$att_xmlT['lang'];
                    $tablaTranslationUnits->almacenarTranslationUnit($cadenaOriginal, $idiomaCadenaOriginal, $cadenaTraducida, $idiomaCadenaTraducida, $idTmx);
                }
                $i=$i+1;
            }
        }
            
        //Eliminar fichero real del servidor
        unlink(UPLOAD_PATH.$nombreArchivo);

    }

    function ajaxAction(){

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        if (!$this->getRequest()->isXmlHttpRequest())
        {
            $this->_redirect('/buscador/buscador');
        }


        $tablaVersiones= new Proyectos_Model_DbTable_Versions();

        $id = $_POST["idproyecto"];
        //$jsondata['id'] = 4;
        //$jsondata['version'] = "versionprueba";
        $i=0;
        foreach ($tablaVersiones->getListaVersionesProyectoPorId($id) as $v) {

            $jsondata[$i]=array(
                'id' => $v->version_id,
                'version' => $v->version_name,
            );
            $i=$i+1;
        }

        echo Zend_Json::encode($jsondata);

    }


}
