<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    $("#census_code_changedate").datepicker();
            $('#tabledivisionnew').dataTable({
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
<?php } ?> id, census_code, old_census_code <?php foreach ($languagelist as $langcode) { if($langcode['mainlanguage']['language_code']!='en'){ ?>
            ,<?php echo 'dist_' . $langcode['mainlanguage']['language_code'].'_activation_flag'?>
<?php } } ?>, census_code_changedate) {
            document.getElementById("actiontype").value = '1';
<?php
foreach ($languagelist as $langcode) {
    ?>
                //        alert(census_code_changedate);
                $('#district_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(district_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
                        $('#census_code').val(census_code);
                        $('#old_census_code').val(old_census_code);
                        $('#census_code_changedate').val(census_code_changedate);
<?php } ?>
            $('#hfupdateflag').val('Y');
            
            <?php
            
            foreach ($languagelist as $langcode) {
              if($langcode['mainlanguage']['language_code']!='en'){
            // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
            ?>
            $('input:radio[name="data[district_new][dist_<?php echo $langcode['mainlanguage']['language_code']; ?>_activation_flag]"]').filter('[value="' + dist_<?php echo $langcode['mainlanguage']['language_code']; ?>_activation_flag + '"]').attr('checked', true);
            
            <?php } } ?>
                
                    $('#hfid').val(id);
                    $('#btnadd').html('Save');
                    return false;
            }
</script> 

<?php echo $this->Form->create('district_new', array('id' => 'district_new', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="pull-left note"> Note: <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?></div><br>   
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmdistrict'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/District/district_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbladmdistrict') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('district_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'district_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                <span id="<?php echo 'district_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['district_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="census_code" control-label"><?php echo __('lblCensusCode'); ?></label> 
                            <?php echo $this->Form->input('census_code', array('label' => false, 'id' => 'census_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="census_code_error" class="form-error"><?php echo $errarr['census_code_error']; ?></span>
                        </div> 

                        <div class="col-sm-3">
                            <label for="old_census_code" control-label"><?php echo __('lbloldcencuscode'); ?></label> 
                            <?php echo $this->Form->input('old_census_code', array('label' => false, 'id' => 'old_census_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="old_census_code_error" class="form-error"><?php echo $errarr['old_census_code_error']; ?></span>
                        </div> 

                        <div class="col-sm-3">
                            <label for="census_code_changedate" control-label"><?php echo __('lblcensuscodechagedate'); ?></label>
                            <?php echo $this->Form->input('census_code_changedate', array('label' => false, 'id' => 'census_code_changedate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="census_code_changedate_error" class="form-error"><?php echo $errarr['census_code_changedate_error']; ?></span>
                        </div> 
                    </div>
                </div>
                  <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <?php
                                foreach ($languagelist as $key => $langcode) {
                                    $langcd=$langcode['mainlanguage']['language_code'];
                                    $nn2='data[district_new][dist_'.$langcd.'_activation_flag]';
                                    $langnm=$langcode['mainlanguage']['language_name'];
                                    if($langcd!='en'){
                            ?>
                            <label for="census_code" control-label"><?php echo $langcode['mainlanguage']['language_name'].' activation flag'; ?></label> 
                            <input type="radio" value="Y" name="<?php echo $nn2;?>"  id="activationY" >Yes
                            <input type="radio" value="N" name="<?php echo $nn2;?>"  id="activationN">No
                            <!--<span id="census_code_error" class="form-error"><?php echo $errarr['census_code_error']; ?></span>-->
                            <?php
                            }
                                }
                            ?>
                        </div> 
                    </div>
                  </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
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

                            <?php foreach ($languagelist as $langcode) { ?>
                                <th class="center"><?php echo __('lbladmdistrict') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>

                            <?php } ?>
                            <th class="center"><?php echo __('lblCensusCode'); ?></th>
                            <th class="center"><?php echo __('lbloldcencuscode'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($districtrecord as $districtrecord1): ?>
                            <tr>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $districtrecord1['District']['district_name_' . $langcode['mainlanguage']['language_code']]; ?></td>

                                <?php } ?>
                                <td ><?php echo $districtrecord1['District']['census_code']; ?></td>
                                <td ><?php echo $districtrecord1['District']['old_census_code']; ?></td>
                                <td >
                                    <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  onclick="javascript: return formupdate(
                                    <?php foreach ($languagelist as $langcode) { ?>
                                                                    ('<?php echo $districtrecord1['District']['district_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>

                                                                ('<?php echo $districtrecord1['District']['id']; ?>'),
                                                                        ('<?php echo $districtrecord1['District']['census_code']; ?>'),
                                                                        ('<?php echo $districtrecord1['District']['old_census_code']; ?>')
                                                                
                                                                        <?php foreach ($languagelist as $langcode) {
                                                                        if($langcode['mainlanguage']['language_code']!='en'){
                                                                        ?>
                                                                    ,('<?php echo $districtrecord1['District']['dist_' . $langcode['mainlanguage']['language_code'].'_activation_flag']; ?>')
                                                                    <?php }
                                                                    }
                                                                    ?> 
                                                                     ,('<?php echo $districtrecord1['District']['census_code_changedate']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'Masters', 'action' => 'encrypt', $districtrecord1['District']['id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'district_new_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
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
         <!--<span id="actiontype_error" class="form-error"><?php //echo $errarr['actiontype_error'];      ?></span>-->
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='S' name='hfupdateflag' id='hfupdateflag'/>

    <?php //echo $hfupdateflag;  ?>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

