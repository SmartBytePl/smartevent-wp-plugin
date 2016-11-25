function choose_package(form_id) {
    form_id = '#'+form_id;

    jQuery(form_id+" .promotion_checkbox").on('change', function(){
        var promotion_id = jQuery(this).data('promotion');
        console.log(promotion_id);
        console.log(this.checked);
        var promotion_input = jQuery(form_id+" #promotion_input_"+promotion_id);
        if(this.checked){
            console.log(promotion_input);
            promotion_input.val(1).change();
        }
        else{
            promotion_input.val(0).change();
        }

    });

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
