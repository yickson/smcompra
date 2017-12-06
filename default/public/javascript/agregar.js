$(document).ready(function(){
    var maxField = 3; //Input fields increment limitation
    var x = 0; //Initial field counter is 1
    var i = 0;
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
     //New input field html
    $(addButton).click(function(){ //Once add button is clicked
        if(x < maxField){ //Check maximum number of input fields
          var fieldHTML = '<div class="'+i+'"><div class="row"><div class="col-xs-12 col-sm-6"><div class="form-group"><label class="col-form-label" for="inputDefault">RUT</label><input name="rut'+i+'" class="form-control" type="text" placeholder="11.111.111-1" /></div></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label class="col-form-label" for="inputDefault">NOMBRE COMPLETO</label><input name="nombre'+i+'" class="form-control" type="text" placeholder="Papelucho Paletin" /></div></div></div><div class="row"><div class="col-xs-12 col-sm-6"><div class="form-group"><label class="col-form-label">CORREO</label><input name="correo'+i+'" class="form-control" type="text" placeholder="" /></div></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label class="col-form-label">CURSO</label><select name="curso'+i+'" class="form-control"><option value="1">Primero Basico</option><option value="2">Segundo Basico</option><option value="3">Tercero Basico</option><option value="4">Cuarto Basico</option><option value="5">Quinto Basico</option><option value="6">Sexto Basico</option><option value="7">Septimo Basico</option><option value="8">Octavo Basico</option><option value="9">Primero Medio</option><option value="10">Segundo Medio</option><option value="11">Tercero Medio</option><option value="12">Cuarto Medio</option></select></div></div></div><a href="javascript:void(0)" class="remove_button btn btn-danger btn-circle" title="'+i+'"><i class="fa fa-minus" aria-hidden="true"></a></div>';
            $(wrapper).append(fieldHTML); // Add field html
            x++; //Increment field counter
            i++;
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
        e.preventDefault();
        var capa = $('.remove_button').attr('title');
        $('.'+capa).remove(); //Remove field html
        x--; //Decrement field counter
        i--;
    });
});
