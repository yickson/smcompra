$(document).ready(function(){
    var maxField = 3; //Input fields increment limitation
    var x = 0; //Initial field counter is 1
    var i = 0;
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
     //New input field html
    $(addButton).click(function(){ //Once add button is clicked
        if(x < maxField){ //Check maximum number of input fields
          var fieldHTML = '<div class="'+ i +'"><div class="row"><div class="col-xs-12 col-sm-6"><div class="form-group"><label class="col-form-label" for="inputDefault">RUT</label><input type="text" class="form-control" placeholder="11.111.111-1" id="inputDefault"></div></div></div><a href="javascript:void(0);" class="remove_button btn btn-info btn-circle" title="'+i+'"><i class="fa fa-minus" aria-hidden="true"></i></a></div>';
            $(wrapper).append(fieldHTML); // Add field html
            console.log($('.remove_button').attr('title'));
            x++; //Increment field counter
            i++;
            console.log('Valor de I: '+i+ ' Valor de x: '+x);
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
        e.preventDefault();
        var capa = $('.remove_button').attr('title');
        $('.'+capa).remove(); //Remove field html
        x--; //Decrement field counter
        i--;
        console.log('Valor final i: '+i +' Valor de x'+x);
    });
});
