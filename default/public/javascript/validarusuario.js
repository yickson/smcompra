  $(document).ready(function(){
    var rutv;
    var curso;
    $("#enviar").prop("disabled", true);
    $(document).on("keyup", '.rut', function(){
      $(".rut")
        .rut({formatOn: 'keyup', validateOn: 'change', minimumLength: 8})
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
    });
    $(document).on("blur", ".rut", function(){
      var numero = $(this).attr('id');
      console.log("Este es un valor antes del ajax "+rutv);
      if(rutv !== undefined){
        $.ajax({
          url: window.location.href + 'usuario/buscar_alumno',
          type: 'POST',
          cache: false,
          data: {"rut":rutv},
          success: function(result){
            console.log(result);
            if(result == 2){
              $("#enviar").prop("disabled", true);
              swal(
                    'Ha ocurrido un error',
                    'Usted no es el apoderado de este alumno',
                    'error'
                  );
            }else{
              $("#enviar").prop("disabled", false);
              $("#nombre"+numero).attr("value", result['nombre']);
              $("#nombre"+numero).prop("disabled", true);
              if(result['email'] != ""){
                $('#email'+numero).attr("value", result['email']);
                $("#email"+numero).prop("disabled", true);
              }
              $("#curso"+numero+ " option").each(function(){
                 curso = $(this).attr('value');
                 if(curso == result['curso']){
                   $(this).prop("selected", true);
                 }
              });
            }
            if(!result){
              $("#enviar").prop("disabled", true);
              $("#nombre"+numero).attr("value", '');
              $("#nombre"+numero).prop("disabled", false);
              $('#email'+numero).attr("value", '');
              $("#email"+numero).prop("disabled", false);
              swal(
                    'Ha ocurrido un error',
                    'Este RUT de alumno no existe en nuestros registros',
                    'error'
                  );
            }
          }
        });
      }
    });
    $("#enviar").submit(function(){
      var c = $("#email").val();
      console.log(c);
      if(c == ''){
        //console.log('Previne el submit de este form');
        return false;
      }
    })
  });
