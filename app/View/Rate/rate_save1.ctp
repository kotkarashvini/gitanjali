
<script>

    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            $.getJSON("get_taluka_name", {district: district}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });

//            $.post("rategrid_save", {district: district}, function (data)
//            {
//                $("#divrategrid").html("");
//                $("#divrategrid").html(data);
//            });

             $.getJSON("getcorplist", {district: district}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
            });

        })

        if ($("#taluka_id option:selected").val() != "") {
            taluka_change_event();
        }
        $('#taluka_id').change(function () {
            taluka_change_event();
        })

//        $('#ulb_type_id').change(function () {
//            var corp = $("#ulb_type_id option:selected").val();
//            var dist = $("#district_id option:selected").val();
//            $.getJSON("get_corp_list", {corp: corp, dist: dist}, function (data)
//            {
//                var sc = '<option value="">--Select--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#corp_id option").remove();
//                $("#corp_id").append(sc);
//            });
//        })

        $('#usage_main_catg_id').change(function () {
            var main_cat = $("#usage_main_catg_id option:selected").val();
            $.getJSON("get_subcat", {main_cat: main_cat}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#usage_sub_catg_id option").remove();
                $("#usage_sub_catg_id").append(sc);
            });
        })

        $('#usage_sub_catg_id').change(function () {
            var sub_cat = $("#usage_sub_catg_id option:selected").val();
            $.getJSON("get_unit", {sub_cat: sub_cat}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#prop_unit option").remove();
                $("#prop_unit").append(sc);
            });
        })

        var hfupdateflag = document.getElementById("hfupdateflag").value;
        if (hfupdateflag == 'S') {
            $('#level_1_desc_en').val("");
            $('#list_1_desc_en').val("");
            $('#lr_code').val("");
            $('#usage_main_catg_id').val("");
            $('#prop_rate').val("");
            $('#prop_unit').val("");
            $('#usage_sub_catg_id').val("");
        }

    });

    function formadd() {
        var district = $("#district_id option:selected").val();
        var taluka = $("#taluka_id option:selected").val();
        var talukaname = $("#taluka_id option:selected").text();
        var village = $("#village_name_en").val();
        $.getJSON("check_village", {district: district, taluka: taluka, village: village}, function (data)
        {
            if (data == 'Y') {
                var result = confirm("The Village Name " + village + " is Alerady Exist in " + talukaname + " Tehsil...!!! \n\
    \n\
Please confirm Governing Body and List..!!!\n\
        \n\
    Do you really want add this Village Name ??????");
                if (result) {

                    var result1 = confirm("Please Reconfirm...!!!  Do you really want add this Village Name ??????");
                    if (result1) {
                        $('#rate').submit();
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                $('#rate').submit();
            }
        });

    }

    function forreset() {
        $('#district_id').val("");
        $('#developed_land_types_id').val("");
        $('#ulb_type_id').val("");
        $('#village_name_en').val("");
        $('#level_1_desc_en').val("");
        $('#list_1_desc_en').val("");
        $('#lr_code').val("");
        $('#segment_no').val("");
        $('#usage_main_catg_id').val("");
        $('#prop_rate').val("");
        $('#prop_unit').val("");
        $('#usage_sub_catg_id').val("");
        $('#corp_id').val("");
        $('#taluka_id').val("");
    }

    function taluka_change_event() {
        var district = $("#district_id option:selected").val();
        var taluka = $("#taluka_id option:selected").val();

        $.post("rategrid_save", {district: district, taluka: taluka}, function (data)
        {
            $("#divrategrid").html(data);
        });
    }

</script>

<?php echo $this->Form->create('rate', array('id' => 'rate')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblrateasperlocation'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Rate Chart/rate_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group"><br>
                        <div class="col-sm-2">
                            <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('district_id', array('options' => array($districtdata), 'empty' => '--select--', 'id' => 'district_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];     ?></span>
                        </div>

                        <div class="col-sm-2">
                            <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('taluka_id', array('options' => array($talukadata), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];     ?></span>
                        </div>

                        <div class="col-sm-2">
                            <label for="developed_land_types_id" class="control-label"><?php echo __('lbldellandtype'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('developed_land_types_id', array('options' => array($landtypedata), 'empty' => '--select--', 'id' => 'developed_land_types_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="developed_land_types_id_error" class="form-error"><?php //echo $errarr['developed_land_types_id_error'];     ?></span>
                        </div>

                        <div class="col-sm-2">
                            <label for="ulb_type_id" class="control-label"><?php echo __('lblCorporationClass'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('ulb_type_id', array('options' => array($corpclassdata), 'empty' => '--select--', 'id' => 'ulb_type_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="ulb_type_id_error" class="form-error"><?php //echo $errarr['ulb_type_id_error'];     ?></span>
                        </div>

                        <div class="col-sm-2">
                            <label for="corp_id" class="control-label"><?php echo __('lblcorpcouncillist'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('corp_id', array('options' => array($corplistdata), 'empty' => '--select--', 'id' => 'corp_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="corp_id_error" class="form-error"><?php //echo $errarr['corp_id_error'];     ?></span>
                        </div>

                    </div>
                </div><br><br>
                <div id="" class="table-responsive">
                    <table id="" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lbladmvillage'); ?></th>
                                <th class="center"><?php echo __('lblLevel1'); ?></th>
                                <th class="center"><?php echo __('lblLevel1list'); ?></th>

                                <th class="center"><?php echo __('LR Code'); ?></th>
                                <th class="center"><?php echo __('Segment no.'); ?></th>
                                <th class="center"><?php echo __('lblusamaincat'); ?></th>
                                <th class="center"><?php echo __('lblsubcat'); ?></th>
                                <th class="center"><?php echo __('lblunit'); ?></th>
                                <th class="center"><?php echo __('lblrate'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?> </th>
                            </tr>  
                        </thead>
                        <tbody>
                            <tr>  
                                <td class="tblbigdata"><?php echo $this->Form->input('village_name_en', array('label' => false, 'id' => 'village_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="village_name_en_error" class="form-error"><?php //echo $errarr['village_name_en_error'];     ?></span>

                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('level_1_desc_en', array('label' => false, 'id' => 'level_1_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="level_1_desc_en_error" class="form-error"><?php //echo $errarr['level_1_desc_en_error'];     ?></span>

                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('list_1_desc_en', array('label' => false, 'id' => 'list_1_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="list_1_desc_en_error" class="form-error"><?php //echo $errarr['list_1_desc_en_error'];     ?></span>

                                </td>

                                <td class="tblbigdata"><?php echo $this->Form->input('lr_code', array('label' => false, 'id' => 'lr_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="lr_code_error" class="form-error"><?php //echo $errarr['lr_code_error'];     ?></span>

                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('segment_no', array('label' => false, 'id' => 'segment_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="segment_no_error" class="form-error"><?php //echo $errarr['segment_no_error'];     ?></span>
                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('usage_main_catg_id', array('label' => false, 'id' => 'usage_main_catg_id', 'class' => 'form-control input-sm', 'empty' => '---Select---', 'type' => 'select', 'options' => $usage_main)) ?>
                                    <span id="usage_main_catg_id_error" class="form-error"><?php //echo $errarr['usage_main_catg_id_error'];     ?></span>

                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('usage_sub_catg_id', array('label' => false, 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm', 'type' => 'select')) ?>
                                    <span id="usage_sub_catg_id_error" class="form-error"><?php //echo $errarr['usage_sub_catg_id_error'];     ?></span>

                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('prop_unit', array('label' => false, 'id' => 'prop_unit', 'class' => 'form-control input-sm', 'type' => 'select')) ?>
                                    <span id="prop_unit_error" class="form-error"><?php //echo $errarr['prop_unit_error'];     ?></span>
                                </td>
                                <td class="tblbigdata"><?php echo $this->Form->input('prop_rate', array('label' => false, 'id' => 'prop_rate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="prop_rate_error" class="form-error"><?php //echo $errarr['prop_rate_error'];     ?></span>
                                </td>
                                <td><button type='button' id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                        <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                                    </button>
                                    <button type='button' id="btnreset" name="btnreset" class="btn btn-info " onclick="javascript: return forreset();">
                                        <span class="glyphicon glyphicon-reset"></span><?php echo __('lblreset'); ?>
                                    </button>
                                </td>

                            </tr>
                    </table> 
                </div>

                <div class="rowht"></div>
                <div class="rowht"></div>

                <div id="divrategrid" class="table-responsive">                   
                </div>
            </div>
        </div>
    </div>

    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
