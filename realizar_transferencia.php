<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $montoTransferencia = floatval($_POST["monto"]);
    $usuarioOrigen = $_POST["usuario_origen"];
    $usuarioDestino = $_POST["usuario_destino"];
    
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

    /* Obtener los saldos actuales de los usuarios */
    $querySaldoOrigen = "SELECT saldo FROM datos WHERE usuario = '$usuarioOrigen'";
    $querySaldoDestino = "SELECT saldo FROM datos WHERE usuario = '$usuarioDestino'";
    
    $resultadoSaldoOrigen = mysqli_query($conexion, $querySaldoOrigen);
    $resultadoSaldoDestino = mysqli_query($conexion, $querySaldoDestino);
    
    if ($resultadoSaldoOrigen && $resultadoSaldoDestino &&
        mysqli_num_rows($resultadoSaldoOrigen) > 0 &&
        mysqli_num_rows($resultadoSaldoDestino) > 0) {
        
        $filaSaldoOrigen = mysqli_fetch_assoc($resultadoSaldoOrigen);
        $filaSaldoDestino = mysqli_fetch_assoc($resultadoSaldoDestino);
        
        $saldoActualOrigen = $filaSaldoOrigen["saldo"];
        $saldoActualDestino = $filaSaldoDestino["saldo"];

        if ($saldoActualOrigen >= $montoTransferencia) {
            // Calcular nuevos saldos después de la transferencia
            $nuevoSaldoOrigen = $saldoActualOrigen - $montoTransferencia;
            $nuevoSaldoDestino = $saldoActualDestino + $montoTransferencia;

            // Actualizar los saldos en la base de datos
            $queryUpdateSaldoOrigen = "UPDATE datos SET saldo = $nuevoSaldoOrigen WHERE usuario = '$usuarioOrigen'";
            $queryUpdateSaldoDestino = "UPDATE datos SET saldo = $nuevoSaldoDestino WHERE usuario = '$usuarioDestino'";
            
            mysqli_autocommit($conexion, false); // Desactivar autocommit
            
            if (mysqli_query($conexion, $queryUpdateSaldoOrigen) &&
                mysqli_query($conexion, $queryUpdateSaldoDestino)) {
                mysqli_commit($conexion); // Confirmar la transacción
                echo "Transferencia exitosa. Tu nuevo saldo es: $nuevoSaldoOrigen";
            } else {
                mysqli_rollback($conexion); // Revertir la transacción en caso de error
                echo "Error al actualizar los saldos: " . mysqli_error($conexion);
            }
            
            mysqli_autocommit($conexion, true); // Reactivar autocommit
        } else {
            echo "Saldo insuficiente para realizar la transferencia.";
        }
    } else {
        echo "Error: Uno o ambos usuarios no fueron encontrados.";
    }

    /* Cerrar la conexión */
    mysqli_close($conexion);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Realizar Transferencia</title>
</head>
<body>
    <h2>Realizar Transferencia</h2>
    <form action="realizar_transferencia.php" method="post">
        Usuario Origen: <input type="text" name="usuario_origen"><br>
        Usuario Destino: <input type="text" name="usuario_destino"><br>
        Monto a Transferir: <input type="number" step="0.01" name="monto"><br>
        <input type="submit" name="transferir" value="Transferir">
    </form>
</body>
</html>
