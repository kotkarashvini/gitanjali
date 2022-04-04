<?php $doc_lang = $this->Session->read('doc_lang'); ?> 

<script>
    $(document).ready(function () {
        $('#joining').hide();
//        $("#joining_date").datepicker();

//        $('#joining_date').datepicker({
//            maxDate: '+0d',
//            yearRange: '1920:2010',
//            changeMonth: true,
//            changeYear: true,
//            dateFormat: "dd MM yy",
//        });
        
         $('#joining_date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

        $('#joining_office_id').change(function () {
            var office = $("#joining_office_id").val();
            $('#joining').hide();
            $.post('<?php echo $this->webroot; ?>Masters/get_office_name', {office: office}, function (data)

            {
                $('#showrecord').html(data);
                
               // if(data != null){
                    $('#joining').show();
                //}

            });

        });


    });
</script> 

<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }

//    function formupdate(joining_officer_empid, designation_id, joining_remark, joining_date, transfer_id) {
//        document.getElementById("actiontype").value = '1';
//        $('#joining_officer_empid').val(joining_officer_empid);
//        $('#designation_id').val(designation_id);
//        $('#joining_remark').val(joining_remark);
//        $('#joining_date').val(joining_date);
//        $('#hftransfer_id').val(transfer_id);
//        $('#btnadd').html('Save');
//        $('#hfupdateflag').val('Y');
//
//    }


    function formcancel() {
        document.getElementById("actiontype").value = '3';
    }
//   


</script>   
<?php echo $this->Form->create('empjoining', array('id' => 'empjoining', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title"><?php echo __('lblempjoin'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/empjoining_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="joining_office_id" class="col-sm-2 control-label"><?php echo __('lblselofc'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('joining_office_id', array('label' => false, 'id' => 'joining_office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $officedata))); ?>
                            <span id="joining_office_id_error" class="form-error"><?php echo $errarr['joining_office_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="row" id="showrecord">

                </div>
            </div>

        </div>
        <div class="box box-primary" id="joining">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="joining_desi_id" class="col-sm-2 control-label"><?php echo __('lbljoiningdesc'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('joining_desi_id', array('label' => false, 'id' => 'joining_desi_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $designationdata))); ?>
                            <span id="joining_desi_id_error" class="form-error"><?php echo $errarr['joining_desi_id_error']; ?></span>
                        </div>
                    </div>  
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="joining_remark" class="col-sm-2 control-label"><?php echo __('lbljoinrmk'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('joining_remark', array('label' => false, 'id' => 'joining_remark', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="joining_remark_error" class="form-error"><?php echo $errarr['joining_remark_error']; ?></span>
                        </div>
                        <label for="joining_date" class="col-sm-2 control-label"><?php echo __('lbljoindt'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('joining_date', array('label' => false, 'id' => 'joining_date', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="joining_date_error" class="form-error"><?php echo $errarr['joining_date_error']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="row" style="text-align: center">
                <div class="form-group">
                    <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " style="text-align: center;" onclick="javascript: return formadd();">
                        <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?>
                    </button>
                    <button id="btncancel" name="btncancel" class="btn btn-info " style="text-align: center;" onclick="javascript: return formcancel();">
                        <?php echo __('btncancel'); ?>
                    </button>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                        <thead> 
                            <tr>  
                                <th class="center width10"><?php echo __('lblempname'); ?> </th>
                                <th class="center width10"><?php echo __('lblofficename'); ?> </th>
                                <th class="center width10"><?php echo __('lbldesignation'); ?> </th>
                                <th class="center width10"> <?php echo __('lblremark'); ?> </th>
                                <th class="center width10"><?php echo __('lbljoindt'); ?> </th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($empjoining as $empjoining1): ?>
                                <tr>
                                    <td ><?php echo $empjoining1[0]['employee_name']; ?></td>
                                    <td ><?php echo $empjoining1[0]['office_name_en']; ?></td>
                                    <td ><?php echo $empjoining1[0]['desg_desc_en']; ?></td>
                                    <td ><?php echo $empjoining1[0]['joining_remark']; ?></td>
                                    <td ><?php echo $empjoining1[0]['joining_date']; ?></td>
                                </tr>

                            <?php endforeach; ?>

                            <?php unset($empjoining1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($empjoining1)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>

            </div>
        </div>

    </div>



</div>

<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
<input type='hidden' value='<?php echo $hftransfer_id; ?>' name='hftransfer_id' id='hftransfer_id'/>
<input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
