<?php // -- Modified on  22-May-2017 By Shridhar --    ?>
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

        if (!navigator.onLine)
        {
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


        $(".divdaterange,.valuationdiv").hide();
        viewoptions();
        $("input:radio[name='data[rptvaluation][filterby]']").change(function () {
            viewoptions();
        });

        $("#from,#to").attr('title', 'click here to select Date');


        $("#to").prop('readOnly', true);
        $("#from").prop('readOnly', true);

        $("#go").click(function () {
            getrecord();
        });


    });
    function viewoptions() {
        var fltflag = $("input:radio[name='data[rptvaluation][filterby]']:checked").val();
        if (fltflag == 'D') {
            $("#divvalno").hide();
            $(".divdaterange").show();
        }
        else {
            $("#divvalno").show();
            $(".divdaterange").hide();
        }
    }
    function getrecord() {
        var fltflag = $("input:radio[name='data[rptvaluation][filterby]']:checked").val();
        if (fltflag == 'V') {//by Valuation No.
            if ($("#valno").val() == "") {
                $("#valno").focus();
                $("#valno").attr('title', 'Enter Valuation Number');
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
        jQuery.post("<?php echo $this->webroot; ?>" + 'getvalList', {fltrby: fltflag, valno: $("#valno").val(), from: from, to: to}, function (data) {
            $('#valuationdiv').html(data);
            $('.valuationdiv').show();
        });
    }

    function formview(valid) {
        $("#actiontype").val('V');
        $("#val_id").val(valid);
        jQuery.post("<?php echo $this->webroot; ?>" + 'rptview', {action: 'V', valno: valid}, function (data) {
            $('#rpt_modal_body').html(data);
            $('#myModal_rpt').modal("show");
        });
        window.scrollTo(500, 200);
        var link = "<?php echo $this->webroot; ?>" + 'rptview/P/' + valid;
        $("#val_pdf").attr("href", link);
        return false;
    }


    function downloadPdf(valid) {
        $("#actiontype").val('P');
        $("#val_id").val(valid);
        jQuery.post("<?php echo $this->webroot; ?>" + 'rptview', {action: 'P', valno: valid}, function (data) {

        });
        return false;
    }
</script>
<?php echo $this->Form->create('rptvaluation', array('id' => 'rptvaluation')); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreprintvaluation'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="Filter Record By" class="control-label col-sm-2"><?php echo __('lblgetrebordby'); ?></label>            
                        <div class="col-sm-3"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('V' => '&nbsp;' . __('lblvaluationno') . '&nbsp;&nbsp;&nbsp;&nbsp;', 'D' => '&nbsp;' . __('lblsearchbydate') . '&nbsp;&nbsp;&nbsp;'), 'value' => 'V', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" >
                    <div class="form-group">
                        <div class="col-sm-12" id="divvalno">
                            <div class="col-sm-3"></div>
                            <label for="Valuation No" class="control-label col-sm-2"><?php echo __('lblvaluationno'); ?></label>            
                            <div class="col-sm-2"><?php echo $this->Form->input("valno", array('id' => 'valno', 'legend' => false, 'type' => 'number', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Enter valuation Number', 'title' => 'Enter valuation Number')); ?></div>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                
                <div class="row divdaterange">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-3"></div>
                            <label for="From_Date" class="col-sm-2"><?php echo __('lblfromdate'); ?></label>   
                            <div class="input-group date col-sm-2">
                                <?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                                <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </div> 
                </div>
                <div  class="rowht"></div>
                
                <div class="row divdaterange">
                    <div class="form-group">
                        <div class="col-sm-12">
                        <div class="col-sm-3"></div>
                        <label for="TO Date" class="col-sm-2" ><?php echo __('lbltodate'); ?></label> 
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                            <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row" id="buttons_row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="Valuation No" class="control-label col-sm-2"></label>            
                        <div class="col-sm-2">
                            <input type="button" id="go" value="View" class="btn btn-info">
                            <input type="hidden" id="actiontype" name="hdnaction" class="btn btn-primary">
                            <input type="hidden" id="val_id" name="valid" class="btn btn-primary">
                        </div>
                    </div>
                </div>   
                
                <div  class="rowht"></div>
                
                <div class="valuationdiv">
                    <div class="box-body">
                        <div width="100%" id="valuationdiv" align="center"> </div>
                    </div>
                </div>



                <div  class="rowht"></div>
                <div id="viewData">

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
                <h4 class="modal-title"><?php echo __('Valuation'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p><?php echo __('lblloading'); ?></p>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6" align="left">
                        <a href="" id="val_pdf" class="btn btn-primary"><?php echo __('lbldownloadpdf'); ?></a>
                    </div>
                    <div class="col-xs-6" align="right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
