<?php
// Solo necesitamos la conexión, el resto lo maneja JavaScript con Fetch API
require_once '../conexion/bd.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nota</title>
    <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" href="../public/css/estilo.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        .badge-secondary { background-color: #6c757d; }
        .alert-info { 
            background-color: #d1ecf1; 
            border-color: #bee5eb; 
            color: #0c5460; 
        }
    </style>
</head>
<body class="form-page">

<div class="form-container">
    <div class="form-header">
        <h2>Editar Nota</h2>
        <p>Modifica los datos de la nota</p>
    </div>
    
    <div class="user-form">
        <form id="editarNotaForm">
            <input type="hidden" id="nota_id" name="id">
            
            <div class="form-group">
                <label for="usuario_id">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="form-control" required>
                    <option value="">Seleccionar usuario...</option>
                </select>
            </div>

            <div class="form-group">
                <label for="materia_id">Materia</label>
                <select name="materia_id" id="materia_id" class="form-control" required>
                    <option value="">Seleccionar materia...</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="n1">Nota 1</label>
                <input type="number" name="n1" id="n1" class="form-control" 
                       step="0.01" min="0" max="20" required>
                <small class="form-text">Valor entre 0 y 20</small>
            </div>
            
            <div class="form-group">
                <label for="n2">Nota 2</label>
                <input type="number" name="n2" id="n2" class="form-control" 
                       step="0.01" min="0" max="20">
                <small class="form-text">Valor entre 0 y 20</small>
            </div>
            
            <div class="form-group">
                <label for="n3">Nota 3</label>
                <input type="number" name="n3" id="n3" class="form-control" 
                       step="0.01" min="0" max="20" required>
                <small class="form-text">Valor entre 0 y 20</small>
            </div>

            <!-- Vista previa del promedio -->
            <div class="alert alert-info">
                <strong>Promedio calculado:</strong> <span id="promedioCalculado">0.00</span>
                <br>
                <strong>Estado:</strong> <span id="estadoNota"><span class="badge badge-secondary">Sin calcular</span></span>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn-submit">
                    <span id="btnText">📝 Actualizar Nota</span>
                    <span id="btnLoading" class="d-none">
                        <span class="spinner-border spinner-border-sm"></span>
                        Actualizando...
                    </span>
                </button>
                <a href="mostrarnotas.php" class="btn-cancel">
                    🔙 Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="../public/js/toast.js"></script>

<script>
let nota_id = null;

// Inicializar cuando cargue la página
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    nota_id = urlParams.get('id');
    
    if (!nota_id) {
        toastNotification.error('ID de nota no válido');
        window.location.href = 'mostrarnotas.php';
        return;
    }

    document.getElementById('nota_id').value = nota_id;
    
    // Cargar datos
    cargarUsuarios();
    cargarMaterias();
    cargarNotaData();
});

// Cargar usuarios
async function cargarUsuarios() {
    try {
        const response = await fetch('../conexion/obtener_usuarios.php');
        const usuarios = await response.json();
        
        const select = document.getElementById('usuario_id');
        usuarios.forEach(usuario => {
            const option = document.createElement('option');
            option.value = usuario.id;
            option.textContent = usuario.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar usuarios:', error);
        toastNotification.error('Error al cargar la lista de usuarios');
    }
}

// Cargar materias
async function cargarMaterias() {
    try {
        const response = await fetch('../conexion/obtener_materias.php');
        const materias = await response.json();
        
        const select = document.getElementById('materia_id');
        materias.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.id;
            option.textContent = materia.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar materias:', error);
        toastNotification.error('Error al cargar la lista de materias');
    }
}

// Cargar datos de la nota
async function cargarNotaData() {
    try {
        const response = await fetch(`obtener_nota.php?id=${nota_id}`);
        const data = await response.json();
        
        if (data.success) {
            const nota = data.nota;
            
            // Rellenar el formulario
            document.getElementById('usuario_id').value = nota.usuario_id;
            document.getElementById('materia_id').value = nota.materia_id;
            document.getElementById('n1').value = nota.n1;
            document.getElementById('n2').value = nota.n2;
            document.getElementById('n3').value = nota.n3;
            
            // Calcular promedio inicial
            calcularPromedio();
        } else {
            toastNotification.error('Error al cargar la nota: ' + data.message);
            window.location.href = 'mostrarnotas.php';
        }
    } catch (error) {
        console.error('Error:', error);
        toastNotification.error('Error al cargar los datos de la nota');
        setTimeout(() => {
            window.location.href = 'mostrarnotas.php';
        }, 2000);
    }
}

// Calcular promedio automáticamente
function calcularPromedio() {
    const n1 = parseFloat(document.getElementById('n1').value) || 0;
    const n2 = parseFloat(document.getElementById('n2').value) || 0;
    const n3 = parseFloat(document.getElementById('n3').value) || 0;
    
    const promedio = (n1 + n2 + n3) / 3;
    
    const promedioElement = document.getElementById('promedioCalculado');
    if (promedioElement) {
        promedioElement.textContent = promedio.toFixed(2);
        
        // Actualizar estado
        const estadoDiv = document.getElementById('estadoNota');
        if (estadoDiv) {
            if (promedio >= 14) {
                estadoDiv.innerHTML = '<span class="badge badge-aprobado">APROBADO</span>';
            } else if (promedio > 0) {
                estadoDiv.innerHTML = '<span class="badge badge-desaprobado">DESAPROBADO</span>';
            } else {
                estadoDiv.innerHTML = '<span class="badge badge-neutral">Sin calcular</span>';
            }
        }
    }
}

// Event listeners para calcular promedio en tiempo real
['n1', 'n2', 'n3'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', calcularPromedio);
    }
});

// Manejar envío del formulario
const form = document.getElementById('editarNotaForm');
if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Mostrar loading
        if (btnText && btnLoading) {
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        }
        if (submitBtn) submitBtn.disabled = true;

        try {
            const formData = new FormData(this);
            
            const response = await fetch('actualizar_nota.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mostrar notificación de éxito con Toastify
                toastNotification.success('¡Nota actualizada exitosamente!');
                
                // Esperar un poco para que se vea la notificación
                setTimeout(() => {
                    // Redirigir a mostrarnotas.php con parámetros para actualizar la vista
                    window.location.href = `mostrarnotas.php?updated=1&id=${nota_id}`;
                }, 1500);
            } else {
                // Mostrar error con Toastify
                toastNotification.error('Error: ' + (data.message || 'No se pudo actualizar la nota'));
            }
        } catch (error) {
            console.error('Error:', error);
            toastNotification.error('Error de conexión al actualizar la nota');
        } finally {
            // Ocultar loading
            if (btnText && btnLoading) {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }
            if (submitBtn) submitBtn.disabled = false;
        }
    });
}
</script>
</body>
</html>
