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
