var CustomFunc = function()
{
    this.sendAjaxRequest = function(httpMethod, callback, url, data) {
        data['_csrf'] = $('meta[name=csrf-token]').attr('content');
        $.ajax(url, {
            type: httpMethod,
            success: callback,
            data: data
        }).fail(function(jqXHR, textStatus, errorThrown) {
            for(var i in jqXHR.responseJSON){
                if (jqXHR.responseJSON[i].message) {
                    UIjs.addAlert(jqXHR.responseJSON[i].message, true);
                }
            }
        });
    }
};
