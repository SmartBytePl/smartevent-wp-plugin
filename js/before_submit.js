function before_submit(form_id) {
    var form_id = '#'+form_id;
    jQuery(form_id).submit(function () {
        jQuery(form_id+" .promotion_input").prop('disabled', true);
        jQuery(form_id+' input.event_checkbox, '+form_id+' input.bonus_checkbox').each(function () {
            if (this.checked) {
                var quantity = jQuery(form_id+' #quantity' + this.value).val();
                jQuery('<input>', {
                    type: 'hidden',
                    name: 'quantity[]',
                    value: quantity
                }).appendTo(form_id);
            }
        });
        /*jQuery('<input>', {
            type: 'hidden',
            name: 'invite',
            value: JSON.parse(Cookies.get('invite')).first()
        }).appendTo(form_id);*/
        return true;
    });
}
