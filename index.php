<?php include 'includes/templates/header.php'; ?>
<?php
if(isset($_GET['logout'])) {
    session_start();
    $_SESSION = array();
    header('Location: index.php');
}

?>
<div class="caja-login">
    <div class="contenido-login">
        <h1>Gestor Administrativo para Salón de Eventos</h1>
        <div class="formulario-login">
            <h2>Inicio de Sesión</h2>
            <form action="login-admin.php" method="post" id="login" name="login">
                <div class="campo">
                    <label for="numero_usuario">Número de Usuario (4 dígitos):</label>
                    <input type="number" name="numero_usuario" id="numero_usuario" min="1000" max="9999" required>
                </div>
                <div class="campo">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="campo-boton">
                    <input type="hidden" name="accion" value="login">
                    <input class="boton" type="submit" value="Iniciar Sesión" name="submit">
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/templates/footer.php'; ?>