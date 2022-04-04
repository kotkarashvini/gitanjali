<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {

        $("#confrmvrifycode").hide();
        $("#divsaverecord").hide();
        $('#district_id').change(function () {

            var dist = $("#district_id option:selected").val();

            getTaluka(dist);
        });
        $("#btnVerify").click(function () {
            var villagemappingcode = $("#villagemappingcode").val();
            $.post('<?php echo $this->webroot; ?>Masters/Verifycode', {villagemappingcode: villagemappingcode}, function (data1)
            {
                if (data1) {
                    alert(data1);
                    if (data1 == 'Verified Successfully...! Please Enter Confirm Village Mapping Code.')
                    {
                        $("#confrmvrifycode").show();
                        $("#vrifycode1").hide();
                    }
                }
                // return false;
            });
        });
        $('#corp_id').change(function () {
            var corp = $("#corp_id option:selected").val();
            $("#office_id").val($("#target option:first").val());

            $.postJSON('<?php echo $this->webroot; ?>Property/corp_change_event_new', {corp: corp, taluka_id: $("#taluka_id").val(), circle_id: $('#circle_id').val()}, function (data)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc2);
                $("#lblvillage_id").hide();
                $("#lblcitytown").show();
                $("#villagetable").show();
                //display checkbox
                $("#checkboxes").html('');
                var html = '';
                var f = 0;
                $.each(data.village, function (index, val) {
                    if (f == 0)
                    {
                        f++;
                        html = html + "<tr><th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label></th>";
                    } else if (f == 1)
                    {
                        f++;
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                    } else {
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th></tr>";
                        f = 0;
                    }

                });
                if (f == 1)
                {
                    html = html + "<td></td><td></td></tr>";
                } else if (f == 2)
                {
                    html = html + "<td></td></tr>";
                }

//                $('#checkboxestbl').DataTable().destroy();
                $("#checkboxes").html(html);
//                $('#checkboxestbl').show();
                $('#listbox').show();
//                $('#checkboxestbl').dataTable({
//                    "iDisplayLength": 5,
//                    "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//                });

            });




        });
        $('#office_id').change(function () {
            var office = $("#office_id option:selected").val();

            $.getJSON('<?php echo $this->webroot; ?>Masters/get_office_data', {office: office}, function (data)
            {

                $("input:checkbox").each(function () {
                    $(this).prop('checked', false);
                });


                $('#aaaa').find('option:not(:first)').remove();

                $.each(data, function (index, val) {
                    $("input:checkbox").each(function () {
                        var cval = $(this).val();
                        if (val == cval)
                        {
                            $(this).prop('checked', true);
                            $('#aaaa').append($('<option>', {
                                value: index,
                                text: $(this).next('label').text()
                            }));

                        }
                    });
                });

            });
        });
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            $("#office_id").val($("#target option:first").val());
            $.getJSON('<?php echo $this->webroot; ?>Property/taluka_change_event_new', {tal: tal}, function (data)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";


                    $("#villagetable").show();
                    //display checkbox
                    $("#checkboxes").html('');
                    var html = '';
                    var f = 0;
                    $.each(data.village, function (index, val) {
                        if (f == 0)
                        {
                            f++;
//                            alert(f);
                            html = html + "<tr><th><input name='village_id[]' type='checkbox' value=' " + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                        } else if (f == 1)
                        {
                            f++;
//                            alert(f);
                            html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                        } else {
                            html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th></tr>";
                            f = 0;
                        }
//                 alert(html);
                    });
                    if (f == 1)
                    {
                        html = html + "<td></td><td></td></tr>";
                    } else if (f == 2)
                    {
                        html = html + "<td></td></tr>";
                    }

//                $('#checkboxestbl').DataTable().destroy();
                    $("#checkboxes").html(html);
                    $('#checkboxestbl').show();
                    $('#vrifycode1').show();

//            checkboxestbl        $('#checkboxestbl').destroy();

//                $('#checkboxestbl').dataTable({
//                    "iDisplayLength": 5,
//                    "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//                });

                });

                var sc = '<option>--select--</option>';
                $.each(data.circle, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#circle_id").prop("disabled", false);
                $("#circle_id option").remove();
                $("#circle_id").append(sc);
            });

        });
        $('#division_id').change(function () {
            var division_id = $('#division_id').val();
            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getdist', {division_id: division_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        });
        $('#circle_id').change(function () {
            var circle_id = $('#circle_id').val();
            $("#office_id").val($("#target option:first").val());
            $.postJSON('<?php echo $this->webroot; ?>Office/getcirclevillage', {circle_id: circle_id}, function (data)
            {
                $("#checkboxes").html('');
                var html = '';
                var f = 0;
                $.each(data.village, function (index, val) {
                    if (f == 0)
                    {
                        f++;
                        html = html + "<tr><th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label></th>";
                    } else if (f == 1)
                    {
                        f++;
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                    } else {
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th></tr>";
                        f = 0;
                    }

                });
                if (f == 1)
                {
                    html = html + "<td></td><td></td></tr>";
                } else if (f == 2)
                {
                    html = html + "<td></td></tr>";
                }

//                $('#checkboxestbl').DataTable().destroy();
                $("#checkboxes").html(html);
            });
        });


        $('#joffice_id').change(function () {
            var office = $("#joffice_id option:selected").val();
            var html = '';
            $.post('<?php echo $this->webroot; ?>Office/get_office_linked_villages', {office: office}, function (data)
            {              
               $("#linkedvillages").html(data); 
            });
        });

    });
    function getTaluka(dist) {
        //alert(dist);
        $.getJSON("<?php echo $this->webroot; ?>Property/district_change_event_new", {dist: dist}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.taluka, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id").prop("disabled", false);
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);

            var sc = '<option>--select--</option>';
            $.each(data.corp, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#corp_id").prop("disabled", false);
            $("#corp_id option").remove();
            $("#corp_id").append(sc);


            var sc = '<option>--select--</option>';
            $.each(data.subdiv, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#subdivision_id").prop("disabled", false);
            $("#subdivision_id option").remove();
            $("#subdivision_id").append(sc);

        });



    }
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formconfrmVerify() {
//        var conf = confirm('Are You Sure to Changes...!');
//        if (!conf) {
//            return false;
//        } else {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'V';

//        }

    }
    function removeSubRule(rid, rsid) {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()))
        });
        var conf = confirm('Are You Sure to delete this subrule');
        if (!conf) {
            return false;
        } else {
            $.ajax({
                type: 'post',
                url: host + 'removeFeeSubRule',
                data: {fee_rule_id: rid, fee_sub_rule_id: rsid},
                success: function (result)
                {
                    if (result == 1) {
                        $("#subrule_" + rsid).fadeOut(300);
                    } else
                        alert(result);
                }
            });

            return false;
        }
    }
    
    function jurisdictionsetyes(office_id,village_id){
          $.post('<?php echo $this->webroot; ?>Office/set_jurisdiction_flag', {flag: 'Yes',office_id:office_id,village_id:village_id}, function (data)
            {
                if(data==1){
                  alert('Jurisdiction sets Successfully!');  
                }
                
            });
    }
    function jurisdictionsetno(office_id,village_id){
        $.post('<?php echo $this->webroot; ?>Office/set_jurisdiction_flag', {flag: 'No',office_id:office_id,village_id:village_id}, function (data)
            {
                if(data==1){
                  alert('Jurisdiction sets Successfully!');  
                }
                
            });
    }


