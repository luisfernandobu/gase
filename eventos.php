<?php 
    include "includes/funciones/sesiones.php";
    include 'includes/templates/header.php'; 
?>

<div class="contenedor">
    <div class="seccion">
        <h1 class="titulo-seccion">Eventos</h1>
    </div>
    <?php 
        // validación para rol de consultor
        if($_SESSION['rol'] != 'consultor') {
    ?>
    <div class="contenedor-formulario">
        <p class="text-center">Llene los campos para registrar un evento <span>*todos los campos son obligatorios*</span></p>
        <div class="formulario">
            <form action="includes/modelos/modelo-eventos.php" method="post" id="guardar_registro_imagen" name="guardar_registro_imagen" enctype="multipart/form-data">
                <div class="contenido-formulario">
                    <div class="campos">
                        <div class="campo">
                            <label for="email_cliente_evento">Email del Cliente:</label>
                            <input type="email" name="email" id="email_cliente_evento" required>
                            <input type="hidden" id="existe_email" value="false">
                        </div>
                        <div class="campo">
                            <label for="tipo_evento">Tipo de Evento:</label>
                            <select name="tipo_evento" id="tipo_evento" required>
                                <option value="" disabled selected>-- Seleccione --</option>
                                <?php
                                    $tipos_eventos = obtenerTiposEventos();
                                    foreach($tipos_eventos as $tipo) {
                                        echo "<option value='{$tipo['id_tipo_evento']}' data-precio='{$tipo['tipo_precio_base']}'>{$tipo['tipo_nombre']}</option> ";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="fecha_evento">Fecha del Evento: <small class="descuento">*lunes a jueves 10% de descuento*</small></label>
                            <input type="date" name="fecha_evento" id="fecha_evento" required>
                        </div>
                        <div class="campo">
                            <label for="imagen_evento">Imagen del Evento:</label>
                            <input type="file" name="imagen_evento" id="imagen_evento" accept="image/*" required>
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
                            <input type="hidden" name="id_cliente" id="id_cliente" value="">
                            <input type="hidden" name="precio_evento" id="precio_evento" value="">
                            <input type="hidden" name="accion" value="crear">
                            <input class="boton" type="submit" value="Guardar" name="submit" id="guardar_evento">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- .contenedor-formulario -->
    <?php
        } // fin del if
    ?>

    <div class="contenedor-tabla seccion">
        <h2>Listado de Eventos</h2>
        <div class="tabla">
            <table id="registros" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tipo de Evento</th>
                        <th>Cliente</th>
                        <th>Fecha del Evento</th>
                        <th>Costo</th>
                        <th>Imagen</th>
                        <!-- validación para consultor pero con operador ternario -->
                        <?php echo $_SESSION['rol'] != 'consultor' ? "<th>Acciones</th>" : ''; ?> 
                    </tr>
                </thead>
                <tbody> 
                <?php
                try {
                    $eventos = obtenerEventos();
                    foreach($eventos as $evento) {
                    ?>
                        <tr>
                            <td><?php echo $evento['tipo_nombre']; ?></td>
                            <td><?php echo $evento['cliente_nombre'] . " " . $evento['cliente_apellido']; ?></td>
                            <td><?php echo $evento['evento_fecha']; ?></td>
                            <td><?php echo $evento['evento_precio']; ?></td>
                            <td class="text-center"><img src="img/eventos/<?php echo $evento['evento_imagen']; ?>" alter="imagen evento" width="140"></td>
                            <?php
                                if($_SESSION['rol'] != 'consultor') {
                            ?>
                            <td>
                                <div class="text-center">
                                    <a href="editar-evento.php?id=<?php echo $evento['id_evento']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="#" data-id="<?php echo $evento['id_evento']; ?>" data-imagen="<?php echo $evento['evento_imagen']; ?>" data-tipo="eventos" class="btn btn-danger borrar-registro-imagen"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                            <?php
                                } // fin del if
                            ?>
                        </tr>
                    <?php 
                    } // fin del foreach
                } catch (\Throwable $th) {
                    echo $th->getMessage();
                } // fin del catch
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Tipo de Evento</th>
                        <th>Cliente</th>
                        <th>Fecha del Evento</th>
                        <th>Costo</th>
                        <th>Imagen</th>
                        <?php echo $_SESSION['rol'] != 'consultor' ? "<th>Acciones</th>" : ''; ?> 
                    </tr>
                </tfoot>
            </table>
        </div><!-- .tabla -->
    </div><!-- .contenedor-tabla -->
</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>