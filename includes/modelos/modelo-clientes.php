<?php
require_once '../funciones/conexion.php';
$accion = $_POST['accion'];

$nombre = htmlspecialchars($_POST['nombre']);
$apellido = htmlspecialchars($_POST['apellido']);
$email = htmlspecialchars($_POST['email']);
$id =$_POST['id'];

if($accion == 'crear') {

    try {
        $stmt = $conn->prepare("INSERT INTO clientes (cliente_nombre, cliente_apellido, cliente_email) VALUES (?,?,?)");
        $stmt->bind_param('sss', $nombre, $apellido, $email);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            // se insertó correctamente
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "pagina" => "clientes.php"
            );
        } else {
            // hubo un error
            $respuesta = array(
                "respuesta" => "error",
                "error" => $stmt->error,
                "errno" => $stmt->errno,
                "campo" => "Email del cliente"
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
        $stmt = $conn->prepare('UPDATE clientes SET cliente_nombre = ?, cliente_apellido = ?, cliente_email = ?, editado = NOW() WHERE id_cliente = ?');
        $stmt->bind_param('sssi', $nombre, $apellido, $email, $id); 
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            $respuesta = array (
                'respuesta' => 'correcto',
                'id_actualizado' => $id,
                'pagina' => 'clientes.php'
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error',
                "error" => $stmt->error,
                "errno" => $stmt->errno,
                "campo" => "Email del cliente"
            );
        }
        $stmt->close();
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
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
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