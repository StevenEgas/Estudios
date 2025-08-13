<?php

require_once '../conexion/bd.php';

/* consulta de datos con pdo - JOIN para obtener nombres de usuario y materia */
$query = "SELECT 
            notas.id,
            usuarios.nombre AS usuario_nombre, 
            materias.nombre AS materia_nombre, 
            notas.n1, 
            notas.n2, 
            notas.n3, 
            notas.promedio 
          FROM notas
          JOIN usuarios ON notas.usuario_id = usuarios.id 
          JOIN materias ON notas.materia_id = materias.id
          ORDER BY notas.id";

$stmt = $pdo->prepare($query);
$stmt->execute();
$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Notas - CRUD</title>
    <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" href="../public/css/estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/css/swalstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body class="notas-page">

<script src="../public/js/toast.js"></script>

<div class="container py-5">
    <div class="table-container">
        <div class="table-header">
            <h1>📊 Gestión de Notas</h1>
            <p>Administra, visualiza y controla todas las notas académicas del sistema</p>
        </div>
        
        <div class="table-actions">
            <div class="d-flex justify-content-between align-items-center">
                <a href="ingresarnotas.php" class="btn btn-primary-gradient">
                    <i class="fas fa-plus-circle"></i> Registrar Nueva Nota
                </a>
                <div class="d-flex gap-2">
                    <button onclick="location.reload()" class="btn btn-info-gradient">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                    <a href="../index.html" class="btn btn-secondary-gradient">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </div>
            </div>
        </div>
        
        <div class="table-content">
    

<?php if(!empty($notas)): ?>

    <div class="table-responsive">
        <table class="table custom-table" id="tablaNotas">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Nombre</th>
                    <th><i class="fas fa-book"></i> Materia</th>
                    <th><i class="fas fa-star"></i> Nota 1</th>
                    <th><i class="fas fa-star"></i> Nota 2</th>
                    <th><i class="fas fa-star"></i> Nota 3</th>
                    <th><i class="fas fa-calculator"></i> Promedio</th>
                    <th><i class="fas fa-check-circle"></i> Estado</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
        <tbody>




                    <?php foreach($notas as $nota): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($nota['usuario_nombre']); ?></strong></td>
                <td><?php echo htmlspecialchars($nota['materia_nombre']); ?></td>
                <td><?php echo number_format($nota['n1'], 2); ?></td>
                <td><?php echo number_format($nota['n2'], 2); ?></td>
                <td><?php echo number_format($nota['n3'], 2); ?></td>
                <td><strong><?php echo number_format($nota['promedio'], 2); ?></strong></td>
                <td class="text-center">
                    <?php 
                    if($nota['promedio'] >= 14): 
                        $estado = 'APROBADO';
                        $badgeClass = 'badge-aprobado';
                    else: 
                        $estado = 'DESAPROBADO';
                        $badgeClass = 'badge-desaprobado';
                    endif; 
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $estado; ?></span>
                </td>
                <td class="text-center">
                    <a href="editar_nota.php?id=<?php echo $nota['id']; ?>" 
                       class="btn btn-action btn-edit" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-action btn-delete" 
                            onclick="eliminarNota(<?php echo $nota['id']; ?>)" 
                            title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

        </div> <!-- Fin table-content -->
    </div> <!-- Fin table-container -->
</div> <!-- Fin container -->

<?php else: ?>

<div class="container py-5">
    <div class="table-container">
        <div class="table-header">
            <h1>📊 Gestión de Notas</h1>
            <p>No hay notas registradas en el sistema</p>
        </div>
        
        <div class="table-content text-center py-5">
            <div class="alert alert-custom alert-info-custom" role="alert">
                <h4 class="alert-heading"><i class="fas fa-info-circle"></i> ¡No hay notas registradas!</h4>
                <p>Aún no se han registrado notas en el sistema.</p>
                <hr>
                <p class="mb-0">
                    <a href="ingresarnotas.php" class="btn btn-primary-gradient">
                        <i class="fas fa-plus"></i> Ingresar primera nota
                    </a>
                </p>
            </div>
            
            <div class="mt-4">
                <a href="../index.html" class="btn btn-secondary-gradient">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="../public/js/toast.js"></script>

<script>
$(document).ready(function() {
    $('#tablaNotas').DataTable({
        pageLength: 10,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        columnDefs: [
            { "orderable": false, "targets": -1 }
        ],
        responsive: true,
        order: [[0, 'asc']]
    });
});

// Función mejorada para eliminar nota con notificación toast
function eliminarNota(id) {
    Swal.fire({
        title: '¿Confirmar eliminación?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Eliminar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger mx-2',
            cancelButton: 'btn btn-secondary mx-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('eliminar_nota.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar notificación toast de éxito
                    toastNotification.success('Nota eliminada correctamente');
                    
                    // Remover la fila de la tabla sin recargar la página
                    const table = $('#tablaNotas').DataTable();
                    const row = $(`button[onclick="eliminarNota(${id})"]`).closest('tr');
                    table.row(row).remove().draw();
                } else {
                    toastNotification.error('Error al eliminar la nota: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastNotification.error('Hubo un problema al eliminar la nota');
            });
        }
    });
}

// Función para actualizar una fila específica después de editar
function actualizarFilaNota(id) {
    fetch(`obtener_nota.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const nota = data.nota;
            const table = $('#tablaNotas').DataTable();
            
            // Encontrar la fila y actualizarla
            const row = $(`a[href="editar_nota.php?id=${id}"]`).closest('tr');
            const rowIndex = table.row(row).index();
            
            // Determinar estado
            const estado = nota.promedio >= 14 ? 'APROBADO' : 'DESAPROBADO';
            const badgeClass = nota.promedio >= 14 ? 'badge-aprobado' : 'badge-desaprobado';
            
            // Actualizar los datos de la fila
            const newData = [
                `<strong>${nota.usuario_nombre}</strong>`,
                nota.materia_nombre,
                parseFloat(nota.n1).toFixed(2),
                parseFloat(nota.n2).toFixed(2),
                parseFloat(nota.n3).toFixed(2),
                `<strong>${parseFloat(nota.promedio).toFixed(2)}</strong>`,
                `<span class="badge ${badgeClass}">${estado}</span>`,
                `<a href="editar_nota.php?id=${nota.id}" class="btn btn-action btn-edit" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-action btn-delete" onclick="eliminarNota(${nota.id})" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>`
            ];
            
            table.row(rowIndex).data(newData).draw();
            toastNotification.success('Nota actualizada exitosamente');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastNotification.error('Error al actualizar la vista');
    });
}

// Verificar si venimos de una edición
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('updated')) {
    const id = urlParams.get('id');
    if (id) {
        setTimeout(() => {
            actualizarFilaNota(id);
        }, 500);
    }
    // Limpiar la URL sin recargar
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>

</body>
</html>
