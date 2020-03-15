<?php 
include "includes/funciones/sesiones.php";
if($_SESSION['rol'] == 'consultor') {
    header('location: clientes.php');
}
if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Error...");
}

include 'includes/templates/header.php'; 

$id = $_GET['id'];
try {
    $resultado = obtenerCliente($id);
    $cliente = $resultado->fetch_assoc();
} catch (\Throwable $th) {
    echo $th->getMessage();
}

?>

<div class="contenedor seccion">
    <div class="seccion">
        <h1 class="titulo-seccion">Editar Clientes</h1>
    </div>
    <div class="contenedor-formulario">
        <p class="text-center">Modifique los datos de un cliente</p>
        <div class="formulario">
            <form action="includes/modelos/modelo-clientes.php" method="post" id="guardar_registro" name="guardar_registro">         
                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $cliente['cliente_nombre']; ?>" required>
                </div>
                <div class="campo">
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" value="<?php echo $cliente['cliente_apellido']; ?>" required>
                </div>
                <div class="campo">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" name="email" id="email" value="<?php echo $cliente['cliente_email']; ?>" required>
                </div>
                    
                <div class="campo-boton">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input class="boton" type="submit" value="Guardar" name="submit" id="actualizar_registro">
                </div>
            </form>
        </div>
    </div><!-- .contenedor-formulario -->
</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>