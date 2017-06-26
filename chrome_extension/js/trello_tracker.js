var TrelloTracker = function()
{
    var public = this;
    public.appRootUrl = 'https://trello.personal.loc';

    var private = {};

    public.inStart = function(content)
    {
        if (!$('#ttEstimation').length) {
            var html = '<div id="ttEstimation"></div>';
            $(html).insertAfter('.add-comment-section');
        }
        $('#ttEstimation').html(content);
    };


    return public;
};

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

var sendEstimation = function(estimation) {
    var trackerClass = new TrelloTracker();
    var findCardId = window.location.href.match(/(trello\.com\/c\/)(.+?)(\/)/);
    if (findCardId[2]) {
        getHTML('GET', '/1/cards/'+findCardId[2], null, function (data) {
            var params = {
                b: data.idBoard,
                l: data.idList,
                c: data.id,
                t: estimation
            };
            getHTML('GET', trackerClass.appRootUrl + '/tracker/save-time-trello/', params);
        });
    }
};

$(function(){
    var trackerClass = new TrelloTracker();
    var def1 = $.Deferred();

    getHTML('GET', '/1/members/me', null, function (data) {
        var userId = data.id;
        def1.resolve(userId);
    });

    def1.done(function(userId) {
        var params = {uid: userId};
        getHTML('GET', trackerClass.appRootUrl + '/tracker/instart/', params, function (data) {
            if (data.status == 'ok') {
                trackerClass.inStart(data.content);
            }
        });
    });
});
