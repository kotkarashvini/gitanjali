<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    //$("#census_code_changedate").datepicker();
    $('#tabledivisionnew').dataTable({
           "order":[],
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });</script>
<script>
    //document.getElementById("hfupdateflag").value = 'S';
    function formadd() {
    document.getElementById("actiontype").value = '1';
    document.getElementById("hfaction").value = 'S';
    }

    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'district_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> division_id,district_id, district_code, census_code,old_census_code,census_code_changedate){ 
var r=confirm("Are you sure to edit");
if(r==true){
    document.getElementById("actiontype").value = '1';

<?php
foreach ($languagelist as $langcode) {
    ?>
        //        alert(census_code_changedate);
        $('#district_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(district_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
     
    
        
<?php } ?>
     
      $('#division_id').val(division_id);   
      $('#district_code').val(district_code);
        $('#census_code').val(census_code);
        $('#old_census_code').val(old_census_code);
        $('#census_code_changedate').val(census_code_changedate);
        
    $('#hfupdateflag').val('Y');
 
    $('#hfid').val(district_id);
    $('#btnadd').html('<?php echo __('btnupdate');?>');
    return false;
}
    }
</script> 

<?php echo $this->Form->create('district_new', array('id' => 'district_new', 'autocomplete' => 'off')); ?>
 <?php echo $this->element("BlockLevel/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
  <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmdistrict'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/District/district_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">

                    <?php
                    if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                        ?>
                        <div class="col-sm-2">
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span class="star">*</span></label>
                            <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="division_id_error"></span>
                        </div>
                    <?php } ?>





                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbladmdistrict') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('district_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'district_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'district_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php //echo $errarr['district_name_' . $langcode['mainlanguage']['language_code'] . '_error'];    ?>
                                </span>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">

                        <div class="col-sm-2">
                            <label for="census_code" control-label><?php echo __('District code'); ?> <span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('district_code', array('label' => false, 'id' => 'district_code', 'class' => 'form-control input-sm', 'type' => 'text','maxlength'=>'10')) ?>
                            <span id="district_code_error" class="form-error"><?php // echo $errarr['district_code_error'];       ?></span>
                        </div> 

                        <div class="col-sm-2">
                            <label for="census_code" control-label"><?php echo __('lblCensusCode'); ?> <span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('census_code', array('label' => false, 'id' => 'census_code', 'class' => 'form-control input-sm', 'type' => 'text','maxlength'=>'50')) ?>
                            <span id="census_code_error" class="form-error"><?php echo $errarr['census_code_error']; ?></span>
                        </div> 

<!--                        <div class="col-sm-2">
                            <label for="old_census_code" control-label"><?php echo __('lbloldcencuscode'); ?></label> 
                            <?php //echo $this->Form->input('old_census_code', array('label' => false, 'id' => 'old_census_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="old_census_code_error" class="form-error"><?php echo $errarr['old_census_code_error']; ?></span>
                        </div> -->

<!--                        <div class="col-sm-2">
                            <label for="census_code_changedate" control-label"><?php echo __('lblcensuscodechagedate'); ?></label>
                            <?php //echo $this->Form->input('census_code_changedate', array('label' => false, 'id' => 'census_code_changedate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="census_code_changedate_error" class="form-error"><?php echo $errarr['census_code_changedate_error']; ?></span>
                        </div> -->
                    </div>
                </div>
               
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <a href="<?php echo $this->webroot;?>BlockLevels/district_new" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                        </div>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">

                <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                    <thead>  
                        <tr>  
                            <th class="center"><?php echo __('District code'); ?></th>
                            <th class="center"><?php echo __('lblCensusCode'); ?></th>  
                            <?php foreach ($languagelist as $langcode) { ?>
                                <th class="center"><?php echo __('lbladmdistrict') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                            <?php } ?>
                            
                                                     
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($districtrecord as $districtrecord1): ?>
                            <tr>
                                 <td><?php echo $districtrecord1['District']['district_code']; ?></td>
                                 <td><?php echo $districtrecord1['District']['census_code']; ?></td>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td><?php echo $districtrecord1['District']['district_name_' . $langcode['mainlanguage']['language_code']]; ?></td>

                                <?php } ?>
                               
                                
                                <td >
                                    <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-success " onclick="javascript: return formupdate(
                                    <?php foreach ($languagelist as $langcode) { ?>
                                            ('<?php echo $districtrecord1['District']['district_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                        ('<?php echo $districtrecord1['District']['division_id']; ?>'),
                                        ('<?php echo $districtrecord1['District']['district_id']; ?>'),
                                        ('<?php echo $districtrecord1['District']['district_code']; ?>'),                                        
                                                ('<?php echo $districtrecord1['District']['census_code']; ?>'),
                                                ('<?php echo $districtrecord1['District']['old_census_code']; ?>'),
                                     
                                         ('<?php echo $districtrecord1['District']['census_code_changedate']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'BlockLevels', 'action' => 'encrypt', $districtrecord1['District']['district_id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'district_new_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($districtrecord1); ?>
                    </tbody>
                </table> 
                <?php if (!empty($districtrecord)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
         <!--<span id="actiontype_error" class="form-error"><?php //echo $errarr['actiontype_error'];            ?></span>-->
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='S' name='hfupdateflag' id='hfupdateflag'/>

    <?php //echo $hfupdateflag;    ?>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

