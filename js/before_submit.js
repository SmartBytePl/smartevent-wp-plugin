function before_submit(form_id) {
    var form_id = '#'+form_id;
    jQuery(form_id).submit(function () {
        jQuery('input[type=checkbox]').each(function () {
            if (this.checked) {
                var quantity = jQuery(form_id+' #quantity' + this.value).val();
                jQuery('<input>', {
                    type: 'hidden',
                    name: 'quantity[]',
                    value: quantity
                }).appendTo(form_id);
            }
        });
        jQuery('<input>', {
            type: 'hidden',
            name: 'invite',
            value: Cookies.get('invite')
        }).appendTo(form_id);
        return true;
    });
}
