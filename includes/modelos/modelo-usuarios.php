<?php
require_once '../funciones/conexion.php';
$accion = $_POST['accion'];

$nombre = htmlspecialchars($_POST['nombre']);
$apellido = htmlspecialchars($_POST['apellido']);
$numero_usuario = htmlspecialchars($_POST['numero_usuario']);
$rol = htmlspecialchars($_POST['rol']);
$password = htmlspecialchars($_POST['password']);
$id =$_POST['id'];

if($accion == 'crear') {
    // crear registro
    $opciones = array(
        'cost' => 12
    );
    
    $password_hashed = password_hash($password, PASSWORD_BCRYPT, $opciones);

    try {
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario_nombre, usuario_apellido, usuario_password, usuario_numero, roles_id_rol) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssii', $nombre, $apellido, $password_hashed, $numero_usuario, $rol);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            // se insertó correctamente
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "pagina" => "usuarios.php"
            );
        } else {
            // hubo un error
            $respuesta = array(
                "respuesta" => "error",
                "error" => $stmt->error,
                "errno" => $stmt->errno,
                "campo" => "Número de usuario"
            );
        }
        $stmt->close();
        $conn->close();
    } catch (\Throwable $th) {
        echo "Error... ".$th->getMessage();
    }
    echo json_encode($respuesta);
}

if($accion == 'actualizar') {
    try {
        if(empty($_POST['password'])) {
            $sentencia = $conn->prepare('UPDATE usuarios SET usuario_nombre = ?, usuario_apellido = ?, usuario_numero = ?, roles_id_rol = ?, editado = NOW() WHERE id_usuario = ?');
            $sentencia->bind_param('ssiii', $nombre, $apellido, $numero_usuario, $rol, $id); 
        } else {
        $opciones = array(
            'cost' => 12
        );
        
        $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

        $sentencia = $conn->prepare('UPDATE usuarios SET usuario_nombre = ?, usuario_apellido = ?, usuario_password = ?, usuario_numero = ?, roles_id_rol = ?, editado = NOW() WHERE id_usuario = ?');
        $sentencia->bind_param('sssiii', $nombre, $apellido, $hash_password, $numero_usuario, $rol, $id); 
        }
    
        $sentencia->execute();
        if($sentencia->affected_rows > 0) {
            $respuesta = array (
                'respuesta' => 'correcto',
                'id_actualizado' => $id,
                'pagina' => 'usuarios.php'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error',
                "error" => $sentencia->error,
                "errno" => $sentencia->errno,
                "campo" => "Número de usuario"
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

if ($accion == 'borrar') {
    try {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            // se ejecutó correctamente la sentencia y hay filas afectadas
            $respuesta = array(
                "respuesta" => "correcto",
                "id_eliminado" => $id
            );
        } else {
            // no hay filas afectadas -> hubo un error
            $respuesta = array(
                "respuesta" => "error",
                "error" => $stmt->error,
                "errno" => $stmt->errno
            );
        }
        $stmt->close();
        $conn->close();
    } catch (\Throwable $th) {
        $respuesta = array(
            "respuesta" => "error",
            "error" => $th->getMessage()
        );
    }
    echo json_encode($respuesta);
}

?>