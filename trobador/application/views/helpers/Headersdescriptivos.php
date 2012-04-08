<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HeadersDescriptivos
 *
 * @author tommy
 */
class Application_View_Helper_Headersdescriptivos extends Zend_View_Helper_Abstract {

    public function headersdescriptivos() {

        return '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <!-- Breve descripción de la web -->
                <meta http-equiv="description" content=""/>
                <!-- Web implementada mediante -->
                <meta name="generator" content="Zend Framework"/>
                <!-- Autor de la web -->
                <meta name="author" content="Tomas Vega Castro"/>
                <!-- Deficinición de palabras clave para ayuda a la búsqueda del website -->
                <meta name="keywords" content="traducción, galego, tmx"/>
                <!-- Indicamos a los robots de búsqueda que busquen e indexen la página -->
                <meta name="robots" content="follow, index"/>
                <!-- Icono pestaña navegador -->
                <link rel="shortcut icon" href=""/>
                <script language="Javascript" type="text/javascript" src="/js/jquery/jquery-1.6.1.js"></script>';

    }

}
?>
