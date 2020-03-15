$(document).ready(function () {
    // tabla de registros
    $("#registros").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "language": {
          paginate: {
            "next": "Siguiente",
            "previous": "Anterior",
            'last': 'Último',
            'first': 'Primero'
          },
          info: 'Mostrando _START_ a _END_ de _TOTAL_ resultados',
          emptyTable: 'No hay información disponible',
          infoEmpty: '0 registros',
          search: 'Buscar: '
        }
    });

    // navegación
    $('.menu-movil').on('click', function(){
        $('.navegacion').slideToggle();
    });

    //scripts para módulo de usuarios
    if(document.querySelector('#crear_usuario')) {
      document.querySelector('#crear_usuario').disabled = true;

      $('#password2').on('input', function () {
        if(validarPassword()) {
          $(this).attr('style', 'border: 2px solid green');
          $('#password').attr('style', 'border: 2px solid green');
          // habilitar botón
          $('#crear_usuario').attr('disabled', false);
        } else {
          $(this).attr('style', 'border: 2px solid red');
          $('#password').attr('style', 'border: 2px solid red');
        }
      });
    }

    function validarPassword() {
      const password = $('#password').val(),
            password2 = $('#password2').val();
      if(password === password2) {
        return true;
      } else {
        return false;
      }
    }

    // scripts para módulo de eventos
    if(document.querySelector('#guardar_evento')) {
      // si existe el botón de guardar_evento (solo en módulo de eventos) se deshabilita
      document.querySelector('#guardar_evento').disabled = true;
    }

    $('#cotizar').click(function (e) { 
      e.preventDefault();
      // campos
      const email = document.querySelector('#email_cliente_evento').value,
            fecha = document.querySelector('#fecha_evento').value,
            idTipoEvento = document.querySelector('#tipo_evento').value;

      // validar que los campos no estén vacíos
      if(email === '' || fecha === '' || idTipoEvento === '') {
        Swal.fire(
          'Error!',
          'Debe llenar todos los campos',
          'error'
        );
        document.querySelector('#guardar_evento').disabled = true;
      } else {
        // validar que el email exista
        if($('#existe_email').val() === "false") {
          Swal.fire(
            'Error!',
            'Ningún cliente registrado con email actual',
            'error'
          ).then(result => {
            document.querySelector('#email_cliente_evento').focus();
          });        
          return;
        } 
        // se habilita el botón una vez pasadas todas las validaciones
        document.querySelector('#guardar_evento').disabled = false;

        // obtener precio y nombre del tipo de evento (para mostrarlos luego en los detalles)
        const precio = $('#tipo_evento>option:selected').attr('data-precio');
        const nombreTipoEvento = $('#tipo_evento>option:selected').text();

        // checar qué día de la semana será el evento para aplicar descuento en caso de ser de L a J
        const dia = new Date(fecha).getDay();
        let total = precio,
            descuento = 0,
            descripcionDia = diaDeLaSemana(dia);
        
        if(dia < 4) {
          // calcular descuento
          descuento = precio * .10;  
          total = total * .90;        
        } 
        total = Number(total).toFixed(2);

        // imprimir los detalles
        let html = `
          <div class="detalle">
            <p>Evento: ${nombreTipoEvento}</p>
            <p>$${precio}</p>
          </div>
          <div class="detalle">
            <p>Día: ${descripcionDia}</p>
            <p>${descuento > 0 ? '- $' + descuento : 'Sin descuento'}</p>
          </div>
        `;
        $('#detalles').html(html);
        $('#total').text('$' + total);
        // campo oculto que guarda el precio final
        $('#precio_evento').val(total);
      }
    });

    // validación del email
    $('#email_cliente_evento').blur(function (e) { 
      e.preventDefault();
      validarEmail(this.value);
    });

    function validarEmail(email) {
      $.ajax({
        type: "POST",
        url: "includes/modelos/modelo-eventos.php",
        data: {
            "email": email,
            "accion": "validar_email"
        },
        dataType: "json",
        success: function (response) {
          //console.log(response);
          if(response.respuesta === 'correcto') {
            // se realizó la consulta correctamente
            if(response.existe_correo == true) {
              // recibir id del cliente y almacenar en campos ocultos el id del cliente y que existe el email
              $('#existe_email').val('true');
              $('#id_cliente').val(response.id_cliente);
            } else {
              $('#existe_email').val('false');
              $('#id_cliente').val('');
              document.querySelector('#guardar_evento').disabled = true;
            }
          } else {
            // imprimir error
          }
        }
      });
    }

    // regresa el día de la semana en texto
    function diaDeLaSemana(dia) {
      let descripcionDia;
      switch (dia) {
        case 0:
          descripcionDia = "Lunes";
          break;
        case 1:
          descripcionDia = "Martes";
          break;
        case 2:
          descripcionDia = "Miércoles";
          break;
        case 3:
          descripcionDia = "Jueves";
          break;
        case 4:
          descripcionDia = "Viernes";
          break;
        case 5:
          descripcionDia = "Sábado";
          break;
        case 6:
          descripcionDia = "Domingo";
          break;
        default:
          descripcionDia = "Día desconocido";
          break;
      }
      return descripcionDia;
    }

}); // fin de document ready