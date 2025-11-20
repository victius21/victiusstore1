<?php
session_start();

/* Si no existe el carrito aún, lo inicializamos */
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];

/* Cálculo del total */
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$montoPaypal = number_format($total, 2, '.', '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago | Victius Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=MXN"></script>

    <style>
        body{
            font-family:'Orbitron', sans-serif;
            background:#0d0f14;
            color:white;
        }
        .navbar{ background:linear-gradient(90deg,#0a2a43,#155a93); }
    </style>
</head>
<body>

<?php include("navbar.php"); ?>

<div class="container py-4">
    <h1 class="h3 mb-3">Pago con PayPal (Sandbox)</h1>

    <?php if (empty($carrito)): ?>
        <div class="alert alert-warning">Tu carrito está vacío.</div>
        <a href="tienda.php" class="btn btn-primary">Ir a la tienda</a>
    <?php else: ?>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Resumen del carrito</h5>
            <ul class="list-group">
                <?php foreach ($carrito as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $item['nombre']; ?> (x<?php echo $item['cantidad']; ?>)
                    <span>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?> MXN</span>
                </li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <h4>Total: $<?php echo number_format($total, 2); ?> MXN</h4>
        </div>
    </div>

    <div id="paypal-button-container"></div>

    <?php endif; ?>

</div>

<script>
// Monto total dinámico
const ORDER_TOTAL = "<?php echo $montoPaypal; ?>";

if (typeof paypal !== 'undefined') {
  paypal.Buttons({
    createOrder: (data, actions) => {
      return actions.order.create({
        purchase_units: [{
          amount: { value: ORDER_TOTAL }
        }]
      });
    },
    onApprove: (data, actions) => {
      return actions.order.capture().then(details => {
        alert("Pago realizado por " + details.payer.name.given_name);
        window.location = "index.php";
      });
    }
  }).render('#paypal-button-container');
}
</script>

</body>
</html>
