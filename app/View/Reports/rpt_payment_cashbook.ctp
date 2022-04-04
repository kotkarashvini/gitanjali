<script>
    $(document).ready(function () {

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

    });


</script>

<script>

    $(function () {
        var host = '<?php echo $this->webroot; ?>';

        $('#go').click(function () {
            $.ajax({
                url: '<index_register_pdf>',
                success: function (data) {
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Dossier_" + new Date() + ".pdf";
                    link.click();
                }
            });

            $('form').submit();
        });
    });
</script>


<?php echo $this->Form->create('rpt_payment_cashbook', array('id' => 'rpt_payment_cashbook')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder"><?php echo __('lbl_payment_cashbook'); ?></h3></center>
    </div>
    <div class="box-body">
        <div class = "row">
        <center><h3 class = "box-title headbolder"><?php echo $officename;; ?></h3></center>
    </div>
         <div  class="rowht">&nbsp;</div>
        <div class="row">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="Filter Record By" class="control-label col-sm-2"><?php echo __('lblgetrebordby'); ?></label>            
                <div class="col-sm-7"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('B' => '&nbsp;Both (Online,Counter)&nbsp;&nbsp;&nbsp;&nbsp;', 'O' => '&nbsp;Online&nbsp;&nbsp;&nbsp;', 'C' => '&nbsp;Counter&nbsp;&nbsp;&nbsp;'), 'value' => 'B', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
            </div>
        </div>
        <div  class="rowht">&nbsp;</div>
        
         
        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="Valuation No" class="control-label col-sm-2"> <?php echo __('lblgetrebordby'); ?> </label>            
                <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'value' => date('Y-m-d'))); ?>
                <!--<span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>-->
                
                </div>
                <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'value' => date('Y-m-d'))); ?>
                
                <!--<span id="to_error" class="form-error"><?php //echo $errarr['to_error'];  ?></span>-->
                
                </div>
            </div>
        </div>

        <div  class="rowht">&nbsp;</div>

    </div> 
    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
    <div class="row center">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <button id="go" class="btn btn-primary" type="submit"> Go </button>
               <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<!--               <button id="go" class="btn btn-primary" type="submit">Save</button>-->
        </div>
    </div>


</div>    

<?php if ($pdf_flag) { ?>
    <iframe src="<?php echo $this->webroot ?>cash_details_pdf" width="100%" height="500px">
    <?php
}
?>
