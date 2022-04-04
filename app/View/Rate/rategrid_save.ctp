
<script>

    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

    });
    function forupdate(id, village_name_en, level_1_desc_en, list_1_desc_en, district_id, taluka_id, developed_land_types_id, ulb_type_id, corp_id, lr_code, usage_main_catg_id, usage_sub_catg_id, prop_unit, prop_rate) {


        $.getJSON("get_taluka_name", {district: district_id}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);
            $('#taluka_id').val(taluka_id);

//            $.getJSON("get_corp_list", {corp: ulb_type_id}, function (data)
//            {
//                var sc = '<option value="">--Select--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#corp_id option").remove();
//                $("#corp_id").append(sc);
//                $('#corp_id').val(corp_id);
//            });

                $.getJSON("getcorplist", {district: district_id}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
                $('#corp_id').val(corp_id);
            });
        });
         $.getJSON("get_subcat", {main_cat: usage_main_catg_id}, function (data)
        {
            var sc = '<option value="">--Select--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#usage_sub_catg_id option").remove();
            $("#usage_sub_catg_id").append(sc);
            $('#usage_sub_catg_id').val(usage_sub_catg_id);

            $.getJSON("get_unit", {sub_cat: usage_sub_catg_id}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#prop_unit option").remove();
                $("#prop_unit").append(sc);
                $('#prop_unit').val(prop_unit);
            });
        });
        $('#hfid').val(id);
        $('#district_id').val(district_id);
        $('#developed_land_types_id').val(developed_land_types_id);
        $('#ulb_type_id').val(ulb_type_id);
        $('#village_name_en').val(village_name_en);
        $('#level_1_desc_en').val(level_1_desc_en);
        $('#list_1_desc_en').val(list_1_desc_en);
        $('#lr_code').val(lr_code);
        $('#usage_main_catg_id').val(usage_main_catg_id);
        $('#prop_rate').val(prop_rate);
        $('#hfupdateflag').val('Y');
        return false;
    }
</script>

<?php echo $this->Form->create('rategrid', array('id' => 'rategrid')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">

            <div class="box-body">
                <br><br>
                <div id="divratedata" class="table-responsive">
                    <table id="tableratedata" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th class="center"><?php echo __('lblgovbodyname'); ?></th>
                                <th class="center"><?php echo __('lblgovbodylistname'); ?></th>
                                <th class="center"><?php echo __('lbladmvillage'); ?></th>
                                <th class="center"><?php echo __('lblLevel1'); ?></th>
                                <th class="center"><?php echo __('lblLevel1list'); ?></th>
                                <?php if($stateid == 20){ ?>
                                <th class="center"><?php echo __('Mauja code'); ?></th>
                                <th class="center"><?php echo __('Ward No'); ?></th>
                                <?php } else { ?>
                                <th class="center"><?php echo __('LR Code'); ?></th>
                                <th class="center"><?php echo __('Segment no.'); ?></th>
                                <?php }  ?>                                
                                <th class="center"><?php echo __('lblusamaincat'); ?></th>
                                <th class="center"><?php echo __('lblsubcat'); ?></th>
                                 <?php if($stateid == 20){ ?>
                                <th class="center"><?php echo __('lblconstuctiontye'); ?></th>
                                <?php }  ?>
                                <th class="center"><?php echo __('lblunit'); ?></th>
                                <th class="center"><?php echo __('lblrate'); ?></th>
                                <!--<th class="center width10"><?php echo __('lblaction'); ?> </th>-->
                            </tr>  
                        </thead>
                        <tbody>
                            <tr>
                                <?php for ($i = 0; $i < count($raterecord); $i++) { ?>

                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['class_description_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['governingbody_name_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['village_name_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['level_1_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['list_1_desc_en']; ?></td>
                                    <?php if($stateid == 20){ ?>
                                <td class="tblbigdata"><?php echo $raterecord[$i][0]['mauja_code']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['word_no']; ?></td>
                                <?php } else { ?>
                                <td class="tblbigdata"><?php echo $raterecord[$i][0]['lr_code']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['segment_no']; ?></td>
                                <?php }  ?>                                    
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['usage_main_catg_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['usage_sub_catg_desc_en'];   ?></td>
                                    <?php if($stateid == 20){ ?>
                                <td class="tblbigdata"><?php echo $raterecord[$i][0]['construction_type_desc_en'];   ?></td>
                                <?php }  ?>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['unit_desc_en'];   ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['prop_rate']; ?></td>
<!--                                    <td >
                                        <button id="btnupdate<?php echo $raterecord[$i][0]['id']; ?>" name="btnupdate" class="btn btn-default "onclick="javascript: return forupdate(
                                                            '<?php echo $raterecord[$i][0]['id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['village_name_en']; ?>',
                                                            '<?php echo $raterecord[$i][0]['level_1_desc_en']; ?>',
                                                            '<?php echo $raterecord[$i][0]['list_1_desc_en']; ?>',
                                                            '<?php echo $raterecord[$i][0]['district_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['taluka_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['developed_land_types_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['ulb_type_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['corp_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['lr_code']; ?>',
                                                            '<?php echo $raterecord[$i][0]['usage_main_catg_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['usage_sub_catg_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['prop_unit']; ?>',
                                                            '<?php echo $raterecord[$i][0]['prop_rate']; ?>');">
                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                        <button id="btndelete" name="btndelete" class="btn btn-default "    onclick="javascript: return formdelete(('<?php echo $raterecord[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"></span></button>
                                    </td>-->
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($raterecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
