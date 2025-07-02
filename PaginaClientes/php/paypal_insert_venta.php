<?php
// paypal_insert_venta.php
require_once __DIR__.'/config.php';

function insertarVentaCompleta($user_id, $productos, $telefono, $direccion) {
    global $conn;
    // 1) insertar en ventas
    $fecha = date("Y-m-d");
    $stmt = $conn->prepare("INSERT INTO ventas (cliente_id, fecha, telefono, direccion) VALUES (?,?,?,?)");
    $stmt->bind_param("isss", $user_id, $fecha, $telefono, $direccion);
    if (!$stmt->execute()) return false;
    $idVenta = $stmt->insert_id;

    // 2) detalle + stock
    $stmtDet = $conn->prepare(
      "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, total)
       VALUES (?,?,?,?,?)"
    );
    $stmtUpd = $conn->prepare(
      "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?"
    );
    foreach ($productos as $p) {
      $idP = (int)$p['id'];
      $cant = (int)$p['cantidad'];
      if ($cant<1) continue;
      // obtén precio
      $res = $conn->query("SELECT precio FROM productos WHERE id=$idP");
      if (!($row=$res->fetch_assoc())) continue;
      $precio = (float)$row['precio'];
      $total = $precio * $cant;
      $stmtDet->bind_param("iiidd", $idVenta, $idP, $cant, $precio, $total);
      if (!$stmtDet->execute()) return false;
      // actualiza stock
      $stmtUpd->bind_param("iii", $cant, $idP, $cant);
      if (!$stmtUpd->execute()) return false;
    }

    return true;
}
