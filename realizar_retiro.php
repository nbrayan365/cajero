<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $montoRetiro = floatval($_POST["monto"]);
    $usuario = $_POST["usuario"];
    
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

    /* Obtener el saldo actual del usuario */
    $querySaldo = "SELECT saldo FROM datos WHERE usuario = '$usuario'";
    $resultadoSaldo = mysqli_query($conexion, $querySaldo);
    
    if ($resultadoSaldo && mysqli_num_rows($resultadoSaldo) > 0) {
        $filaSaldo = mysqli_fetch_assoc($resultadoSaldo);
        $saldoActual = $filaSaldo["saldo"];

        if ($saldoActual >= $montoRetiro) {
            // Calcular nuevo saldo después del retiro
            $nuevoSaldo = $saldoActual - $montoRetiro;

            // Actualizar el saldo en la base de datos
            $queryUpdateSaldo = "UPDATE datos SET saldo = $nuevoSaldo WHERE usuario = '$usuario'";
            if (mysqli_query($conexion, $queryUpdateSaldo)) {
                echo "Retiro exitoso. Tu nuevo saldo es: $nuevoSaldo";
            } else {
                echo "Error al actualizar el saldo: " . mysqli_error($conexion);
            }
        } else {
            echo "Saldo insuficiente para realizar el retiro.";
        }
    } else {
        echo "Error: Usuario no encontrado.";
    }

    /* Cerrar la conexión */
    mysqli_close($conexion);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Realizar Retiro</title>
</head>
<body>
    <h2>Realizar Retiro</h2>
    <form action="realizar_retiro.php" method="post">
        Usuario: <input type="text" name="usuario"><br>
        Monto a Retirar: <input type="number" step="0.01" name="monto"><br>
        <input type="submit" name="retirar" value="Retirar">
    </form>
</body>
</html>
