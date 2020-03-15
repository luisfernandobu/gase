<?php
require_once '../funciones/conexion.php';
$accion = $_POST['accion'];

$tipo_evento = $_POST['tipo_evento'];
$fecha_evento = htmlspecialchars($_POST['fecha_evento']);
$id_cliente = $_POST['id_cliente'];
$precio_evento = $_POST['precio_evento'];

$id =$_POST['id'];

$imagen_evento = $_FILES['imagen_evento'];
// directorio donde se almacenan las imágenes
$directorio = "../../img/eventos/";

if($accion == 'crear') {
    // crear registro
    try {
        $imagen_url = $imagen_evento['name'];
        $stmt = $conn->prepare("INSERT INTO eventos (evento_imagen, evento_fecha, evento_precio, tipos_id_tipo, clientes_id_cliente) VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssdii', $imagen_url, $fecha_evento, $precio_evento, $tipo_evento, $id_cliente);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            // se insertó correctamente, mover imagen
            // revisar que exista el directorio
            if(!is_dir($directorio)) {
                // si no existe entonces crear directorio
                mkdir($directorio, 0755, true);
            }
            // mover el archivo de la ubicación temporal hacia la ubicación final
            if(move_uploaded_file($imagen_evento['tmp_name'], $directorio.$imagen_evento['name'])) {     
                $imagen_resultado = "Se subió correctamente";
            } else {
                $imagen_resultado = "Error al subir imagen: " . error_get_last();
            } 
            // construir respuesta
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "pagina" => "eventos.php",
                "resultado_imagen" => $imagen_resultado
            );
        } else {
            // hubo un error
            $respuesta = array(
                "respuesta" => "error",
                "error" => $stmt->error,
                "errno" => $stmt->errno,
                "campo" => "Fecha del evento"
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
        $imagen_url = $imagen_evento['name'];
        if($_FILES['imagen_evento']['size'] > 0) {
            // cuando se cargó una imagen nueva
            $sentencia = $conn->prepare('UPDATE eventos SET evento_imagen = ?, evento_fecha = ?, evento_precio = ?, tipos_id_tipo = ?, clientes_id_cliente = ?, editado = NOW() WHERE id_evento = ?');
            $sentencia->bind_param('ssdiii', $imagen_url, $fecha_evento, $precio_evento, $tipo_evento, $id_cliente, $id); 
        } else {
            // cuando no se cargó una imagen
            $sentencia = $conn->prepare('UPDATE eventos SET evento_fecha = ?, evento_precio = ?, tipos_id_tipo = ?, clientes_id_cliente = ?, editado = NOW() WHERE id_evento = ?');
            $sentencia->bind_param('sdiii', $fecha_evento, $precio_evento, $tipo_evento, $id_cliente, $id); 
        }

        $sentencia->execute();
        if($sentencia->affected_rows > 0) {
            // se insertó correctamente
            if($_FILES['imagen_evento']['size'] > 0) {
                // se cargó una imagen nueva
                // revisar que exista el directorio
                if(!is_dir($directorio)) {
                    // si no existe entonces crear directorio
                    mkdir($directorio, 0755, true);
                }
                // mover el archivo de la ubicación temporal hacia la ubicación final
                if(move_uploaded_file($imagen_evento['tmp_name'], $directorio.$imagen_evento['name'])) {
                    $imagen_resultado = "Se subió correctamente";
        
                    // eliminar imagen antigua
                    $imagen_antigua = $_POST['imagen_antigua'];
                    if(unlink($directorio . $imagen_antigua)){
                        // se eliminó la imagen correctamente
                        $imagen_resultado .= ", se eliminó la imagen anterior";
                    } else {
                        $imagen_resultado .= ", error al eliminar imagen anterior: " . error_get_last();
                    }
                } else {
                    $imagen_resultado = "Error al subir imagen: " . error_get_last();
                }
            }
            $respuesta = array (
                'respuesta' => 'correcto',
                'id_actualizado' => $id,
                'pagina' => 'eventos.php',
                'resultado_imagen' => $imagen_resultado
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error',
                "error" => $sentencia->error,
                "errno" => $sentencia->errno,
                "campo" => "Fecha del evento"
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
    $imagen = $_POST['imagen'];
    try {
        $stmt = $conn->prepare("DELETE FROM eventos WHERE id_evento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            // se ejecutó correctamente la sentencia y hay filas afectadas
            // eliminar imagen de la carpeta
            if(unlink($directorio . $imagen)){
                // se eliminó la imagen correctamente
                $resultado_imagen = "La imagen fue eliminada";
            } else {
                $resultado_imagen = "Error al eliminar la imagen: " . error_get_last();
            }
            $respuesta = array(
                "respuesta" => "correcto",
                "id_eliminado" => $id,
                "resultado_imagen" => $resultado_imagen
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

// validar email del cliente
if($accion == 'validar_email') {
    // die(json_encode($_POST));
    $email = htmlspecialchars($_POST['email']);
    try {
        $sentencia = $conn->prepare("SELECT id_cliente FROM clientes WHERE cliente_email = ?");
        $sentencia->bind_param('s', $email);
        $sentencia->execute();
        $sentencia->bind_result($id_cliente);
        $sentencia->fetch();
        if(!empty($id_cliente)) {
            $respuesta = array(
                "respuesta" => "correcto",
                "existe_correo" => true,
                "id_cliente" => $id_cliente
            );
        } else {
            $respuesta = array(
                "respuesta" => "correcto",
                "existe_correo" => false
            );
        }
        $sentencia->close();
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