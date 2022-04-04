<script type="text/javascript">
    $(document).ready(function () {

        $('.date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

        if (!navigator.onLine){
            // document.body.innerHTML = 'Loading...';
            // window.location = '../cterror.html';
        }
        
        function disableBack() {
            window.history.forward();
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        }
        
        $("#divdaterange").hide();
        
        viewoptions();
        
        $("input:radio[name='data[rptFeeCalc][filterby]']").change(function () {
            viewoptions();
        });

        $("#from,#to").attr('title', 'click here to select Date');
        $("#to,#from").prop('readOnly', true);

        $("#go").click(function () {
            getrecord();
        });


    });
//-------------------------------------------------------------------------------------------------------------------------------------------------    
    var host = '<?php echo $this->webroot; ?>';
    function viewoptions() {
        var fltflag = $("input:radio[name='data[rptFeeCalc][filterby]']:checked").val();
        switch (fltflag) {
            case 'D':
                $("#divNo,#divMonth,#divYear").hide();
                $("#divDate").show();
                break;
            case 'M':
                $("#divNo,#divDate,#divYear").hide();
                $("#divMonth").show();
                break;
            case 'Y':
                $("#divNo,#divMonth,#divDate").hide();
                $("#divYear").show();
                break;
            default :
                $("#divDate,#divMonth,#divYear").hide();
                $("#divNo").show();
                break;
        }
    }
//-------------------------------------------------------------------------------------------------------------------------------------------------
    function getrecord() {
        var fltflag = $("input:radio[name='data[rptFeeCalc][filterby]']:checked").val();
        if (fltflag == 'V') {//by Valuation No.
            if ($("#fee_calc_id").val() == "") {
                $("#fee_calc_id").focus();
                $("#fee_calc_id").attr('placeholder', 'Enter Valuation Number');
                return false;
            }
        } else if (fltflag == 'D') {//by Date Range
            var from = $("#from").val();
            var to = $('#to').val();
            if (($("#from").val() == "")) {
                $("#from").focus();
                return false;
            } else if (($("#to").val() == "")) {
                $("#to").focus();
                return false;
            }
        }

        $.ajax(
                {
                    type: 'post',
                    url: host + 'getFeeCalcList',
                    data: $("#rptFeeCalc").serialize(),
                    success: function (result)
                    {
                        $('#valuationdiv').html(result);
                    }
                });
    }
//-------------------------------------------------------------------------------------------------------------------------------------------------
    function formview(calc_id, rpt_type_flag) {
        jQuery.post(host + 'viewFeeCalc', {fee_calc_id: calc_id, rpt_type_flag: rpt_type_flag}, function (data) {
            $('#rpt_modal_body').html(data);
            $('#myModal_rpt').modal("show");
        });
        window.scrollTo(500, 200);
        return false;

    }
//------------------------------------------------------------------------------------------------------------------------------------------------- 

</script>
<?php echo $this->Form->create('rptFeeCalc', array('id' => 'rptFeeCalc')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblReprintSD'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Filter Record By" class="control-label col-sm-2"><?php echo __('lblgerrptbyid'); ?></label>            
                        <div class="col-sm-8"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('N' => '&nbsp;Fee Calculation Id &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'M' => '&nbsp;Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'Y' => '&nbsp;Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'D' => '&nbsp;Date&nbsp;&nbsp;&nbsp;'), 'value' => 'N', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row" id="divNo">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Valuation No" class="control-label col-sm-2"><?php echo __('lblFeeCalcNo'); ?></label>            
                        <div class="col-sm-2"><?php echo $this->Form->input("fee_calc_id", array('id' => 'fee_calc_id', 'type' => 'text', 'legend' => false, 'class' => 'form-control', 'label' => false)); ?></div>
                    <span id="fee_calc_id_error" class="form-error"><?php //echo $errarr['fee_calc_id_error']; ?></span>
                    </div>
                </div>
                <div  class="rowht"></div>

                <div class="row" id="divMonth">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="MOnth" class="control-label col-sm-2"><?php echo __('lblMonth'); ?></label>            
                        <div class="col-sm-2"><?php echo $this->Form->input("month", array('id' => 'month', 'options' => $months, 'legend' => false, 'class' => 'form-control', 'label' => false)); ?></div>
                    </div>
                </div>

                <div  class="rowht"></div>
                <div class="row" id="divYear">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Valuation No" class="control-label col-sm-2"><?php echo __('lblYear'); ?></label>            
                        <div class="col-sm-2"><?php echo $this->Form->input("year", array('id' => 'year', 'options' => $years, 'legend' => false, 'class' => 'form-control', 'label' => false)); ?></div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row" id="divDate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Valuation No" class="control-label col-sm-2"><?php echo __('lbldate'); ?></label>            
                        <div class="col-sm-2"><?php echo $this->Form->input("fromDate", array('id' => 'fromDate', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'readOnly' => TRUE, 'value' => date('Y-m-d'))); ?></div>
                        <div class="col-sm-2"><?php echo $this->Form->input("toDate", array('id' => 'toDate', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'readOnly' => TRUE, 'value' => date('Y-m-d'))); ?></div>
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <input type="button" id="go" value="Go" class="btn btn-info">
                        <input type="hidden" id="actiontype" name="hdnaction" class="btn btn-info">
                        <input type="hidden" id="val_id" name="valid" class="btn btn-info">
                    </div>
                </div>
                <div  class="rowht"></div>

                <div id="viewData">

                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div id="valuationdiv" align="center"> 

                </div>
            </div>
        </div>
    </div>
</div>


<div id="myModal_rpt" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fees Calculation Report</h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
