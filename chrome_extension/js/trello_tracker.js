var TrelloTracker = function()
{
    var public = this;
    var private = {
        appConf: {
            key: '4750260007210082bd12c32527e76008',
            name: 'Tracker_Time_v2',
            response_type: 'token',
            expiration: 'never',
            return_url: 'http://trello.personal.loc/tracker/auth-trello',
            windowSize: 'width=500,height=500',
        }

    };

    private.init = function()
    {
        $('<button class="openIFrame">Auth in Tracker Time app</button>').insertAfter('.add-comment-section');
    }();

    $('.openIFrame').on('click', function(){
        var params = '';
        params += 'key='+private.appConf.key;
        params += '&name='+private.appConf.name;
        params += '&response_type='+private.appConf.response_type;
        params += '&expiration='+private.appConf.expiration;
        params += '&return_url='+private.appConf.return_url;
        window.open("https://trello.com/1/connect?"+params, "authTrelloWindow", private.appConf.windowSize);
    });

    return public;
};

$(function(){
    new TrelloTracker();
});
