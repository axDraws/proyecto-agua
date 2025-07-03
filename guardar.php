<?php
// Mostrar errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar que se haya enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    try {
        // Conexión a la base de datos en la raíz
        $db = new PDO("sqlite:agua.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear tabla si no existe
        $db->exec("CREATE TABLE IF NOT EXISTS mensajes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            mensaje TEXT NOT NULL,
            fecha DATETIME DEFAULT (datetime('now', 'localtime'))
        );");

        // Insertar datos
        $stmt = $db->prepare("INSERT INTO mensajes (nombre, mensaje) VALUES (:nombre, :mensaje)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->execute();

        echo "<h2>Gracias, $nombre. Tu mensaje fue guardado correctamente.</h2>";
        echo "<p><a href='templates/proyecto_web.html'>Volver</a></p>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>

