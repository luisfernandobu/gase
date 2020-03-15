$(document).ready(function () {
    $('#login').on('submit', function(e) {
        e.preventDefault();
        
        const usuario = document.querySelector('#numero_usuario').value,
              password = document.querySelector('#password').value;
        
        if(usuario === '' || password === '') {
            Swal.fire(
                'Error...',
                'Ambos campos son obligatorios!',
                'error'
                );
        } else {   
            let datos = $(this).serializeArray();
            console.log(datos);
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: datos,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if(response.respuesta === 'correcto') {
                        Swal.fire(
                            'Acceso Correcto!',
                            `Bienvenido(a) ${response.nombre}! (${response.rol}). <br/>Presiona OK para continuar.`,
                            'success'
                        ).then (result => {
                            window.location.href = 'eventos.php';
                        });
                    }
                     else {
                        Swal.fire(
                            'Error...',
                            response.mensaje,
                            'error'
                        );
                    }
                }
            });
        }
    });

}); // fin document ready