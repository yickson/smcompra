class Mapa {
    
    static $this(){
        
    }
    constructor() {
       var  $this = {"asd": 1}
    }
    
    initMap(result) {
        console.log(result)
        var direccion = result ;
        var uluru = {lat: -25.363, lng: 131.044};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
        console.log($this.asd);
        
    }
    
    buscarDireccion(map, direccion){
        //console.log(this.GOOGLE.map);
//      geocoder.geocode( { 'address': direccion}, function(results, status) {
//        if (status == 'OK') {
//          this.GOOGLE.map.setCenter(results[0].geometry.location);
//          var marker = new google.maps.Marker({
//              map: map,
//              position: results[0].geometry.location
//          });
//        } else {
//          alert('Geocode was not successful for the following reason: ' + status);
//        }
//      });
    }
}