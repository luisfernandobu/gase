<?php
require "includes/funciones/conexion.php";
$accion = $_POST['accion'];
$numero_usuario = htmlspecialchars($_POST['numero_usuario']);
$password = htmlspecialchars($_POST['password']);

if($accion == 'login') {
    // código para iniciar sesión
    try {
        // seleccionar usuario de la base de datos
        $sentencia = $conn->prepare('SELECT id_usuario, usuario_nombre, usuario_apellido, usuario_numero, usuario_password, rol_nombre FROM usuarios INNER JOIN roles ON usuarios.roles_id_rol = roles.id_rol WHERE usuario_numero = ?');
        $sentencia->bind_param('i', $numero_usuario);
        $sentencia->execute();
        // recibir datos del usuario
        $sentencia->bind_result($id_usuario, $nombre_usuario, $apellido_usuario, $num_usuario, $password_usuario, $rol_usuario);
        $sentencia->fetch();
        if($id_usuario) {
            // el usuario existe, verificar el password
            if(password_verify($password, $password_usuario)) {
                // loggin correcto
                // iniciar la sesión
                session_start();
                $_SESSION['nombre']= $nombre_usuario . " " . $apellido_usuario;
                $_SESSION['id'] = $id_usuario;
                $_SESSION['numero_usuario'] = $num_usuario;
                $_SESSION['rol'] = $rol_usuario;
                $_SESSION['login'] = true;
                // construir respuesta
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'nombre' => $_SESSION['nombre'],
                    'rol' => $rol_usuario
                );
            } else {
                // loggin incorrecto, enviar error
                $respuesta = array(
                    'respuesta' => 'error',
                    'mensaje' => 'Contraseña incorrecta',
                    "error" => $sentencia->error
                );
            }
        } else {
            $respuesta = array(
                'respuesta' => 'error',
                'mensaje' => 'El usuario no existe'
            );
        }
        $sentencia->close();
        $conn->close();

    } catch (\Throwable $th) {
        $respuesta = array(
            'respuesta' => 'error',
            'error' => $th->getMessage()
        );
    }
    echo json_encode($respuesta);
}



?>