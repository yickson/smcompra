var Graficos = function(option){
  var atribs = {
          tipo       : "bar",
          sucursales : "",
          canvas     : "",
          legenda    : ""
      };

      var root = this;

      /*
       * Constructor
       */
      this.construct = function(options){
          $.extend(atribs , options);
      };

      this.estadistica = function(indices){
      var colegios =  new Array();
      var cantidad_apoderado =  new Array();
      var cantidad_profesor =  new Array();
      var color = new Array();
      var borde = new Array();
      var tope = indices.length;
      var i=0;
      $.each( indices, function( key, val )
      {
        colegios[i] = val.tipo;
        if(val.tipo == "apoderado"){
          cantidad_apoderado[i]  = val.cantidad;
          color[i] = 'rgba(255, 99, 132, 0.2)';
          borde[i] = 'rgba(255,99,132,1)';
        }else{
          cantidad_profesor[i] = val.cantidad;
          color[i] = 'rgba(54, 162, 235, 0.2)';
          borde[i] = 'rgba(54, 162, 235, 1)';
        }
        i++;
      });

      var ctx = document.getElementById(option.canvas);
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: colegios,
              datasets: [{
                  label: 'Apoderados',
                  data: cantidad_apoderado,
                  backgroundColor: 'rgba(255, 99, 132, 0.2)',
                  borderColor: 'rgba(255, 162, 235, 100)',
                  borderWidth: 1
              },
              {
                  label: 'Profesores',
                  data: cantidad_profesor,
                  backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  borderColor: 'rgba(2,99,132,0.5)',
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              legend: {
                  position: 'top',
              },
              title: {
                  display: true,
                  text: 'Tipos de usuarios'
              }
          }
      });
    }

    this.estadistica2 = function(indices){
    var colegios =  new Array();
    var cantidad_apoderado =  new Array();
    var cantidad_profesor =  new Array();
    var color = new Array();
    var borde = new Array();
    var tope = indices.length;
    var i=0;
    $.each( indices, function( key, val )
    {
      console.log(val.tipo);
      colegios[i] = val.tipo;
      if(val.tipo == "apoderado"){
        cantidad_apoderado[i]  = val.cantidad;
        //color[i] = 'rgba(255, 99, 132, 0.2)';
        //borde[i] = 'rgba(255,99,132,1)';
      }else{
        cantidad_profesor[i] = val.cantidad;
        //color[i] = 'rgba(54, 162, 235, 0.2)';
        //borde[i] = 'rgba(54, 162, 235, 1)';
      }
      i++;
    });
    console.log(cantidad_profesor);
    var ctx = document.getElementById(option.canvas);
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: colegios,
            datasets: [{
                label: 'Apoderados',
                data: cantidad_apoderado,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255,99,132,1)',
                borderWidth: 1
            },
            {
                label: 'Profesores',
                data: cantidad_profesor,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Compra por tipo de usuario'
            }
        }
    });
  }

  this.estadistica3 = function (indices){
    var cantidad = new Array();
    var meses = new Array();
    var tope = indices.length;
    var i=0;
    $.each( indices, function( key, val )
    {
      if(val.mes == "1"){
        meses[i] = "Enero";
        cantidad[i] = val.compras;
      }
      if(val.mes == "2"){
        meses[i] = "Febrero";
        cantidad[i] = val.compras;
      }
      if(val.mes == "3"){
        meses[i] = "Marzo";
        cantidad[i] = val.compras;
      }
      if(val.mes == "4"){
        meses[i] = "Abril";
        cantidad[i] = val.compras;
      }
      if(val.mes == "5"){
        meses[i] = "Mayo";
        cantidad[i] = val.compras;
      }
      i++;
    });
    //console.log(cantidad_profesor);
    var ctx = document.getElementById(option.canvas);
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Compra por mes',
                data: cantidad,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255,99,132,1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Compra por tipo de usuario'
            }
        }
    });
  }

  this.estadistica4 = function (indices){
    var cantidad = new Array();
    var dias = new Array();
    var tope = indices.length;
    var i=0;
    $.each( indices, function( key, val )
    {
      dias[i] = val.dia;
      cantidad[i] = val.compras;
      i++;
    });
    //console.log(cantidad_profesor);
    var ctx = document.getElementById(option.canvas);
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dias,
            datasets: [{
                label: 'Compra por dias',
                data: cantidad,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255,99,132,1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Compra por tipo de usuario'
            }
        }
    });
  }
}
