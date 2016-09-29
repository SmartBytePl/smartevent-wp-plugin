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
        var trainees_row = jQuery(".trainees.event-"+this.value);
        if(this.checked && !(quantity > 0)){
            quantityInput.val(1);
            trainees_row.show();
            trainees_row.after("<tr class='trainee event-"+this.value+"'>"+
                "<td><input type='text' name='name[]' placeholder='Imię'></td>"+
                "<td><input type='text' name='surname[]' placeholder='Nazwisko'></td>"+
                "<td><input type='text' name='phone[]' placeholder='Telefon'></td>"+
                "<td><input type='text' name='email[]' placeholder='Email'></td></tr>"
            );
        }
        else if(!this.checked){
            quantityInput.val(0);
            jQuery(".trainee.event-"+this.value).remove();
            trainees_row.hide();
        }
    });

    jQuery(form_id +" input[type=number]").on("change", function(){
        var event_id =this.getAttribute('data-eventid');
        var count = jQuery(".trainee.event-"+event_id).length;

        if(count < this.value){
            for(var i = 0; i < this.value-count; i++)
            {
                if(jQuery(".trainee.event-"+event_id).length > 0){
                    jQuery(".trainee.event-"+event_id).last().after("<tr class='trainee event-"+event_id+"'>"+
                        "<td><input type='text' name='name[]' placeholder='Imię'></td>"+
                        "<td><input type='text' name='surname[]' placeholder='Nazwisko'></td>"+
                        "<td><input type='text' name='phone[]' placeholder='Telefon'></td>"+
                        "<td><input type='text' name='email[]' placeholder='Email'></td></tr>"
                    );
                }
                else
                {
                    var trainees_row = jQuery(".trainees.event-"+event_id);
                    trainees_row.show();
                    trainees_row.after("<tr class='trainee event-"+event_id+"'>"+
                        "<td><input type='text' name='name[]' placeholder='Imię'></td>"+
                        "<td><input type='text' name='surname[]' placeholder='Nazwisko'></td>"+
                        "<td><input type='text' name='phone[]' placeholder='Telefon'></td>"+
                        "<td><input type='text' name='email[]' placeholder='Email'></td></tr>"
                    );
                }
            }
        }
        else
        {
            for(i = 0; i < count-this.value; i++)
                jQuery(".trainee.event-"+event_id).last().remove();
            if(this.value == 0)
                jQuery(".trainees.event-"+event_id).hide();
        }
    });
}
