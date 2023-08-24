<!DOCTYPE html>
<html>
<head>
  <title>Operaciones Bancarias</title>
</head>
<body>
  <h2>Operaciones Bancarias</h2>
  <?php
  if (isset($_GET["usuario"])) {
      $user = $_GET["usuario"];
      echo "Hola, $user. Bienvenido a tu cuenta bancaria.";
  } else {
      echo "Error: No se proporcionó el usuario.";
  }
  ?>
  <button onclick="irAConsultaSaldo()">Consulta Saldo</button>
  <button onclick="irARetiro()">Retiro</button>
  <button onclick="irADeposito()">Depósito</button>
  <button onclick="irATransferencia()">Transferir</button>

  <script>
    function irAConsultaSaldo() {
      window.location.href = "consulta_saldo.php?usuario=<?php echo urlencode($user); ?>";
    }

    function irARetiro() {
      window.location.href = "realizar_retiro.php";
    }

    function irADeposito() {
      window.location.href = "realizar_deposito.php";
    }

    function irATransferencia() {
      window.location.href = "realizar_transferencia.php";
    }
  </script>
</body>
</html>
