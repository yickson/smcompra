class Mapa {
    
    $this = {
        "map"      : "",
        "geocoder" : "",
    }
    
    constructor() {
    }
    
    initMap(result) {
        console.log(result)
        var direccion = result ;
        var uluru = {lat: -25.363, lng: 131.044};

        $this.map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
        
        this.buscarDireccion(map, direccion);
    }
    
    buscarDireccion(map, direccion){
      geocoder.geocode( { 'address': direccion}, function(results, status) {
        if (status == 'OK') {
          map.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location
          });
        } else {
          alert('Geocode was not successful for the following reason: ' + status);
        }
      });
    }
}