<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
}

$id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    
    if ($titulo !== '') {
        // Preparar la consulta para evitar inyección SQL
        $stmt = $conexion->prepare("UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id");
        $stmt->execute(['titulo' => $titulo, 'descripcion' => $descripcion, 'id' => $id]);
        header("Location: index.php");
        exit;
    } else {
        $error = "El título no puede estar vacío";
    }
}

$stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = :id");
$stmt->execute(['id' => $id]);
$tarea = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarea) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Editar</title>
</head>
<body>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="titulo">Título:</label><br />
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($tarea['titulo']); ?>" required><br /><br />

        <label for="descripcion">Descripción:</label><br /><br />
        <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($tarea['descripcion']); ?></textarea><br /><br />

        <button type="submit">Editar</button>
        
        <form method="get" action="index.php">
            <button type="submit">Volver</button>
        </form>
    </form><br />
</body>
</html>