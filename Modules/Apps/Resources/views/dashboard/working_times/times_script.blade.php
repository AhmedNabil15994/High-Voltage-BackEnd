<script>
    $(function() {
        var timePicker = $(".timepicker");
        timePicker.timepicker({
            timeFormat: 'HH',
        });
    });

    var rowCountsArray = [0];

    function hideCustomTime(id, requestType) {
        $("#collapse-" + requestType + '-' + id).hide();
    }

    function showCustomTime(id, requestType) {
        $("#collapse-" + requestType + '-' + id).show();
    }

    function addMoreDayTimes(e, dayCode, requestType) {

        if (e.preventDefault) {
            e.preventDefault();
        } else {
            e.returnValue = false;
        }

        var rowCount = Math.floor(Math.random() * 9000000000) + 1000000000;
        rowCountsArray.push(rowCount);

        var divContent = $('#div-content-' + requestType + '-' + dayCode);
        var newRow = `
            <div class="row times-row" id="rowId-${requestType}-${dayCode}-${rowCount}">
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control timepicker 24_format" 
                            name="selected_days[${ requestType }][${dayCode}][times][${rowCount}][from]"
                            data-name="selected_days.${ requestType }.${dayCode}.times.${rowCount}.from" value="00">
                        <span class="input-group-btn">
                            <button class="btn default" type="button">
                                <i class="fa fa-clock-o"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control timepicker 24_format" 
                            name="selected_days[${ requestType }][${dayCode}][times][${rowCount}][to]"
                            data-name="selected_days.${ requestType }.${dayCode}.times.${rowCount}.to" value="23">
                        <span class="input-group-btn">
                            <button class="btn default" type="button">
                                <i class="fa fa-clock-o"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger" onclick="removeDayTimes('${dayCode}', '${requestType}', ${rowCount}, 'row')">X</button>
                </div>
            </div>
            `;

        divContent.append(newRow);

        $(".timepicker").timepicker({
            timeFormat: 'HH',
        });
    }

    function removeDayTimes(dayCode, requestType, index, flag = '') {

        if (flag === 'row') {
            $('#rowId-' + requestType + '-' + dayCode + '-' + index).remove();
            const i = rowCountsArray.indexOf(index);
            if (i > -1) {
                rowCountsArray.splice(i, 1);
            }
        }

    }
</script>
