function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

jQuery(function(){
    var invite_select = jQuery("#invite_select");
    invite_select.parent().hide();
    var invite = GetURLParameter('invite');
    var array_str = Cookies.get('invite');
    var array = [];
    if(array_str){
        array = JSON.parse(array_str);
    }
    if(invite){
        if(array.lastIndexOf(invite) == -1)
            array.push(invite);
        Cookies.set('invite', JSON.stringify(array));
    }
    if(array.length > 0){
        invite_select.parent().show();
    }
    array.forEach(function(value){
        invite_select.append(jQuery('<option>', {
            value: value,
            text : value
        }));
    });
});
