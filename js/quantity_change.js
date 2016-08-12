function quantity_change(form_id){

    form_id = '#'+form_id;
    jQuery(form_id+" #submit_button").attr("disabled", true);
    jQuery(form_id+" .errors").append("<p>Musisz wybrać przynajmniej jedno szkolenie!</p>");

    jQuery(form_id+" input[type=number]").change(function(){
        var event_id = this.getAttribute('data-eventid');
        if(this.value > 0)
            jQuery(form_id+' #checkbox'+event_id).prop( "checked", true ).trigger("change");
        else
            jQuery(form_id+' #checkbox'+event_id).prop( "checked", false ).trigger("change");
    });

    jQuery(form_id+" input[type=checkbox].event_checkbox").on("change", function(){
        if (jQuery(form_id+" input:checkbox.event_checkbox:checked").length > 0){
            jQuery(form_id+" #submit_button").attr("disabled", false);
            jQuery(form_id+" .errors").empty();
        }
        else{
            jQuery(form_id+" #submit_button").attr("disabled", true);
            jQuery(form_id+" .errors").empty();
            jQuery(form_id+" .errors").append("<p>Musisz wybrać przynajmniej jedno szkolenie!</p>");
        }
        var quantityInput = jQuery(form_id+' #quantity'+this.value);
        var quantity = quantityInput.val();
        if(this.checked && !(quantity > 0)){
            quantityInput.val(1);
            jQuery(this).parent().parent().after("<tr class='trainee'><td></td>"+
                "<td><input type='text' name='name[]' placeholder='Imię'></td>"+
                "<td><input type='text' name='surname[]' placeholder='Nazwisko'></td>"+
                "<td><input type='text' name='phone[]' placeholder='Telefon'></td>"+
                "<td><input type='text' name='email[]' placeholder='Email'></td><td></td><td></td></tr>");
        }
        else if(!this.checked){
            quantityInput.val(0);
            var current = jQuery(this).parent().parent();
            while(current.next().attr('class') == 'trainee'){
                current.next().remove();
            }
        }
    });

    jQuery(form_id +" input[type=number]").on("change", function(){

        var count = 0;
        var current = jQuery(this).parent().parent();
        while(current.next().attr('class') == 'trainee'){
            count += 1;
            current = current.next();
        }
        console.log(count);

        if(count < this.value){
            for(var i = 0; i < this.value-count; i++)
            {
                current.after("<tr class='trainee'><td></td>"+
                    "<td><input type='text' name='name[]' placeholder='Imię'></td>"+
                    "<td><input type='text' name='surname[]' placeholder='Nazwisko'></td>"+
                    "<td><input type='text' name='phone[]' placeholder='Telefon'></td>"+
                    "<td><input type='text' name='email[]' placeholder='Email'></td><td></td><td></td></tr>");
            }
        }
        else
        {
            for(i = 0; i < count-this.value; i++)
            {
                var toDelete = current;
                current = current.prev();
                toDelete.remove();
            }
        }
    });
}
