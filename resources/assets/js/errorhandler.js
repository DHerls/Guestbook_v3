$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {

    if (jqxhr.status == 0){
        $.notifyBar({
            cssClass: 'error',
            html: "Internet Connection Error. Check Network."
        })
    } else if (jqxhr.status != 422){
        $.notifyBar({
            cssClass: 'error',
            html: "Error " + jqxhr.status + ": " + thrownError
        })
    }

});