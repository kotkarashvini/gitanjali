<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {

    $('#tableoffice').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    
    
//            $('#state_id').change(function () {
//            var state = $("#state_id option:selected").val();
//            var token = $("#token").val();
////            alert(token);
//            var i;
//            $.getJSON("<?php echo $this->webroot; ?>regdivision", {state: state, token: token}, function (data)
//            {
////                alert(data);
//            var sc = '<option value="empty">--Select Division--</option>';
//                    $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                    });
//                    $("#division_id option").remove();
//                    $("#division_id").append(sc);
//            });
//         })

          $('#state_id').change(function () {
            var state = $("#state_id option:selected").val();


            var i;
            $.getJSON("<?php echo $this->webroot; ?>Office/get_district_name", {state: state}, function (data)
            {
                var sc = '<option value="empty">--Select District--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        });

      $('#division_id').change(function () {
            var division_id = $('#division_id').val();
            //alert('ddd');
            $.postJSON('<?php echo $this->webroot;?>Office/getdist', {division_id: division_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        });
        //---------------------------------    
        
      
      
      
       $('#district_id').change(function () {
            var district_id = $('#district_id').val();
            
              $.postJSON('<?php echo $this->webroot; ?>Office/getgovtbody', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#corp_id option").remove();
                    $("#corp_id").append(sc);
                });
            
            
<?php if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                $.postJSON('<?php echo $this->webroot; ?>Office/getsubdiv', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                });

<?php } else { ?>
                var district_id = $('#district_id').val();
                $.postJSON('<?php echo $this->webroot; ?>Office/gettalukadist', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                });

<?php } ?>
        });
      
        //---------------------------------    
        //---------------Subdivision->Taluka filteration
        $('#subdivision_id').change(function () {
            var subdivision_id = $('#subdivision_id').val();
            $.postJSON('<?php echo $this->webroot; ?>Office/gettaluka', {subdivision_id: subdivision_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        });
        //---------------------------------    

        //---------------Taluka->Circle filteration
//        $('#taluka_id').change(function () {
//            var taluka_id = $('#taluka_id').val();
//            $.postJSON('<?php //echo $this->webroot; ?>Office/getcircle', {taluka_id: taluka_id}, function (data)
//            {
//                var sc = '<option value="">--select--</option>';
//                $.each(data, function (index, val) {
//
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#circle_id option").remove();
//                $("#circle_id").append(sc);
//            });
//        });

 $('#taluka_id').change(function () {
            var taluka_id = $('#taluka_id').val();

            
<?php if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') { ?>
                $.postJSON('<?php echo $this->webroot; ?>Office/getcircle', {taluka_id: taluka_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                });

<?php } else { ?>
               
                $.postJSON('<?php echo $this->webroot; ?>Office/getvillage', {taluka_id: taluka_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#village_id option").remove();
                    $("#village_id").append(sc);
                });

<?php } ?>
        });
        
        
                $('#circle_id').change(function () {
            var circle_id = $('#circle_id').val();
            $.postJSON('<?php echo $this->webroot; ?>Office/getvillage', {circle_id: circle_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        });
        
        
        
        
        //---------------------------------    


    


    });
            function formadd() {

            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
   
    
    function checkoffice1() {

        var username = $('#user_name').val();
        if (username != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>checkofficename",
                data: {'username': username},
                success: function (data) {
                    if (data == 1)
                    {
                        $("#user_name").val('');


                        $('#user_name').focus();
                        alert('user name already exist');
                        return false;
                    }
                }
            });
        }
    }
</script>
</script>



<?php echo $this->Form->create('officenew', array('id' => 'officenew', 'class' => 'form-vertical')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbloffice'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Office/officenew_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">

                        <div class="col-sm-3">
                            <label for="dept_id" class="control-label"><?php echo __('lbldept'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('dept_id', array('label' => false, 'id' => 'dept_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $department))); ?>
                            <span id="dept_id_error" class="form-error"></span>
                        </div>

                        <?php
                        //  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblofficename') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('office_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'office_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '200')) ?>
                                <span id="<?php echo 'office_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['office_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblofcaddr'); ?></h3>
            </div>
            <div class="box-body">
                
                            <div  class="rowht"></div>
                
                            
                            <div class="col-md-12">
<!--                                 <div class="col-sm-2">
                            <label for="state_id" class="control-label"><?php //echo __('lbladmstate'); ?><span style="color: #ff0000">*</span> </label>
                            <?php //echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select State--', $State))); ?>
                          <span id="state_id_error" class="form-error"><?php //echo $errarr['state_id_error']; ?></span>
                        </div>-->

                    <?php
                    //   pr($is_div_flag);
                    //exit;
                   
                    if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                        ?>
                        <div class="col-sm-2">
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?><span style="color: #ff0000">*</span> </label>
                            <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                            <span class="form-error" id="division_id_error"></span>
                        </div>
                    <?php } ?>


                    <?php  if ($is_div_flag['adminLevelConfig']['is_dist'] == 'Y') {  ?>
                    <div class="col-sm-2">
                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>                            
                        <span class="form-error" id="district_id_error"></span>
                    </div>
                    <?php }  ?> 



                    <?php if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdivision'); ?> <span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('subdivision_id', array('options' => $subdivisiondata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                            <span class="form-error" id="subdivision_id_error"></span>
                        </div>
                    <?php } ?>


                    <?php if ($is_div_flag['adminLevelConfig']['is_taluka'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?> <span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('taluka_id', array('options' => $talukadata, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                            <span class="form-error" id="taluka_id_error"></span>
                        </div>
                    <?php } ?>


                    <?php if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="circle_id" class="control-label"><?php echo __('lbladmcircle'); ?><span style="color: #ff0000">*</span> </label>
                            <?php echo $this->Form->input('circle_id', array('options' => $circledata, 'empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                            <span class="form-error" id="taluka_id_error"></span>
                        </div>
                    <?php } ?>

                          <div class="col-sm-2">
                             <label for="village_id" class="control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>
                             <?php echo $this->Form->input('village_id',array( 'options' =>$villagedata, 'empty' => '--Select Village--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?> 
                            <span class="form-error" id="village_id_error"></span>
                        </div>      
                                
                </div>
                            
                            
                            
                            
                    <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row"></div>
                  
                
                     <div  class="rowht"></div> <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="flat" class="col-sm-2 control-label"><?php echo __('lblflat'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('flat', array('label' => false, 'id' => 'flat', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="flat_error" class="form-error"><?php //echo $errarr['flat_error']; ?></span>
                        </div>
                        <label for="building" class="col-sm-2 control-label"><?php echo __('lblbuildingnamenofloor'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('building', array('label' => false, 'id' => 'building', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="building_error" class="form-error"><?php //echo $errarr['building_error']; ?></span>
                        </div>

                        <label for="road" class="col-sm-2 control-label"><?php echo __('lblroadname'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('road', array('label' => false, 'id' => 'road', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="road_error" class="form-error"><?php //echo $errarr['road_error']; ?></span>
                        </div>

                    </div>
                </div>
                     
                <div  class="rowht"></div><div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="locality" class="col-sm-2 control-label"><?php echo __('lbllocality'); ?><span style="color: #ff0000"></span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('locality', array('label' => false, 'id' => 'locality', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="locality_error" class="form-error"><?php //echo $errarr['locality_error']; ?></span>
                        </div>
<!--                        <label for="city" class="col-sm-2 control-label"><?php //echo __('lblcity'); ?><span style="color: #ff0000"></span></label> 
                        <div class="col-sm-2">
                            <?php //echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="city_error" class="form-error"><?php //echo $errarr['city_error']; ?></span>
                        </div>-->
                       
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="pincode" class="col-sm-2 control-label"><?php echo __('lblpincode'); ?></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="pincode_error" class="form-error"><?php //echo $errarr['pincode_error']; ?></span>
                        </div>
                        <label for="officc_contact_no" class="col-sm-2 control-label"><?php echo __('lblofccontact'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('officc_contact_no', array('label' => false, 'id' => 'officc_contact_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="officc_contact_no_error" class="form-error"><?php //echo $errarr['officc_contact_no_error']; ?></span>
                        </div>
                        <label for="office_email_id" class="col-sm-2 control-label"><?php echo __('lblofcemail'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_email_id', array('label' => false, 'id' => 'office_email_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '50')) ?>
                            <span id="office_email_id_error" class="form-error"><?php //echo $errarr['office_email_id_error']; ?></span>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblreportinginfo'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php //if($officecount!=0){?>
                        <label for="reporting_office_id" class="col-sm-2 control-label"><?php echo __('lblreportingoffice'); ?></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('reporting_office_id', array('label' => false, 'id' => 'reporting_office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $reportingofficedata))) ?>
                            <span id="reporting_office_id_error" class="form-error"><?php //echo $errarr['reporting_office_id_error'];  ?></span>
                        </div>
                        <?PHP //}?>
                        <label for="hierarchy_id" class="col-sm-2 control-label"><?php echo __('lblofficehierarchy'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('hierarchy_id', array('label' => false, 'id' => 'hierarchy_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $hierarchydata))); ?>
                            <span id="hierarchy_id_error" class="form-error"><?php //echo $errarr['hierarchy_id_error'];  ?></span>
                        </div>
                        <label for="shift_id" class="col-sm-2 control-label">Office Shift<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2"> 
                            <?php echo $this->Form->input('shift_id', array('label' => false, 'id' => 'shift_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $officesift))); ?>
                            <span id="shift_id_error" class="form-error"><?php //echo $errarr['shift_id_error'];  ?></span>
                        </div>
                        
                        
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                <label for="shift_id" class="col-sm-2 control-label">Select Office Slot<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('slot_id', array('label' => false, 'id' => 'slot_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $timeslot))); ?>
                            <span id="slot_id_error" class="form-error"><?php //echo $errarr['slot_id_error'];  ?></span>
                        </div>
                 </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
                        
                        <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                        
                          <a href="<?php echo $this->webroot; ?>Office/officenew/" class="btn btn-info"><span class="glyphicon glyphicon-floppy-remove"><?php echo __('btncancel'); ?></span> </a>    

<!--                        <button  id="btncancel" name="btncancel" class="btn btn-info" onclick="javascript: return formcancel();">
                           &nbsp;&nbsp; <?php //echo __('btncancel'); ?></button>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tableoffice" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center width10"><?php echo __('lbldept'); ?></th>

                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblofficename') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lbladmstate'); ?></th>    
                                <th class="center width10"><?php echo __('lbladmdistrict'); ?></th>    

                                <th class="center width10"><?php echo __('lbladmvillage'); ?></th>    
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($officerecord as $officerecord1): ?>
                                <tr>
                                    <td><?php echo $officerecord1[0]['dept_name_en']; ?></td>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td ><?php echo $officerecord1[0]['office_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td><?php echo $officerecord1[0]['state_name_en']; ?></td>    
                                    <td><?php echo $officerecord1[0]['district_name_en']; ?></td>    
                                    <td><?php echo $officerecord1[0]['village_name_en']; ?></td>    
                                    <td>
                                        
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'officenew', $officerecord1[0]['office_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_officenew', $officerecord1[0]['office_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                                    </td>
                                </tr>       
                            <?php endforeach; ?>
                            <?php unset($officerecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($officerecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>

    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    
     <?php  echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'type' => 'hidden'));   ?>
</div>
