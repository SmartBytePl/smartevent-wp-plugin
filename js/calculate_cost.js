jQuery(function () {
    jQuery("input[type=checkbox], input[type=number], #coupon").on("change", function () {
      var url = "";
      jQuery('input[type=checkbox]').each(function () {
         if (this.checked) {
             var quantity = jQuery('#quantity'+this.value).val();
             if(url)
                url += '&';
             else
                url += '?';
             url = url + 'id[]='+this.value+'&quantity[]='+quantity;
         }
      });
      var coupon = jQuery('#coupon').val();
        if(coupon)
        {
            if(url)
                url += '&';
            else
                url += '?';
            url = url + 'coupon='+coupon;
        }
       jQuery.ajax({
           url: window.backend_host + "/mycart/calculate" + url,
           crossDomain: true,
           dataType: 'jsonp',
           success: function( result ) {
               result /= 100;
               jQuery('#invoice_cost').html(result);
           },
           error: function (request, error) {
               console.log(" Can't do because: " + error);
           }
       });
    });
});
