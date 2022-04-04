<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {

        $('#table').dataTable({
               "order":[],
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
        //---------------------------------    
        //---------------District->Subdivision filteration
        $('#district_id').change(function () {
            var district_id = $('#district_id').val();

<?php if ($configure[0][0]['is_subdiv'] == 'Y') { ?>
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

                $.postJSON('<?php echo $this->webroot; ?>BlockLevels/gettaluka', {district_id: district_id}, function (data)
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




    });</script>
<?php echo $this->element("BlockLevel/main_menu"); ?>

<?php echo $this->Form->create('circle', array('id' => 'circle', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmcircle'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Circle/circle_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span class="form-error" id="division_id_error"></span>
                        </div>
                    <?php } ?>


                    <?php // if ($configure[0][0]['is_dist'] == 'Y') { ?>
                    <div class="col-sm-2">
                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>                            
                        <span class="form-error" id="district_id_error"></span>
                    </div>
                    <?php // } ?> 



                    <?php if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdiv'); ?> <span class="star">*</span></label>
                            <?php echo $this->Form->input('subdivision_id', array('options' => $subdivisiondata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span class="form-error" id="subdivision_id_error"></span>
                        </div>
                    <?php } ?>

                    <?php if ($is_div_flag['adminLevelConfig']['is_taluka'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?> <span class="star">*</span></label>
                            <?php echo $this->Form->input('taluka_id', array('options' => $talukadata1, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="subdivision_id_error"></span>
                        </div>
                    <?php } ?>

                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>

                <!--<div class="row">-->
                <div class="col-md-12"><div  class="rowht">&nbsp;</div>
                    <div  class="rowht">&nbsp;</div>
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lbladmcircle') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('circle_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'circle_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'circle_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['taluka_name_' . $langcode['mainlanguage']['language_code'] . '_error'];                              ?></span>
                            <?php //echo $errarr['taluka_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-12">           
                    <div class="form-group"> 
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                             <div class="col-md-3">
                            <label><?php echo __('lblcirclecode'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input("circle_code", array('label' => false, 'id' => 'circle_code', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="circle_code_error" class="form-error"></span>

                        </div>

                    </div>
                </div>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>


                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br>

                            <br>
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>BlockLevels/circle" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>
                    </div>
                </div>



                <!--</div>-->
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>

                <?php
                //if (isset($result)) {
                echo $this->Form->input('circle_id', array('label' => false, 'id' => 'circle_id', 'type' => 'hidden'));
                // }
                ?>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

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

                        <tr> 
                            <th class="center"><?php echo __('lblcirclecode'); ?></th>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lbladmcircle') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>

<!--<th class="center"><?php // echo __('lbloldcencuscode');          ?></th>-->
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <!--<tr>--> 


                        <?php
                        //pr($talukadata);exit;
                        foreach ($circledata as $circledata1) {
                            // PR($circledata1);EXIT;
                            ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $circledata1[0]['circle_code']; ?></td>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $circledata1[0]['circle_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

     <!--<td class="tblbigdata"><?php // echo $circledata1[0]['old_census_code'];          ?></td>-->

                                <td>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'circle', $circledata1[0]['circle_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_circle', $circledata1[0]['circle_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>