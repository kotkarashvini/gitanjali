<script>
    $(document).ready(function () {
    
        $("#date").hide();
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

        $("input:radio[name='data[rpt_index_register][filterby]']").change(function () {
            viewoptions();
        });

    });
       



    function viewoptions() {
        var fltflag = $("input:radio[name='data[rpt_index_register][filterby]']:checked").val();
        //  alert(fltflag);
        if (fltflag == 'IR1') {

            $("#date").hide();
            $("#date").slideDown(1000);
        }
        else if (fltflag == 'IR2') {
//            alert(fltflag);
            $("#date").hide();
            $("#date").fadeIn("slow");
        }
        else if (fltflag == 'IR3') {
            $("#date").hide();
            $("#date").slideDown(100);
        }
        else if (fltflag == 'IR4') {
//              alert(fltflag);
            $("#date").hide();
            $("#date").slideDown(1000);
        }
//         else if (fltflag == 'dpr') {
////              alert(fltflag);
//            $("#date").hide();
//            $("#date").slideDown(1000);
//        }
        else {
            $("#date").hide();
        }
    }

</script>

<script>

    $(function () {
        var host = '<?php echo $this->webroot; ?>';

        $('#go').click(function () {
             $.ajax({
        url: '<index_register_pdf>',
        success: function(data) {
            var blob=new Blob([data]);
            var link=document.createElement('a');
            link.href=window.URL.createObjectURL(blob);
            link.download="Dossier_"+new Date()+".pdf";
            link.click();
        }
    });
//            $.post('<?php //echo $this->webroot; ?>PunjabReports/index_register', $('#rpt_index_register').serialize(), function (data) {
//                $('#indexregister').html(data);
//                return false;
//            });
//            return false;
            $('form').submit();
        });
    });
</script>

<?php echo $this->Form->create('rpt_index_register', array('id' => 'rpt_index_register')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">Index Register</h3></center>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Filter Record By" class="control-label col-sm-2"><?php echo __('lblgetrecordby'); ?></label>            
                <div class="col-sm-7"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('IR1' => '&nbsp;Index Register 1 &nbsp;&nbsp;&nbsp;&nbsp;', 'IR2' => '&nbsp;Index Register 2 &nbsp;&nbsp;&nbsp;', 'IR3' => '&nbsp;Index Register 3 &nbsp;&nbsp;&nbsp;', 'IR4' => '&nbsp;Index Register 4 &nbsp;&nbsp;&nbsp;&nbsp;'), 'value' => 'N', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
            </div>
        </div>
        <div  class="rowht">&nbsp;</div>
        <div id="date">
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <label for="From_Date" class="col-sm-2"><?php echo __('lblfromdate'); ?></label>   
                    <div class="input-group date col-sm-2">
                        <?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                        <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                    </div>
                </div> 
            </div>
            <div  class="rowht">&nbsp;</div>
            <div class="row" >
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <label for="TO Date" class="col-sm-2" ><?php echo __('lbltodate'); ?></label> 
                    <div class="input-group date col-sm-2">
                        <?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                        <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                    </div>
                </div>
            </div>
        </div> 
        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
        <div class="row center">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <button id="go" class="btn btn-primary" type="submit"> Go </button>
                <input type="hidden" id="actiontype" name="hdnaction" class="btn btn-primary">
            </div>
        </div>
        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>

    </div>    
</div>
<?php if ($pdf_flag) { ?>
    <iframe src="<?php echo $this->webroot ?>index_register_pdf" width="100%" height="500px">
    <?php
}
?>
<!--<div class="row" id='indexregister'>

</div>-->