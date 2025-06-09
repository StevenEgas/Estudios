function guardarDatosUsuario(){
    // 1. Obtener los valores de las cajas de textos

    var usuario_n=document.getElementById('txt_nombre').value;
    var usuario_a=document.getElementById('txt_apellido').value;
    var usuario_e=document.getElementById('txt_email').value;
   
    // 2. Convertir esas variables o atributos en objetos
    // SINTAXIS:
    // (let, var),nombre_objeto = { newvariable:valor };
    
    var datos_usuarios = {
        nombre_usuarios : usuario_n,
        apellido_usuarios : usuario_a,
        email_usuarios : usuario_e,
    };
    localStorage.setItem('datos_usuario', JSON.stringify(datos_usuarios));
    alert('Datos del Usuario almacenados exitosamente we');
    location.reload();
        
}

function eliminarDatosUsuarios(){
    
}