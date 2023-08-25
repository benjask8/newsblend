<?php
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "nombre_de_tu_base_de_datos";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Iniciar transacción
  $conn->beginTransaction();

  // Obtener información del producto con SELECT FOR UPDATE
  $stmt = $conn->prepare("SELECT * FROM productos WHERE id = :producto_id FOR UPDATE");
  $producto_id = 1;
  $stmt->bindParam(':producto_id', $producto_id);
  $stmt->execute();
  $producto = $stmt->fetch();

  // Verificar versión y procesar actualización
  if ($producto['version'] == $_POST['version_actual']) {
    $nueva_cantidad = $producto['cantidad'] - 1;
    $nueva_version = $producto['version'] + 1;

    // Actualizar la cantidad y la versión del producto
    $update_stmt = $conn->prepare("UPDATE productos SET cantidad = :nueva_cantidad, version = :nueva_version WHERE id = :producto_id");
    $update_stmt->bindParam(':nueva_cantidad', $nueva_cantidad);
    $update_stmt->bindParam(':nueva_version', $nueva_version);
    $update_stmt->bindParam(':producto_id', $producto_id);
    $update_stmt->execute();

    // Confirmar transacción
    $conn->commit();
  } else {
    // Versión no válida, revertir transacción
    $conn->rollback();
    echo "Error: Versión no válida. Otro usuario ha modificado el producto.";
  }
} catch (PDOException $e) {
  // Revertir transacción en caso de error
  $conn->rollback();
  echo "Error: " . $e->getMessage();
}

$conn = null;
