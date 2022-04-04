<script type="text/javascript">
    $(document).ready(function () {

        $('#officeid').hide();
        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#to').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#to').change(function () {
            var from = $("#from").val();
            var to = $("#to").val();

            if (Date.parse(from) > Date.parse(to)) {
                alert("Invalid Date Range");
                $('#to').val('');
                return false;
            } 
        });


        $('#tabledoc').dataTable({
            "bPaginate": false,
            "ordering": false
        });

        $('#usercreate_flag').change(function () {
            
            if (this.value == 'O') {
                $('#officeid').hide();
            } else {
                $('#officeid').show();
            }
        });
    });

    function func() {
        var radios = $("#usercreate_flag").val();
        $("#rdbtn").val(radios);
    }
</script>
<style>
    .table-responsive
    {
        overflow-y:auto;
        height:380px;
    }
</style>

<?php
echo $this->Form->create('rpt_appointment_pendency', array('id' => 'rpt_appointment_pendency', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class="box-title headbolder"> <?php echo __('Appointment Pendency Details'); ?> </h3></center>
            </div>
            <div class="box-body">

                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="usercreate" class="control-label col-sm-2"><?php echo __('Select Records By:'); ?></label>            
                    <div class="col-sm-2"> 
                        <?php //echo $this->Form->input('usercreate_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Office Wise&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;All Offices'), 'value' => $rdbtn, 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'usercreate_flag', 'name' => 'usercreate_flag')); ?>
                        <?php echo $this->Form->input('usercreate_flag', array('label' => false, 'id' => 'usercreate_flag', 'class' => 'form-control input-sm', 'value' => $rdbtn, 'options' => array(' ' => 'select', $usercreate_flag))); ?>
                        <span id="usercreate_flag_error" class="form-error"><?php //echo $errarr['usercreate_flag_error'];                                     ?></span>
                    </div> 
                    <div class="col-sm-2"> </div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 


                <div class="row" id="officeid">
                    <div class="col-sm-2"></div>
                    <label for="office_id" class="col-sm-2 control-label"><?php echo __('Select Office:'); ?></label> 
                    <div class="col-sm-4"> 
                        <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array($office), 'empty' => '--Select--')); ?>
                        <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];                                    ?></span>
                    </div>
                    <div class="col-sm-2"> </div>

                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div> 

                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date:'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                                        ?></span>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                        <span id="to_error" class="to-error"><?php //echo $errarr['to_error'];                                              ?></span>
                    </div>

                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit" onclick="func();"> <?php echo __('lblsearch'); ?> </button></div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 

            </div>
        </div>
        <?php
        if (!empty($app_off)) {
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <!--                    <div>
                                            <center><h4 class = "box-title headbolder"><?php //echo __('Monthly Fee Report For '); echo $office;     ?> </h4></center>
                                        </div>-->
                    <div id="selectdocument" class="table-responsive">
                        <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead class="center">  
                                <tr >  
                                    <th><?php echo __('Office Name'); ?></th>
                                    <th><?php echo __('Assinged'); ?></th>
                                    <th><?php echo __('Approved'); ?></th>
                                    <th><?php echo __('Username'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php
                                foreach ($app_off as $rec):
                                    ?>
                                    <tr>
                                        <td ><?php echo $rec[0]['office_name_en']; ?></td>
                                        <td ><?php echo $rec[0]['assigned']; ?></td>
                                        <td ><?php echo $rec[0]['approved']; ?></td>
                                        <td ><?php echo $rec[0]['username']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<input type='hidden' value='<?php echo $rdbtn; ?>' name='rdbtn' id='rdbtn'/>
<?php echo $this->Form->end(); ?>
