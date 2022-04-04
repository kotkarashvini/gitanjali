<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        //$("#census_code_changedate").datepicker();
        $('#tabledivisionnew').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>

<script>
    //document.getElementById("hfupdateflag").value = 'S';
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    } 
</script> 

<?php echo $this->Form->create('usage_category', array('id' => 'usage_category', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblusagecategorylinkage'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/usagecatlink_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="usage_main_catg_id" class="control-label"><?php echo __('lblusamaincat'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('usage_main_catg_id', array('options' => $main_cat_data, 'empty' => '--select--', 'id' => 'usage_main_catg_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                        <span class="form-error" id="usage_main_catg_id_error"></span>
                    </div>

                    <div class="col-sm-2">
                        <label for="usage_sub_catg_id" class="control-label"><?php echo __('lblsubcat'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('usage_sub_catg_id', array('options' => $sub_cat_data, 'empty' => '--select--', 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                        <span class="form-error" id="usage_main_catg_id_error"></span>
                    </div>
                </div>
            </div>
            <div class="box-body">                
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
                            <a href="<?php echo $this->webroot; ?>Property/usage_category" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                        </div>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                        <?php echo $this->Form->input('usage_cat_id', array('label' => false, 'id' => 'usage_cat_id', 'type' => 'hidden')); ?>
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
                                <th class="center"><?php echo __('lblusamaincat') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                
                            <?php } ?>    
                                
                            <?php foreach ($languagelist as $langcode) { ?>
                                
                                <th class="center"><?php echo __('lblsubcat') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                            <?php } ?> 
                                
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($udagecatrecord as $districtrecord1) { ?>
                            <tr>  
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td><?php echo $districtrecord1[0]['usage_main_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    
                                <?php } ?>
                                    
                                <?php
                                   foreach ($languagelist as $langcode) {
                                 ?>
                                    <td><?php echo $districtrecord1[0]['usage_sub_catg_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>

                                <?php } ?>

                                <td>
                                    
                              
                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'Property', 'action' => 'encrypt', $districtrecord1[0]['usage_cat_id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'usage_category', $districtrecord1[0]['usage_cat_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to edit?')); ?>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'usage_category_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to delete?')); ?>
                                </td>

                            </tr>
                        <?php } ?>

                        <?php  unset($districtrecord1);   ?>
                    </tbody>
                </table> 
                <?php if (!empty($districtrecord)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
         <!--<span id="actiontype_error" class="form-error"><?php //echo $errarr['actiontype_error'];                                ?></span>-->
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='S' name='hfupdateflag' id='hfupdateflag'/>

    <?php //echo $hfupdateflag;      ?>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

