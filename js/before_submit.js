jQuery( "#target" ).submit(function() {
    jQuery('input[type=checkbox]').each(function () {
        if(this.checked){
            var quantity = jQuery('#quantity'+this.value).val();
            jQuery('<input>', {
                type: 'hidden',
                name: 'quantity[]',
                value: quantity
            }).appendTo('#target');
            for(var i = 0; i < quantity; i++)
            {
                jQuery('<input>', {
                    type: 'hidden',
                    name: 'name[]'
                }).appendTo('#target');
                jQuery('<input>', {
                    type: 'hidden',
                    name: 'surname[]'
                }).appendTo('#target');
                jQuery('<input>', {
                    type: 'hidden',
                    name: 'phone[]'
                }).appendTo('#target');
                jQuery('<input>', {
                    type: 'hidden',
                    name: 'email[]'
                }).appendTo('#target');
            }
        }
    });
    jQuery('<input>', {
        type: 'hidden',
        name: 'invite',
        value: Cookies.get('invite')
    }).appendTo('#target');
    return true;
});
