function choose_package(form_id) {
    form_id = '#'+form_id;
    jQuery(form_id+" .promotion_button").on('click', function(event){
        event.preventDefault();
       //console.log(jQuery(this).data('variants'));
        var variants = jQuery(this).data('variants');
        for(var i in variants)
        {
            var input = jQuery(form_id+" input[data-variant='"+variants[i]+"']");
            console.log(jQuery(form_id+" input[data-variant='"+variants[i]+"']"));
            if(!input.is(':checked'))
                input.prop('checked', true).trigger("change");
        }
    });
}
