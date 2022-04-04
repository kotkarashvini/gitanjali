<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>

    $(document).ready(function () {

        $('#tableratedata').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#division_id').change(function () {
            var division_id = $('#division_id').val();
            $.postJSON('<?php echo $this->webroot; ?>MHRate/getdist', {division_id: division_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        });

        $('#district_id').change(function () {
            var district_id = $('#district_id').val();

<?php if ($config[0][0]['subdivision_id'] == 'Y') { ?>
                $.postJSON('<?php echo $this->webroot; ?>MHRate/getsubdiv', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                });
<?php } else { ?>
                $.postJSON('<?php echo $this->webroot; ?>MHRate/gettalukadist', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                });
<?php } ?>
<?php if (($config[0][0]['subdivision_id'] == 'N') && ($config[0][0]['taluka_id'] == 'N') && ($config[0][0]['village_id'] == 'Y')) { ?>
                $.postJSON('<?php echo $this->webroot; ?>MHRate/getvillagedist', {district_id: district_id}, function (data)
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

        $('#subdivision_id').change(function () {
            var subdivision_id = $('#subdivision_id').val();
            $.postJSON('<?php echo $this->webroot; ?>MHRate/gettaluka', {subdivision_id: subdivision_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        });

        $('#taluka_id').change(function () {
            var taluka_id = $('#taluka_id').val();

<?php if ($config[0][0]['village_id'] == 'Y') { ?>
                var taluka_id = $('#taluka_id').val();
                $.postJSON('<?php echo $this->webroot; ?>MHRate/getvillage', {taluka_id: taluka_id}, function (data)
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

        $('#village_id').change(function () {
            var village_id = $('#village_id').val();

            $.postJSON('<?php echo $this->webroot; ?>MHRate/getlevel1', {village_id: village_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level1_id option").remove();
                $("#level1_id").append(sc);
            });
        });

        $('#level1_id').change(function () {
            var level1_id = $('#level1_id').val();

            $.postJSON('<?php echo $this->webroot; ?>MHRate/getlevel1list', {level1_id: level1_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level1_list_id option").remove();
                $("#level1_list_id").append(sc);
            });
        });

        $('#usage_sub_catg_id').change(function () {
            var usage_sub_catg_id = $('#usage_sub_catg_id').val();
<?php if ($config[0][0]['usage_sub_sub_catg_id'] == 'Y') { ?>
                $.postJSON('<?php echo $this->webroot; ?>MHRate/getusagesubsub', {usage_sub_catg_id: usage_sub_catg_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#usage_sub_sub_catg_id option").remove();
                    $("#usage_sub_sub_catg_id").append(sc);
                });
<?php } ?>
        });


    });

    function forsubmit() {
    $('#hfid').val('');
        $('#hfactionflag').val('');
        $('#hfaction').val("1");
        $('#rate').submit();
    }

    function forsearch() {
        $('#hfid').val('');
        $('#hfactionflag').val('');
        $('#hfaction').val("2");
        $('#rate').submit();
    }
    
    function forsave(id, flag) {         
        $('#hfid').val(id);
        $('#hfactionflag').val(flag);
        $('#hfaction').val("2");
        $('#rate').submit();
    }



</script>

<?php echo $this->Form->create('rate', array('id' => 'rate')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblrate'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Rate Chart/rate_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <?php $value = array('Y' => 'Yes', 'N' => 'No'); ?>
                        <div class="col-sm-2">
                            <label for="developed_land_types_id" class="control-label"><?php echo __('lbldellandtype'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('developed_land_types_id', array('options' => array($Developedland), 'empty' => '--select--', 'id' => 'developed_land_types_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                         <span class="form-error" id="developed_land_types_id_error"></span>
                        </div>

                        <div class="col-sm-2">
                            <label for="usage_main_cat_id" class="control-label"><?php echo __('lblusamaincat'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('usage_main_catg_id', array('options' => array($Usagemain), 'empty' => '--select--', 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                         <span class="form-error" id="usage_main_catg_id_error"></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="readyrecflag" class="control-label"><?php echo __('Ready Reckoner'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('readyrecflag', array('options' => array($value), 'id' => 'readyrecflag', 'label' => false, 'class' => 'form-control input-sm')); ?>
                         <!--<span class="form-error" id="readyrecflag_error"></span>-->
                        </div>
                        <div class="col-sm-2 align-bottom">
                            <button type='button' id="btnsubmit" name="btnsubmit" class="btn btn-info align-bottom" onclick="javascript: return forsubmit();">
                                <span class="glyphicon glyphicon-search"></span><?php echo __('Submit'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($config)) { ?>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">

                                <?php if ($config[0][0]['finyear_id'] == 'Y') {
                                    ?>
                                    <div class="col-sm-2">
                                        <label for="finyear_id" class="control-label"><?php echo __('lblfineyer'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('finyear_id', array('options' => $finyear, 'id' => 'finyear_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="finyear_id_error"></span>
                                    </div>
                                <?php } ?>

                                <?php if ($config[0][0]['division_id'] == 'Y') {
                                    ?>
                                    <div class="col-sm-2">
                                        <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('division_id', array('options' => $divdata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="division_id_error"></span>
                                    </div>
                                <?php } ?>


                                <?php if ($config[0][0]['district_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>                            
                                        <span class="form-error" id="district_id_error"></span>
                                    </div>
                                <?php } ?> 



                                <?php if ($config[0][0]['subdivision_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdiv'); ?> <span style="color: #ff0000">*</span></label>
                                        <?php echo $this->Form->input('subdivision_id', array('options' => $subdivdata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="subdivision_id_error"></span>
                                    </div>
                                <?php } ?>


                                <?php if ($config[0][0]['taluka_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?> <span style="color: #ff0000">*</span></label>
                                        <?php echo $this->Form->input('taluka_id', array('options' => $talukadata, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="taluka_id_error"></span>
                                    </div>
                                <?php } ?>


                                <?php if ($config[0][0]['ulb_type_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="ulb_type_id" class="control-label"><?php echo __('lblCorporationClass'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('ulb_type_id', array('options' => $ulbdata, 'empty' => '--select--', 'id' => 'ulb_type_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="ulb_type_id_error"></span>
                                    </div>
                                <?php } ?>
                                <?php if ($config[0][0]['valutation_zone_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="valutation_zone_id" class="control-label"><?php echo __('lblvalzone'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('valutation_zone_id', array('options' => $zonedata, 'empty' => '--select--', 'id' => 'valutation_zone_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="valutation_zone_id_error"></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div> <br>                   
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">

                                <?php if ($config[0][0]['village_id'] == 'Y') {
                                    ?>
                                    <div class="col-sm-2">
                                        <label for="village_id" class="control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('village_id', array('options' => $villagedata, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="village_id_error"></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="level1_id" class="control-label"><?php echo __('lblLevel1'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('level1_id', array('options' => $locdata, 'empty' => '--select--', 'id' => 'level1_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="level1_id_error"></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="level1_list_id" class="control-label"><?php echo __('lblLevel1list'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('level1_list_id', array('options' => $loclistdata, 'empty' => '--select--', 'id' => 'level1_list_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="level1_list_id_error"></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div><br><br>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">

                                <?php if ($config[0][0]['usage_sub_catg_id'] == 'Y') {
                                    ?>
                                    <div class="col-sm-2">
                                        <label for="usage_sub_catg_id" class="control-label"><?php echo __('lblsubcat'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('usage_sub_catg_id', array('options' => $subcatdata, 'empty' => '--select--', 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="usage_sub_catg_id_error"></span>
                                    </div>
                                <?php } ?>

                                <?php if ($config[0][0]['usage_sub_sub_catg_id'] == 'Y') {
                                    ?>
                                    <div class="col-sm-2">
                                        <label for="usage_sub_sub_catg_id" class="control-label"><?php echo __('lblsubccategory'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('usage_sub_sub_catg_id', array('options' => $subsubcatdata, 'empty' => '--select--', 'id' => 'usage_sub_sub_catg_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="usage_sub_sub_catg_id_error"></span>
                                    </div>
                                <?php } ?>
                                <?php if ($config[0][0]['valutation_subzone_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="valutation_subzone_id" class="control-label"><?php echo __('lblvalsubzone'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('valutation_subzone_id', array('options' => $subzonedata, 'empty' => '--select--', 'id' => 'valutation_subzone_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="valutation_subzone_id_error"></span>
                                    </div>
                                <?php } ?>


                                <?php if ($config[0][0]['construction_type_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="construction_type_id" class="control-label"><?php echo __('lblconstuctiontye'); ?> <span class="star">*</span></label>
                                        <?php echo $this->Form->input('construction_type_id', array('options' => $constdata, 'empty' => '--select--', 'id' => 'construction_type_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>                            
                                        <span class="form-error" id="construction_type_id_error"></span>
                                    </div>
                                <?php } ?> 



                                <?php if ($config[0][0]['road_vicinity_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="road_vicinity_id" class="control-label"><?php echo __('lblroadvicinity'); ?> <span style="color: #ff0000">*</span></label>
                                        <?php echo $this->Form->input('road_vicinity_id', array('options' => $roadvicdata, 'empty' => '--select--', 'id' => 'road_vicinity_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="road_vicinity_id_error"></span>
                                    </div>
                                <?php } ?>


                                <?php if ($config[0][0]['user_defined_dependency1_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="user_defined_dependency1_id" class="control-label"><?php echo __('lbluserdefineddependency1'); ?> <span style="color: #ff0000">*</span></label>
                                        <?php echo $this->Form->input('user_defined_dependency1_id', array('options' => $userdep1data, 'empty' => '--select--', 'id' => 'user_defined_dependency1_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="user_defined_dependency1_id_error"></span>
                                    </div>
                                <?php } ?>


                                <?php if ($config[0][0]['user_defined_dependency2_id'] == 'Y') { ?>
                                    <div class="col-sm-2">
                                        <label for="user_defined_dependency2_id" class="control-label"><?php echo __('lbluserdefineddependency2'); ?><span style="color: #ff0000">*</span> </label>
                                        <?php echo $this->Form->input('user_defined_dependency2_id', array('options' => $userdep2data, 'empty' => '--select--', 'id' => 'user_defined_dependency2_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                                        <span class="form-error" id="user_defined_dependency2_id_error"></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div><br><br>

                    <div class="row center">
                        <div class="form-group">
                            <div class="col-sm-12 tdselect">
                                <button id="btnsearch" name="btnsearch" class="btn btn-info " onclick="javascript: return forsearch();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsearch'); ?>
                                </button>


                                <a href="<?php echo $this->webroot; ?>MHRate/rate" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                            </div>
                        </div>
                    </div>

                </div>
            <?php } else { ?>
                <div class="row center">
                    <div class="form-group col-sm-12" > 
                        <!--<div class="col-sm-12"><h2 style="color: red">Record Not Found...!!!!!</h2></div>-->
                    </div>           
                </div>
            <?php } ?>
        </div>
        <?php if ($hfaction == 2) { ?>
            <div class="box box-primary">
                <div class="row">
    <div class="col-md-12">
         <div class="box box-primary">
             <div class="box-body">
                            <table id="tableratedata" class="table table-striped table-bordered table-hover">  
                            <thead >  
                                <tr> 
                                    <th class="center" ><?php echo __('Sr. No.'); ?></th>                                   
                                    <th class="center"><?php echo __('Rate'); ?></th>
                                     <th class="center"><?php echo __('Unit'); ?></th>
                                    <th class="center width10"><?php echo __('lblaction'); ?> </th>
                                </tr>  
                            </thead>
                            <tbody>

                                
                                    <?php if(!empty($raterecord)){
                                    for ($i = 0; $i < count($raterecord); $i++) { ?>
                                    <tr>
                                        <td class="tblbigdata"><?php echo $i + 1; ?></td>

                                        
                                        <td class="tblbigdata"><?php echo $this->Form->input('prop_rate', array('label' => false, 'id' => 'prop_rate' . $raterecord[$i][0]['id'], 'class' => 'form-control input-sm', 'type' => 'text', 'required' => 'required', 'maxlength' => '10', 'value' => $raterecord[$i][0]['prop_rate'])) ?>
                                        <td class="tblbigdata"><?php echo $this->Form->input('prop_unit', array('label' => false, 'id' => 'prop_unit'.$raterecord[$i][0]['id'], 'class' => 'form-control input-sm', 'type' => 'select', 'options' => $unit, 'value' => $raterecord[$i][0]['prop_unit'])) ?></td>
                                        <!--<span id="prop_rate_error" class="form-error"><?php //echo $errarr['prop_rate_error'];   ?></span>-->
                                        </td>

                                        <td >
                                            <input type='submit' name='Save' value='Save'  onclick="javascript: return forsave('<?php echo $raterecord[$i][0]['id']; ?>','U');"  >
                                             <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'rate_delete', $raterecord[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?></a>
                                        </td>
                                    </tr>
                                    <?php } } else {?>
                                    <tr>
                                        <td class="tblbigdata"><?php echo 1; ?></td>

                                        
                                        <td class="tblbigdata"><?php echo $this->Form->input('prop_rate', array('label' => false, 'id' => 'prop_rate', 'class' => 'form-control input-sm', 'type' => 'text', 'required' => 'required', 'maxlength' => '10', 'value' => '')) ?>
                                        <td class="tblbigdata"><?php echo $this->Form->input('prop_unit', array('label' => false, 'id' => 'prop_unit', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => $unit, 'empty' => '--select--')) ?></td>
                                        <!--<span id="prop_rate_error" class="form-error"><?php //echo $errarr['prop_rate_error'];   ?></span>-->
                                        </td>

                                        <td >
                                            <input type='submit' name='Save' value='Save'  onclick="javascript: return forsave('999999999','S');"  >
                                        </td>
                                    </tr>
                                    <?php } ?>
                                        
                            </tbody>
                        </table>
                        </div>
             </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfaction; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfactionflag; ?>' name='hfactionflag' id='hfactionflag'/>

</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
