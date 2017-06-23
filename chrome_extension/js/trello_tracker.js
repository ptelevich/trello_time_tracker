var TrelloTracker = function()
{
    const APP_ROOTS_URL = 'https://trello.personal.loc';
    var public = this;
    var private = {
        appConf: {
            key: '4750260007210082bd12c32527e76008',
            name: 'Tracker_Time_v2',
            response_type: 'token',
            expiration: 'never',
            return_url: APP_ROOTS_URL + '/tracker/auth-trello',
            windowSize: 'width=500,height=500'
        }
    };

    public.appRootUrl = function()
    {
        return APP_ROOTS_URL;
    };

    public.inStart = function(content)
    {
        if (!$('#ttEstimation').length) {
            var html = '<div id="ttEstimation"></div>';
            $(html).insertAfter('.add-comment-section');
        }
        $('#ttEstimation').html(content);

        $('.ttOpenIFrame').on('click', function(){
            var params = '';
            params += 'key='+private.appConf.key;
            params += '&name='+private.appConf.name;
            params += '&scope=read,write,account';
            params += '&response_type='+private.appConf.response_type;
            params += '&expiration='+private.appConf.expiration;
            params += '&return_url='+private.appConf.return_url;
            window.open("https://trello.com/1/connect?"+params, "authTrelloWindow", private.appConf.windowSize);
        });
    };


    return public;
};

var getHTML = function ( url, callback ) {

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

    // Get the HTML
    xhr.open( 'GET', url );
    xhr.responseType = 'json';
    xhr.send();
};

var sendEstimation = function(estimation) {
    var trackerClass = new TrelloTracker();
        var findCardId = window.location.href.match(/(trello\.com\/c\/)(.+?)(\/)/);
        if (findCardId[2]) {
            console.log(findCardId[2]);
            getHTML('https://trello.com/1/cards/'+findCardId[2], function (data) {
                function encodeData(data) {
                    return Object.keys(data).map(function(key) {
                        return [key, data[key]].map(encodeURIComponent).join("=");
                    }).join("&");
                }
                var data = {
                    b: data.idBoard,
                    l: data.idList,
                    c: data.id,
                    t: estimation,
                };
                var querystring = encodeData(data);
                console.log(querystring);
                getHTML(trackerClass.appRootUrl() + '/tracker/save-time-trello/?'+encodeData(data), function (data) {
                });
            });
        }
};

$(function(){
    var trackerClass = new TrelloTracker();
    var def1 = $.Deferred();

    getHTML('https://trello.com/1/members/me', function (data) {
        var userId = data.id;
        def1.resolve(userId);
    });

    def1.done(function(userId) {
        getHTML(trackerClass.appRootUrl() + '/tracker/instart/'+userId, function (data) {
            if (data.status == 'ok') {
                trackerClass.inStart(data.content);
            } else {

            }
        });
    });
});
