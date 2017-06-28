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
    $('#ttEstimation .ttSaveEstimationButton').on('click', function() {
        var isNumeric = function(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        };

        var resetFields = function()
        {
            $('.ttEstimationHours').val('');
            $('.ttEstimationMinutes').val('');
        };

        var hours = $('.ttEstimationHours').val();
        var minutes = $('.ttEstimationMinutes').val();
        var errorText = '';
        if (!isNumeric(hours)) {
            errorText = 'Hours should be a number';
        } else if (!isNumeric(minutes)) {
            errorText = 'Minutes should be a number';
        }
        if (errorText) {
            $('.ttEstimationError').text(errorText);
        } else {
            var estimation = (parseInt(hours)*60) + parseInt(minutes);
            var findCardId = window.location.href.match(/(trello\.com\/c\/)(.+?)(\/)/);
            if (findCardId[2]) {
                getHTML('GET', '/1/cards/'+findCardId[2], null, function (data) {
                    var params = {
                        b: data.idBoard,
                        l: data.idList,
                        c: data.id,
                        t: estimation,
                        uid: trackerClass.getMeid()
                    };
                    getHTML('GET', trackerClass.appRootUrl + '/tracker/save-time-trello/', params, function(res){
                        if (res.status == 'ok') {
                            resetFields();
                        }
                    });
                });
            }
        }
    });
</script>
<label>Time Tracker Extension:</label>
<div class="ttEstimationError"></div>
<input type="input" class="ttEstimationHours" maxlength="2" size="2" placeholder="H" />
<input type="input" class="ttEstimationMinutes" maxlength="2" size="2" placeholder="M" />
<div class="ttSaveEstimationButton">Save</div>
