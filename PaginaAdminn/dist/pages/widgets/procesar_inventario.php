<?php
// procesar_inventario.php
include 'conexion.php';

$clasificacion = $_POST['clasificacion'];
$cuentas       = $_POST['cuenta_ids'] ?? [];
$montos        = $_POST['montos']      ?? [];

if(!$clasificacion || count($cuentas)===0){
  die("Faltan datos.");
}

// 1) inserto el grupo
$stmt = $conn->prepare("INSERT INTO grupos_inventario (clasificacion) VALUES (?)");
$stmt->bind_param("s",$clasificacion);
$stmt->execute();
$grupo_id = $stmt->insert_id;

// 2) inserto cada detalle (cuenta + monto)
$stmt2 = $conn->prepare("
  INSERT INTO grupos_inventario_detalle
    (grupo_id, cuenta_id, monto)
  VALUES (?,?,?)
");
foreach($cuentas as $i=>$cid){
  $m = floatval($montos[$i]);
  $stmt2->bind_param("iid",$grupo_id, $cid, $m);
  $stmt2->execute();
}

header("Location: clasificar_inventario.php");
exit;
