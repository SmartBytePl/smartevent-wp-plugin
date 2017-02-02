function calculate_cost(form_id) {
    form_id = '#'+form_id;
    jQuery(form_id+" input[type=checkbox],"+form_id+" input[type=number],"+form_id+" #coupon").on("change", function () {
      var url = "";
      var at_least_one_checked = false;
      jQuery(form_id+' .event_checkbox, '+form_id+' .bonus_checkbox').each(function () {
         if (this.checked) {
             at_least_one_checked = true;
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
        var check_id = Cookies.get('check_id');
        if(check_id == undefined)
            check_id = 0;
        check_id++;
        Cookies.set('check_id', check_id);
        console.log(check_id);
        if(url)
            url += '&';
        else
            url += '?';
        url = url + 'check_id='+check_id;
        if(at_least_one_checked == false){
            console.log("pusto :(");
            jQuery(form_id+' #invoice_cost').html(0);
            jQuery(form_id+' #promotion-values').html("");
            return;
        }
        console.log('pełno :D');
       jQuery.ajax({
           url: window.backend_host + "/mycart/calculate" + url,
           crossDomain: true,
           dataType: 'jsonp',
           success: function( result ) {
               var total = result.total;
               var promotion_values = result.promotions;
               var check_id_from_response = result.check_id;
               var check_id_from_cookie = Cookies.get('check_id');
               console.log(check_id_from_response+' comp '+check_id_from_cookie);
               if(check_id_from_response != check_id_from_cookie){
                   return;
               }
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
