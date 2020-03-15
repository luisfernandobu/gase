<?php 
    include "includes/funciones/sesiones.php";
    if($_SESSION['rol'] != 'administrador') {
        header('location: eventos.php');
    }
    include 'includes/templates/header.php'; 
?>

<div class="contenedor">
    <div class="seccion">
        <h1 class="titulo-seccion">Usuarios</h1>
    </div>
    <div class="contenedor-formulario">
        <p class="text-center">Llene los campos para agregar un usuario nuevo <span>*todos los campos son obligatorios*</span></p>
        <div class="formulario">
            <form action="includes/modelos/modelo-usuarios.php" method="post" id="guardar_registro" name="guardar_registro">
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
                        <label for="numero_usuario">Número de Usuario (4 dígitos):</label>
                        <input type="number" name="numero_usuario" id="numero_usuario" min="1000" max="9999" required>
                    </div>
                    <div class="campo">
                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol" required>
                            <option value="" disabled selected>-- Seleccione --</option>
                            <?php
                                $roles = obtenerRoles();
                                foreach($roles as $rol) {
                                    echo "<option value='{$rol['id_rol']}'>{$rol['rol_nombre']}</option> ";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div class="campo">
                        <label for="password2">Confirmar Contraseña:</label>
                        <input type="password" id="password2" required>
                    </div>
                </div><!-- .campos -->
                <div class="campo-boton">
                    <input type="hidden" name="accion" value="crear">
                    <input class="boton" type="submit" value="Guardar" name="submit" id="crear_usuario">
                </div>
            </form>
        </div>
    </div><!-- .contenedor-formulario -->
    <div class="contenedor-tabla seccion">
        <h2>Listado de Usuarios</h2>
        <div class="tabla">
            <table id="registros" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Núm. de Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody> 
                <?php
                try {
                    $usuarios = obtenerUsuarios();
                    foreach($usuarios as $usuario) {
                ?>
                    <tr>
                    <td><?php echo $usuario['usuario_nombre'] . " " . $usuario['usuario_apellido']; ?></td>
                    <td><?php echo $usuario['usuario_numero'] ?></td>
                    <td><?php echo $usuario['rol_nombre'] ?></td>
                    <td>
                        <div class="text-center">
                        <a href="editar-usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="#" data-id="<?php echo $usuario['id_usuario']; ?>" data-tipo="usuarios" class="btn btn-danger borrar-registro"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
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
                        <th>Núm. de Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
            </table>
        </div><!-- .tabla -->
    </div><!-- .contenedor-tabla -->
</div><!-- .contenedor -->
<?php include 'includes/templates/footer.php'; ?>