$productoNuevo = [
    'id' => $_POST['producto_id'],
    'cantidad' => $_POST['cantidad']
];

// Leer cookie actual
$carrito = json_decode($_COOKIE['carrito'] ?? '[]', true);
$encontrado = false;

// Sumar cantidad si ya existe
foreach ($carrito as &$item) {
    if ($item['id'] == $productoNuevo['id']) {
        $item['cantidad'] += $productoNuevo['cantidad'];
        $encontrado = true;
        break;
    }
}
if (!$encontrado) {
    $carrito[] = $productoNuevo;
}

// Guardar de nuevo en cookie
setcookie('carrito', json_encode($carrito), time() + 3600, "/");

// Redirigir o mostrar mensaje
header("Location: carrito.php");
exit();
