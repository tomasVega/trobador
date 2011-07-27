//Carga las versiones en un combo según el proyecto selecionado
function cargarVersiones(){

    var opcionSeleccionada=$("#proyecto").val();
    var selectDestino=$('#version');
    
    $.ajax(
        {
            async: true,
            type: 'POST',
            url: '/memorias/memorias/ajax',
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
