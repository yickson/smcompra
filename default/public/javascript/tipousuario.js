  $(document).ready(function()
    {
    $("#btn2").click(function () {
      var tipo = $('#apo').val();;//
      $.ajax({
        type: 'POST',
        cache: false,
        url: 'usuario/tipo',
        data: {"tipo": tipo},
        success: function(result){
          console.log(tipo);
          $("#cap").empty().load("usuario/datos");
          //window.location.href = "usuario/datos";
        },
      })
    });
    $("#btn1").click(function () {
      var tipo = $('#profe').val();; //
      $.ajax({
        type: 'POST',
        cache: false,
        url: 'usuario/tipo',
        data: {'tipo': tipo},
        success: function(result){
          console.log(tipo);
          $("#cap").empty().load("usuario/datos");
          //window.location.href = "usuario/datos";
        },
      })
    });
  });
