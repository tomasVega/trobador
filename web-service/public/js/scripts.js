    //Carga las versiones en un combo según el proyecto selecionado
    function cargarVersiones(){

        var opcionSeleccionada=$("#proyecto").val();
        var selectDestino=$('#version');

        $.ajax(
            {
                async: true,
                type: 'POST',
                url: '/proyectos/memorias/ajax',
                data: "idproyecto="+opcionSeleccionada,
                dataType: 'json',

                beforeSend:function(data){
                    selectDestino.empty();
                },
                success: function(data){
                    for (var i=0 in data)
                    {
                        selectDestino.append(
                            $('<option></option>').val(data[i].id).html(data[i].version)
                        );
                    }
                },
                error: function(requestData){

                }
            });
    }


    function submitFormElegirIdioma() {

        document.forms["frmElegirIdioma"].submit();

    }


    function addEliminarProyecto(idProyecto, mensaje, mensajeBoton){

        modalHeader = document.getElementById("modal-header");
        modalFooter = document.getElementById("modal-footer");

        //if(!document.getElementById("cabeceraPregunta") && !document.getElementById("btnGuardarModal")){
            h3 = document.createElement("h3");
            h3.setAttribute('id', 'cabeceraPregunta');
            h3.appendChild(document.createTextNode(mensaje+'?'));
            modalHeader.appendChild(h3);

            linkAceptar = document.createElement('a');
            linkAceptar.setAttribute('class', 'btn btn-primary');
            linkAceptar.setAttribute('id', 'btnGuardarModal');
            linkAceptar.innerHTML = mensajeBoton;
            linkAceptar.setAttribute('href', "http://"+location.host+"/proyectos/proyectos/eliminarproyecto?idProyecto="+idProyecto);

            modalFooter.appendChild(linkAceptar);
        //}

    }


    function addEliminarVersion(idVersion, mensaje, mensajeBoton){

        modalHeader = document.getElementById("modal-header");
        modalFooter = document.getElementById("modal-footer");

        //if(!document.getElementById("cabeceraPregunta") && !document.getElementById("btnGuardarModal")){
            h3 = document.createElement("h3");
            h3.setAttribute('id', 'cabeceraPregunta');
            h3.appendChild(document.createTextNode(mensaje+'?'));
            modalHeader.appendChild(h3);

            linkAceptar = document.createElement('a');
            linkAceptar.setAttribute('class', 'btn btn-primary');
            linkAceptar.setAttribute('id', 'btnGuardarModal');
            linkAceptar.innerHTML = mensajeBoton;
            linkAceptar.setAttribute('href', "http://"+location.host+"/proyectos/versiones/eliminarversion?idVersion="+idVersion);

            modalFooter.appendChild(linkAceptar);
        //}

    }


    function addEliminarUsuario(idUsuario, mensaje, mensajeBoton){

        modalHeader = document.getElementById("modal-header");
        modalFooter = document.getElementById("modal-footer");

        //if(!document.getElementById("cabeceraPregunta") && !document.getElementById("btnGuardarModal")){
            h3 = document.createElement("h3");
            h3.setAttribute('id', 'cabeceraPregunta');
            h3.appendChild(document.createTextNode(mensaje+'?'));
            modalHeader.appendChild(h3);

            linkAceptar = document.createElement('a');
            linkAceptar.setAttribute('class', 'btn btn-primary');
            linkAceptar.setAttribute('id', 'btnGuardarModal');
            linkAceptar.innerHTML = mensajeBoton;
            linkAceptar.setAttribute('href', "http://"+location.host+"/administracion/usuarios/eliminarusuario?idUsuario="+idUsuario);

            modalFooter.appendChild(linkAceptar);
        //}

    }

