<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<?php
echo $this->element("Helper/jqueryhelper");
?> 
<script type="text/javascript">
    $(document).ready(function () {
        $("#prohibition_end_date").datepicker({
            yearRange: "-20:+100",
            changeMonth: true,
            changeYear: true,
            dateFormat: "mm-dd-yyyy"
        });
        $("#court_order_date").datepicker({
            yearRange: "-20:+100",
            changeMonth: true,
            changeYear: true,
            dateFormat: "mm-dd-yyyy"
        });


        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divproprodts').slideDown(1000);
        } else {
            $('#divproprodts').hide();
        }
        $('#tableproprodts').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('input[type=radio][name=is_clear]').change(function () {


            if (this.value == 'Y') {
                $('#divend').show();

            } else if (this.value == 'N') {
                $('#divend').hide();
                $('#prohibition_end_date').val('');
                $('#end_remark').val('');
//                $('input[name="is_clear"]').prop('checked', false);
            } else {
                $('#divend').hide();
            }
        });

        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();
            getTaluka(dist);

            $.postJSON('<?php echo $this->webroot; ?>Masters/get_corp_list', {district: dist}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data.corp, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#corporation_class_id option").remove();
                $("#corporation_class_id").prop("disabled", false);
                $("#corporation_class_id").append(sc);
            });

        });
        // Circle
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();

            $.postJSON('<?php echo $this->webroot; ?>Masters/taluka_change_event', {tal: tal}, function (data)
            {


                var sc = '<option value="">--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").prop("disabled", false);
                $("#village_id").append(sc);
            });
        });

        $('#corporation_class_id').change(function () {
            var corp = $("#corporation_class_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Masters/corp_change_event', {corp: corp}, function (data)
            {
                var sc2 = '<option value="">--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").prop("disabled", false);
                $("#village_id").append(sc2);
            });
        });



        //village drop down list code
        $('#village_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Masters/village_change_event', {village_id: village_id}, function (data)
            {

                var sc1 = '<option value="">--select--</option>';

                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";

                });
                $("#level1_id option").remove();
                $("#level1_id").prop("disabled", false);
                $("#level1_id").append(sc1);
            });
        });

        $('#level1_id').change(function () {
            var level1list = $("#level1_id option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Masters/Level1_change_event', {level1list: level1list, village_id: village_id}, function (data)
            {
                if (data['level1listflag'].toString() === '1') {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.data1, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#hflevel1list").val('1');
                    $("#level1_list_id option").remove();
                    $("#level1_list_id").append(sc);
                    $("#divlevel1list").fadeIn("slow");
                    $("#level1_list_id").prop("disabled", false);
                } else {
                    $("#divlevel1list").hide();
                    $("#divlevel2").hide();
                    $("#divlevel2list").hide();
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
                if (data['level2flag'].toString() === '1') {
                    var sc1 = '<option value="">--select--</option>';
                    $.each(data.data2, function (index, val) {
                        sc1 += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#hflevel2").val('1');
                    $("#level2_id option").remove();
                    $("#level2_id").append(sc1);
                    $("#divlevel2").fadeIn("slow");
                    $("#level2_id").prop("disabled", false);
                } else {
                    $("#divlevel2").hide();
                    $("#divlevel2list").hide();
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }

            });
        });

        if (document.getElementById("hflevel1list").value === '1') {
            $("#divlevel1list").show();
            $("#level1_list_id").prop("disabled", false);
        }
        if (document.getElementById("hflevel2").value === '1') {
            $("#divlevel2").show();
            $("#level2_id").prop("disabled", false);
        }

        //Level 2   
        $('#level2_id').change(function () {
            var level2list = $("#level2_id option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Masters/Level2_change_event', {level2list: level2list, village_id: village_id}, function (data)
            {
                if (data['level2listflag'].toString() === '1') {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.data1, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#hflevel2list").val('1');
                    $("#level2_list_id option").remove();
                    $("#level2_list_id").append(sc);
                    $("#divlevel2list").fadeIn("slow");
                    $("#level2_list_id").prop("disabled", false);
                } else {
                    $("#divlevel2list").hide();
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }

                if (data['level3flag'].toString() === '1') {
                    var sc1 = '<option value="">--select--</option>';
                    $.each(data.data2, function (index, val) {
                        sc1 += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#hflevel3").val('1');
                    $("#level3_id option").remove();
                    $("#level3_id").append(sc1);
                    $("#divlevel3").fadeIn("slow");
                    $("#level3_id").prop("disabled", false);
                } else {
                    $("#divlevel3").hide();
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
            });
        });

        if (document.getElementById("hflevel2list").value === '1') {
            $("#divlevel2list").show();
            $("#level2_list_id").prop("disabled", false);
        }
        if (document.getElementById("hflevel3").value === '1') {
            $("#divlevel3").show();
            $("#level3_id").prop("disabled", false);
        }

        //Level 3
        $('#level3_id').change(function () {
            var level3list = $("#level3_id option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Masters/Level3_change_event', {level3list: level3list, village_id: village_id}, function (data)
            {
                if (data['level3listflag'].toString() === '1') {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.data1, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#hflevel3list").val('1');
                    $("#level3_list_id option").remove();
                    $("#level3_list_id").append(sc);
                    $("#divlevel3list").fadeIn("slow");
                    $("#level3_list_id").prop("disabled", false);
                } else {
                    $("#divlevel3list").hide();
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
                if (data['level4flag'].toString() === '1') {
                    var sc1 = '<option value="">--select--</option>';
                    $.each(data.data2, function (index, val) {
                        sc1 += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#hflevel4").val('1');
                    $("#level4_id option").remove();
                    $("#level4_id").append(sc1);
                    $("#divlevel4").fadeIn("slow");
                    $("#level4_id").prop("disabled", false);
                } else {
                    $("#divlevel4").hide();
                    $("#divlevel4list").hide();
                }
            });
        });

        if (document.getElementById("hflevel3list").value === '1') {
            $("#divlevel3list").show();
            $("#level3_list_id").prop("disabled", false);
        }
        if (document.getElementById("hflevel4").value === '1') {
            $("#divlevel4").show();
            $("#level4_id").prop("disabled", false);
        }

        //Level 4
        $('#level4_id').change(function () {
            var level4list = $("#level4_id option:selected").val();
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Masters/Level4_change_event', {level4list: level4list, village_id: village_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#hflevel4list").val('1');
                $("#level4_list_id option").remove();
                $("#level4_list_id").append(sc);
                $("#divlevel4list").fadeIn("slow");
                $("#level4_list_id").prop("disabled", false);
            });
        });

        if (document.getElementById("hflevel4list").value === '1') {
            $("#divlevel4list").show();
            $("#level4_list_id").prop("disabled", false);
        }

        // checkbox check event

        $("#conffeetpe11 input:checkbox").change(function () {
            var ischecked = $(this).is(':checked');
            var id = $(this).val();
            if (!ischecked) {
//                      alert('uncheckd ' + $(this).val());
                $('#paramter_value' + id).prop("disabled", true);
                $('#paramter_value' + id).val('');
            }
            if (ischecked) {
                $('#paramter_value' + id).removeAttr('disabled');
            }
        });

    });
    function getTaluka(dist) {
        $.postJSON("<?php echo $this->webroot; ?>districtchangeevent", {dist: dist}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            $.each(data.taluka, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id").prop("disabled", false);
            $("#taluka_id option").remove();
            $("#taluka_id").prop("disabled", false);
            $("#taluka_id").append(sc);



        });
    }

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(<?php foreach ($languagelist as $langcode) { ?><?php echo 'prohibition_desc_' . $langcode['mainlanguage']['language_code']; ?>,
    <?php echo 'prohibition_remark_' . $langcode['mainlanguage']['language_code']; ?>,<?php } ?> prohibited_id, id, district_id, taluka_id, village_id,
            corporation_class_id, level1_id, level1_list_id, level2_id, level2_list_id, level3_id, level3_list_id, level4_id, level4_list_id,
            prohibition_end_flag, prohibition_end_date, court_order_date, end_remark, referance, paramter_id, parameter_value) {
<?php foreach ($languagelist as $langcode) { ?>
            $('#prohibition_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(prohibition_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
            $('#prohibition_remark_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(prohibition_remark_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>


        if (level1_list_id != '') {
            $("#divlevel1list").show();
            $("#level1_list_id").prop("disabled", false);
        } else {
            $("#divlevel1list").hide();
        }
        if (level2_id != '') {
            $("#divlevel2").show();
            $("#level2_id").prop("disabled", false);
        } else {
            $("#divlevel2").hide();
        }
        if (level2_list_id != '') {
            $("#divlevel2list").show();
            $("#level2_list_id").prop("disabled", false);
        } else {
            $("#divlevel2list").hide();
        }
        if (level2_list_id != '') {
            $("#divlevel2list").show();
            $("#level2_list_id").prop("disabled", false);
        } else {
            $("#divlevel2list").hide();
        }
        if (level3_id != '') {
            $("#divlevel3").show();
            $("#level3_id").prop("disabled", false);
        } else {
            $("#divlevel3").hide();
        }
        if (level3_list_id != '') {
            $("#divlevel3list").show();
            $("#level3_list_id").prop("disabled", false);
        } else {
            $("#divlevel3list").hide();
        }
        if (level4_id != '') {
            $("#divlevel4").show();
            $("#level4_id").prop("disabled", false);
        } else {
            $("#divlevel4").hide();
        }
        if (level4_list_id != '') {
            $("#divlevel4list").show();
            $("#level4_list_id").prop("disabled", false);
        } else {
            $("#divlevel4list").hide();
        }
        if (prohibition_end_flag == 'Y') {
            $('#divend').show();
        } else {
            $('#divend').hide();
        }

        $("#taluka_id").prop("disabled", false);
        $("#village_id").prop("disabled", false);
        $("#corporation_class_id").prop("disabled", false);
        $("#level1_id").prop("disabled", false);

        $("input:radio").attr("checked", false);
        $('input[name=is_clear][value="' + prohibition_end_flag + '"]').prop('checked', 'checked');
        $('input:checkbox').removeAttr('checked');
        var values = paramter_id;
        var values1 = parameter_value;

        $("#conffeetpe11").find('[value=' + values.join('], [value=') + ']').prop("checked", true);
        var k = 0;
        $("#conffeetpe11 input:checkbox").each(function () {
            var ischecked = $(this).is(':checked');
            var id = $(this).val();
            var i;
            var j;
            if (!ischecked) {
                $('#paramter_value' + id).prop("disabled", true);
                $('#paramter_value' + id).val('');
            }
            if (ischecked) {
                $('#paramter_value' + id).removeAttr('disabled');
                for (i = 0, j = 0; i < paramter_id.length; i++, j++) {

                    if (i == j) {
                        var temp = parameter_value[k];
                        $('#paramter_value' + id).val(temp);
                        k++;
                        break;
                    }
                }
            }
        });

        $('#hfid').val(id);
        $('#referance').val(referance);

        $('#hfproid').val(prohibited_id);
        $('#district_id').val(district_id);
        $('#taluka_id').val(taluka_id);
        $('#village_id').val(village_id);
        $('#corporation_class_id').val(corporation_class_id);
        $('#level1_id').val(level1_id);
        $('#level1_list_id').val(level1_list_id);
        $('#level2_id').val(level2_id);
        $('#level2_list_id').val(level2_list_id);
        $('#level3_id').val(level3_id);
        $('#level3_list_id').val(level3_list_id);
        $('#level4_id').val(level4_id);
        $('#level4_list_id').val(level4_list_id);
        $('#prohibition_end_date').val(prohibition_end_date);
        $('#court_order_date').val(court_order_date);
        $('#end_remark').val(end_remark);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(prohibited_id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfproid').val(prohibited_id);
        } else {
            return false;
        }
    }
</script>

<?php echo $this->Form->create('proprodts', array('type' => 'file', 'id' => 'proprodts', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblprohibitedprop'); ?></h3></center>

                <a  href="<?php echo $this->webroot; ?>helpfiles/admin/proprodts_<?php echo $language; ?>.html" class="btn btn-default pull-right " target="_blank"> <?php echo __('help'); ?> <span class="fa fa-question fa-circle-o"></span></a>                                
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>

                        <?php if ($configure[0][0]['is_dist'] == 'Y') { ?>
                            <div class="col-md-2">
                                <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?></label>
                            </div>

                            <div class="col-md-2">
                                <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>                            
                                <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];     ?></span>
                            </div>
                        <?php } ?> 
                        <?php if ($configure[0][0]['is_taluka'] == 'Y') { ?>

                            <div class="col-md-2">
                                <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?></label>
                            </div>
                            <div class="col-md-2">

                                <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];     ?></span>
                            </div>
                        <?php } ?> 




                    </div>
                </div>

                <div  class="rowht"></div>

                <div class="row">
                    <div class="col-md-12">



                        <div class="col-md-4" id="lbl_corp">
                            <div class="row">
                                <div class="col-md-6" >
                                    <label for="corporation_class_id" class="control-label" ><?php echo __('lblcorporation'); ?></label>                              
                                </div> 
                                <div class="col-md-6" >
                                    <?php echo $this->Form->input('corporation_class_id', array('options' => $corp, 'empty' => '--select--', 'id' => 'corporation_class_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                                    <span id="corporation_class_id_error" class="form-error"><?php //echo $errarr['corporation_class_id_error'];     ?></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2" >
                            <label for="village_id" class="control-label"><?php echo __('lbladmvillage'); ?></label>

                        </div>   
                        <div class="col-md-2" >
                            <?php echo $this->Form->input('village_id', array('options' => $village, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                            <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];     ?></span>
                        </div>


                    </div>
                </div>
                <div  class="rowht"></div>

                <div class="row">
                    <div class="col-md-12">

                        <?php if ($configure1[0]['levelconfig']['is_level_1_id'] == 1 || $configure1[0]['levelconfig']['is_level_2_id'] == 1 || $configure1[0]['levelconfig']['is_level_3_id'] == 1 || $configure1[0]['levelconfig']['is_level_4_id'] == 1) { ?>


                            <div class="col-md-2"   id="divlevel1lbl">
                                <label for="lblLevel1" class="control-label"><?php echo __('lbllocation'); ?></label>
                            </div>

                            <div class="col-sm-2"   divlevel1field>
                                <?php echo $this->Form->input('level1_id', array('options' => array($level1), 'empty' => '--select--', 'id' => 'level1_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level1_id_error" class="form-error"><?php //echo $errarr['level1_id_error'];     ?></span>

                            </div>
                            <div class="col-sm-2" id="divlevel1list" hidden="true">
                                <?php echo $this->Form->input('level1_list_id', array('options' => array($levellist1), 'empty' => '--select--', 'id' => 'level1_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level1_list_id_error" class="form-error"><?php //echo $errarr['level1_list_id_error'];     ?></span>

                            </div>
                            <div class="col-sm-2" id="divlevel2" hidden="true">
                                <?php echo $this->Form->input('level2_id', array('options' => array($level2), 'empty' => '--select--', 'id' => 'level2_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level2_id_error" class="form-error"><?php //echo $errarr['level2_id_error'];     ?></span>

                            </div>
                            <div class="col-sm-2" id="divlevel2list" hidden="true">
                                <?php echo $this->Form->input('level2_list_id', array('options' => array($levellist2), 'empty' => '--select--', 'id' => 'level2_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level2_list_id_error" class="form-error"><?php //echo $errarr['level2_list_id_error'];     ?></span>
                            </div>

                        </div>         
                    </div>
                    <div  class="rowht"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="col-sm-2" >
                                &nbsp;
                            </div>
                            <div class="col-sm-2" id="divlevel3" hidden="true">
                                <?php echo $this->Form->input('level3_id', array('options' => array($level3), 'empty' => '--select--', 'id' => 'level3_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level3_id_error" class="form-error"><?php //echo $errarr['level3_id_error'];     ?></span>

                            </div>
                            <div class="col-sm-2" id="divlevel3list" hidden="true">
                                <?php echo $this->Form->input('level3_list_id', array('options' => array($levellist3), 'empty' => '--select--', 'id' => 'level3_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level3_list_id_error" class="form-error"><?php //echo $errarr['level3_list_id_error'];     ?></span>

                            </div>
                            <div class="col-sm-2" id="divlevel4" hidden="true">
                                <?php echo $this->Form->input('level4_id', array('options' => array($level4), 'empty' => '--select--', 'id' => 'level4_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level4_id_error" class="form-error"><?php //echo $errarr['level4_id_error'];     ?></span>

                            </div>
                            <div class="col-sm-2" id="divlevel4list" hidden="true">
                                <?php echo $this->Form->input('level4_list_id', array('options' => array($levellist4), 'empty' => '--select--', 'id' => 'level4_list_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                                <span id="level4_list_id_error" class="form-error"><?php //echo $errarr['level4_list_id_error'];     ?></span>

                            </div>
                        </div>
                    </div>
                    <div  class="rowht"></div>
                <?php } ?>
                <div class="row">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="prohibition_desc_en" class="col-sm-4 control-label"><?php echo __('lblprohibitiondesc'); ?><span style="color: #ff0000">*</span></label>
                            </div> </div>
                        <?php
                        $i = 1;
                        foreach ($languagelist as $key => $langcode) {
                            if ($i % 6 == 0) {
                                echo "<div class=row>";
                            }
                            ?>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('prohibition_desc_' . $langcode['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'prohibition_desc_' . $langcode['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $langcode['mainlanguage']['language_name'], 'onkeyup' => "validate(2,this.value,1,8)")) ?>
                            <span id="<?php echo 'prohibition_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['prohibition_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            
                            </div>
                            <?php
                            if ($i % 6 == 0) {
                                if ($i > 1) {
                                    echo "</div><br>";
                                }
                            }
                            $i++;
                        }
                        ?> 
                            
                        
                        
                    </div>
                </div>

                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="prohibition_remark_en" class="col-sm-4 control-label"><?php echo __('lblprohibitionrmk'); ?><span style="color: #ff0000">*</span></label>
                            </div> </div>
                        <?php
                        $i = 1;
                        foreach ($languagelist as $key => $langcode) {
                            if ($i % 6 == 0) {
                                echo "<div class=row>";
                            }
                            ?>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('prohibition_remark_' . $langcode['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'prohibition_remark_' . $langcode['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $langcode['mainlanguage']['language_name'], 'onkeyup' => "validate(2,this.value,1,8)")) ?>
                              <span id="<?php echo 'prohibition_remark_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['prohibition_remark_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                            <?php
                            if ($i % 6 == 0) {
                                if ($i > 1) {
                                    echo "</div><br>";
                                }
                            }
                            $i++;
                        }
                        ?> 
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbluploaddock'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('upload', array('label' => false, 'id' => 'upload_file', 'type' => 'file')); ?>

                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblReferenceNo'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('referance', array('label' => false, 'id' => 'referance', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                        <span id="referance_error" class="form-error"><?php //echo $errarr['referance_error'];     ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbl_court_order_date'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('court_order_date', array('label' => false, 'id' => 'court_order_date', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="court_order_date_error" class="form-error"><?php //echo $errarr[court_order_date_error'];     ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="paramter_id " class="col-sm-2 control-label"><?php echo __('lblpropertyattribute'); ?><span style="color: #ff0000">*</span></label>    
                            </div>
                            <div class="col-sm-6">
                                <div class="conffeetpe11" id="conffeetpe11">
                                    <div class="col-sm-6" ><?php echo $this->Form->input('paramter_id', array('label' => false, 'id' => 'paramter_id', 'multiple' => 'checkbox', 'class' => 'paramter_id', 'options' => array($attributes))); ?></div>
                                    <?php foreach ($attributes as $key => $temp) { ?>
                                        <div class="col-sm-6" > <?php echo $this->Form->input('paramter_value' . $key, array('label' => false, 'name' => 'paramter_value[]', 'id' => 'paramter_value' . $key, 'class' => 'confpay11 form-control input-smmm', 'type' => 'text', 'disabled' => 'disabled', 'placeholder' => $temp)) ?>
                                         <!--<span id="paramter_value_error" class="form-error"><?php //echo $errarr[paramter_value_error'];     ?></span>-->
                                        
                                        </div><br>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="prohibition_end_flag" class="control-label col-sm-6"><?php echo __('lblisprohibitionclr'); ?> </label>            
                                <div class="col-sm-6"><?php echo $this->Form->input('prohibition_end_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => '', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'prohibition_end_flag', 'name' => 'is_clear')); ?></div> 
                            </div>
                        </div><br>
                        <div class="form-group" hidden="true" id="divend">
                            <label for="prohibition_end_date" class="control-label col-sm-3" ><?php echo __('lblprohibitionenddt'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('prohibition_end_date', array('label' => false, 'id' => 'prohibition_end_date', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>

                            <label for="end_remark" class="control-label col-sm-2" ><?php echo __('lblendrmk'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-4" ><?php echo $this->Form->input('end_remark', array('label' => false, 'id' => 'end_remark', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body" id="divproprodts">
                <div class="table-responsive">
                    <table id="tableproprodts" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblprohibitiondesc'); ?></th>
                                <th class="center"><?php echo __('lblprohibitionrmk'); ?></th>
                                <th class="center"><?php echo __('lbldownload'); ?></th>
                                <th class="center"><?php echo __('lblpropertyattribute'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>

                        <?php for ($i = 0; $i < count($proprodts); $i++) { ?>
                            <tr>

                                <td ><?php echo $proprodts[$i][0]['prohibition_desc_' . $language]; ?></td>
                                <td ><?php echo $proprodts[$i][0]['prohibition_remark_' . $language]; ?></td>
                                <td><?php
                                    if ($proprodts[$i][0]['upload_file'] != '') {
                                        echo $this->Html->link(
                                                'Download', array(
                                            'id' => 'view_upload', 'name' => 'view_upload' . $i,
                                            'disabled' => TRUE,
                                            'controller' => 'Masters', // controller name
                                            'action' => 'downloadfile', //action name
                                            'full_base' => true, $proprodts[$i][0]['upload_file'])
                                        );
                                    }
                                    ?></td>
                                <td >
                                    <?php
                                    $attr_name = "";
                                    $k = 1;
                                    $ids = "";
                                    $val = "";
                                    for ($j = 0; $j < count($attribute); $j++) {
                                        if ($proprodts[$i][0]['prohibited_id'] == $attribute[$j][0]['prohibited_id']) {
                                            $attr_name .= " " . "$k ) " . $attribute[$j][0]['eri_attribute_name'] . " = " . $attribute[$j][0]['paramter_value'] . "<br>";
                                            $ids .= "," . $attribute[$j][0]['paramter_id'];
                                            $val .= ",'" . $attribute[$j][0]['paramter_value'] . "'";
                                            $k++;
                                        }
                                    }
                                    echo substr($attr_name, 1);
                                    ?>
                                </td>

                                <td >
                                    <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                    <?php foreach ($languagelist as $langcode) { ?>
                                                ('<?php echo $proprodts[$i][0]['prohibition_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                        ('<?php echo $proprodts[$i][0]['prohibition_remark_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                            ('<?php echo $proprodts[$i][0]['prohibited_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['district_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['taluka_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['village_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['corporation_class_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level1_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level1_list_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level2_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level2_list_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level3_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level3_list_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level4_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['level4_list_id']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['prohibition_end_flag']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['prohibition_end_date']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['court_order_date']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['end_remark']; ?>'),
                                                    ('<?php echo $proprodts[$i][0]['referance']; ?>'),
                                    <?php echo "[" . substr($ids, 1) . "]"; ?>,
                                    <?php echo "[" . substr($val, 1) . "]"; ?>
                                            );">
                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                       <a href="<?php echo $this->webroot; ?>Masters/delete_prohibition/<?php echo $proprodts[$i][0]['prohibited_id']; ?>" class="btn btn-warning" onclick="return confirm('Are You Sure ? ')"> <span class="glyphicon glyphicon-remove"></span></a>
                                              
                                       
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($proprodts)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfproid; ?>' name='hfproid' id='hfproid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>

    <input type='hidden' value=<?php echo $hflevel1list; ?> name='hflevel1list' id='hflevel1list'/>
    <input type='hidden' value=<?php echo $hflevel2; ?> name='hflevel2' id='hflevel2'/>
    <input type='hidden' value=<?php echo $hflevel2list; ?> name='hflevel2list' id='hflevel2list'/>
    <input type='hidden' value=<?php echo $hflevel3; ?> name='hflevel3' id='hflevel3'/>
    <input type='hidden' value=<?php echo $hflevel3list; ?> name='hflevel3list' id='hflevel3list'/>
    <input type='hidden' value=<?php echo $hflevel4; ?> name='hflevel4' id='hflevel4'/>
    <input type='hidden' value=<?php echo $hflevel4list; ?> name='hflevel4list' id='hflevel4list'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




