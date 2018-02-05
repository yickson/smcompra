var Graficos = function(option){

  var $this = {
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
        $.extend($this , options);
    };

    this.estadistica = function(indices){
      var sucursales =  new Array();
      var cantidad =  new Array();
      var tope = indices.length;
      var i=0;
      $.each( indices, function( key, val )
      {
        sucursales[i] = val.nombre;
        cantidad[i]   = val.cantidad
        i++;
      });
      
      var ctx = document.getElementById(option.canvas);
      var myChart = new Chart(ctx, {
          type: option.tipo,
          data: {
              labels: sucursales,
              datasets: [{
                  label: option.legenda,
                  data: cantidad,
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                      'rgba(255,99,132,1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              }
          }
      });
    }


  this.estadistica2 = function(indices){
    var sucursales =  new Array();
    var cantidad_texto =  new Array();
    var cantidad_ingles =  new Array();
    var tipo = new Array();
    var bordes = new Array();
    var tope = indices.length;
    var i=0;
    var color = "";
    var color_b = "";
    $.each( indices, function( key, val )
    {
      sucursales[i] = val.nombre;
      if(val.descripcion == "Textos"){
        cantidad_texto[i]  = val.cantidad;
      }else{
        cantidad_ingles[i] = val.cantidad;
      }
      tipo[i] = val.color;
      bordes[i]  = val.color_b;
      i++;
    });
    var ctx = document.getElementById(option.canvas);
    var myChart = new Chart(ctx, {
        type: option.tipo,
        data: {
            labels: sucursales,
            datasets: [{
                label: 'Textos',
                data: cantidad_texto,
                backgroundColor: tipo[0],
                borderColor: bordes[0],
                borderWidth: 1
            },{
                label: 'Inglés',
                data: cantidad_ingles,
                backgroundColor: tipo[1],
                borderColor: bordes[1],
                borderWidth: 1
            }]
        },
        options: {
          legend: {
            display: true,
                        labels: {
                            fontColor: 'rgb(255, 99, 132)'
                        }
          },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
  }

  this.estadistica3 = function(indices){
    var colegios =  new Array();
    var cantidad_apoderado =  new Array();
    var cantidad_profesor =  new Array();
    var color = new Array();
    var borde = new Array();
    var tope = indices.length;
    var i=0;
    $.each( indices, function( key, val )
    {
      colegios[i] = val.colegio;
      if(val.nombre == "Apoderado"){
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
                backgroundColor: color[0],
                borderColor: borde[0],
                borderWidth: 1
            },
            {
                label: 'Profesores',
                data: cantidad_profesor,
                backgroundColor: color[1],
                borderColor: borde[1],
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
              text: 'Chart.js Horizontal Bar Chart'
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

  this.estadistica4 = function(indices){
    var colegios =  new Array();
    var cantidad_apoderado =  new Array();
    var cantidad_profesor =  new Array();
    var disponibilidad_result = new Array();
    var color = new Array();
    var borde = new Array();
    var disponibilidad = "";
    var tope = indices.length;
    var i=0;
    $.each( indices, function( key, val )
    {

        if(val.usados != 'full'){
            disponibilidad = (val.usados/(val.apoderados * (val.cuponera*2))*100);
            disponibilidad_result[i] =  100 - disponibilidad;
        }else{
            disponibilidad_result[i] = 100;
        }
      colegios[i] = val.nombre;
      i++;
    });

    var ctx = document.getElementById(option.canvas);
    var myChart = new Chart(ctx, {
        type: option.tipo,
        data: {
            labels: colegios,
            datasets: [{
                label: 'Disponibilidad en %',
                data: disponibilidad_result,
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
              text: 'Chart.js Horizontal Bar Chart'
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
