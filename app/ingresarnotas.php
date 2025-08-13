
<?php
require_once '../conexion/bd.php';

$query = "SELECT * FROM usuarios";
$stm = $pdo->prepare($query);
$stm->execute();
$usuarios = $stm->fetchAll(PDO::FETCH_ASSOC);

$queryMaterias = "SELECT * FROM materias";
$stmMaterias = $pdo->prepare($queryMaterias);
$stmMaterias->execute();
$materias = $stmMaterias->fetchAll(PDO::FETCH_ASSOC);


?>
<!-- Consultar usuarios de base de datos -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Notas - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .header-section {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 2rem;
            text-align: center;
        }
        .form-section {
            padding: 2rem;
        }
        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(78, 115, 223, 0.3);
            color: white;
        }
        .btn-outline-gradient {
            border: 2px solid #4e73df;
            color: #4e73df;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border-color: #4e73df;
            color: white;
            transform: translateY(-1px);
        }
        .note-input {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }
        .note-input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="main-card">
                    <!-- Header -->
                    <div class="header-section">
                        <div class="icon-wrapper mx-auto">
                            <i class="fas fa-graduation-cap fa-lg"></i>
                        </div>
                        <h1 class="h3 mb-2">Registro de Notas</h1>
                        <p class="mb-0 opacity-90">Ingresa las calificaciones académicas de los estudiantes</p>
                    </div>

                    <!-- Form Section -->
                    <div class="form-section">
                        <form id="formIngresarNotas">
                            <!-- Estudiante -->
                            <div class="form-floating mb-4">
                                <select name="usuario_id" id="usuario_id" class="form-select" required>
                                    <option value="">Seleccione un estudiante</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="usuario_id">
                                    <i class="fas fa-user me-2"></i>Estudiante
                                </label>
                            </div>

                            <!-- Materia -->
                            <div class="form-floating mb-4">
                                <select name="materia_id" id="materia_id" class="form-select" required>
                                    <option value="">Seleccione una materia</option>
                                    <?php foreach ($materias as $materia): ?>
                                        <option value="<?= $materia['id'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="materia_id">
                                    <i class="fas fa-book me-2"></i>Materia
                                </label>
                            </div>

                            <!-- Notas -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-floating">
                                        <input type="number" name="nota_1" id="nota_1" class="form-control note-input" 
                                               step="0.01" required max="20" min="0" placeholder="0.00">
                                        <label for="nota_1">
                                            <i class="fas fa-star text-warning me-2"></i>Nota 1
                                        </label>
                                    </div>
                                    <div class="form-text">Valor entre 0 y 20</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-floating">
                                        <input type="number" name="nota_2" id="nota_2" class="form-control note-input" 
                                               step="0.01" max="20" min="0" placeholder="0.00">
                                        <label for="nota_2">
                                            <i class="fas fa-star text-warning me-2"></i>Nota 2
                                        </label>
                                    </div>
                                    <div class="form-text">Valor entre 0 y 20</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-floating">
                                        <input type="number" name="nota_3" id="nota_3" class="form-control note-input" 
                                               step="0.01" required max="20" min="0" placeholder="0.00">
                                        <label for="nota_3">
                                            <i class="fas fa-star text-warning me-2"></i>Nota 3
                                        </label>
                                    </div>
                                    <div class="form-text">Valor entre 0 y 20</div>
                                </div>
                            </div>

                            <!-- Promedio Preview -->
                            <div class="alert alert-light border-0 mb-4" style="background: #f8f9fc;">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <small class="text-muted d-block">Promedio calculado</small>
                                        <span class="h4 mb-0" id="promedioPreview">0.00</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge bg-secondary" id="estadoPreview">Sin calcular</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-gradient btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    <span id="btnText">Guardar Notas</span>
                                    <span id="btnLoading" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Guardando...
                                    </span>
                                </button>
                                
                                <div class="row g-2">
                                    <div class="col">
                                        <a href="mostrarnotas.php" class="btn btn-outline-gradient w-100">
                                            <i class="fas fa-list me-2"></i>Ver Notas
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="../index.html" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-home me-2"></i>Inicio
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../public/js/toast.js"></script>

    <script>
        // Calcular promedio en tiempo real
        function calcularPromedio() {
            const n1 = parseFloat(document.getElementById('nota_1').value) || 0;
            const n2 = parseFloat(document.getElementById('nota_2').value) || 0;
            const n3 = parseFloat(document.getElementById('nota_3').value) || 0;
            
            const promedio = (n1 + n2 + n3) / 3;
            
            document.getElementById('promedioPreview').textContent = promedio.toFixed(2);
            
            const estadoBadge = document.getElementById('estadoPreview');
            if (promedio >= 14) {
                estadoBadge.textContent = 'APROBADO';
                estadoBadge.className = 'badge bg-success';
            } else if (promedio > 0) {
                estadoBadge.textContent = 'DESAPROBADO';
                estadoBadge.className = 'badge bg-danger';
            } else {
                estadoBadge.textContent = 'Sin calcular';
                estadoBadge.className = 'badge bg-secondary';
            }
        }

        // Event listeners para calcular promedio
        ['nota_1', 'nota_2', 'nota_3'].forEach(id => {
            document.getElementById(id).addEventListener('input', calcularPromedio);
        });

        // Manejar envío del formulario
        const formIngresarNotas = document.getElementById('formIngresarNotas');
        formIngresarNotas.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validar notas en el frontend
            const nota1 = parseFloat(document.getElementById('nota_1').value);
            const nota2 = parseFloat(document.getElementById('nota_2').value);
            const nota3 = parseFloat(document.getElementById('nota_3').value);
            
            if (nota1 < 0 || nota1 > 20 || nota2 < 0 || nota2 > 20 || nota3 < 0 || nota3 > 20) {
                toastNotification.error('Las notas deben estar entre 0 y 20');
                return;
            }
            
            const formData = new FormData(formIngresarNotas);
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            // Mostrar loading
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;
            
            fetch('guardar_notas.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastNotification.success('Notas guardadas correctamente');
                    formIngresarNotas.reset();
                    calcularPromedio(); // Reset promedio display
                } else {
                    toastNotification.error(data.message || 'Error al guardar las notas');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastNotification.error('Hubo un problema al guardar las notas');
            })
            .finally(() => {
                // Restaurar botón
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        });

        // Inicializar promedio al cargar
        document.addEventListener('DOMContentLoaded', calcularPromedio);
    </script>
</body>
</html>