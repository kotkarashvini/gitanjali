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
        $('#go').click(function () {
            $('form').submit();
        });
    });
</script>

<?php echo $this->Form->create('rpt_scanning_pending', array('id' => 'rpt_scanning_pending')); ?>
 <div class="box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">Scanning Pending Document</h3></center>
    </div>
            <div class="box-body">
                <div class="row" id="officeid">
                    <div class="col-sm-2"></div>
                    <label for="office_id" class="col-sm-2 control-label"><?php echo __('Select Office:'); ?></label> 
                    <div class="col-sm-4"> 
                        <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array($office_list), 'empty' => '--Select--')); ?>
                        <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];                                    ?></span>
                    </div>
                    <div class="col-sm-2"> </div>

                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div> 

                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="label_id" class="control-label col-sm-2"><?php echo __('lblgetrecordby'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                                        ?></span>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                        <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];                                              ?></span>
                    </div>

                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('Go'); ?> </button></div>
                     <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 

            </div>
</div>    

