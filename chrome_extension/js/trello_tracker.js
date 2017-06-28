var TrelloTracker = function()
{
    var public = this;
    public.appRootUrl = 'https://trello.personal.loc';

    var private = {};
    private.me = '';

    public.setMeid = function(id)
    {
        private.me = id;
    };

    public.getMeid = function()
    {
        return private.me;
    };

    public.setContent = function(content)
    {
        if (!$('#ttEstimation').length) {
            var html = '<div id="ttEstimation"></div>';
            $(html).insertAfter('.add-comment-section');
        }
        $('#ttEstimation').html(content);
    };


    return public;
};

var trackerClass = new TrelloTracker();

var getHTML = function ( method, baseUrl, params, callback ) {

    var encodeData = function (data) {
        return Object.keys(data).map(function(key) {
            return [key, data[key]].map(encodeURIComponent).join("=");
        }).join("&");
    };

    // Feature detection
    if ( !window.XMLHttpRequest ) return;

    // Create new request
    var xhr = new XMLHttpRequest();

    // Setup callback
    xhr.onload = function() {
        if ( callback && typeof( callback ) === 'function' ) {
            callback( this.response );
        }
    };

    if (params) {
        baseUrl += '?';
        baseUrl += encodeData(params);
    }

    // Get the HTML
    xhr.open( method, baseUrl );
    xhr.responseType = 'json';
    xhr.send();
};

var refreshContent = function()
{
    var def1 = $.Deferred();

    getHTML('GET', '/1/members/me', null, function (data) {
        var userId = data.id;
        trackerClass.setMeid(userId);
        def1.resolve(userId);
    });

    def1.done(function(userId) {
        var params = {uid: userId};
        getHTML('GET', trackerClass.appRootUrl + '/tracker/show-content/', params, function (data) {
            if (data.status == 'ok') {
                trackerClass.setContent(data.content);
            }
        });
    });
};

$(function(){
    refreshContent();
});
