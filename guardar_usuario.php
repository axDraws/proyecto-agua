<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = htmlspecialchars($_POST["usuario"]);
    $correo = htmlspecialchars($_POST["correo"]);
    $password = $_POST["password"];
    $confirmar = $_POST["confirmar"];

    if ($password !== $confirmar) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $db = new PDO("sqlite:agua.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("INSERT INTO usuarios (usuario, correo, contrasena) VALUES (:usuario, :correo, :contrasena)");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $hash);
        $stmt->execute();

        echo "<h2>Usuario registrado correctamente: $usuario</h2>";
        echo "<p><a href='templates/proyecto_web.html'>Volver al inicio</a></p>";
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
