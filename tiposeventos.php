<?php 
    include "includes/funciones/sesiones.php";
    include 'includes/templates/header.php'; 
?>

<div class="contenedor">
    <div class="seccion">
        <h1 class="titulo-seccion">Tipos de Eventos</h1>
    </div>
    <?php 
        // validación para rol de consultor
        if($_SESSION['rol'] != 'consultor') {
    ?>
    <div class="contenedor-formulario">
        <p class="text-center">Llene los campos para agregar un nuevo tipo de evento <span>*todos los campos son obligatorios*</span></p>
        <div class="formulario">
            <form action="includes/modelos/modelo-tiposeventos.php" method="post" id="guardar_registro_imagen" name="guardar_registro_imagen" enctype="multipart/form-data">
                <div class="campos">
                    <div class="campo">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                    <div class="campo">
                        <label for="precio">Precio:</label>
                        <input type="number" name="precio" id="precio" required>
                    </div>
                    <div class="campo">
                        <label for="imagen_tipo">Imagen del tipo de evento:</label>
                        <input type="file" name="imagen_tipo" id="imagen_tipo" accept="image/*" required>
                    </div>
                </div>
                <div class="campo-boton">
                    <input type="hidden" name="accion" value="crear">
                    <input class="boton" type="submit" value="Guardar" name="submit" id="crear_registro">
                </div>
            </form>
        </div>
    </div><!-- .contenedor-formulario -->
    <?php
        } // fin del if
    ?>
    <div class="contenedor-tabla seccion">
        <h2>Listado de Tipos de Eventos</h2>
        <div class="tabla">
            <table id="registros" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio (base)</th>
                    <th>Imagen</th>
                    <!-- validación para consultor pero con operador ternario -->
                    <?php echo $_SESSION['rol'] != 'consultor' ? "<th>Acciones</th>" : ''; ?> 
                </tr>
                </thead>
                <tbody> 
                <?php
                try {
                    $tipos_eventos = obtenerTiposEventos();
                    foreach($tipos_eventos as $tipo) {
                    ?>
                        <tr>
                            <td><?php echo $tipo['tipo_nombre']; ?></td>
                            <td><?php echo $tipo['tipo_precio_base']; ?></td>
                            <td class="text-center"><img src="img/tipos_eventos/<?php echo $tipo['tipo_imagen']; ?>" alter="imagen tipo de evento" width="140"></td>
                            
                            <?php 
                                // validación para rol de consultor
                                if($_SESSION['rol'] != 'consultor') {
                            ?>
                            <td>
                                <div class="text-center">
                                    <a href="editar-tipoevento.php?id=<?php echo $tipo['id_tipo_evento']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="#" data-id="<?php echo $tipo['id_tipo_evento']; ?>" data-imagen="<?php echo $tipo['tipo_imagen']; ?>" data-tipo="tiposeventos" class="btn btn-danger borrar-registro-imagen"><i class="fas fa-trash-alt"></i></a>
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
                        <th>Nombre</th>
                        <th>Precio (base)</th>
                        <th>Imagen</th>
                        <!-- validación para consultor pero con operador ternario -->
                        <?php echo $_SESSION['rol'] != 'consultor' ? "<th>Acciones</th>" : ''; ?> 
                    </tr>
                </tfoot>
            </table>
        </div><!-- .tabla -->
    </div><!-- .contenedor-tabla -->
</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>