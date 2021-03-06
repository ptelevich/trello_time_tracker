var koContent = function()
{
    var public = this;
    var private = {};

    public.boards = ko.observableArray([]);
    public.lists = ko.observableArray([]);
    public.cards = ko.observableArray([]);
    public.changedTime = ko.observable('');
    public.boardId = ko.observable('');
    public.listId = ko.observable('');
    public.cardId = ko.observable(-1);

    CustomFunc.apply(public);

    private.sendAjaxRequest = public.sendAjaxRequest;

    private.init = function()
    {
        var authenticationSuccess = function() { console.log('Successful authentication'); getBoard(); };
        var authenticationFailure = function() { console.log('Failed authentication'); };
        Trello.authorize({
            type: 'popup',
            name: 'Getting Started Application',
            scope: {
                read: 'true',
                write: 'true',
                comments: 'true'
            },
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

            Trello.get('/cards/5883593e269689bc677d5902/actions', {
                filter : 'commentCard',

            });
            //Trello.post('/cards/5883593e269689bc677d5902/actions/comments', {text: '@trello_tracker comment1'});
        }
    };

    private.resetBlocks = function()
    {
        public.lists.removeAll();
        public.cards.removeAll();
        public.boardId('');
        public.listId('');
        public.cardId('');
        public.changedTime('');
    };

    public.showLists = function(item)
    {
        private.resetBlocks();
        public.boardId(item.id);
        var success = function(response) {
            console.log(response);
            public.lists(response);
        };

        var error = function(errorMsg) {
            console.log(errorMsg);
        };

        Trello.get('/boards/'+public.boardId()+'/lists', success, error);
    };

    public.showCards = function(item)
    {
        console.log(item);
        public.listId(item.id);
        public.cardId(-1);
        var success = function(response) {
            console.log(response);
            public.cards(response);
        };

        var error = function(errorMsg) {
            console.log(errorMsg);
        };

        Trello.get('/lists/'+public.listId()+'/cards', success, error);
    };

    public.openTime = function(item)
    {
        public.cardId(item.id);
        public.changedTime('');
        private.sendAjaxRequest('POST', function(data){
            public.changedTime(data);
        }, '/tracker/get-time-track', {id: public.cardId()});
    };

    public.saveTime = function(id)
    {
        var putData = {boardId: public.boardId(), listId: public.listId(), cardId: id, time: public.changedTime()};
        private.sendAjaxRequest('PUT', function(data){}, '/tracker/save-time', putData);
    };

    private.getHash = function()
    {
        var hashes = window.location.hash;
        private.sendAjaxRequest('PUT', function(data){}, '/tracker/save-token', {

        });
        $.post(hashes.replace('#token=',''));
    }
};

var generalModel = {
    mainContent: new koContent
};
ko.applyBindings(generalModel);
