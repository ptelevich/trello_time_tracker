<script src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
    window.onload = function()
    {
        var hashes = window.location.hash;
        var token_hash = hashes.replace('#token=','');
        $.post('/tracker/auth-trello', {token: token_hash},  function(){
            window.close();
        });
    }
</script>
Thank you for authentication in the application.