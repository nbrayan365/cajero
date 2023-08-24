<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["usuario"];
    $password = $_POST["pass"];
    
    $servidor = "localhost";
    $usuario_db = "ever";  // Cambia esto a tu usuario de base de datos
    $clave_db = "toor";    // Cambia esto a tu contraseña de base de datos
    $basedatos = "cajero";

    $conexion = mysqli_connect($servidor, $usuario_db, $clave_db, $basedatos);

    /* Verificar la conexión */
    if (mysqli_connect_errno()) {
        echo "Error al conectar con la base de datos: " . mysqli_connect_error();
        exit();
    }

    /* Escapar caracteres especiales en el valor del usuario */
    $user = mysqli_real_escape_string($conexion, $user);

    /* Consulta de selección con el usuario y contraseña ingresados */
    $query = "SELECT usuario FROM datos WHERE usuario = '$user' AND pass = '$password'";
    if ($resultado = mysqli_query($conexion, $query)) {
        if ($fila = mysqli_fetch_assoc($resultado)) {
            // Autenticación exitosa, redirigir a la página con los botones
            mysqli_free_result($resultado);
            mysqli_close($conexion);
            
            // Redirigir a botones.php con el usuario como parámetro
            header("Location: botones.php?usuario=" . urlencode($user));
            exit();
        } else {
            // Autenticación fallida
            echo "Error: Usuario o contraseña incorrectos.";
        }
    } else {
        echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
    }

    /* Cerrar la conexión */
    mysqli_close($conexion);
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
