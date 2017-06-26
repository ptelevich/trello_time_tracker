<script>
    let APP_ROOTS_URL = 'https://trello.personal.loc';
    var private = {
        appConf: {
            key: '4750260007210082bd12c32527e76008',
            name: 'Tracker_Time_v2',
            scope: 'read,write,account',
            response_type: 'token',
            expiration: 'never',
            return_url: APP_ROOTS_URL + '/tracker/auth-trello',
            windowSize: 'width=500,height=500'
        }
    };
    $(document).on('click', '.ttOpenIFrame', function(){
        var params = '';
        params += 'key='+private.appConf.key;
        params += '&name='+private.appConf.name;
        params += '&scope='+private.appConf.scope;
        params += '&response_type='+private.appConf.response_type;
        params += '&expiration='+private.appConf.expiration;
        params += '&return_url='+private.appConf.return_url;
        window.open("/1/connect?"+params, "authTrelloWindow", private.appConf.windowSize);
    });
</script>
<button class="ttOpenIFrame">Auth in Tracker Time app</button>