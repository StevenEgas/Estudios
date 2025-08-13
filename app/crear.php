<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - CRUD</title>
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body class="form-page">

    <div class="container-fluid">
        <div class="form-container">
            <div class="form-header">
                <h2>Crear Nuevo Usuario</h2>
                <p>Completa el formulario para agregar un nuevo usuario</p>
            </div>
            
            <form action="guardar.php" method="POST" class="user-form">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="edad">Edad</label>
                    <input type="number" id="edad" name="edad" placeholder="Ingresa tu edad" min="1" max="120" class="form-control" required>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">
                        <span>💾</span> Guardar Usuario
                    </button>
                    <a href="../index.html" class="btn-cancel">
                        <span>↩️</span> Volver al Inicio
                    </a>
                </div>
            </form>
        </div>
    </div>

<script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="../public/js/toast.js"></script>

<script>
// Agregar funcionalidad con Fetch API y notificaciones Toastify
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    // Validaciones frontend
    const nombre = formData.get('nombre').trim();
    const email = formData.get('email').trim();
    const edad = parseInt(formData.get('edad'));
    
    if (!nombre || !email || !edad) {
        toastNotification.error('Por favor completa todos los campos');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        toastNotification.error('Por favor ingresa un email válido');
        return;
    }
    
    if (edad < 1 || edad > 120) {
        toastNotification.error('La edad debe estar entre 1 y 120 años');
        return;
    }
    
    // Mostrar loading
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
    
    // Enviar con Fetch API
    fetch('guardar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Asumiendo que guardar.php redirecciona o devuelve algo
        // Si es exitoso, mostrar notificación
        toastNotification.success('¡Usuario creado exitosamente!');
        
        // Limpiar formulario
        document.querySelector('form').reset();
        
        setTimeout(() => {
            window.location.href = 'listar.php';
        }, 2000);
    })
    .catch(error => {
        console.error('Error:', error);
        toastNotification.error('Error al crear el usuario');
    })
    .finally(() => {
        // Restaurar botón
        button.disabled = false;
        button.innerHTML = originalText;
    });
});
</script>   
</body>
</html>