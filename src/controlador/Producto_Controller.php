<?php




use Leandro\app\modelo\Producto;
use Leandro\app\libs\Controlador;


class Producto_Controller extends Controlador
{
  public function listar()
  {
    $lista = Producto::listar();
    $this->cargarVista("producto/listar", $lista);
  }
  public function nuevo()
  {
    $this->cargarVista("producto/nuevo");
  }
  public function crear()
  {
    try {
      $codigo = $_POST['codigo'];
      $descripcion = $_POST['descripcion'];
      $precio = $_POST['precio'];
      $fecha = $_POST['fecha'];
      $fechaF = DateTime::createFromFormat('Y-m-d', $fecha)->format('Y-m-d');
      $producto = new Producto(null, $codigo, $descripcion, $precio, $fechaF);
      $id = $producto->crear();
      $this->cargarVista("producto/crear", $id);
    } catch (\Throwable $th) {
      //throw $th;
      $this->cargarVista("producto/error");
    }
  }
}
