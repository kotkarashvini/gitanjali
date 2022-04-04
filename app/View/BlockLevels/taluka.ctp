<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {
        $('#table').dataTable({
            "iDisplayLength": 10,
               "order":[],
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
            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getsubdiv', {district_id: district_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#subdivision_id option").remove();
                $("#subdivision_id").append(sc);
            });
        });
        //---------------------------------    





    });</script>
<?php echo $this->element("BlockLevel/main_menu"); ?>

<?php echo $this->Form->create('taluka', array('id' => 'taluka', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmtaluka'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Taluka/taluka_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">

                <div class="col-md-12">

                    <?php
                    //   pr($adminLevelConfig);
                    //exit;
                    if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                        ?>
                        <div class="col-sm-2">
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span class="star">*</span></label>
                            <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="division_id_error"></span>
                        </div>
                    <?php } ?>


                    <?php // if ($configure[0][0]['is_dist'] == 'Y') { ?>
                    <div class="col-sm-2">
                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                        <span class="form-error" id="district_id_error"></span>
                    </div>
                    <?php //} ?> 



                    <?php if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') { ?>
                        <div class="col-sm-2">
                            <label for="subdivision_id" class="control-label"><?php echo __('lbladmsubdiv'); ?> <span class="star">*</span> </label>
                            <?php echo $this->Form->input('subdivision_id', array('options' => $subdivisiondata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="subdivision_id_error"></span>
                        </div>
                    <?php } ?>

                </div>

                <div class="col-md-12">
                    </br>  
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('lbladmtaluka') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('taluka_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'taluka_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'taluka_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>

                <div class="col-md-12">
                    <div class="col-md-4">                        
                        <div class="form-group">
                            <label for="census_code" class=" control-label"><?php echo __('lbltalukacode'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('taluka_code', array('label' => false, 'id' => 'taluka_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="<?php echo 'taluka_code' . '_error'; ?>" class="form-error"></span>
                        </div>
                    </div>
                </div>



                <?php
                //pr($result);
                if (isset($result)) {
                    echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'type' => 'hidden', 'value' => $result['taluka']['taluka_id']));
                }
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

                            <a href="<?php echo $this->webroot; ?>BlockLevels/taluka" class="btn btn-info "><?php echo __('btncancel'); ?></a>

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

                <div class="responstable">
                    <table id="table" class="table table-striped table-bordered table-condensed">  
                        <thead>  

                            <tr> 
                                <th class="center"><?php echo __('lbltalukacode'); ?></th> 
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbladmtaluka') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                                <?php } ?>

                                <th class="center width10"><?php echo __('lblaction'); ?></th>

                            </tr>  
                        </thead>
                        <tbody>
                            <!--<tr>--> 


                            <?php
                            //pr($talukadata);exit;
                            foreach ($talukadata as $talukarecord1) {
                                ?>
                                <tr>
                                    <td class="tblbigdata"><?php echo $talukarecord1[0]['taluka_code']; ?></td>
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $talukarecord1[0]['taluka_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>


                                    <td>
                                        <!--<a href="<?php //echo $this->webroot;  ?>BlockLevels/taluka/<?php //echo $talukarecord1[0]['taluka_id'];  ?>" class="btn-sm btn-default"><span class="fa fa-pencil"></span> </a>-->    
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'taluka', $talukarecord1[0]['taluka_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_taluka', $talukarecord1[0]['taluka_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                                    </td>  </tr> 
                            <?php } ?>

                        </tbody>

                    </table> 
                </div>

            </div>
        </div>




    </div>
</div>