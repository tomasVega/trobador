$(document).ready(function() {

    // Mostrar form de crear nuevo usuario
    $("#frmNuevoUsuario").hide();
    $("#linkCancelar").hide();
    $("#linkNuevoUsuario").click(function() {
        $("#frmNuevoUsuario").show();
        $("#linkCancelar").show();
    });

    //Ocultar form de crear nuevo usuario
    $("#linkCancelar").click(function(){
        $("#frmNuevoUsuario").hide();
        $("#linkCancelar").hide();
    });
    
    // Cerrar mensajes de error
    $("#closealerta").click(function () {
        $("#alerta").remove();
        
    });
    
    


    if(document.getElementById("tablaProyectos")){
        var tablaProyectos = document.getElementById("tablaProyectos");
        var linksProyectos = tablaProyectos.getElementsByTagName("A");
        
        for (var i = 0; i < linksProyectos.length; i++) {
            $("#btnModal"+i).click(function () {
                $.blockUIModal(); 
                $('#myModal').removeClass('hide');
            });
        }
    }
    
    if(document.getElementById("tablaVersiones")){
        var tablaVersiones = document.getElementById("tablaVersiones");
        var linksVersiones = tablaVersiones.getElementsByTagName("A");
        
        for (var j = 0; j < linksVersiones.length; j++) {
            $("#btnModal"+j).click(function () {
                $.blockUIModal(); 
                $('#myModal').removeClass('hide');
            });
        }
    }
    
    
    if(document.getElementById("tablaUsuarios")){
        var tablaUsuarios = document.getElementById("tablaUsuarios");
        var linksUsuarios = tablaUsuarios.getElementsByTagName("A");
        
        for (var j = 0; j < linksUsuarios.length; j++) {
            $("#btnModal"+j).click(function () {
                $.blockUIModal(); 
                $('#myModal').removeClass('hide');
            });
        }
    }

    
    
    
    
    
    $("#btnCerrarModal").click(function () {
        $.unblockUIModal();
        $('#myModal').addClass('hide');
        $('#btnGuardarModal').remove();
        $('#cabeceraPregunta').remove();
        
    });
    
    $("#btnCerrarModal1").click(function () {
        $.unblockUIModal();
        $('#myModal').addClass('hide');
        $('#btnGuardarModal').remove();
        $('#cabeceraPregunta').remove();
        
    });
    
    $("#btnGuardarModal").click(function () {
        $.unblockUIModal();
        $('#myModal').addClass('hide');
        $('#btnGuardarModal').remove();
        $('#cabeceraPregunta').remove();
    });
    
    
    
    
    function init(){
        
        currentLink=document.getElementsByTagName("A")
        num=0

        while(currentLink[num]){
            if(currentLink[num].className==""){
                currentLink[num].enabledState=true // note to self: enabledState is my own custom variable ... DOH!
                currentLink[num].onclick = function(){return false} // cancel default action
            }
        num++
        }
 
    }
 
    function togglelink(etiqueta){

        currentLink=document.getElementById(etiqueta)

        if(currentLink.enabledState){
            //alert('hey')
            currentLink.enabledState = false
            currentLink.onclick=function(){return false} // cancel default action of the link

            currentLink.className="disabled"

        } else {
            //alert('hou')
            currentLink.enabledState=true
            currentLink.onclick=function(){return true}

            currentLink.className=""
            obj.innerHTML="Disable Link "+n

        }

    }
    
    
    $('#submitMemoria').click(function() { 
            $.blockUI(); 
            currentLink=document.getElementById('menu-Pag1');
            currentLink.enabledState=false;
            //$('menu-Pag1').addClass('disabled');
            init();
            currentLink=document.getElementsByTagName("A")
            num=0
 
            while(currentLink[num]){
                togglelink(currentLink[num].id);
                num++;
            }
            //alert('hola');
    });
    
 



    
});


