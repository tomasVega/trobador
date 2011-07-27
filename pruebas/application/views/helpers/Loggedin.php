<?php

class Application_View_Helper_Loggedin extends Zend_View_Helper_Abstract {

    public function loggedin($nombre) {

        return "<ul id='menu'>
                    <li><a href='/buscador/buscador/'>Inicio</a></li>
                    <li><a href='/memorias/memorias/subirmemoria'>Subir arquivo</a></li>
                    <li><a href='/proyectos/proyectos/verproyectos'>Os meus proxectos</a></li>
                    <li><a href='#'>Crear</a>
                        <ul>
                            <li><a href='/proyectos/proyectos/crearproyecto'>Novo proxecto</a></li>
                            <li><a href='/proyectos/proyectos/crearversion'>Nova version</a></li>
                        </ul>
                    </li>
                    <li><a href='#'>".$nombre."</a>
                        <ul>
                            <li><a href='/usuarios/usuarios/modificardatos'>Modificar datos</a></li>
                            <li><a href='/usuarios/usuarios/logout'>Cerrar sesion</a></li>
                        </ul>
                    </li>
                </ul>";
    }

}
?>
