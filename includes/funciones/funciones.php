<?php

// obtiene la página actual
function paginaActual() {
    $archivo = basename($_SERVER['PHP_SELF']);
    $pagina = str_replace('.php', '', $archivo);
    return $pagina;
}

// obtiene todos los roles
function obtenerRoles() {
    require 'conexion.php';
    return $conn->query("SELECT * FROM roles");
}

// obtener todos los usuarios
function obtenerUsuarios() {
    require 'conexion.php';
    $sql = "SELECT id_usuario, usuario_nombre, usuario_apellido, usuario_numero, rol_nombre FROM usuarios 
            INNER JOIN roles 
            ON usuarios.roles_id_rol = roles.id_rol";
    return $conn->query($sql);
}

// obtener un usuario (editar-usuario)
function obtenerUsuario($id = null) {
    require 'conexion.php';
    return $conn->query("SELECT * FROM usuarios WHERE id_usuario = {$id}");
}

// obtener todos los tipos de eventos
function obtenerTiposEventos() {
    require 'conexion.php';
    return $conn->query("SELECT * FROM tipos_eventos ORDER BY tipo_nombre");
}

// obtener un tipo de evento
function obtenerTipoEvento($id = null) {
    require 'conexion.php';
    return $conn->query("SELECT * FROM tipos_eventos WHERE id_tipo_evento = {$id}");
}

// obtener todos los clientes
function obtenerClientes() {
    require 'conexion.php';
    return $conn->query("SELECT * FROM clientes");
}

// obtener un cliente
function obtenerCliente($id = null) {
    require 'conexion.php';
    return $conn->query("SELECT * FROM clientes WHERE id_cliente = {$id}");
}

// obtener todos los eventos
function obtenerEventos() {
    require 'conexion.php';
    $sql = "SELECT id_evento, evento_imagen, evento_fecha, evento_precio, tipo_nombre, cliente_nombre, cliente_apellido FROM eventos 
            INNER JOIN tipos_eventos 
            ON eventos.tipos_id_tipo = tipos_eventos.id_tipo_evento 
            INNER JOIN clientes 
            ON eventos.clientes_id_cliente = clientes.id_cliente";
    return $conn->query($sql);
}

// obtener un evento
function obtenerEvento($id = null) {
    require 'conexion.php';
    $sql = "SELECT eventos.*, tipo_nombre, cliente_email FROM eventos 
            INNER JOIN tipos_eventos 
            ON eventos.tipos_id_tipo = tipos_eventos.id_tipo_evento 
            INNER JOIN clientes 
            ON eventos.clientes_id_cliente = clientes.id_cliente 
            WHERE id_evento = {$id}";
    return $conn->query($sql);
}

?>