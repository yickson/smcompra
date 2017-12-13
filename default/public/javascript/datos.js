$(document).ready(function(){
  var rutv;
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
    if(rutv != ''){
      $('#rut').blur(function(){
        var rut = rutv;
        console.log(rut);
        if(rut == undefined){
          $("#validar").prop("disabled", true);
        }
        $.ajax({
          url: window.location.href+'usuario/buscar',
          type: 'POST',
          cache: false,
          data: {"rut":rut},
          success: function(result){
            if(result != false){
              $("#nombre").attr("value", result['nombre'] +' '+ result['apellido']);
              $("#nombre").prop("disabled", true);
              $('.email').attr("value", result['email']);
              $(".email").prop("disabled", true);
              $("#validar").prop("disabled", false);
            }
            else{
              $(".form-group").parents(".form-group").append('<div class="invalid-feedback">Este RUT es inválido</div>')
              $("#validar").prop("disabled", true);
            }
          }
        })
      });
      $("#validar").click(function(){
        var div;
        $.ajax({
          type: "POST",
          url: window.location.href+'usuario/verificar_usuario',
          cache: false,
          data: {"rut": rutv},
          success: function(result){
            switch(result) {
                case 1:
                $('#cap').empty().load('usuario/alumno');
                    break;
                case 2:
                swal(
                      'Ha ocurrido un error',
                      'Usted no es un Profesor',
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
            }
          }
        })
      });
    }
})
