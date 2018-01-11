$(document).ready(function(){
  var rutv;
  var rutc;
  var nombre;
  var email;
  $('#volver').click(function(){
    $('#cap').empty().load('usuario/principal');
  });
  $("#rut")
    .rut({formatOn: 'keyup', validateOn: 'change', minimumLength: 7})
    .on('rutInvalido', function(){
      $(this).parents(".form-group").addClass("has-danger");
      $(this).addClass("is-invalid");
      rutv = '';
    })
    .on('rutValido', function(){
      $(this).parents(".form-group").removeClass("has-danger");
      $(this).removeClass("is-invalid");
    })
    .on('rutValido', function(e, rut){
      rutv = rut;
    });
    if(rutv !== ''){
      $('#rut').blur(function(){
        rutc = $("#rut").val();
        var rut = rutv;
        console.log(rut);
        if(rut === undefined){
          //$("#validar").prop("disabled", true);
        }
        $.ajax({
          url: window.location.href+'usuario/buscar',
          type: 'POST',
          cache: false,
          data: {"rut":rut, "rutc":rutc},
          success: function(result){
            console.log(result);
            if(result !== false){
              $("#nombre").attr("value", result['nombre'] +' '+ result['apellido']);
              $("#nombre").prop("disabled", true);
              if(result['email'] !== ''){
                $('.email').attr("value", result['email']);
                $(".email").prop("disabled", true);
              }
              $("#validar").prop("disabled", false);
            }
            else{
              swal(
                    'Usted no se encuentra registrado',
                    'Coloque su nombre y correo electrónico',
                    'info'
                  );
              $(".form-group").parents(".form-group").append('<div class="invalid-feedback">Este RUT es inválido</div>')
              $("#validar").prop("disabled", false);
            }
          }
        })
      });
      $("#validar").click(function(){
        var nombre = $("#nombre").val();
        var correo = $(".email").val();
        if(nombre === '' || correo === ''){
          swal(
                'Ha ocurrido un error',
                'Su nombre o correo no pueden ser nulos, llene los campos correspondientes',
                'error'
              );
              return;
        }
        else{
          //$("#validar").prop("disabled", false);
        }
        if(rutv === undefined){
          $.ajax({
            type: "POST",
            url: window.location.href+'usuario/rut',
            cache: false,
            data: {"dato":''},
            success: function(result){
              //console.log("este es mi rut "+result);
              rutv = result;
              nombre = $("#nombre").val();
              email = $("#email").val();
              console.log("Pasa aqui");
              $.ajax({
                type: "POST",
                url: window.location.href+'usuario/verificar_usuario',
                cache: false,
                data: {"rut": rutv, "nombre": nombre, "email": email},
                success: function(result){
                  console.log("resultado de busqueda "+result);
                  switch(result) {
                      case 1:
                      $('#cap').empty().load('usuario/alumno');
                          break;
                      case 2:
                      swal(
                            'Ha ocurrido un error',
                            'Usted no figura registrado como PROFESOR, es probable que haya equivocado el ingreso, por lo cual deberá dar clic en el botón APODERADO. Si efectivamente es profesor, por favor contáctenos al 6003811312.',
                            'error'
                          );
                      $('#cap').empty().load('usuario/principal');
                          break;
                      case 3:
                      swal(
                            'Ha ocurrido un error',
                            'Usted no es un Apoderado',
                            'error'
                          );
                      $('#cap').empty().load('usuario/principal');
                          break;

                  }
                }
              })
            }
          })
        }else{
          nombre = $("#nombre").val();
          email = $(".email").val();
          console.log("Pasa este otro");
          $.ajax({
            type: "POST",
            url: window.location.href+'usuario/verificar_usuario',
            cache: false,
            data: {"rut": rutv, "nombre": nombre, "email":email},
            success: function(result){
              console.log("resultado de busqueda "+result)
              switch(result) {
                  case 1:
                  $('#cap').empty().load('usuario/alumno');
                      break;
                  case 2:
                  swal(
                        'Ha ocurrido un error',
                        'Usted no figura registrado como PROFESOR, es probable que haya equivocado el ingreso, por lo cual deberá dar clic en el botón APODERADO. Si efectivamente es profesor, por favor contáctenos al 6003811312.',
                        'error'
                      );
                  $('#cap').empty().load('usuario/principal');
                      break;
                  case 3:
                  swal(
                        'Ha ocurrido un error',
                        'Usted no es un Apoderado',
                        'error'
                      );
                  $('#cap').empty().load('usuario/principal');
                  break;
                  case 4:
                  swal(
                        'Ha ocurrido un error',
                        'Usted no se encuentra registrado en nuestro sistema',
                        'error'
                      );
                  //$("#validar").prop("disabled", true);
                      break;
              }
            }
          })
        }
      });
    }
    var rt = $("#rut").val();
    if(rt === ''){
      //$("#validar").prop("disabled", true);
    }
    else{
      $("#nombre").prop("disabled", true);
      var email = $('.email').val();
      if(email !== ''){
        $(".email").prop("disabled", true);
      }
      $(".email").prop("disabled", true);
    }
})
