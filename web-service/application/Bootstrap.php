<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    // Inicializa la ruta para que el coja el index de /buscador/buscador/index
    public function _initRoutes()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $route = new Zend_Controller_Router_Route_Static (
            '',
            array(
                'module'     => 'buscador',
                'controller' => 'buscador',
                'action'     => 'index'
            )
        );

        $router->addRoute('', $route);
    }

    // Mensajes de error genéricos en español
    protected function _initLanguages()
    {
        $translator = new Zend_Translate(
            'array',
            '../resources/languages',
            'es',
            array('scan' => Zend_Translate::LOCALE_DIRECTORY)
        );

        Zend_Validate_Abstract::setDefaultTranslator($translator);

    }

    // Multilenguaje
    protected function _initTranslation()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            if (isset($_COOKIE['lang'])) {
                setcookie('lang', $_COOKIE['lang'], 0, "/");
                $localeCookie = $_COOKIE['lang'];
            } else {
                setcookie('lang', 'gl_GL', 0, "/");
                $localeCookie = 'gl_GL';
                $translate = new Zend_Translate('array', '../resources/translations/gl_GL.php', $localeCookie);
                Zend_Registry::set('Zend_Translate', $translate);
            }

        } else {
            //Elimnar cookie idioma
            setcookie('lang', null);

            $auth = Zend_Auth::getInstance();
            $data = $auth->getStorage()->read();
            $localeCookie = $data['lang'];

        }

        if ($localeCookie == 'gl_GL') {
            $translate = new Zend_Translate('array', '../resources/translations/gl_GL.php', $localeCookie);
            Zend_Registry::set('Zend_Translate', $translate);
        } elseif ($localeCookie == 'es_ES') {
            $translate = new Zend_Translate('array', '../resources/translations/es_ES.php', $localeCookie);
            Zend_Registry::set('Zend_Translate', $translate);
        } elseif ($localeCookie == 'en_EN') {
            $translate = new Zend_Translate('array', '../resources/translations/en_EN.php', $localeCookie);
            Zend_Registry::set('Zend_Translate', $translate);
        }

    }

}
