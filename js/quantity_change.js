jQuery(function(){

    jQuery("#submit_button").attr("disabled", true);
    jQuery(".errors").append("<p>Musisz wybrać przynajmniej jedno szkolenie!</p>");

    jQuery("input[type=number]").change(function(){
        var event_id = this.getAttribute('data-eventid');
        if(this.value > 0)
            jQuery('#checkbox'+event_id).prop( "checked", true ).trigger("change");
        else
            jQuery('#checkbox'+event_id).prop( "checked", false ).trigger("change");
    });

    jQuery("input[type=checkbox]").on("change", function(){
        if (jQuery("#target input:checkbox.event_checkbox:checked").length > 0){
            jQuery("#submit_button").attr("disabled", false);
            jQuery(".errors").empty();
        }
        else{
            jQuery("#submit_button").attr("disabled", true);
            jQuery(".errors").empty();
            jQuery(".errors").append("<p>Musisz wybrać przynajmniej jedno szkolenie!</p>");
        }
        var quantityInput = jQuery('#quantity'+this.value);
        var quantity = quantityInput.val();
        if(this.checked && !(quantity > 0))
            quantityInput.val(1);
        else if(!this.checked)
            quantityInput.val(0);
    });
});
