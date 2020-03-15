<?php
require_once '../funciones/conexion.php';
$accion = $_POST['accion'];

$nombre = htmlspecialchars($_POST['nombre']);
$precio = htmlspecialchars($_POST['precio']);
$id =$_POST['id'];
$imagen_tipo = $_FILES['imagen_tipo'];
// directorio donde se almacenan las imágenes
$directorio = "../../img/tipos_eventos/";

if($accion == 'crear') {
    // crear registro
    try {
        $imagen_url = $imagen_tipo['name'];
        $stmt = $conn->prepare("INSERT INTO tipos_eventos (tipo_nombre, tipo_imagen, tipo_precio_base) VALUES (?,?,?)");
        $stmt->bind_param('ssd', $nombre, $imagen_url, $precio);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            // se insertó correctamente
            // revisar que exista el directorio
            if(!is_dir($directorio)) {
                // si no existe entonces crear directorio
                mkdir($directorio, 0755, true);
            }
            // mover el archivo de la ubicación temporal hacia la ubicación final
            if(move_uploaded_file($imagen_tipo['tmp_name'], $directorio.$imagen_tipo['name'])) {   
                $imagen_resultado = "Se subió correctamente";
            } else {
                $imagen_resultado = "Error al subir imagen: " . error_get_last();
            }
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "pagina" => "tiposeventos.php",
                "resultado_imagen" => $imagen_resultado
            );
        } else {
            // hubo un error
            $respuesta = array(
                "respuesta" => "error",
                "error" => $stmt->error,
                "errno" => $stmt->errno,
                "campo" => "Nombre de tipo de evento"
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
        $imagen_url = $imagen_tipo['name'];
        if($_FILES['imagen_tipo']['size'] > 0) {
            // cuando se cargó una imagen nueva
            $sentencia = $conn->prepare('UPDATE tipos_eventos SET tipo_nombre = ?, tipo_imagen = ?, tipo_precio_base = ?, editado = NOW() WHERE id_tipo_evento = ?');
            $sentencia->bind_param('ssdi', $nombre, $imagen_url, $precio, $id); 
        } else {
            // cuando no se cargó una imagen
            $sentencia = $conn->prepare('UPDATE tipos_eventos SET tipo_nombre = ?, tipo_precio_base = ?, editado = NOW() WHERE id_tipo_evento = ?');
            $sentencia->bind_param('sdi', $nombre, $precio, $id); 
        }

        $sentencia->execute();
        if($sentencia->affected_rows > 0) {
            if($_FILES['imagen_tipo']['size'] > 0) {
                // se cargó una imagen nueva
                // revisar que exista el directorio
                if(!is_dir($directorio)) {
                    // si no existe entonces crear directorio
                    mkdir($directorio, 0755, true);
                }
                // mover el archivo de la ubicación temporal hacia la ubicación final
                if(move_uploaded_file($imagen_tipo['tmp_name'], $directorio.$imagen_tipo['name'])) {
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
                'pagina' => 'tiposeventos.php',
                'resultado_imagen' => $imagen_resultado
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error',
                "error" => $sentencia->error,
                "errno" => $sentencia->errno,
                "campo" => "Nombre de tipo de evento"
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
        $stmt = $conn->prepare("DELETE FROM tipos_eventos WHERE id_tipo_evento = ?");
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

?>