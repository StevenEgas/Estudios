
function guardarUsuario() {
    // Capturar el valor del campo de texto
    var nombre_usuario = document.getElementById('txt_nombre_usuario').value;
    console.log(nombre_usuario)
    
    localStorage.setItem('nombre_user', nombre_usuario);
    alert('Usuario guardado: ' + nombre_usuario);
}