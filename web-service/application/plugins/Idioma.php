<?php
    
    class Application_Plugin_Idioma extends Zend_Controller_Plugin_Abstract{
        
//        public function preDispatch(Zend_Controller_Request_Abstract $request){
//            
//            $lang = $request->getParam('lang','');
//
//            if ($lang !== 'en' && $lang !== 'gl' && $lang !== 'es')
//                $request->setParam('lang','gl');
//
//            $lang = $request->getParam('lang');
//            if ($lang == 'en'){
//                $locale = 'en_EN';
//            } else if ($lang == 'gl'){
//                $locale = 'gl_GL';
//            } else if ($lang == 'es') {
//                $locale = 'es_ES';
//            }
//            
//            $zl = new Zend_Locale();
//            $zl->setLocale($locale);
//            Zend_Registry::set('Zend_Locale', $zl);
//
//            $translate = new Zend_Translate('array', '../resources/translations/'. $locale . '.php' , $lang);
//            Zend_Registry::set('Zend_Translate', $translate);
//        
//        }
    
    }

?>