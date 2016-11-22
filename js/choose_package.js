function choose_package(form_id) {
    form_id = '#'+form_id;
    jQuery(form_id+" .promotion_input").on('change', function(event){
        event.preventDefault();
       //console.log(jQuery(this).data('variants'));
        var variants = jQuery(this).data('variants');
        for(var i in variants)
        {
            var input_checkbox = jQuery(form_id+" input[type='checkbox'][data-variant='"+variants[i]+"']");
            var input = jQuery(form_id+" input[type='number'][data-variant='"+variants[i]+"']");

            if(this.value > 0)
                input_checkbox.prop('checked', true).trigger("change");
            else
                input_checkbox.prop('checked', false).trigger("change");
            input.val(this.value).change();
        }
    });
}
