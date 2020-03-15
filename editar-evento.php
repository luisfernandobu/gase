<?php 
    include "includes/funciones/sesiones.php";
    if($_SESSION['rol'] == 'consultor') {
        header('location: eventos.php');
    }
    if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        die("Error...");
    }

    include 'includes/templates/header.php'; 

    $id = $_GET['id'];
    try {
        $resultado = obtenerEvento($id);
        $evento = $resultado->fetch_assoc();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
?>
<div class="contenedor">
    <div class="seccion">
        <h1 class="titulo-seccion">Editar Eventos</h1>
    </div>
    <div class="contenedor-formulario seccion">
        <p class="text-center">Modifique los datos de un evento <span>*debe volver a cotizar*</span></p>
        <div class="formulario">
            <form action="includes/modelos/modelo-eventos.php" method="post" id="guardar_registro_imagen" name="guardar_registro_imagen" enctype="multipart/form-data">
                <div class="contenido-formulario">
                    <div class="campos">
                        <div class="campo">
                            <label for="email_cliente_evento">Email del Cliente:</label>
                            <input type="email" name="email" id="email_cliente_evento" value="<?php echo $evento['cliente_email']; ?>" required>
                            <input type="hidden" id="existe_email" value="true">
                        </div>
                        <div class="campo">
                            <label for="tipo_evento">Tipo de Evento:</label>
                            <select name="tipo_evento" id="tipo_evento" required>
                                <option value="" disabled selected>-- Seleccione --</option>
                                <?php
                                    $tipos_eventos = obtenerTiposEventos();
                                    foreach($tipos_eventos as $tipo) {
                                        if($tipo['id_tipo_evento'] == $evento['tipos_id_tipo']) {
                                            echo "<option value='{$tipo['id_tipo_evento']}' data-precio='{$tipo['tipo_precio_base']}' selected>{$tipo['tipo_nombre']}</option> ";
                                        } else {
                                            echo "<option value='{$tipo['id_tipo_evento']}' data-precio='{$tipo['tipo_precio_base']}'>{$tipo['tipo_nombre']}</option> ";
                                        }   
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="fecha_evento">Fecha del Evento: <small class="descuento">*lunes a jueves 10% de descuento*</small></label>
                            <input type="date" name="fecha_evento" id="fecha_evento" value="<?php echo $evento['evento_fecha']; ?>" required>
                        </div>
                        <div class="campo">
                            <label for="imagen_actual">Imagen actual:</label>
                            <div class="imagen-actual">
                                <img src="img/eventos/<?php echo $evento['evento_imagen']; ?>" alt="Imagen de evento">
                            </div>
                        </div>
                        <div class="campo">
                            <label for="imagen_evento">Imagen del Evento:</label>
                            <input type="file" name="imagen_evento" id="imagen_evento" accept="image/*">
                        </div>
                        <div class="campo">
                            <button class="boton" id="cotizar">Cotizar</button>
                        </div>
                    </div><!-- .campos -->

                    <div class="resumen">
                        <p>Detalles</p>
                        <div id="detalles" class="detalles">
                            <div class="text-center">
                                <p>sin detalles</p>
                            </div>
                        </div>
                        <p>Total: <span id="total"></span></p>
                        <div class="campo-boton">
                            <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $evento['clientes_id_cliente']; ?>">
                            <input type="hidden" name="precio_evento" id="precio_evento" value="<?php echo $evento['evento_precio']; ?>">
                            <input type="hidden" name="imagen_antigua" value="<?php echo $evento['evento_imagen']; ?>">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input class="boton" type="submit" value="Guardar" name="submit" id="guardar_evento">
                        </div>
                    </div>
                </div><!-- .contenido-formulario -->
            </form>
        </div>
    </div><!-- .contenedor-formulario -->

</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>