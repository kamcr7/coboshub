<?php
// Configuración de la base de datos
$host = 'localhost:3306';
$dbname = 'coboshubbd';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = $_POST['correo'];

        // Llamar al procedimiento almacenado para recuperar la contraseña
        $stmt = $pdo->prepare("CALL sp_recuperarcontraseña(:correo)");
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener el resultado del procedimiento almacenado
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $nombreUsuario = $row['nombreUsuario'];
            $claveLogin = $row['claveLogin'];

            // Mostrar la contraseña al usuario (solo para demostración)
            echo "Hola $nombreUsuario,<br><br>Tu contraseña es: " . htmlspecialchars($claveLogin);
        } else {
            echo "El correo electrónico no está registrado.";
            header("Refresh: 2; URL=index.html"); // Redirigir a la página de inicio después de 2 segundos
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

