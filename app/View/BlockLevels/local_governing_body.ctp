<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }
        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tablelocal_governing_body').dataTable({
                   "order":[],
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tablelocal_governing_body').dataTable({
                   "order":[],
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
            $('#class_description_en').focus();
        }
    });</script>

<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }
    function formcancel() {

        document.getElementById("actiontype").value = '5';
    }
    //dyanamic function creation for collecting parameters in update function     




</script> 

<?php echo $this->Form->create('local_governing_body', array('id' => 'local_governing_body', 'autocomplete' => 'off')); ?>
<?php echo $this->element("BlockLevel/main_menu"); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: red">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbllocalgoberningbody'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/LocalGoverningBody/local_governing_body_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>

                            <div class="col-md-3">
                                <label><?php echo __('lbllocalgoberningbody') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label> 
                                <?php echo $this->Form->input('class_description_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'class_description_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'class_description_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

                                </span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                        <?php echo $this->Form->input('ulb_type_id', array('label' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'type' => 'hidden')) ?>
                    </div>
                </div>

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

                            <a href="<?php echo $this->webroot; ?>BlockLevels/local_governing_body" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectlocal_governing_body">
                    <table id="tablelocal_governing_body" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  

                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lbllocalgoberningbody') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>

<!--                                <th class="center"><?php// echo __('lblclasstype'); ?></th>-->
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($governingbody as $governingbody1):
                              //  pr($governingbody1);exit;
                                ?>
                                <tr>
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $governingbody1['local_governing_body']['class_description_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

<!--                                    <td ><?php //echo $governingbody1['local_governing_body']['class_type']; ?></td>-->

                                    <td >

                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'BlockLevels', 'action' => 'encrypt', $governingbody1['local_governing_body']['ulb_type_id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>
                                        <!--<a href="<?php echo $this->webroot; ?>BlockLevels/local_governing_body/<?php //echo $governingbody1['local_governing_body']['ulb_type_id']; ?>" class="btn-sm btn-default"><span class="fa fa-pencil"></span> </a>-->    
                                       <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'local_governing_body', $governingbody1['local_governing_body']['ulb_type_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_local_governing_body', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($governingbody1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($governingbody)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




