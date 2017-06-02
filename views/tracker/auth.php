<script>
    var hashes = window.location.hash;
    $.post(hashes.replace('#token=',''));
    //window.close();
</script>