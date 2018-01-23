class Mapa {
    
    constructor() {
       this.mapa       = null;
       this.geocoder   = new google.maps.Geocoder();
       this.initLatLng = {lat: -33.447898, lng: -70.667972}; 
       this.markers    = [];
    }
    
    initMap(calle, region) {
       this.buscarDireccion(this.mapa, this.geocoder, this.initLatLng, calle, region, this.markers)
    }
    
    buscarDireccion(mapa, geocoder, initLatLng, calle, region, marcadores){
        console.log(region);
        geocoder.geocode( { 'address': calle, 
                            'region' : region,
                                componentRestrictions: {
                                    country : 'CL',
                                    route   : calle,
                            }}, function(results, status) {
            if (status == 'OK') {
                console.log(results[0]);
                var mapOptions = {
                    center: results[0].geometry.location,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                setTimeout(function(){
                    $("#loadmap").addClass("hidden")
                    mapa = new google.maps.Map($("#map").get(0), mapOptions);
                    mapa.fitBounds(results[0].geometry.viewport);
                    var markerOptions = { position: results[0].geometry.location }
                    var marker = new google.maps.Marker(markerOptions);
                    marker.setMap(mapa);
                    
                    // Escucha Evento Click
                    mapa.addListener('click', function(e) {
                        console.log(results[0]);
                        for (var i = 0; i < marcadores.length; i++) {
                            marcadores[i].setMap(null);
                        }
                        var marker = new google.maps.Marker({
                            position: e.latLng,
                            map: mapa
                        });
                        mapa.panTo(e.latLng);
                        marcadores.push(marker);
//                        $("#calle").val(results[0].address_components[0].long_name);
                    });
                    //** Fin evento click **//
                    
                }, 3000);

            } else {
                setTimeout(function(){
                    $("#loadmap").addClass("hidden")
                    mapa = new google.maps.Map(document.getElementById('map'), {
                        zoom: 12,
                        center: initLatLng
                    });
                    
                    // Escucha Evento Click
                    mapa.addListener('click', function(e) {
                        console.log(results[0]);
                        for (var i = 0; i < marcadores.length; i++) {
                            marcadores[i].setMap(null);
                        }
                        var marker = new google.maps.Marker({
                            position: e.latLng,
                            map: mapa
                        });
                        mapa.panTo(e.latLng);
                        marcadores.push(marker);
//                        $("#calle").val(results[0].address_components[0].long_name);
                    });
                    //** Fin evento click **//
                    
              }, 3000);
            }
        });
    }
    
    
    recargarMapa(calle, region){
        this.buscarDireccion(this.mapa, this.geocoder, this.initLatLng, calle, region, this.markers)
    }
    
    setCalle(calle){
        $("#calle").val(calle);
    }
    
    setRegion(region){
        $("#region").val(region);
    }
    
    setComuna(comuna){
        $("#comuna").val(comuna);
    }
    
    setNumero(numero){
        $("#numero").val(numero);
    }
    
    setTipo(tipo){
        $("#tipo").val(tipo);
    }
    
    setAdicional(adicional){
        $("#adicional").val(adicional);
    }
}