function calculate_cost(form_id) {
    form_id = '#'+form_id;
    jQuery("input[type=checkbox], input[type=number], #coupon").on("change", function () {
      var url = "";
      jQuery(form_id+' .event_checkbox, '+form_id+' .bonus_checkbox').each(function () {
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
               var total = result.total;
               var promotion_values = result.promotions;
               total /= 100;
               promotion_values /= -100;
               jQuery(form_id+' #invoice_cost').html(total);
               if(promotion_values > 0)
                    jQuery(form_id+' #promotion-values').html("("+promotion_values+" zł rabatu)");
               else
                    jQuery(form_id+' #promotion-values').html("");

           },
           error: function (request, error) {
               console.log(" Can't do because: " + error);
           }
       });
    });
}

function calculate_packet_cost(packet_cost_td, ids){
    var url =  window.backend_host + "/mycart/calculate?";
    for(var i = 0; i < ids.length; i++){
        if(i > 0){
            url += "&";
        }
        url = url + 'id[]='+ids[i]+'&quantity[]=1';
    }
    jQuery.ajax({
        url: url,
        crossDomain: true,
        dataType: 'jsonp',
        success: function( result ) {
            result = result.total;
            result /= 100;
            result /= 1.23;
            jQuery(packet_cost_td).html(result.toFixed(0));
        },
        error: function (request, error) {
            console.log(" Can't do because: " + error);
        }
    });
}
