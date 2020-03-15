<?php 
include "includes/funciones/sesiones.php";
if($_SESSION['rol'] != 'administrador') {
    header('location: eventos.php');
}

if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Error...");
}

include 'includes/templates/header.php'; 

$id = $_GET['id'];
try {
    $resultado = obtenerUsuario($id);
    $usuario = $resultado->fetch_assoc();
} catch (\Throwable $th) {
    echo $th->getMessage();
}

?>

<div class="contenedor seccion">
    <div class="seccion">
        <h1 class="titulo-seccion">Editar Usuarios</h1>
    </div>
    <div class="contenedor-formulario">
        <p class="text-center">Modifique los datos de un usuario</p>
        <div class="formulario">
            <form action="includes/modelos/modelo-usuarios.php" method="post" id="guardar_registro" name="guardar_registro">
                
                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $usuario['usuario_nombre']; ?>" required>
                </div>
                <div class="campo">
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" value="<?php echo $usuario['usuario_apellido']; ?>" required>
                </div>
                <div class="campo">
                    <label for="numero_usuario">Número de Usuario (4 dígitos):</label>
                    <input type="number" name="numero_usuario" id="numero_usuario" min="1000" max="9999" value="<?php echo $usuario['usuario_numero']; ?>" required>
                </div>
                <div class="campo">
                    <label for="rol">Rol:</label>
                    <select name="rol" id="rol" required>
                        <option value="" disabled selected>-- Seleccione --</option>
                        <?php
                            try {
                                $roles = obtenerRoles();
                                foreach($roles as $rol) {
                                    if($rol['id_rol'] == $usuario['roles_id_rol']) {
                                        echo "<option value='{$rol['id_rol']}' selected>{$rol['rol_nombre']}</option> ";
                                    } else {
                                        echo "<option value='{$rol['id_rol']}'>{$rol['rol_nombre']}</option> ";
                                    }                        
                                }
                            } catch (\Throwable $th) {
                                echo $th->getMessage();
                            }       
                        ?>
                    </select>
                </div>
                <div class="campo">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="campo">
                    <label for="password2">Confirmar Contraseña:</label>
                    <input type="password" id="password2">
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