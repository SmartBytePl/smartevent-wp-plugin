function calculate_cost(form_id) {
    form_id = '#'+form_id;
    jQuery("input[type=checkbox], input[type=number], #coupon").on("change", function () {
      var url = "";
      jQuery(form_id+' input[type=checkbox]').each(function () {
         if (this.checked) {
             var quantity = jQuery(form_id+' #quantity'+this.value).val();
             if(url)
                url += '&';
             else
                url += '?';
             url = url + 'id[]='+this.value+'&quantity[]='+quantity;
         }
      });
      var coupon = jQuery(form_id+' #coupon').val();
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
               jQuery(form_id+' #invoice_cost').html(result);
           },
           error: function (request, error) {
               console.log(" Can't do because: " + error);
           }
       });
    });
}
