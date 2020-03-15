<?php 
    include "includes/funciones/sesiones.php";
    if($_SESSION['rol'] == 'consultor') {
        header('location: tiposeventos.php');
    }
    if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        die("Error...");
    }

    include 'includes/templates/header.php'; 

    $id = $_GET['id'];
    try {
        $resultado = obtenerTipoEvento($id);
        $tipo_evento = $resultado->fetch_assoc();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
    
?>

<div class="contenedor">
    <div class="seccion">
        <h1 class="titulo-seccion">Editar Tipos de Eventos</h1>
    </div>
    <div class="contenedor-formulario seccion">
        <p class="text-center">Modifique los datos de un tipo de evento</p>
        <div class="formulario">
            <form action="includes/modelos/modelo-tiposeventos.php" method="post" id="guardar_registro_imagen" name="guardar_registro_imagen" enctype="multipart/form-data">
                
                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $tipo_evento['tipo_nombre']; ?>" required>
                </div>
                <div class="campo">
                    <label for="precio">Precio:</label>
                    <input type="number" name="precio" id="precio" value="<?php echo $tipo_evento['tipo_precio_base']; ?>" required>
                </div>
                <div class="campo">
                    <label for="imagen_actual">Imagen actual:</label>
                    <div class="imagen-actual">
                        <img src="img/tipos_eventos/<?php echo $tipo_evento['tipo_imagen']; ?>" alt="Imagen del tipo de evento">
                    </div>
                </div>
                <div class="campo">
                    <label for="imagen_tipo">Imagen del tipo de evento:</label>
                    <input type="file" name="imagen_tipo" id="imagen_tipo" accept="image/*">
                </div>
                <div class="campo-boton">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="imagen_antigua" value="<?php echo $tipo_evento['tipo_imagen']; ?>">
                    <input class="boton" type="submit" value="Guardar" name="submit" id="crear_registro">
                </div>
            </form>
        </div>
    </div><!-- .contenedor-formulario -->

</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>