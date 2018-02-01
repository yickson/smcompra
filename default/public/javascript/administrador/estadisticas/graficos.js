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
        if(val.tipo == "Apoderado"){
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
            elements: {
               rectangle: {
                 borderSkipped: 'left',
               }
             },
            responsive: true,
            legend: {
                position: 'right',
            },
            title: {
                display: true,
                text: 'Tipo de usuarios'
            },
            scales: {
              xAxes: [{
                  ticks: {
                     suggestedMin: 0,
                     scaleBeginAtZero : true,
                  }
              }]
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
      colegios[i] = val.tipo;
      if(val.tipo == "Apoderado"){
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
          elements: {
             rectangle: {
               borderSkipped: 'left',
             }
           },
          responsive: true,
          legend: {
              position: 'right',
          },
          title: {
              display: true,
              text: 'Compras por tipo de usuario'
          },
          scales: {
            xAxes: [{
                ticks: {
                   suggestedMin: 0,
                   scaleBeginAtZero : true,
                }
            }]
          }
        }
    });
  }
}
