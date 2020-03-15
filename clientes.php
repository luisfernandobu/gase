<?php 
    include "includes/funciones/sesiones.php";
    include 'includes/templates/header.php'; 
?>

<div class="contenedor">
    <div class="seccion">
        <h1 class="titulo-seccion">Clientes</h1>
    </div>
    <?php
        if($_SESSION['rol'] != 'consultor') {
    ?>
    <div class="contenedor-formulario">
        <p class="text-center">Llene los campos para agregar un nuevo cliente <span>*todos los campos son obligatorios*</span></p>
        <div class="formulario">
            <form action="includes/modelos/modelo-clientes.php" method="post" id="guardar_registro" name="guardar_registro">
                <div class="campos">
                    <div class="campo">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                    <div class="campo">
                        <label for="apellido">Apellido:</label>
                        <input type="text" name="apellido" id="apellido" required>
                    </div>
                    <div class="campo">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" name="email" id="email" required>
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
        <h2>Listado de Clientes</h2>
        <div class="tabla">
            <table id="registros" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <?php echo $_SESSION['rol'] != 'consultor' ? "<th>Acciones</th>" : ''; ?> 
                </tr>
                </thead>
                <tbody> 
                <?php
                try {
                    $clientes = obtenerClientes();
                    foreach($clientes as $cliente) {
                ?>
                    <tr>
                    <td><?php echo $cliente['cliente_nombre'] . " " . $cliente['cliente_apellido']; ?></td>
                    <td><?php echo $cliente['cliente_email'] ?></td>
                    <?php
                        if($_SESSION['rol'] != 'consultor') {
                    ?>
                    <td>
                        <div class="text-center">
                        <a href="editar-cliente.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="#" data-id="<?php echo $cliente['id_cliente']; ?>" data-tipo="clientes" class="btn btn-danger borrar-registro"><i class="fas fa-trash-alt"></i></a>
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
                        <th>Email</th>
                        <?php echo $_SESSION['rol'] != 'consultor' ? "<th>Acciones</th>" : ''; ?> 
                    </tr>
                </tfoot>
            </table>
        </div><!-- .tabla -->
    </div><!-- .contenedor-tabla -->
</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>