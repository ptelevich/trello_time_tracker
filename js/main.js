var koContent = function()
{
    var public = this;
    var private = {};

    public.boards = ko.observableArray([]);
    public.lists = ko.observableArray([]);
    public.cards = ko.observableArray([]);

    private.init = function()
    {
        var authenticationSuccess = function() { console.log('Successful authentication'); getBoard(); };
        var authenticationFailure = function() { console.log('Failed authentication'); };
        Trello.authorize({
            type: 'popup',
            name: 'Getting Started Application',
            scope: {
                read: 'true',
                write: 'true' },
            expiration: 'never',
            key: '4750260007210082bd12c32527e76008',
            return_url: 'http://trello.personal.loc/connect.html',
            success: authenticationSuccess,
            error: authenticationFailure
        });

        function getBoard() {
            var success = function(response) {
                console.log(response);
                public.boards(response);
            };

            var error = function(errorMsg) {
                console.log(errorMsg);
            };

            Trello.get('/member/me/boards', success, error);
        }
    }();

    public.showLists = function(item)
    {
        var success = function(response) {
            console.log(response);
            public.lists(response);
        };

        var error = function(errorMsg) {
            console.log(errorMsg);
        };

        Trello.get('/boards/'+item.id+'/lists', success, error);
    };

    public.showCards = function(item)
    {
        console.log(item);
        var success = function(response) {
            console.log(response);
            public.cards(response);
        };

        var error = function(errorMsg) {
            console.log(errorMsg);
        };

        Trello.get('/lists/'+item.id+'/cards', success, error);
    };

};

var generalModel = {
    mainContent: new koContent
};
ko.applyBindings(generalModel);
