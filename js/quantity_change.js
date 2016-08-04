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

    jQuery(form_id+" input[type=checkbox]").on("change", function(){
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
        if(this.checked && !(quantity > 0))
            quantityInput.val(1);
        else if(!this.checked)
            quantityInput.val(0);
    });
}
