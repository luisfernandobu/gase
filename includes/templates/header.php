<?php include 'includes/funciones/funciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome -->
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- fuente de google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <!-- normalize -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <!-- datatables bootstrap4 -->
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

    
    <link rel="stylesheet" href="css/estilos.css">
    <title>Gestor para Salón de Eventos</title>
</head>
<body>
<?php  
$paginaActual = paginaActual();
if($paginaActual != "index") {
?>
<nav class="barra">
    <div class="contenedor">
        <div class="menu-movil">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="navegacion">
            <a href="eventos.php">Eventos</a>
            <a href="clientes.php">Clientes</a>
            <a href="tiposeventos.php">Tipos de Eventos</a>
            <?php
                if($_SESSION['rol'] == 'administrador') {
                    echo " <a href='usuarios.php'>Usuarios</a>";
                }
            ?>
            <a href="index.php?logout=1">Cerrar Sesión</a>
        </div>
    </div>
</nav>
<?php
} // fin del if
?>
