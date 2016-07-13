jQuery('#coupon').on("change", function(){
    jQuery.ajax({
        url: window.backend_host + "/mycart/check_coupon/" + this.value,
        crossDomain: true,
        dataType: 'jsonp',
        success: function( result ) {
            jQuery('#coupon_result').html(result);
        }
    });
});
