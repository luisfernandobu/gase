$(document).ready(function () {
    // llamado AJAX para formulario sin imagen
    $('#guardar_registro').on('submit', function(e) {
        e.preventDefault();

        let datos = $(this).serializeArray();
         //console.log(datos);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: datos,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if(response.respuesta === 'correcto') {
                    // se guardó correctamente, enviar notificación e insertar en el html
                    Swal.fire(
                        'Correcto!',
                        'Guardado correctamente',
                        'success'
                    )
                    .then(result => {
                        window.location.href = response.pagina;
                    });
                    
                    // if(!document.querySelector('#actualizar_registro')){
                    //     document.querySelector('#guardar_registro').reset();
                    // }
                } else {
                    // error 1062 -> error de campo único ya existente
                    if(response.respuesta === 'error' && response.errno === 1062) {
                        Swal.fire(
                            'Error!',
                            response.campo + ' ya existe',
                            'error'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            'Hubo un error...',
                            'error'
                        );
                    }
                }
            }
        });
    });

    // llamado AJAX para formulario con imagen
    $('#guardar_registro_imagen').on('submit', function(e) {
        e.preventDefault();

        let datos = new FormData(this);
        // console.log(datos);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: datos,
            dataType: 'json',
            contentType: false,
            processData: false,
            async: true,
            cache: false,
            success: function (response) {
                console.log(response);
                if(response.respuesta === 'correcto') {
                    Swal.fire(
                        'Correcto!',
                        'Guardado correctamente',
                        'success'
                    )
                    .then(result => {
                        window.location.href = response.pagina;
                    });

                    // if(!document.querySelector('#actualizar_registro')){
                    //     document.querySelector('#guardar_registro_archivo').reset();
                    // }
                    
                } else {
                    // error 1062 -> error de campo único ya existente
                    if(response.respuesta === 'error' && response.errno === 1062) {
                        Swal.fire(
                            'Error!',
                            response.campo + ' ya existe',
                            'error'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            'Hubo un error...',
                            'error'
                        );
                    }
                }
            }
        });
    });

    // eliminar un registro
    $('.borrar-registro').on('click', function(e) {
        e.preventDefault();

       
        const id = $(this).attr('data-id'),
              tipo = $(this).attr('data-tipo');

        let datos = new FormData();
        datos.append('id', id);
        datos.append('accion', 'borrar');

        // if(tipo === 'tiposeventos' || tipo === 'eventos') {
        //     const imagen = $(this).attr('data-imagen');
        //     datos.append('imagen', imagen);
        // }

        Swal.fire({
        title: 'Estás seguro(a)?',
        text: "Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
            console.log(result);
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: `includes/modelos/modelo-${tipo}.php`,
                    data: {
                        'id': id,
                        'accion': 'borrar'
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(response.respuesta === 'correcto') {
                            Swal.fire(
                            'Eliminado!',
                            `Registro eliminado correctamente!`,
                            'success'
                            );
                            jQuery('[data-id="'+ response.id_eliminado +'"]').parents('tr').remove();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al eliminar el registro'
                            });
                        }
                    }
                });  
            }    
        })
    });

    // eliminar un registro con imagen
    $('.borrar-registro-imagen').on('click', function(e) {
        e.preventDefault();

        const id = $(this).attr('data-id'),
              tipo = $(this).attr('data-tipo'),
              imagen = $(this).attr('data-imagen');

        Swal.fire({
        title: 'Estás seguro(a)?',
        text: "Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
            console.log(result);
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: `includes/modelos/modelo-${tipo}.php`,
                    data: {
                        'id': id,
                        'accion': 'borrar',
                        'imagen': imagen
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(response.respuesta === 'correcto') {
                            Swal.fire(
                            'Eliminado!',
                            `Registro eliminado correctamente!`,
                            'success'
                            );
                            jQuery('[data-id="'+ response.id_eliminado +'"]').parents('tr').remove();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al eliminar el registro'
                            });
                        }
                    }
                });  
            }    
        })
    });
}); // fin de document ready