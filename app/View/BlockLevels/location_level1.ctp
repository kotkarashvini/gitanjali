<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {

        $('#table').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        //---------------Division->District filteration

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
          
        $('#district_id').change(function () {
            var district_id = $('#district_id').val();

            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getgovtbody', {district_id: district_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
            });


<?php if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getsubdiv', {district_id: district_id}, function (data)
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
                $.postJSON('<?php echo $this->webroot; ?>BlockLevels/gettalukadist', {district_id: district_id}, function (data)
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
            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/gettaluka', {subdivision_id: subdivision_id}, function (data)
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
        $('#taluka_id').change(function () {
            var taluka_id = $('#taluka_id').val();
            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getcircle', {taluka_id: taluka_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#circle_id option").remove();
                $("#circle_id").append(sc);
            });
        });
        //---------------------------------    

        // _________________________village_________________________

        $('#taluka_id').change(function () {

            var taluka_id = $('#taluka_id').val();
            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getvillage', {taluka_id: taluka_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });

        });


        $('#circle_id').change(function () {
<?php if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') { ?>
                var circle_id = $('#circle_id').val();
                $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getvillage', {circle_id: circle_id}, function (data)
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

        //______________end__________________________________

    });</script>

<?php //echo $this->element("BlockLevel/main_menu"); ?>

<?php echo $this->Form->create('location_level1', array('id' => 'location_level1', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblLevel1'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Village/level1_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12">

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


                    <?php // if ($configure[0][0]['is_dist'] == 'Y') {  ?>
                    <div class="col-sm-2">
                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>                            
                        <span class="form-error" id="district_id_error"></span>
                    </div>
                    <?php //}  ?> 



                    <?php if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdiv'); ?> <span style="color: #ff0000">*</span></label>
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
                    
                     
                    <?php if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y' || $is_div_flag['adminLevelConfig']['is_taluka'] == 'Y') { ?>
                        <div class="col-md-2 ">
                            <label for="village_id" class="control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span> </label>
                            <?php echo $this->Form->input('village_id', array('options' => $villagedata, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                            <span class="form-error" id="developed_land_types_id_error"></span>
                        </div>
                    <?php } ?>
                

                </div>

                <!--<div class="row">-->
                <div class="col-md-12">
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-2">
                            <label><?php echo __('lbllocationlevel1') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('level_1_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'level_1_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'level_1_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['village_name_' . $langcode['mainlanguage']['language_code'] . '_error'];                              ?></span>
                            <?php //echo $errarr['village_name_' . $langcode['mainlanguage']['language_code'] . '_error'];  ?>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>
                <!--</div>-->
              

                <?php
                echo $this->Form->input('level_1_id', array('label' => false, 'id' => 'level_1_id', 'type' => 'hidden'));
                ?>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>BlockLevels/village" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>




<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  

<!--                        <tr> 
                            <th class="center"><?php //echo __('lblvillagecode'); ?></th>
                            <th class="center"><?php //echo __('lblCensusCode'); ?></th>   -->
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lbllocationlevel1') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>

                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <!--<tr>--> 


                        <?php
                        // pr($level1data1);
                        foreach ($level1data1 as $level1data11) {
                            ?>
                            <tr>
<!--                                <td class="tblbigdata"><?php //echo $villagedata[0]['village_code']; ?></td>
                                <td class="tblbigdata"><?php //echo $villagedata[0]['census_code']; ?></td>-->

                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $level1data11[0]['level_1_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>


                                <td>
                                     <!--<a <?php // echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'village', $villagedata[0]['village_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-default"), array('Are you sure to Edit?'));       ?></a>-->

                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'location_level1', $level1data11[0]['level_1_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?></a>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'location_level1_delete', $level1data11[0]['level_1_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?></a>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>

</div>