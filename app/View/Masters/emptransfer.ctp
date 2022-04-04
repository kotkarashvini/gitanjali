<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>

<script>

    $(document).ready(function () {

 $('#transfer_fromdate').datepicker({
//            daysOfWeekDisabled: [0,6],
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
        $('#relieve_date').datepicker({
//            daysOfWeekDisabled: [0,6],
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true

        });
      

        $('#tableemptransfer').hide();

        $('#previous_office_id').change(function () {
            var emp = $("#previous_office_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>Masters/get_employee_name', {emp: emp}, function (data)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data.employee, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#emp_code").prop("disabled", false);
                $("#emp_code option").remove();
                $("#emp_code").append(sc2);

            });
            curoff();
        });
        $('#emp_code').change(function () {
            $('#emp_name').html($('#emp_code option:selected').text());
            $('#tableemptransfer').show();
            $()
        });

//        $('#previous_office_id').change(function () {
//            var state = $("#previous_office_id option:selected").val();
//          
//            $.getJSON("<?php echo $this->webroot; ?>regdivision", {state: state}, function (data)
//            {
//                var sc = '<option value="empty">--Select Division--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#division_id option").remove();
//                $("#division_id").append(sc);
//            });
//        })


        //get designation name from employee change
//        $('#employee_id').change(function () {
//            var desi = $("#employee_id option:selected").val();
//            $.getJSON('<?php echo $this->webroot; ?>Masters/get_designation', {desi: desi}, function (data)
//            {
//                var sc1 = '<option>--select--</option>';
//                $.each(data.designation, function (index, val) {
//                    sc1 += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#designation_id").prop("disabled", false);
//                $("#designation_id option").remove();
//                $("#designation_id").append(sc1);
//
//            });
//
//        });
    });
</script>

<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
     function formcancel() {
        document.getElementById("actiontype").value = '2';
        
    }
    
    function curoff(off_id) {
        var office = $("#previous_office_id option:selected").val();
        $.getJSON('<?php echo $this->webroot; ?>Masters/office_change_event', {office: office}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.office, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#transfer_office_id").prop("disabled", false);
            $("#transfer_office_id option").remove();

            $("#transfer_office_id").append(sc);
            if (off_id) {
                $("#transfer_office_id").val(off_id);
            }

        });

    }


</script>
<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<?php echo $this->Form->create('emptransfer', array('id' => 'emptransfer', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblemptranshead'); ?> </h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/emptransfer_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="previous_office_id" class="col-sm-2 control-label"><?php echo __('lblcurrofc'); ?>  :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('previous_office_id', array('label' => false, 'id' => 'previous_office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $officedata))); ?>
                            <span id="previous_office_id_error" class="form-error"><?php echo $errarr['previous_office_id_error']; ?></span>
                        </div>
                        <label for="employee_id" class="col-sm-3 control-label"><?php echo __('lblempname'); ?>  :<span style="color: #ff0000">*</span></label> 
                        <!--                        <div class="col-sm-2">
                        <?php echo $this->Form->input('employee_id', array('label' => false, 'id' => 'employee_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--'))); ?>
                                                    <span id="employee_id_error" class="form-error"><?php echo $errarr['employee_id_error']; ?></span>
                                                </div>-->

                        <div class="col-sm-2">
                            <?php echo $this->Form->input('emp_code', array('options' => array($Empcode), 'empty' => '--select--', 'id' => 'emp_code', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="emp_code_error" class="form-error"><?php //echo $errarr['emp_code_error'];                  ?></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>   

        <!--        <div class="box-body">
                        <table id="tableemptransfer" class="table table-striped table-bordered table-hover">  
                            <thead> 
                                <tr>  
                                            <th class="center width10">Current Office Name</th>
                                    <th class="center width10"> <?php //echo __('lblempname');   ?></th>
        
        <th class="center width10">Action</th>
        
                                </tr>  
                            </thead>
                            <tbody>
        <?php // foreach ($employee_name as $employee_name1):?>
                                <tr>
        
                                    <td id="emp_name"><?php // echo $employee_name1      ?></td>
                                    <td><?php //echo $this->Html->link("Select", array('controller' => 'Masters', 'action' => ''));    ?></td>
                                </tr>
        
        <?php //endforeach; ?>
        
        <?php // unset($emooffdesi1); ?>
                            </tbody>
                        </table> 
        
                </div>-->


        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="transfer_office_id" class="col-sm-2 control-label"> <?php echo __('lbltrnsfto'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('transfer_office_id', array('label' => false, 'id' => 'transfer_office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $officedata))); ?>
                            <span id="transfer_office_id_error" class="form-error"><?php echo $errarr['transfer_office_id_error']; ?></span>
                        </div>
                        <label for="transfer_desi_id" class="col-sm-3 control-label"><?php echo __('lbldesignation'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('transfer_desi_id', array('label' => false, 'id' => 'transfer_desi_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $designationdata))); ?>
                            <span id="transfer_desi_id_error" class="form-error"><?php echo $errarr['transfer_desi_id_error']; ?></span>
                        </div>
                    </div>
                </div>    
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="transfer_fromdate" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('transfer_fromdate', array('label' => false, 'id' => 'transfer_fromdate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="transfer_fromdate_error" class="form-error"><?php echo $errarr['transfer_fromdate_error']; ?></span>
                        </div>
                        <label for="relieve_date" class="col-sm-3 control-label"><?php echo __('lblrelievedt'); ?>  :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('relieve_date', array('label' => false, 'id' => 'relieve_date', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="relieve_date_error" class="form-error"><?php echo $errarr['relieve_date_error']; ?></span>
                        </div>
                    </div>
                </div>    
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="transfer_remark" class="col-sm-2 control-label"><?php echo __('lblremark'); ?>  :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('transfer_remark', array('label' => false, 'id' => 'transfer_remark', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="transfer_remark_error" class="form-error"><?php echo $errarr['transfer_remark_error']; ?></span>
                        </div>
                    </div>
                </div> 
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                        </button>
                        <button id="btncancel" name="btncancel" class="btn btn-info " onclick="javascript: return formcancel();">
                            &nbsp;&nbsp;<?php echo __('btncancel'); ?>
                        </button>
                    </div>
                </div>  
                <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                    <thead> 
                        <tr>  
                            <th class="center width10"> <?php echo __('lblofficename'); ?> </th>
                            <th class="center width10"> <?php echo __('lblempname'); ?> </th>
                            <th class="center width10"> <?php echo __('lbltrnsfto'); ?> </th>
                            <th class="center width10"> <?php echo __('lbldesignation'); ?> </th>
                            <th class="center width10"> <?php echo __('lblfromdate'); ?> </th>
                            <th class="center width10"><?php echo __('lblrelievedt'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($empgrid as $empgrid1): ?>
                            <tr>
                                <td ><?php echo $empgrid1[0]['office_name_en']; ?></td>
                                <td ><?php echo $empgrid1[0]['emp_fname']; ?></td>
                                <td ><?php echo $empgrid1[0]['transfer_office_name']; ?></td>
                                <td ><?php echo $empgrid1[0]['desg_desc_en']; ?></td>
                                <td ><?php echo $empgrid1[0]['transfer_fromdate']; ?></td>
                                <td ><?php echo $empgrid1[0]['relieve_date']; ?></td>
                            </tr>

                        <?php endforeach; ?>

                        <?php unset($empgrid1); ?>
                    </tbody>
                </table> 
                <?php if (!empty($empgrid1)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div> 
        </div> 


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>

