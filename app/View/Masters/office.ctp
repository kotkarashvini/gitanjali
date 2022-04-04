<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<!--<script>

    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });
</script>-->

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {

        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tableoffice1').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        var div = '<?php echo $configure[0][0]['is_div']; ?>';
        var dist = '<?php echo $configure[0][0]['is_dist']; ?>';
        var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
        var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
        var circle = '<?php echo $configure[0][0]['is_block']; ?>';
        if (div == 1) {
            $("#division_id").prop("disabled", false);
        } else if (dist == 1) {
            $("#state_id").prop("disabled", false);
        } else if (subdiv == 1) {
            $("#subdivision_id").prop("disabled", false);
        } else if (tal == 0 && level3 == 1) {
            $("#taluka_id").prop("disabled", false);
        } else if (circle == 0 && level3 == 0 && level4 == 1) {
            $("#circle_id").prop("disabled", false);
        }
//       alert(circle);
        //district
        $('#division_id').change(function () {
//             alert('hii');
            var div = $("#division_id option:selected").val();
            $.getJSON('getdist', {div: div}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (dist == 1) {
                    $("#state_id").prop("disabled", false);
                    $("#state_id option").remove();
                    $("#state_id").append(sc);
                } else if (dist == 0 && subdiv == 1) {
                    $("#subdivision_id").prop("disabled", false);
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                } else if (dist == 0 && subdiv == 0 && tal == 1) {
                    $("#taluka_id").prop("disabled", false);
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                } else if (dist == 0 && subdiv == 0 && tal == 0 && circle == 1) {
                    $("#circle_id").prop("disabled", false);
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id").prop("disabled", false);
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });
        $('#state_id').change(function () {
            var dist_head = $("#state_id option:selected").val();
            $('#state_id').val(dist_head);
        });
        //sub division
        $('#state_id').change(function () {
            var dist = $("#state_id option:selected").val();
            $.getJSON('getsubdiv', {dist: dist}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (subdiv == 1) {
                    $("#subdivision_id").prop("disabled", false);
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                } else if (subdiv == 0 && tal == 1) {
                    $("#taluka_id").prop("disabled", false);
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                } else if (subdiv == 0 && tal == 0 && circle == 1) {
                    $("#circle_id").prop("disabled", false);
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id").prop("disabled", false);
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });
        $('#subdivision_id').change(function () {

            var subdiv_head = $("#subdivision_id option:selected").val();
            $('#subdivision_id').val(subdiv_head);
        });
        // Taluka
        $('#subdivision_id').change(function () {
            var subdiv = $("#subdivision_id option:selected").val();
            var i;
            $.getJSON('gettalukaname', {subdiv: subdiv}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (tal == 1) {
                    $("#taluka_id").prop("disabled", false);
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                } else if (tal == 0 && circle == 1) {
                    $("#circle_id").prop("disabled", false);
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id").prop("disabled", false);
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });
        $('#taluka_id').change(function () {

            var tal_head = $("#taluka_id option:selected").val();
            $('#taluka_id').val(tal_head);
        });
        // Circle
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            var i;
            $.getJSON('getcircle', {tal: tal}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (circle == 1) {
                    $("#circle_id").prop("disabled", false);
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id").prop("disabled", false);
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });
        $('#circle_id').change(function () {

            var tal_head = $("#circle_id option:selected").val();
            $('#circle_id').val(tal_head);
        });
        // Governing Body
        $('#circle_id').change(function () {
//            alert($('#circle_id').val());
            var ulb = $("#circle_id option:selected").val();
            var i;
            $.getJSON('getulb', {ulb: ulb}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#ulb_type_id").prop("disabled", false);
                $("#ulb_type_id option").remove();
                $("#ulb_type_id").append(sc);
            });
        });
        $('#ulb_type_id').change(function () {

            var ulb_head = $("#ulb_type_id option:selected").val();
            $('#ulb_type_id').val(ulb_head);
        });
        //Village
        $('#ulb_type_id').change(function () {
//            alert($('#ulb_type_id').val());
            var vil = $("#ulb_type_id option:selected").val();
            var i;
            $.getJSON('getvillage', {vil: vil}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        });
        $('#village_id').change(function () {

            var village_head = $("#village_id option:selected").val();
            $('#village_id').val(village_head);
        });
        // LOCATION
        var level1 = '<?php echo $configure1[0]['levelconfig']['is_level_1_id']; ?>';
        var level2 = '<?php echo $configure1[0]['levelconfig']['is_level_2_id']; ?>';
        var level3 = '<?php echo $configure1[0]['levelconfig']['is_level_3_id']; ?>';
        var level4 = '<?php echo $configure1[0]['levelconfig']['is_level_4_id']; ?>';
        if (level1 == 1) {
            $("#level_1_desc_eng").prop("disabled", false);
        } else if (level2 == 1) {
            $("#level_2_desc_eng").prop("disabled", false);
        } else if (level2 == 0 && level3 == 1) {
            $("#level_3_desc_eng").prop("disabled", false);
        } else if (level2 == 0 && level3 == 0 && level4 == 1) {
            $("#level_4_desc_eng").prop("disabled", false);
        }

        //village drop down list code
        $('#village_id').change(function () {
            var landtype = $("#village_id option:selected").val();
            var i;
            $.getJSON('getlandtype', {landtype: landtype}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#Developedland").prop("disabled", false);
                $("#Developedland option").remove();
                $("#Developedland").append(sc);
            });
        });
        $('#Developedland').change(function () {

            var develop_head = $("#Developedland option:selected").val();
            $('#Developedland').val(develop_head);
        });
        //level 1 dropdown list code
        $('#level_1_desc_eng').change(function () {

            var level1_list = $("#level_1_desc_eng option:selected").val();
            $.getJSON('getlevel1_list', {level1_list: level1_list}, function (data)
            {
                var sc = '<option>select</option>';
                var sc1 = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });
                if (level2 == 1) {
                    $("#list_1_desc_eng").prop("disabled", false);
                    $("#list_1_desc_eng option").remove();
                    $("#list_1_desc_eng").append(sc);
                    $("#level_2_desc_eng").prop("disabled", false);
                    $("#level_2_desc_eng option").remove();
                    $("#level_2_desc_eng").append(sc1);
                } else if (level2 == 0 && level3 == 1) {
                    $("#list_1_desc_eng").prop("disabled", false);
                    $("#list_1_desc_eng option").remove();
                    $("#list_1_desc_eng").append(sc);
                    $("#level_3_desc_eng").prop("disabled", false);
                    $("#level_3_desc_eng option").remove();
                    $("#level_3_desc_eng").append(sc1);
                } else if (level2 == 0 && level3 == 0 && level4 == 1) {
                    $("#list_1_desc_eng").prop("disabled", false);
                    $("#list_1_desc_eng option").remove();
                    $("#list_1_desc_eng").append(sc);
                    $("#level_4_desc_eng").prop("disabled", false);
                    $("#level_4_desc_eng option").remove();
                    $("#level_4_desc_eng").append(sc1);
                }
            });
        });
        //level 2 dropdown list code
        $('#level_2_desc_eng').change(function () {
            var level2_list = $("#level_2_desc_eng option:selected").val();
            var i;
            $.getJSON('getlevel2_list', {level2_list: level2_list}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                var sc1 = '<option>select</option>';
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });
                if (level3 == 1) {
                    $("#list_2_desc_eng").prop("disabled", false);
                    $("#list_2_desc_eng option").remove();
                    $("#list_2_desc_eng").append(sc);
                    $("#level_3_desc_eng").prop("disabled", false);
                    $("#level_3_desc_eng option").remove();
                    $("#level_3_desc_eng").append(sc1);
                } else {
                    $("#list_2_desc_eng").prop("disabled", false);
                    $("#list_2_desc_eng option").remove();
                    $("#list_2_desc_eng").append(sc);
                    $("#level_4_desc_eng").prop("disabled", false);
                    $("#level_4_desc_eng option").remove();
                    $("#level_4_desc_eng").append(sc1);
                }
            });
        });
        //level 3 dropdown list code
        $('#level_3_desc_eng').change(function () {
            var level3_list = $("#level_3_desc_eng option:selected").val();
            var i;
            $.getJSON('getlevel3_list', {level3_list: level3_list}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#list_3_desc_eng").prop("disabled", false);
                $("#list_3_desc_eng option").remove();
                $("#list_3_desc_eng").append(sc);
                var sc4 = '<option>select</option>';
                $.each(data.data2, function (index, val) {
                    sc4 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level_4_desc_eng").prop("disabled", false);
                $("#level_4_desc_eng option").remove();
                $("#level_4_desc_eng").append(sc4);
            });
        });
        //level 4 dropdown list code
        $('#level_4_desc_eng').change(function () {
            var level4_list = $("#level_4_desc_eng option:selected").val();
            $.getJSON('getlevel4_list', {level4_list: level4_list}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#list_4_desc_eng").prop("disabled", false);
                $("#list_4_desc_eng option").remove();
                $("#list_4_desc_eng").append(sc);
            });
        });
        $('#list_4_desc_eng').change(function () {
            var develop_head = $("#list_4_desc_eng option:selected").val();
            $('#list_4_desc_eng').val(develop_head);
        });
        $('#level_3_desc_eng').change(function () {
            var develop_head = $("#level_3_desc_eng option:selected").val();
            $('#level_3_desc_eng').val(develop_head);
        });
        $('#level_2_desc_eng').change(function () {
            var develop_head = $("#level_2_desc_eng option:selected").val();
            $('#level_2_desc_eng').val(develop_head);
        });
    });
    function formsave() {
        document.getElementById("actiontype").value = '1';
    }

    function formupdate(id, office_code, hierarchy_id, office_name_en, office_name_ll, division_id
            , district_id, subdivision_id, taluka_id, circle_id, ulb_type_id, village_id
            , developed_land_types_id, level1_id, level1_list_id, level2_id, level2_list_id
            , level3_id, level3_list_id, level4_id, level4_list_id, address, pincode, contact_person
            , designation, mobile_no, office_no, office_shift, reporting_office) {

        $("#state_id").prop("disabled", false);
        $("#subdivision_id").prop("disabled", false);
        $("#taluka_id").prop("disabled", false);
        $("#circle_id").prop("disabled", false);
        $("#ulb_type_id").prop("disabled", false);
        $("#village_id").prop("disabled", false);
        $("#Developedland").prop("disabled", false);
        $("#level_1_desc_eng").prop("disabled", false);
        $("#level_2_desc_eng").prop("disabled", false);
        $("#level_3_desc_eng").prop("disabled", false);
        $("#level_4_desc_eng").prop("disabled", false);
        $("#list_1_desc_eng").prop("disabled", false);
        $("#list_2_desc_eng").prop("disabled", false);
        $("#list_3_desc_eng").prop("disabled", false);
        $("#list_4_desc_eng").prop("disabled", false);
        $("#btndelete" + id).prop("disabled", true);
        $("#btnupdate" + id).css('background-color', 'red');
        ;

        $('#hfid').val(id);
        $('#office_code').val(office_code);
        $('#hierarchy_id').val(hierarchy_id);
        $('#office_name_en').val(office_name_en);
        $('#office_name_ll').val(office_name_ll);
        $('#division_id').val(division_id);
        $('#state_id').val(district_id);
        $('#subdivision_id').val(subdivision_id);
        $('#taluka_id').val(taluka_id);
        $('#circle_id').val(circle_id);
        $('#ulb_type_id').val(ulb_type_id);
        $('#village_id').val(village_id);
        $('#Developedland').val(developed_land_types_id);
        $('#level_1_desc_eng').val(level1_id);
        $('#list_1_desc_eng').val(level1_list_id);
        $('#level_2_desc_eng').val(level2_id);
        $('#list_2_desc_eng').val(level2_list_id);
        $('#level_3_desc_eng').val(level3_id);
        $('#list_3_desc_eng').val(level3_list_id);
        $('#level_4_desc_eng').val(level4_id);
        $('#list_4_desc_eng').val(level4_list_id);
        $('#address').val(address);
        $('#pincode').val(pincode);
        $('#contact_person').val(contact_person);
        $('#desg_id').val(designation);
        $('#mobile_no').val(mobile_no);
        $('#office_no').val(office_no);
        $('#office_shift').val(office_shift);
        $('#reporting_office').val(reporting_office);
        $('#hfupdateflag').val('Y');
        return false;
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '2';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }
</script> 


