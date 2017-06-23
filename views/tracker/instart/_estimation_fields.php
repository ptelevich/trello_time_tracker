<style type="text/css">
    #ttEstimation input {
        display: inline-block;
        margin-right: 5px;
    }
    .ttSaveEstimationButton {
        background-color: lightgrey;
        padding: 10px;
        display: inline-block;
        cursor: pointer;
    }
    .ttSaveEstimationButton:hover {
        background-color: #298FCA;
        color: white;
    }
</style>
<script type="application/javascript">
    $('.ttSaveEstimationButton').on('click', function() {
        sendEstimation($('.ttEstimation').val());
    });
</script>
<input type="input" class="ttEstimation" />
<div class="ttSaveEstimationButton">Save</div>
