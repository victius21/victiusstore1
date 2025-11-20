<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = &$_SESSION['carrito'];
$mensaje = "";

/* Acciones: quitar producto o vaciar carrito */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'remove') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            $mensaje = "Producto eliminado del carrito.";
        }
    } elseif ($accion === 'clear') {
        $carrito = [];
        $mensaje = "Carrito vaciado.";
    }
}

/* Total */
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Victius · Carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos -->
    <link rel="stylesheet" href="assets/css/styles.css?v=1">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm main-navbar">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Victius Veracruz</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="tienda.php">Tienda</a></li>
        <li class="nav-item"><a class="nav-link active" href="carrito.php">Carrito</a></li>
        <li class="nav-item"><a class="nav-link" href="pago.php">Pago</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="py-4">
  <div class="container">
    <h1 class="h3 mb-3">Carrito de Compras</h1>

    <?php if ($mensaje): ?>
      <div class="alert alert-info py-2"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <?php if (empty($carrito)): ?>
      <div class="alert alert-warning">
        Tu carrito está vacío.
      </div>
      <a href="tienda.php" class="btn btn-primary">Ir a la tienda</a>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-dark table-striped align-middle">
          <thead>
            <tr>
              <th>Producto</th>
              <th class="text-center">Cantidad</th>
              <th class="text-end">Precio</th>
              <th class="text-end">Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($carrito as $id => $item): ?>
              <tr>
                <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                <td class="text-center"><?php echo (int)$item['cantidad']; ?></td>
                <td class="text-end">$<?php echo number_format($item['precio'], 2); ?></td>
                <td class="text-end">$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                <td class="text-end">
                  <form method="post" class="d-inline">
                    <input type="hidden" name="accion" value="remove">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Quitar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Total:</th>
              <th class="text-end">$<?php echo number_format($total, 2); ?> MXN</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="d-flex flex-wrap gap-2 mt-3">
        <form method="post">
          <input type="hidden" name="accion" value="clear">
          <button type="submit" class="btn btn-outline-light">Vaciar carrito</button>
        </form>
        <a href="tienda.php" class="btn btn-outline-secondary">Seguir comprando</a>
        <a href="pago.php" class="btn btn-success">Ir a pagar</a>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
