<?php

class Buscador_BuscadorController extends Zend_Controller_Action
{
    protected $_tablaMemorias;
    protected $_tablaVersiones;
    protected $_tablaProyectos;

    public function init()
    {
        $this->_tablaMemorias = new Proyectos_Model_DbTable_Translationunits();
        $this->_tablaVersiones = new Proyectos_Model_DbTable_Versions();
        $this->_tablaProyectos = new Proyectos_Model_DbTable_Projects();

    }

    // Buscador, página de inicio de la aplicación
    public function indexAction()
    {
        $this->view->headTitle("Trobador");
        $formularioBusqueda = new Buscador_Form_Buscar();
        $mensaje = "";

        $cadena = $this->_getParam('cadena');
        $idiomaOrigen = $this->_getParam('idiomaOrigen');
        $idiomaDestino = $this->_getParam('idiomaDestino');

        if ($this->getRequest()->isPost()
            || (!is_null($cadena) && !is_null($idiomaDestino) && !is_null($idiomaDestino))
        ) {

            if ($this->getRequest()->isPost() && $formularioBusqueda->isValid($_POST)) {
                // Datos recibidos del formulario
                $cadena = $formularioBusqueda->getValue('cadena');
                $idiomaOrigen = $formularioBusqueda->getValue('idiomaOrigen');
                $idiomaDestino = $formularioBusqueda->getValue('idiomaDestino');
            }


            $this->view->searchTopic = $cadena;

            // Realizar busqueda
            $resultados = $this->_tablaMemorias->getResultadosBusquedaIdiomas($cadena, $idiomaOrigen, $idiomaDestino);

            if ($resultados != null) {

                list($proyectos, $versiones) = $this->getProjectsAndVersions();

                $this->view->num = $resultados->count();
                $this->view->proyectos = $proyectos;
                $this->view->versiones = $versiones;

                // Get pagination configuration
                $pageNumber = $itemNumber = 30;
                $paginator = Zend_Paginator::factory($resultados);
                $paginator->setItemCountPerPage($pageNumber);
                $paginator->getItemsByPage($itemNumber);
                $paginator->setCurrentPageNumber($this->_getParam('page'));
                Zend_Paginator::setDefaultScrollingStyle('Sliding');

                $this->view->resultado = $paginator;
                $this->view->paginator = $paginator;
                $this->view->cadena = $cadena;
                $this->view->idiomaOrigen = $idiomaOrigen;
                $this->view->idiomaDestino = $idiomaDestino;

            } else {
                $mensaje = Zend_Registry::get('Zend_Translate')->translate('m023');
                $this->_helper->FlashMessenger($mensaje);
            }
        }

        $this->view->form = $formularioBusqueda;
    }

    public function cambiaridiomaAction()
    {
        $formularioCambiarIdiomaApp = new Buscador_Form_Elegiridioma();
        // Si se reciben datos por post
        if ($this->getRequest()->isPost()) {
            // Si los datos recibidos son válidos
            if ($formularioCambiarIdiomaApp->isValid($_POST)) {
                // Datos recibidos del formulario
                $idioma = $formularioCambiarIdiomaApp->getValue('idiomaApp');
                $url = $formularioCambiarIdiomaApp->getValue('url');

                if (!Zend_Auth::getInstance()->hasIdentity()) {
                    if (isset($_COOKIE['lang'])) {
                        setcookie('lang', $idioma, 0, "/");
                    } else {
                        setcookie('lang', $idioma, 0, "/");
                    }
                } else {

                    $auth = Zend_Auth::getInstance();
                    $data = $auth->getStorage()->read();

                    if ($data['lang']) {
                        $data['lang'] = $idioma;
                        $auth->getStorage()->write($data);
                    }
                }

                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);
                $this->_redirect($url);
            }
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_redirect('/buscador/buscador/index');
    }

    private function getProjectsAndVersions() {
        $proyectos = $versiones = array();

        $projectsRAW = $this->_tablaProyectos->fetchAll();
        foreach ($projectsRAW as $project) {
            $proyectos[$project['project_id']] = array(
                'project_name' => $project['project_name'],
                'user_id'      => $project['user_id']
            );
        }

        $versionesRAW = $this->_tablaVersiones->fetchAll();
        foreach ($versionesRAW as $version) {
            $versiones[$version['version_id']] = array(
                'version_name' => $version['version_name'],
                'project_id'   => $version['project_id'],
                'project_name' => $proyectos[$version['project_id']]['project_name']
            );
        }

        return array($proyectos, $versiones);
    }

}
