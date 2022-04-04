<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    //$("#census_code_changedate").datepicker();
    $('#tabledivisionnew').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });
</script>

<script>
    //document.getElementById("hfupdateflag").value = 'S';
    function formadd() {
    document.getElementById("actiontype").value = '1';
    document.getElementById("hfaction").value = 'S';
    }



    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>usage_sub_catg_id) {
var r=confirm("Are you sure to edit");
if(r==true){
    document.getElementById("actiontype").value = '1';

$('#btnadd').html('<?php echo __('btnupdate');?>');
<?php
foreach ($languagelist as $langcode) {
    ?>
              //  alert(usage_main_catg_id);
        $('#usage_sub_catg_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(usage_sub_catg_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
     
<?php } ?>
        
    $('#hfupdateflag').val('Y');
 
    $('#hfid').val(usage_sub_catg_id);
    $('#btnadd').html('<?php echo __('btnupdate');?>');
    return false;
    }
}
    
</script> 

<?php echo $this->Form->create('usage_sub_cat', array('id' => 'usage_sub_cat', 'autocomplete' => 'off')); ?>
 
<div class="row">
    <div class="col-lg-12">
  <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblsubcat'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/usagesub_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-5">
                                <label><?php echo __('lblUsagesubcategoryname') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-lg', 'type' => 'text', 'maxlength' => '255')) ?>
                                <span id="<?php echo 'usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php //echo $errarr['district_name_' . $langcode['mainlanguage']['language_code'] . '_error'];    ?>
                                </span>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <a href="<?php echo $this->webroot;?>Property/usage_sub_cat" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
                                <th class="center"><?php echo __('lblUsagesubcategoryname') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                            <?php } ?>
                            
                                                     
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($subcatrecord as $districtrecord1):?>
                            <tr>
                                
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td><?php echo $districtrecord1['usage_sub_category']['usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>

                                <?php } ?>
                               
                                
                                 <td >
                                    <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-success "  onclick="javascript: return formupdate(
                                    <?php foreach ($languagelist as $langcode) { ?>
                                            ('<?php echo $districtrecord1['usage_sub_category']['usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                        ('<?php echo $districtrecord1['usage_sub_category']['usage_sub_catg_id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'Property', 'action' => 'encrypt', $districtrecord1['usage_sub_category']['usage_sub_catg_id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'usage_sub_cat_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to delete?')); ?></a>
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

