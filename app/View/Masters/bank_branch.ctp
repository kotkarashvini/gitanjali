<script>
    $(document).ready(function () {

    $('#tablebranch').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            $('#state_id').change(function () {
    var state = $("#state_id option:selected").val();
            var token = $("#token").val();
//            alert(token);
            var i;
            $.getJSON("<?php echo $this->webroot; ?>regdivision", {state: state, token: token}, function (data)
            {
//                alert(data);
            var sc = '<option value="empty">--Select Division--</option>';
                    $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#division_id option").remove();
                    $("#division_id").append(sc);
            });
            var i;
            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {state: state, token: token}, function (data)
            {
//                alert(data);
            var sc = '<option value="0">--Select District--</option>';
                    $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#district_id option").remove();
                    $("#district_id").append(sc);
            });
    })

            $('#division_id').change(function () {
    var division = $("#division_id option:selected").val();
            var token = $("#token").val();
//             alert(division);
            var i;
            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {division: division, token: token}, function (data)
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
            $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district, token: token}, function (data)
            {
            var sc = '<option value="empty">--Select Taluka--</option>';
                    $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
            });
    })
    });
            function formadd() {

            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formupdate(bank_id,
<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'branch_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    ifsc, micr_code, address, contact_no, state_id, division_id, district_id, taluka_id,city, id) {
    document.getElementById("actiontype").value = '1';
<?php foreach ($languagelist as $langcode) { ?>
        $('#branch_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(branch_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    var token = $("#token").val();
    $('#bank_id').val(bank_id);
            $('#ifsc').val(ifsc);
            $('#micr_code').val(micr_code);
            $('#address').val(address);
            $('#contact_no').val(contact_no);
            $('#state_id').val(state_id);
            
            
            
            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {state: state_id, token: token}, function (data)
            {
            var sc = '<option value="empty">--Select District--</option>';
                    $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#district_id option").remove();
                    $("#district_id").append(sc);
                    $('#district_id').val(district_id);
                    $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district_id, token: token}, function (data)
                    {
                    var sc = '<option value="empty">--Select Taluka--</option>';
                            $.each(data, function (index, val) {
                            sc += "<option value=" + index + ">" + val + "</option>";
                            });
                            $("#taluka_id option").remove();
                            $("#taluka_id").append(sc);
                          $('#taluka_id').val(taluka_id);
          });
            });
             $('#city').val(city);
            $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            
            return false;
    }
</script>
<?php echo $this->Form->create('bank_branch', array('id' => 'bank_branch')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblbankbranch'); ?> </h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/bank_branch_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <label for="bank_id" class="col-sm-2 control-label"><?php echo __('lblselectbank'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
<?php echo $this->Form->input('bank_id', array('label' => false, 'id' => 'bank_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $bank))); ?>
                            <span id="bank_id_error" class="form-error"><?php echo $errarr['bank_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
<?php
//creating dyanamic text boxes using same array of config language
foreach ($languagelist as $key => $langcode) {
    ?>
                            <div class="col-md-2">
                                <label><?php echo __('lblbankbranch') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
    <?php echo $this->Form->input('branch_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'branch_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                                <span id="<?php echo 'branch_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                            <?php echo $errarr['branch_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <label for="ifsc" class="col-sm-2 control-label"><?php echo __('lblifsccode'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
<?php echo $this->Form->input('ifsc', array('label' => false, 'id' => 'ifsc', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                            <span id="ifsc_error" class="form-error"><?php echo $errarr['ifsc_error']; ?></span>
                        </div>
                        <label for="micr_code" class="col-sm-2 control-label"><?php echo __('lblmicrcode'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
<?php echo $this->Form->input('micr_code', array('label' => false, 'id' => 'micr_code', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                            <span id="micr_code_error" class="form-error"><?php echo $errarr['micr_code_error']; ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <label for="ifsc" class="col-sm-2 control-label"><?php echo __('lblAddress'); ?>:-<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
<?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="address_error" class="form-error"><?php echo $errarr['address_error']; ?></span>
                        </div>
                        <label for="contact_no" class="col-sm-2 control-label"><?php echo __('lblcontactno'); ?>:-<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
<?php echo $this->Form->input('contact_no', array('label' => false, 'id' => 'contact_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "10")) ?>
                            <span id="contact_no_error" class="form-error"><?php echo $errarr['contact_no_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <label for="state" class="col-sm-2 control-label"><?php echo __('lbladmstate'); ?></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select State--', $State))); ?>
                            <span id="state_id_error" class="form-error"><?php echo $errarr['state_id_error']; ?></span>
                        </div>
                        <label for="Division" class="col-sm-2 control-label"><?php //echo __('lbladmdivision');  ?></label>    
                        <div class="col-sm-3">
<?php //echo $this->Form->input('division_id', array('label' => false, 'id' => 'division_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Division--')));  ?>
                            <span id="division_id_error" class="form-error"><?php //echo $errarr['division_id_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <label for="district" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select District--'))); ?>
                            <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
                        <label for="taluka" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?></label>
                        <div class="col-sm-3">
<?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select Taluka--'))); ?>
                            <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <label for="ifsc" class="col-sm-2 control-label"><?php echo __('lblcity'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
<?php echo $this->Form->input('city', array('label' => false, 'id' => 'city', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                            <span id="city_error" class="form-error"><?php echo $errarr['city_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
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
                    <table id="tablebranch" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center width10"><?php echo __('lblselectbank'); ?></th>

<?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblbankbranch') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
<?php } ?>
                                <th class="center width10"><?php echo __('lblifsccode'); ?></th>    
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
<?php foreach ($bankbranchrecord as $bankbranchrecord1) { ?>
                                <tr>

                                    <td><?php echo $bankbranchrecord1[0]['bank_name_en']; ?></td>

                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td ><?php echo $bankbranchrecord1[0]['branch_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
    <?php } ?>

                                    <td><?php echo $bankbranchrecord1[0]['ifsc']; ?></td>    
                                    <td>

                                        <button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(('<?php echo $bankbranchrecord1[0]['bank_id']; ?>'),
    <?php foreach ($languagelist as $langcode) { ?>
                                                        ('<?php echo $bankbranchrecord1[0]['branch_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                                    ('<?php echo $bankbranchrecord1[0]['ifsc']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['micr_code']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['address']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['contact_no']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['state_id']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['division_id']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['district_id']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['taluka_id']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['city']; ?>'),
                                                            ('<?php echo $bankbranchrecord1[0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>


                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_bankbranch', $bankbranchrecord1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>       
                                    <?php } ?>
                                    <?php unset($bankbranchrecord1); ?>
                        </tbody>
                    </table>
<?php if (!empty($bankbranchrecord)) { ?>
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