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
            $.getJSON("<?php echo $this->webroot; ?>Masters/get_district_name", {state: state}, function (data)
            {
                var sc = '<option value="empty">--Select District--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        })

       $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            var token = $("#token").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>Masters/get_taluka_name1", {district: district, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })



        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            var token = $("#token").val();
           $.getJSON("<?php echo $this->webroot; ?>Masters/get_village_name", {taluka: taluka, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
    })


    });
            function formadd() {

            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formupdate(dept_id,
<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'office_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    flat, building, road, locality, city, taluka_id, district_id, state_id, pincode, officc_contact_no, office_email_id, reporting_office_id, shift_id, hierarchy_id,village_id,slot_id, id) {
    document.getElementById("actiontype").value = '1';
<?php foreach ($languagelist as $langcode) { ?>
        $('#office_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(office_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>

        var state = state_id;
        var i;
        $.getJSON("<?php echo $this->webroot; ?>regdistrict", {state: state}, function (data)
        {
            var sc = district_id;
            $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#district_id option").remove();
            $("#district_id").append(sc);
            $('#district_id').val(district_id);

        });
        
        var district = district_id;
        var token = $("#token").val();
        var i1;
        $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district, token: token}, function (data1)
        {
            var sc1 = taluka_id;
            $.each(data1, function (index1, val1) {
                sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
            });
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc1);
            $('#taluka_id').val(taluka_id);
        });
        
         var taluka = taluka_id;
           var token = $("#token").val();
       
         $.getJSON("<?php echo $this->webroot; ?>Masters/get_village_name", {taluka: taluka, token: token}, function (data)
        {
            var sc3 = village_id;
            $.each(data, function (index, val3) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
                sc3 += "<option value=" + index + ">" + val3 + "</option>";
            });
            $("#village_id option").remove();
            $("#village_id").append(sc3);
            $('#village_id').val(village_id);

        });

            
            $('#dept_id').val(dept_id);
            $('#building').val(building);
            $('#road').val(road);
            $('#flat').val(flat);
            $('#locality').val(locality);
            $('#city').val(city);
            $('#state_id').val(state_id);
