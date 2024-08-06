<?php
// Iniciar sesión
session_start();

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Puedes asignar valores por defecto a los campos que no están en el formulario
    $apPaternoUsuario = ""; // Valor por defecto o recoger del formulario si está disponible
    $apMaternoUsuario = ""; // Valor por defecto o recoger del formulario si está disponible
    $telUsuario = ""; // Valor por defecto o recoger del formulario si está disponible
    $fotoUsuario = null; // Asumimos que no se sube ninguna foto
    $idRolUsuario = 1; // Valor por defecto, ajustar según necesidad

    // Hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Datos de la conexión a la base de datos
    $servername = "localhost:3306";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "coboshubbd";

    try {
        // Crear una nueva conexión PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar y ejecutar el procedimiento almacenado
        $stmt = $conn->prepare("CALL sp_crearcuenta(:nombreUsuario, :apPaternoUsuario, :apMaternoUsuario, :emailUsuario, :telUsuario, :fotoUsuario, :nombreLogin, :claveLogin, :idRolUsuario)");
        $stmt->bindParam(':nombreUsuario', $username);
        $stmt->bindParam(':apPaternoUsuario', $apPaternoUsuario);
        $stmt->bindParam(':apMaternoUsuario', $apMaternoUsuario);
        $stmt->bindParam(':emailUsuario', $email);
        $stmt->bindParam(':telUsuario', $telUsuario);
        $stmt->bindParam(':fotoUsuario', $fotoUsuario, PDO::PARAM_LOB);
        $stmt->bindParam(':nombreLogin', $username);
        $stmt->bindParam(':claveLogin', $hashedPassword);
        $stmt->bindParam(':idRolUsuario', $idRolUsuario, PDO::PARAM_INT);

        $stmt->execute();

        // Mensaje de éxito
        echo "Cuenta creada correctamente.";
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error: " . $e->getMessage();
    }

    // Cerrar la conexión
    $conn = null;
}
?>