<?php echo $this->Form->create('office', array('type' => 'file', 'class' => 'office', 'autocomplete' => 'off', 'id' => 'office')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lbloffice'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="office_code" class="control-label col-sm-2"><?php echo __('lblofficecode'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('office_code', array('label' => false, 'id' => 'office_code', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                            <div class="col-sm-1"></div>
                            <label for="hierarchy_id"class="control-label col-sm-2"><?php echo __('lblofficehierarchy'); ?></label>
                            <div class="col-sm-2" ><?php echo $this->Form->input('hierarchy_id', array('options' => array('empty' => '--select--', $hierarchydata), 'id' => 'hierarchy_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="mobile_no" class="control-label col-sm-2"><?php echo __('lblofficename'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('office_name_en', array('label' => false, 'id' => 'office_name_en', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                            <div class="col-sm-1"></div>
                            <label for="office_no"class="control-label col-sm-2"><?php echo __('lblofficenamell'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('office_name_ll', array('label' => false, 'id' => 'office_name_ll', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive" id="selectoffice">
                            <table id="tableoffice" class="table table-striped table-bordered table-hover">  
                                <thead >  
                                    <tr>  
                                        <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                            <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmdivision'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmdistrict'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmsubdivision'); ?> </td><?php } ?>
                                            <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmtaluka'); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmcircle'); ?> </td><?php } ?>
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblCorporationClass'); ?> </td>
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmvillage'); ?> </td> 
                                        <?php }
                                        ?>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                        <tr>
                                            <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                            <td  style="text-align: center"><?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'state_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('subdivision_id', array('options' => $subdivisiondata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('circle_id', array('options' => $blockdata, 'empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <td style="text-align: center"><?php echo $this->Form->input('ulb_type_id', array('options' => $corpclassdata, 'empty' => '--select--', 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td>
                                            <td style="text-align: center"><?php echo $this->Form->input('village_id', array('options' => $villagenname, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive" id="village">
                            <table id="tablevillage" class="table table-striped table-bordered table-hover">  
                                <thead >  
                                    <?php for ($i = 0; $i < count($configure1); $i++) { ?>
                                        <tr> 
                                            <?php if ($configure1[$i]['levelconfig']['is_developed_land_types_id'] == 'Y') { ?>
                                                <td style="text-align: center;width:11%; font-weight:bold; "><?php echo __('lblLandType'); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_1_id'] == 'Y') { ?>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel1'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel1list'); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_2_id'] == 'Y') { ?>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel2'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel2list'); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_3_id'] == 'Y') { ?>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel3'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel3list'); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_4_id'] == 'Y') { ?>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel4'); ?></td>
                                                <td style="text-align: center;width:11%; font-weight:bold;"><?php echo __('lblLevel4list'); ?></td><?php } ?>
                                        </tr> 
                                    <?php } ?>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($configure1); $i++) { ?>
                                        <tr>
                                            <?php if ($configure1[$i]['levelconfig']['is_developed_land_types_id'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('developed_land_types_id', array('options' => array('empty' => '--select--', $Developedland), 'id' => 'Developedland', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_1_id'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('level1_id', array('options' => array('empty' => '--select--', $level1propertydata), 'id' => 'level_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>

                                                <td style="text-align: center"><?php echo $this->Form->input('level1_list_id', array('options' => array($level1propertylist), 'empty' => '--select--', 'id' => 'list_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_2_id'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('level2_id', array('options' => array('empty' => '--select--', $level2propertydata), 'id' => 'level_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>

                                                <td style="text-align: center"><?php echo $this->Form->input('level2_list_id', array('options' => array($level2propertylist), 'empty' => '--select--', 'id' => 'list_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_3_id'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('level3_id', array('options' => array('empty' => '--select--', $level3propertydata), 'id' => 'level_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>

                                                <td style="text-align: center"><?php echo $this->Form->input('level3_list_id', array('options' => array($level3propertylist), 'empty' => '--select--', 'id' => 'list_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                            <?php if ($configure1[$i]['levelconfig']['is_level_4_id'] == 'Y') { ?>
                                                <td style="text-align: center"><?php echo $this->Form->input('level4_id', array('options' => array('empty' => '--select--', $level4propertydata), 'id' => 'level_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>

                                                <td style="text-align: center"><?php echo $this->Form->input('level4_list_id', array('options' => array($level4propertylist), 'empty' => '--select--', 'id' => 'list_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="address" class="control-label col-sm-2"><?php echo __('lblAddress'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                            <label for="pincode"class="control-label col-sm-2"><?php echo __('lblpincode'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('pincode', array('label' => false, 'id' => 'pincode', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                            <label for="contact_person" class="control-label col-sm-2"><?php echo __('lblcontactperson'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('contact_person', array('label' => false, 'id' => 'contact_person', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="designation"class="control-label col-sm-2"><?php echo __('lbldesignation'); ?></label>
                            <div class="col-sm-2" ><?php echo $this->Form->input('designation', array('options' => array('empty' => '--select--', $designation), 'id' => 'desg_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                            <label for="mobile_no" class="control-label col-sm-2"><?php echo __('lblmobileno'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                            <label for="office_no"class="control-label col-sm-2"><?php echo __('lblofficeno'); ?></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('office_no', array('label' => false, 'id' => 'office_no', 'type' => 'text', 'class' => 'form-control input-sm')); ?></div>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="office_shift" class="control-label col-sm-2"><?php echo __('lblofficeshift'); ?></label>
                            <div class="col-sm-2" ><?php echo $this->Form->input('office_shift', array('options' => array('empty' => '--select--', $workshift), 'id' => 'office_shift', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                            <label for="reporting_office"class="control-label col-sm-2"><?php echo __('lblreportingoffice'); ?></label>
                            <div class="col-sm-2" ><?php echo $this->Form->input('reporting_office', array('options' => array('empty' => '--select--', $reportingofficedata), 'id' => 'reporting_office', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button id="btnsave" name="btnsave" class="btn btn-primary " onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                            <input type="button" class="btn btn-primary " value="<?php echo __('btncancel'); ?>" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Masters', 'action' => 'office')); ?>';"/>
                            <input type="button" class="btn btn-primary " value="<?php echo __('lblexit'); ?>" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'welcome')); ?>';"/>
                        </div>
                    </div>
                </div>
                </div>
                            <div class="panel-heading" style="text-align: center"><b><?php echo __('lbloffice'); ?></b></div>
                            <div class="panel-body">
                                <div class="table-responsive" id="selectoffice">
                                    <table id="tableoffice1" class="table table-striped table-bordered table-hover">  
                                        <thead >  
                                            <tr>  
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblofficecode'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblofficehierarchy'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblofficenameeng'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblcontactperson'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbldesignation'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblofficeshift'); ?></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($grid); $i++) { ?>   
                                                <tr>
                                                    <td style="text-align: center;"><?php echo $grid[$i][0]['office_code']; ?></td>
                                                    <td style="text-align: center;"><?php echo $grid[$i][0]['hierarchy_desc']; ?></td>
                                                    <td style="text-align: center;"><?php echo $grid[$i][0]['office_name_' . $language]; ?></td>
                                                    <td style="text-align: center;"><?php echo $grid[$i][0]['contact_person']; ?></td>
                                                    <td style="text-align: center;"><?php echo $grid[$i][0]['desg_desc_en']; ?></td>
                                                    <td style="text-align: center;"><?php echo $grid[$i][0]['shift_desc_eng']; ?></td>
                                                    <td class="tdselect" style="text-align: center;">
                                                        <button id="btnupdate<?php echo $grid[$i][0]['id']; ?>" name="btnupdate" class="btn btn-default" onclick="javascript: return formupdate('<?php echo $grid[$i][0]['id']; ?>', '<?php echo $grid[$i][0]['office_code']; ?>', '<?php echo $grid[$i][0]['hierarchy_id']; ?>', '<?php echo $grid[$i][0]['office_name_en']; ?>', '<?php echo $grid[$i][0]['office_name_ll']; ?>'
                                                                        , '<?php echo $grid[$i][0]['division_id']; ?>', '<?php echo $grid[$i][0]['district_id']; ?>', '<?php echo $grid[$i][0]['subdivision_id']; ?>', '<?php echo $grid[$i][0]['taluka_id']; ?>'
                                                                        , '<?php echo $grid[$i][0]['circle_id']; ?>', '<?php echo $grid[$i][0]['ulb_type_id']; ?>', '<?php echo $grid[$i][0]['village_id']; ?>', '<?php echo $grid[$i][0]['developed_land_types_id']; ?>'
                                                                        , '<?php echo $grid[$i][0]['level1_id']; ?>', '<?php echo $grid[$i][0]['level1_list_id']; ?>', '<?php echo $grid[$i][0]['level2_id']; ?>', '<?php echo $grid[$i][0]['level2_list_id']; ?>'
                                                                        , '<?php echo $grid[$i][0]['level3_id']; ?>', '<?php echo $grid[$i][0]['level3_list_id']; ?>', '<?php echo $grid[$i][0]['level4_id']; ?>', '<?php echo $grid[$i][0]['level4_list_id']; ?>'
                                                                        , '<?php echo $grid[$i][0]['address']; ?>', '<?php echo $grid[$i][0]['pincode']; ?>', '<?php echo $grid[$i][0]['contact_person']; ?>', '<?php echo $grid[$i][0]['designation']; ?>'
                                                                        , '<?php echo $grid[$i][0]['mobile_no']; ?>', '<?php echo $grid[$i][0]['office_no']; ?>', '<?php echo $grid[$i][0]['office_shift']; ?>', '<?php echo $grid[$i][0]['reporting_office']; ?>')">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                        <button id="btndelete<?php echo $grid[$i][0]['id']; ?>" name="btndelete" class="btn btn-default " onclick="javascript: return formdelete('<?php echo $grid[$i][0]['id']; ?>')">
                                                            <span class="glyphicon glyphicon-remove"></span></button></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
                                    <?php  if (!empty($grid)) { ?>
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
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