</script>
<script>
    $(document).ready(function () {
        
        $('#villagelist').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script> 
 

<?php echo $this->Form->create('office_village_linking', array('id' => 'office_village_linking')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofclvljlink'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Office/office_village_linking_en.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <?php if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                        ?>
                        <div class="col-sm-2">
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span class="star">*</span></label>
                            <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="division_id_error"></span>
                        </div>
                    <?php } ?>
                    <div class="col-sm-2">
                        <label for="" class="control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <!--<div class="col-sm-3">-->
                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                        <span id="district_id_error" class="form-error"></span>
                        <!--</div>-->
                    </div>

                    <?php if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdiv'); ?> <span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('subdivision_id', array('options' => $subdivisiondata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="subdivision_id_error"></span>
                        </div>
                    <?php } ?>

                    <div class="col-sm-2">
                        <label for="" class="control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
                        <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                        <span id="taluka_id_error" class="form-error"></span>
                    </div>   
                    <?php if ($adminLevelConfig['adminLevelConfig']['is_circle'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="circle_id" class="control-label"><?php echo __('lbladmcircle'); ?><span style="color: #ff0000">*</span> </label>
                            <?php echo $this->Form->input('circle_id', array('options' => $circledata, 'empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="circle_id_error"></span>
                        </div>
                    <?php } ?>
                </div>
                <div  class="rowht"></div> 

                <div class="row">
                    <!--<div class="form-group">-->

                    <div class="col-sm-2">
                        <label for="" class="control-label"><?php echo __('lblgovbodylistname'); ?></label> 
                        <?php echo $this->Form->input('corp_id', array('options' => $corpclasslist, 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>

                        <span id="corp_id_error" class="form-error"></span>
                    </div>

                    <div class="col-sm-2">
                        <label for="" class="control-label"><?php echo __('lblselofc'); ?><span style="color: #ff0000">*</span></label>  
                        <?php echo $this->Form->input('office_id', array('options' => array($officedata), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="office_id_error" class="form-error"></span>
                    </div>
                    <!--                    <div class="col-sm-2">
                                            <label for="" class="control-label"><?php echo __('lbljurisdiction_flag'); ?><span style="color: #ff0000">*</span></label>  
                    <?php
                    // $jurisdiction['Y']=__('lblyes');
                    // $jurisdiction['N']=__('lblno');
                    ?>
                    <?php echo $this->Form->input('jurisdiction_flag', array('options' => array($jurisdiction), 'empty' => '--select--', 'id' => 'jurisdiction_flag', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                            <span id="jurisdiction_flag_error" class="form-error"></span>
                                        </div>-->


                    <!--</div>-->
                </div>

                <div  class="rowht"></div> 
                <br>
                <div class="row" id="temp">
                    <div id="checkboxestemp">


                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10 ">
                        <table class="table" id="checkboxestbl" hidden="true">
                            <thead>
                                <tr>
                                    <th id="villagetable" width="30%" ><?php echo __('lblselvillage'); ?></th>
                                    <th  id="selectall" width="30%">Select All 

                                    </th>
                                    <th  width="30%"></th>
                                </tr>
                            </thead>
                            <tbody id="checkboxes">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr> 

                            </tbody>



                        </table> 

                    </div>

                    <!--                    <div class="col-sm-1"></div>
                                        <div class="col-sm-4" id="listbox" hidden="true">
                    <?php echo $this->Form->input('zazzz', array('type' => 'select', 'id' => 'aaaa', 'class' => 'form-control input-sm', 'label' => false, 'multiple' => 'multiple')); ?>
                    
                                        </div> -->
                </div>
                <br>       
                <div class="row " id="vrifycode1" hidden="true">
                    <div class="form-group">



                        <label for="villagemappingcode" class="col-sm-2 control-label"> <?php echo __('lblEnterVillageMappingCode'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('villagemappingcode', array('label' => false, 'id' => 'villagemappingcode', 'class' => 'form-control input-sm', 'type' => 'password', 'maxlength' => '10', 'autocomplete' => 'off')) ?>
                            <span id="villagemappingcode_error" class="form-error"><?php //echo $errarr['villagemappingcode_error'];                 ?></span>

                        </div>
                        <div class="col-sm-3">
                            <input type="button" id='btnVerify' class="btn btn-info " name="Verify" value="Verify">

                            <!--                        <button id="btnVerify" name="btnVerify" class="btn btn-info"  >
                                                            <span class="glyphicon glyphicon-plus"></span>Verify</button>-->
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row " id="confrmvrifycode" class="btn btn-info" hidden="true">
                    <div class="form-group">
                        <label for="villagemappingcode" class="col-sm-3 control-label"> <?php echo __('lblCofirmVillageMappingCode'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('confrmvillagemappingcode1', array('label' => false, 'id' => 'confrmvillagemappingcode1', 'class' => 'form-control input-sm', 'type' => 'password', 'maxlength' => '10', 'autocomplete' => 'off')) ?>
                            <!--<span id="emp_lname_error" class="form-error"><?php //echo $errarr['emp_lname_error'];                 ?></span>-->

                        </div>

                        <div class="col-sm-3">
                            <button id="btnconfrmVerify" name="btnconfrmVerify" class="btn btn-info "  onclick="javascript: return formconfrmVerify();">
                                <span class="glyphicon glyphicon-plus"></span>Confirm & Save</button>
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>



                <!--                <div class="row ">
                                    <div id='divsaverecord'>
                                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                            <span class="glyphicon glyphicon-plus"></span><?php //echo __('btnsave');           ?></button>
                                        <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php //echo __('btncancel');           ?></button>
                
                                    </div>
                                </div>-->
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>

</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofficejurisdiction'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <label for="" class="control-label"><?php echo __('lblselofc'); ?><span style="color: #ff0000">*</span></label>  
                        <?php echo $this->Form->input('joffice_id', array('options' => array($officedata), 'empty' => '--select--', 'id' => 'joffice_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                        <span id="office_id_error" class="form-error"></span>
                    </div>                    
                </div>

                <div class="col-sm-12">
                    </br></br>
                    <table class="table" id="villagelist" >
                        <thead>
                            <tr>
                                <th id="villagetable" width="25%" ><?php echo __('lbladmdistrict'); ?></th>
                                 <th id="villagetable" width="25%" ><?php echo __('lbladmtaluka'); ?></th>
                                  <th id="villagetable" width="25%" ><?php echo __('lbladmvillage'); ?></th>
                                   <th id="villagetable" width="25%" ><?php echo __('lbljurisdiction_flag'); ?></th>

                                
                            </tr>
                        </thead>
                        <tbody id="linkedvillages">
                             

                        </tbody>



                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>