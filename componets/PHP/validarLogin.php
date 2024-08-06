<?php
session_start();

// Recoger datos del formulario
$usuario = $_POST['username'];
$contraseña = $_POST['password'];

// Datos de la conexión a la base de datos
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "coboshubbd";

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la llamada al procedimiento almacenado
    $stmt = $conn->prepare("CALL sp_validar_login(:usuarioU, :claveU)");
    $stmt->bindParam(':usuarioU', $usuario);
    $stmt->bindParam(':claveU', $contraseña);
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    // Depuración: Mostrar el resultado para verificar los datos devueltos
  /*  echo '<pre>';
    print_r($result);
    echo '</pre>';*/

    // Verificar si se obtuvo algún resultado
    if (count($result) > 0) {
        // Obtener datos del primer resultado
        $usuariobd = $result[0]['nomLogin'] ?? null; // Usar null si la clave no existe
        $contraseñabd = $result[0]['claveLogin'] ?? null; // Usar null si la clave no existe

        // Comparar contraseñas en texto plano
        if ($usuariobd == $usuario && $contraseñabd == $contraseña) {
            // Iniciar sesión
            $_SESSION['usuarioValido'] = $usuariobd;
            // Redirigir a la página de administración
            header('Location: ../../../public/index.html');
            exit(); // Asegurarse de que no se ejecute el resto del script
        } else {
            echo "Usuario y/o contraseña incorrectos.";
        }
    } else {
        echo "Usuario y/o contraseña incorrectos.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>
