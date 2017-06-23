<script>
    window.onload = function()
    {
        var hashes = window.location.hash;
        var token_hash = hashes.replace('#token=','')
        $.post('/tracker/auth-trello', {token: token_hash},  function(){
            window.close();
        });
    }
</script>