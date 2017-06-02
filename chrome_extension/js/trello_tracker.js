var TrelloTracker = function()
{
    var public = this;
    var private = {};

    private.init = function()
    {
        $('<button class="openIFrame">Auth in Tracker Time app</button>').insertAfter('.add-comment-section');
    }();

    $('.openIFrame').on('click', function(){
        window.open("https://trello.com/1/connect?key=4750260007210082bd12c32527e76008&name=Tracker_Time&response_type=token&expiration=never&return_url=http://trello.personal.loc/tracker/auth-trello", "authTrelloWindow", "width=500,height=500");

    });
};

$(function(){
    new TrelloTracker();
});