//            $('#division_id').val(division_id);
            $('#district_id').val(district_id);
            $('#village_id').val(village_id);
            $('#slot_id').val(slot_id);
            $('#taluka_id').val(taluka_id);
            $('#pincode').val(pincode);
            $('#officc_contact_no').val(officc_contact_no);
            $('#office_email_id').val(office_email_id);
            $('#reporting_office_id').val(reporting_office_id);
            $('#shift_id').val(shift_id);
            $('#hierarchy_id').val(hierarchy_id);
            $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
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
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbloffice'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Office/officenew_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">

                        <div class="col-sm-3">
                            <label for="dept_id" class="control-label"><?php echo __('lbldept'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('dept_id', array('label' => false, 'id' => 'dept_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $department))); ?>
                            <span id="dept_id_error" class="form-error"><?php echo $errarr['dept_id_error']; ?></span>
                        </div>

                        <?php
                        //  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblofficename') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('office_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'office_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20')) ?>
                                <span id="<?php echo 'office_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['office_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
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
                <div class="row">
                    <div class="form-group">
                        <label for="state_id" class="col-sm-2 control-label"><?php echo __('lbladmstate'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select State--', $State))); ?>
                            <span id="state_id_error" class="form-error"><?php echo $errarr['state_id_error']; ?></span>
                        </div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php //echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select District--', $District))); ?>
                             <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => array())); ?>
                            <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
                        <label for="taluka_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php //echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Taluka--', $taluka))); ?>
                              <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'empty' => '--Select Taluka--', 'options' => array())); ?>
                            <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                    <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                          <label for="village_id" class="control-label col-sm-2"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                             <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'empty' => '--Select Village--', 'options' => array())); ?> 
                            
                            <span id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                     <div  class="rowht"></div> <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="flat" class="col-sm-2 control-label"><?php echo __('lblflat'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('flat', array('label' => false, 'id' => 'flat', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="flat_error" class="form-error"><?php echo $errarr['flat_error']; ?></span>
                        </div>
                        <label for="building" class="col-sm-2 control-label"><?php echo __('lblbuildingnamenofloor'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('building', array('label' => false, 'id' => 'building', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="building_error" class="form-error"><?php echo $errarr['building_error']; ?></span>
                        </div>

                        <label for="road" class="col-sm-2 control-label"><?php echo __('lblroadname'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('road', array('label' => false, 'id' => 'road', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="road_error" class="form-error"><?php echo $errarr['road_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="locality" class="col-sm-2 control-label"><?php echo __('lbllocality'); ?><span style="color: #ff0000"></span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('locality', array('label' => false, 'id' => 'locality', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="locality_error" class="form-error"><?php echo $errarr['locality_error']; ?></span>
                        </div>
                        <label for="city" class="col-sm-2 control-label"><?php echo __('lblcity'); ?><span style="color: #ff0000"></span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="city_error" class="form-error"><?php echo $errarr['city_error']; ?></span>
                        </div>
                       
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="pincode" class="col-sm-2 control-label"><?php echo __('lblpincode'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="pincode_error" class="form-error"><?php echo $errarr['pincode_error']; ?></span>
                        </div>
                        <label for="officc_contact_no" class="col-sm-2 control-label"><?php echo __('lblofccontact'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('officc_contact_no', array('label' => false, 'id' => 'officc_contact_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="officc_contact_no_error" class="form-error"><?php echo $errarr['officc_contact_no_error']; ?></span>
                        </div>
                        <label for="office_email_id" class="col-sm-2 control-label"><?php echo __('lblofcemail'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_email_id', array('label' => false, 'id' => 'office_email_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '50')) ?>
                            <span id="office_email_id_error" class="form-error"><?php echo $errarr['office_email_id_error']; ?></span>
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
                        <label for="reporting_office_id" class="col-sm-2 control-label"><?php echo __('lblreportingoffice'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('reporting_office_id', array('label' => false, 'id' => 'reporting_office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $reportingofficedata))) ?>
                            <span id="reporting_office_id_error" class="form-error"><?php //echo $errarr['reporting_office_id_error'];  ?></span>
                        </div>
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
                        <label for="shift_id" class="col-sm-2 control-label">Select Office Slot<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('slot_id', array('label' => false, 'id' => 'slot_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $timeslot))); ?>
                            <span id="slot_id_error" class="form-error"><?php //echo $errarr['slot_id_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>

                        <button  id="btncancel" name="btncancel" class="btn btn-info" onclick="javascript: return formcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
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

                                <th class="center width10"><?php echo __('lblcity'); ?></th>    
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
                                    <td><?php echo $officerecord1[0]['city']; ?></td>    
                                    <td>
                                        <button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(('<?php echo $officerecord1[0]['dept_id']; ?>'),
                                        <?php foreach ($languagelist as $langcode) { ?>
                                                            ('<?php echo $officerecord1[0]['office_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                        ('<?php echo $officerecord1[0]['flat']; ?>'),
                                                                ('<?php echo $officerecord1[0]['building']; ?>'),
                                                                ('<?php echo $officerecord1[0]['road']; ?>'),
                                                                ('<?php echo $officerecord1[0]['locality']; ?>'),
                                                                ('<?php echo $officerecord1[0]['city']; ?>'),
                                                                ('<?php echo $officerecord1[0]['taluka_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['district_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['state_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['pincode']; ?>'),
                                                                ('<?php echo $officerecord1[0]['officc_contact_no']; ?>'),
                                                                ('<?php echo $officerecord1[0]['office_email_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['reporting_office_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['shift_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['hierarchy_id']; ?>'),
                                                                 ('<?php echo $officerecord1[0]['village_id']; ?>'),
                                                                  ('<?php echo $officerecord1[0]['slot_id']; ?>'),
                                                                ('<?php echo $officerecord1[0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_officenew', $officerecord1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
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
</div>
