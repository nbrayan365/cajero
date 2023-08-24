<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Saldo</title>
</head>
<body>
    <h2>Consulta de Saldo</h2>
    <?php
    if (isset($_GET["usuario"])) {
        $user = $_GET["usuario"];
        
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

        /* Consulta para obtener el saldo del usuario */
        $query = "SELECT saldo FROM datos WHERE usuario = '$user'";
        if ($resultado = mysqli_query($conexion, $query)) {
            if ($fila = mysqli_fetch_assoc($resultado)) {
                $saldo = $fila["saldo"];
                echo "Hola, $user. Tu saldo actual es: $saldo";
            } else {
                echo "Error: No se encontró el usuario.";
            }

            /* Liberar el conjunto de resultados */
            mysqli_free_result($resultado);
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
        }

        /* Cerrar la conexión */
        mysqli_close($conexion);
    } else {
        echo "Error: No se proporcionó el usuario.";
    }
    ?>
    <!-- Puedes añadir algún enlace o botón para volver a la página anterior si lo deseas -->
</body>
</html>

