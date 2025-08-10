<?php
require 'db.php';

// Si se envió el formulario para crear tarea
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    if ($titulo !== '') {
        // Preparar la consulta para evitar inyección SQL
        $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion) VALUES (:titulo, :descripcion)");
        $stmt->execute(['titulo' => $titulo, 'descripcion' => $descripcion]);
        header("Location: index.php"); //Redirigir para evitar resubmisión
        exit;
    } else {
        $error = "El título no puede estar vacío";
    }
}

// Obtener todas las tareas
$stmt = $conexion->query("SELECT * FROM tareas ORDER BY fecha_creacion DESC");
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
</head>
<body>
    <?php if (!empty($error)): ?> 
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="index.php">
        <label for="titulo">Título:</label><br />
        <input type="text" id="titulo" name="titulo" required><br /><br />

        <label for="descripcion">Descripción:</label><br /><br />
        <textarea id="descripcion" name="descripcion"></textarea><br /><br />

        <button type="submit">Agregar Tarea</button>
    </form>

    <h2>Lista de tareas</h2>
    <?php if(count($tareas) === 0): ?>
        <p>No hay tareas aún.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($tareas as $tarea): ?>
                <li>
                    <strong><?php echo htmlspecialchars($tarea['titulo']); ?></strong>
                    <?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?><br />
                    <small>Creada el: <?php echo $tarea['fecha_creacion']; ?></small>
                </li>
                <?php endforeach; ?>
        </ul>
        <?php endif; ?>
</body>
</html>