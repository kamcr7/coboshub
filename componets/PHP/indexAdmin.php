<?php
session_start();
if (isset($_SESSION['usuarioValido'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Pagina Administrador</h1>
        <h3>
            Bienvenido
            <?php
            echo $_SESSION['usuarioValido'];
            ?>
        </h3>
        <br>
        <a href="cerrarSession.php">Cerrar sesión</a>
    </div>
</body>
</html>
<?php
} // Cierre de if de comienzo
else {
    echo 'Debes iniciar sesión';
    echo '<a href="../../../public/index.html">Login</a>';
}
?>