<?php

class Webservices_Service_Tmxservices
{
    public function getTmx($proyecto, $version)
    {
        $tablaProyectos = new Proyectos_Model_DbTable_Projects();
        $tablaVersiones = new Proyectos_Model_DbTable_Versions();
        $tablaCadenas = new Proyectos_Model_DbTable_Translationunits();

        $idProyecto = $tablaProyectos->getIdProyectoPorNombre($proyecto);
        $idVersion = $tablaVersiones->getIdVersionProyectoPorNombre($version, $idProyecto);

        $cadenas = $tablaCadenas->getTranslationUnitsPorIdVersion($idVersion);

        set_time_limit(0);//para evitar que se exceda el limite de tiempo

        // TODO generar tmx englobando las cadenas correspodientes a la version
        // http://zf.local/webservices/webservices/index?method=getTmx&proyecto=Proyecto&version=1.0

        $tmx = "<?xml version='1.0' encoding='UTF-8'?>
                    <!DOCTYPE tmx SYSTEM 'tmx12.dtd'>
                    <tmx version='1.4'>
                    <header datatype='PlainText'/>
                    <body>";

            $i = 0;
            while ($i < $cadenas->count()) {

                $cadenaActual = $cadenas[$i];

                $cadenaOriginal = $cadenaActual['original_unit'];
                $cadenaTraducida = $cadenaActual['translated_unit'];

                $cadenaOriginal = htmlspecialchars($cadenaOriginal, ENT_QUOTES);
                $cadenaTraducida = htmlspecialchars($cadenaTraducida, ENT_QUOTES);

                $tmx .= "<tu";
                if ($cadenaActual['comment'] != null) {
                    $tmx .= " note='".$cadenaActual['comment']."'";
                }
                $tmx .= ">";

                    $tmx .= " <tuv xml:lang='".$cadenaActual['original_unit_language']."'>";
                        $tmx .= " <seg>".$cadenaOriginal."</seg>";
                    $tmx .= "</tuv>";

                    $tmx .= " <tuv xml:lang='".$cadenaActual['translated_unit_language']."'>";
                        $tmx .= " <seg>".$cadenaTraducida."</seg>";
                    $tmx .= "</tuv>";

                $tmx .= "</tu>";

                $i = $i + 1;
            }

            $tmx .= "</body> </tmx>";

            //echo $tmx;exit;

            $xml = simplexml_load_string($tmx);

            //echo $xml;exit;
            return $xml;

    }

    public function getResults($cadena, $proyecto, $version)
    {
        $tablaCadenas = new Proyectos_Model_DbTable_Translationunits();

        $cadenas = $tablaCadenas->getResultadosBusquedaLimit($cadena, $proyecto, $version);
        // TODO generar tmx englobando las cadenas correspodientes a la búsqueda
        // http://zf.local/webservices/webservices/index?method=getResults&cadena=statistics

        if ($cadenas != null) {

            $tmx = "<?xml version='1.0' encoding='UTF-8'?>
                    <!DOCTYPE tmx SYSTEM 'tmx12.dtd'>
                    <tmx version='1.4'>
                    <header datatype='PlainText'/>
                    <body>";

            $i = 0;

            while ($i < $cadenas->count()) {

                $cadenaActual = $cadenas[$i];

                $cadenaOriginal = $cadenaActual['original_unit'];
                $cadenaTraducida = $cadenaActual['translated_unit'];

                $cadenaOriginal = htmlspecialchars($cadenaOriginal);
                $cadenaTraducida = htmlspecialchars($cadenaTraducida);

                $tmx .= "<tu";
                if ($cadenaActual['comment'] != null) {
                    $tmx .= " note='".$cadenaActual['comment']."'";
                }
                $tmx .= ">";

                    $tmx .= " <tuv xml:lang='".$cadenaActual['original_unit_language']."'>";
                        $tmx .= " <seg>".$cadenaOriginal."</seg>";
                    $tmx .= "</tuv>";

                    $tmx .= " <tuv xml:lang='".$cadenaActual['translated_unit_language']."'>";
                        $tmx .= " <seg>".$cadenaTraducida."</seg>";
                    $tmx .= "</tuv>";

                $tmx .= "</tu>";

                $i = $i + 1;
            }

            $tmx .= "</body> </tmx>";

            $xml = simplexml_load_string($tmx);

            return $xml;

        } else {
            //TODO no hay resultados
            return '0';
        }

    }

}
