<?php

class Proyectos_MemoriasController extends Zend_Controller_Action
{
    protected $_tablaVersiones = null;
    protected $_tablaMemorias = null;
    protected $_userData = null;

    public function init()
    {
        $this->_tablaVersiones = new Proyectos_Model_DbTable_Versions();
        $this->_tablaMemorias = new Proyectos_Model_DbTable_Translationunits();
        $this->_acl = new Usuarios_Model_Acl();

        $auth = Zend_Auth::getInstance();
        $this->_userData = $auth->getStorage()->read();

    }

    public function subirmemoriaAction()
    {
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'cadenas', 'crear')) {

            $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m004'));
            $formularioNuevaMemoria = new Proyectos_Form_Nuevamemoria();

            //Si se reciben datos por post
            if ($this->getRequest()->isPost()) {
                //Si los datos recibidos son válidos
                if ($formularioNuevaMemoria->isValid($_POST)) {
                    //Valores del formulario
                    $data = $formularioNuevaMemoria->getValues();
                    //Datos del usuario
                    $auth = Zend_Auth::getInstance();
                    $userData = $auth->getStorage()->read();

                    $params = array(
                            'user_id' => $userData['user_id'],
                            'version_id' => $data['version'],
                            'archivo' => $data['archivo'],
                    );
                    //Recibir archivo
                    $formularioNuevaMemoria->archivo->receive();

                    $this->_helper->viewRenderer->setNoRender(true);
                    $this->_helper->redirector('parseararchivo', 'memorias', 'proyectos', $params);

                }
            }

            $this->view->form = $formularioNuevaMemoria;

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    private function comprobarExtension($nombreArchivo)
    {
        // @todo comprobar patron exp reg
        $patron = '/\.zip$/i';

        if (preg_match($patron, $nombreArchivo)) {
            return true;// si es .zip
        } else {
            return false;
        }

    }

    private function extraerCadenasXml($nombreArchivo, $idUsuario, $idVersion)
    {
        // Carga un archivo XML
        //var_dump(UPLOAD_PATH.$nombreArchivo);exit;
        $tmx = new SimpleXMLElement(UPLOAD_PATH.$nombreArchivo, null, true);
        libxml_use_internal_errors(true);
        // Comprobar errores
        if (!$tmx) {
            // @todo
            //echo "Error cargando XML\n";
            //foreach (libxml_get_errors() as $error) {
                //echo "\t", $error->message;
            //}
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('err006');
        } else {
            $body = $tmx->body;

            //Almacenar las cadenas de traduccion
            foreach ($body->tu as $tu) {

                $comentario = null;
                if ($tu->note) {
                    $comentario = $tu->note;
                }

                $cadenaOriginal = $tu->tuv[0]->seg;
                $att_xmlO = $tu->tuv[0]->attributes('xml',1);
                $idiomaCadenaOriginal = $att_xmlO['lang'];
                $i = 0;

                foreach ($tu->tuv as $tuv) {
                    if (!$i == 0) {
                        $cadenaTraducida = $tuv->seg;
                        $att_xmlT = $tuv->attributes('xml',1);
                        $idiomaCadenaTraducida = $att_xmlT['lang'];

//                        $cadenaOriginal = str_replace("\"","'",$cadenaOriginal);
//                        $cadenaTraducida = str_replace("\"","'",$cadenaTraducida);
//
//                        $cadenaOriginal = str_replace("&amp;#169;","&copy;",$cadenaOriginal);
//                        $cadenaTraducida = str_replace("&amp;#169;","&copy;",$cadenaTraducida);
//                        $cadenaOriginal = str_replace("&amp;#234;","&copy;",$cadenaOriginal);
//                        $cadenaTraducida = str_replace("&amp;#234;","&copy;",$cadenaTraducida);

//                        $cadenaOriginal = html_entity_decode($cadenaOriginal);
//                        $cadenaTraducida = html_entity_decode($cadenaTraducida);

                        $cadenaOriginal = htmlspecialchars_decode($cadenaOriginal);
                        $cadenaTraducida = htmlspecialchars_decode($cadenaTraducida);

                        //echo $cadenaTraducida;
                        //$cadenaTraducida = htmlentities($cadenaTraducida);
//                        $cadenaTraducida = htmlentities($cadenaTraducida);
//                        echo "\n".$cadenaTraducida;
//                        $cadenaTraducida = htmlspecialchars($cadenaTraducida);
//                        echo "\n".$cadenaTraducida;
                        $this->_tablaMemorias->almacenarTranslationUnit($cadenaOriginal, $idiomaCadenaOriginal, $cadenaTraducida, $idiomaCadenaTraducida, $idVersion, $idUsuario, $comentario);
                    }
                    $i = $i + 1;
                }
                //exit;
            }
            $mensaje = Zend_Registry::get('Zend_Translate')->translate('m038');
        }

    }

    // Eliminar los archivos subidos en caso de error
    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    private function extraerZip($nombreArchivo)
    {
        $zip = new ZipArchive;
        if ($zip->open(UPLOAD_PATH.$nombreArchivo) === TRUE) {
            $zip->extractTo(UPLOAD_PATH);
            $zip->close();

            $names[] = null;
            $i = 0;

            $zip = zip_open(UPLOAD_PATH.$nombreArchivo);

            while ($archivo = zip_read($zip)) {
                if (!strpos(zip_entry_name($archivo), DIRECTORY_SEPARATOR)) {
                    $names[$i] = zip_entry_name($archivo);
                    $i = $i +1;
                } else {
                    $indice = strpos(zip_entry_name($archivo), DIRECTORY_SEPARATOR);
                    $dir = substr(zip_entry_name($archivo), 0, $indice+1);
                    $this->rrmdir(UPLOAD_PATH.$dir);

                    return 0;
                }
            }

            zip_close($zip);

            return $names;

        } else {
            return null;
        }

    }

    public function parseararchivoAction()
    {
        // Comprobación de permisos
        if ($this->_acl->tienePermiso($this->_userData['role_name'], 'cadenas', 'crear')) {
            $mensaje = "";
            if($this->getRequest()->getParam('user_id') != null
                    && $this->getRequest()->getParam('version_id') != null
                    && $this->getRequest()->getParam('archivo')) {

                $this->view->headTitle(Zend_Registry::get('Zend_Translate')->translate('m039'));

                $idUsuario = $this->getRequest()->getParam('user_id');
                $idVersion = $this->getRequest()->getParam('version_id');
                $nombreArchivo = $this->getRequest()->getParam('archivo');

                set_time_limit(0);//para evitar que se exceda el limite de tiempo
                // si es un .zip
                if ($this->comprobarExtension($nombreArchivo)) {

                    $names = $this->extraerZip($nombreArchivo);
                    if ($names != null && $names != 0) {
                        // Al subir un archivo de una versión existente se eliminan todas las cadenas anteriores
                        $this->_tablaMemorias->eliminarCadenasVersion($idVersion);

                        $j = 0;
                        while ($j < sizeof($names)) {
                            //var_dump($names[$j]);exit;
                            $this->extraerCadenasXml($names[$j], $idUsuario, $idVersion);
                            // Eliminar archivo
                            unlink(UPLOAD_PATH.$names[$j]);
                            $j = $j +1;
                        }
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('m038');
                    } elseif ($names == 0) {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err007');
                    } else {
                        $mensaje = Zend_Registry::get('Zend_Translate')->translate('err008');
                    }
                //si es un solo archivo
                } else {
                    // Al subir un archivo de una versión existente se eliminan todas las cadenas anteriores
                    $this->_tablaMemorias->eliminarCadenasVersion($idVersion);
                    $this->extraerCadenasXml($nombreArchivo, $idUsuario, $idVersion);
                    $mensaje = Zend_Registry::get('Zend_Translate')->translate('m038');
                }

                //Eliminar fichero real del servidor
                unlink(UPLOAD_PATH.$nombreArchivo);

            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('err009');
            }

            $this->view->mensaje = $mensaje;

        } else {
            $this->_redirect('administracion/recursos/errorpermisos');
        }

    }

    public function ajaxAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->_redirect('/buscador/buscador');
        }

        $id = $_POST["idproyecto"];
        //$jsondata['id'] = 4;
        //$jsondata['version'] = "versionprueba";
        $i = 0;
        foreach ($this->_tablaVersiones->getListaVersionesProyectoPorId($id) as $v) {
            $jsondata[$i] = array(
                'id' => $v->version_id,
                'version' => $v->version_name,
            );
            $i = $i + 1;
        }

        echo Zend_Json::encode($jsondata);

    }

}
