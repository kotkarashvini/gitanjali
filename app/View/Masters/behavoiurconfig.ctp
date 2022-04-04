<script>

    $(document).ready(function (){

    $('#tablebehavioural').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            $('#tablebehaviouraldetails').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            $('#tablebehaviouralpattern').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            // --------------------Grid hide and show -------------------------------------//

            // ----------------------------------------  div behavioural ------------------------------------------------
            $('#btnshowbehavioural').hide();
            $("#btnhidebehavioural").click(function () {
    $('#btnshowbehavioural').show();
            $('#btnhidemain').hide();
            $('#divmain').hide();
            $('#behavioral_desc_'.$langcode['mainlanguage']['language_code']).val('');
            $('#btnshowbehaviouraldetails').show();
            $('#btnhidebehaviouraldetails').hide();
            $('#divsub').hide();
            $('#btnshowbehaviouralpattern').show();
            $('#btnhidebehaviouralpattern').hide();
            $('#divsubsub').hide();
            return false;
    });
            $("#btnshowbehavioural").click(function () {
    $('#btnhidebehavioural').show();
            $('#btnshowbehavioural').hide();
            $('#divmain').slideDown(1000);
            return false;
    });
            // ----------------------------------------  div behavioural Details ------------------------------------------------

            $('#btnhidebehaviouraldetails').hide();
            $('#divsub').hide();
            $("#btnhidebehaviouraldetails").click(function () {
    $('#btnshowbehaviouraldetails').show();
            $('#btnhidebehaviouraldetails').hide();
            $('#divsub').hide();
            $('#btnshowbehaviouralpattern').show();
            $('#btnhidebehaviouralpattern').hide();
            $('#divsubsub').hide();
            return false;
    });
            $("#btnshowbehaviouraldetails").click(function () {
    $('#btnhidebehaviouraldetails').show();
            $('#btnshowbehaviouraldetails').hide();
            $('#divsub').slideDown(1000);
            return false;
    });
            //----------------------------------------------- div behavioural pattern---------------------------------------------

            $('#btnhidebehaviouralpattern').hide();
            $('#divsubsub').hide();
            $("#btnhidebehaviouralpattern").click(function () {
    $('#btnshowbehaviouralpattern').show();
            $('#btnhidebehaviouralpattern').hide();
            $('#divsubsub').hide();
            $('#btnshowbehaviouralpattern').show();
            $('#btnhidebehaviouralpattern').hide();
            // $('#divsubsub').hide();

            return false;
    });
            $("#btnshowbehaviouralpattern").click(function () {
    $('#btnhidebehaviouralpattern').show();
            $('#btnshowbehaviouralpattern').hide();
            $('#divsubsub').slideDown(1000);
            return false;
    });
    });</script>  

<script>
            function formaddbehavioural() {

            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }

    //dyanamic function creation for collecting parameters in update function     
    function formupdatebehavioural(
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
    <?php echo 'behavioral_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>
    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#behavioral_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(behavioral_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>

    $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnaddbehavioural').html('Save');
            return false;
    }


</script> 

<script>
    function formaddbehaviouraldetails() {
    document.getElementById("hfactionbehaviouraldetail").value = 'S';
            document.getElementById("actiontypebehaviouraldetail").value = '2';
    }

    //dyanamic function creation for collecting parameters in update function     
    function formupdatebehaviouraldetails(behavioral_id,
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
    <?php echo 'behavioral_details_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>
    id) {


    document.getElementById("actiontypebehaviouraldetail").value = '2';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#behavioral_details_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(behavioral_details_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>

    $('#hfidbehaviouraldetail').val(id);
            $('#behavioral_id').val(behavioral_id);
            $('#hfupdateflagbehaviouraldetail').val('Y');
            $('#btnaddbehaviouraldetail').html('Save');
            return false;
    }


</script> 

<script>
    function formaddbehaviouralpattern() {

    document.getElementById("hfactionbehaviouralpattern").value = 'S';
            document.getElementById("actiontypebehaviouralpattern").value = '3';
    }
    function formupdatebehaviouralpattern(behavioral_id, behavioral_details_id,
<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'pattern_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>
    id) {
    document.getElementById("actiontypebehaviouralpattern").value = '3';
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#pattern_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(pattern_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfidbehaviouralpattern').val(id);
//            $('#behavioral_id').val(behavioral_id);
            $('#behavioral_id1').val(behavioral_id);
            $('#behavioral_details_id').val(behavioral_details_id);
            $('#hfupdateflagbehaviouralpattern').val('Y');
            $('#btnaddbehaviouralpattern').html('Save');
            return false;
    }
</script> 

<?php echo $this->Form->create('Behavioural', array('id' => 'Behavioural')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12"> 
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblbehaviouralconfiguration'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/BehaviouralConfig/behavoiurconfig_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <b><?php echo __('lblbehaviour'); ?></b>
                                            </th>
                                            <th style="text-align: right">
                                                <button id="btnshowbehavioural"  class="btn btn-primary "  >
                                                    <span class="glyphicon glyphicon-plus"></span></button> 
                                                <button id="btnhidebehavioural" class="btn btn-default "  >
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="divmain">
                                <div class="panel-body">
                                    <div id="selectBehavioural">
                                        <div class="row">
                                            <div class="form-group">
                                                <?php
                                                //  creating dyanamic text boxes using same array of config language
                                                foreach ($languagelist as $key => $langcode) {
                                                    ?>
                                                    <div class="col-md-3">
                                                        <label><?php echo __('lblbehavourdescription') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                                        <?php echo $this->Form->input('behavioral_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'behavioral_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '40')) ?>
                                                        <span id="<?php echo 'behavioral_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['behavioral_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                                        <div class="row center">
                                            <div class="form-group">
                                                <button id="btnaddbehavioural"type="submit" name="btnadd" class="btn btn-info "onclick="javascript: return formaddbehavioural();">
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                                </button>
                                                <button id="btncancel" name="btncancel" class="btn btn-info "  >
                                                    <span class="glyphicon glyphicon-reset"></span>&nbsp;&nbsp;<?php echo __('lblreset'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="rowht"></div>
                                    <hr style="border: 1px lightblue dotted;">
                                    <div  class="rowht"></div>
                                    <table id="tablebehavioural" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>  
                                                <?php
                                                foreach ($languagelist as $langcode) {
                                                    ?>
                                                    <th class="center"><?php echo __('lblbehavourdescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                                <?php } ?>
                                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php foreach ($behaviouralrecord as $behaviouralrecord1): ?>
                                                <tr>
                                                    <?php
                                                    foreach ($languagelist as $langcode) {
                                                        ?>
                                                        <td ><?php echo $behaviouralrecord1['Behavioural']['behavioral_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                                    <?php } ?>
                                                    <td >
                                                        <button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdatebehavioural(
                                                        <?php
                                                        foreach ($languagelist as $langcode) {
                                                            ?>
                                                                            ('<?php echo $behaviouralrecord1['Behavioural']['behavioral_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                        <?php } ?>
                                                                        ('<?php echo $behaviouralrecord1['Behavioural']['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                        </button>
                                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_behavioural', $behaviouralrecord1['Behavioural']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php unset($behaviouralrecord1); ?>
                                        
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                            <?php if (!empty($behaviouralrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

                        </div>
                    </div>
                    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
                    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
                    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
                    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
                </div>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>

                <!--====================================================================Behavioural Details==============================================-->
                <?php echo $this->Form->create('BehaviouralDetails', array('id' => 'BehaviouralDetails')); ?>  

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <b><?php echo __('lblbehaviordetails'); ?></b>
                                            </th>
                                            <th style="text-align: right">
                                                <button id="btnshowbehaviouraldetails"  class="btn btn-default ">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                </button>
                                                <button id="btnhidebehaviouraldetails" class="btn btn-default ">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button> 
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="divsub">
                                <div class="panel-body">
                                    <div id="selectBehavioural">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label"><?php echo __('lblselectbehavior'); ?><span style="color: #ff0000">*</span></label>    
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('behavioral_id', array('options' => array($Behaviouraldata), 'empty' => '--select--', 'id' => 'behavioral_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                                    <span id="behavioral_id_error" class="form-error"><?php //echo $errarr['behavioral_id_error']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="rowht"></div>
                                        <div class="row">
                                            <div class="form-group">
                                                <?php
                                                foreach ($languagelist as $key => $langcode) {
                                                    ?>
                                                    <div class="col-md-3">
                                                        <label><?php echo __('lblbehaviordetails') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                                        <?php echo $this->Form->input('behavioral_details_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'behavioral_details_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '30')) ?>
                                                        <span id="<?php echo 'behavioral_details_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['behavioral_details_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                                        <div class="row center">
                                                <div class="form-group">
                                                    <button id="btnaddbehaviouraldetail" type="submit"name="btnadd" class="btn btn-info "  onclick="javascript: return formaddbehaviouraldetails();">
                                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                                    </button>
                                                    <button id="btncancel" name="btncancel" class="btn btn-info " >
                                                        <span class="glyphicon glyphicon-reset"></span>&nbsp;&nbsp;<?php echo __('lblreset'); ?>
                                                    </button>
                                                </div>
                                        </div>
                                    </div>
                                    <div  class="rowht"></div>
                                    <hr style="border: 1px lightblue dotted;">
                                    <div  class="rowht"></div>
                                    <table id="tablebehaviouraldetails" class="table table-striped table-bordered table-hover" >
                                    <thead>  
                                        <tr>  
                                            <th class="center"><?php echo __('lblbehaviour'); ?></th>
                                            <?php
                                            foreach ($languagelist as $langcode) {
                                                ?>
                                                <th class="center"><?php echo __('lblbehaviordetails') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                            <?php } ?>
                                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                                        </tr>  
                                    </thead>
                                    <tbody>
                                        <?php foreach ($behaviouraldetailsrecord as $behaviouraldetailsrecord1): ?>
                                            <tr>
                                                <td ><?php echo $behaviouraldetailsrecord1['0']['behavioral_desc_en']; ?></td>
                                                <?php
                                                foreach ($languagelist as $langcode) {
                                                    ?>
                                                    <td ><?php echo $behaviouraldetailsrecord1['0']['behavioral_details_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                                <?php } ?>

                                                <td >
                                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdatebehaviouraldetails(('<?php echo $behaviouraldetailsrecord1['0']['behavioral_id']; ?>'),
                                                    <?php
                                                    foreach ($languagelist as $langcode) {
                                                        // pr($langcode);
                                                        ?>
                                                                        ('<?php echo $behaviouraldetailsrecord1['0']['behavioral_details_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                    <?php } ?>
                                                                    ('<?php echo $behaviouraldetailsrecord1['0']['id']; ?>'));">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                    </button>
                                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_behavioural_details', $behaviouraldetailsrecord1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                                </td>
                                            <?php endforeach; ?>
                                            <?php unset($behaviouraldetailsrecord1); ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>    
                            <?php if (!empty($behaviouraldetailsrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

                        </div>
                        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontypebehaviouraldetail'/>
                        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfidbehaviouraldetail'/>
                        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflagbehaviouraldetail'/>
                        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfactionbehaviouraldetail'/>
                    </div>

                </div>

                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
                <!--========================================================= Behavioural pattern =====================================================-->          
                <?php echo $this->Form->create('BehavioralPattens', array('id' => 'BehavioralPattens')); ?>        
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <b><?php echo __('lblbehaviorpattaern'); ?></b>
                                            </th>
                                            <th style="text-align: right">
                                                <button id="btnshowbehaviouralpattern"  class="btn btn-default "  >
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                </button> 
                                                <button id="btnhidebehaviouralpattern" class="btn btn-default "  >
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button> 
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="divsubsub">
                                <div class="panel-body">
                                    <div id="selectBehavioural">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label"><?php echo __('lblselectbehavior'); ?><span style="color: #ff0000">*</span></label>    
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('behavioral_id1', array('options' => array($Behaviouraldata), 'empty' => '--select--', 'id' => 'behavioral_id1', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                                    <span id="behavioral_id1_error" class="form-error"><?php //echo $errarr['behavioral_id1_error']; ?></span>
                                                </div>
                                                <label for="" class="col-sm-3 control-label"><?php echo __('lblselectbahaviordetails'); ?> <span style="color: #ff0000">*</span></label>    
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('behavioral_details_id', array('options' => array($Behaviouraldetailsdata), 'empty' => '--select--', 'id' => 'behavioral_details_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                                    <span id="behavioral_details_id_error" class="form-error"><?php //echo $errarr['behavioral_details_id_error']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="rowht"></div>
                                        <div class="row">
                                            <div class="form-group">
                                                <?php
                                                foreach ($languagelist as $key => $langcode) {
                                                    ?>
                                                    <div class="col-md-3">
                                                        <label><?php echo __('lblbehaviouralpatterns') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                                        <?php echo $this->Form->input('pattern_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'pattern_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '30')) ?>
                                                        <span id="<?php echo 'pattern_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['pattern_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                                        <div class="row center" >
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <button id="btnaddbehaviouralpattern"type="submit" name="btnadd" class="btn btn-info "  onclick="javascript: return formaddbehaviouralpattern();">
                                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                                    </button>
                                                    <button id="btncancel" name="btncancel" class="btn btn-info "  >
                                                        <span class="glyphicon glyphicon-reset"></span>&nbsp;&nbsp;<?php echo __('lblreset'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                       <div  class="rowht"></div>
                                    <hr style="border: 1px lightblue dotted;">
                                    <div  class="rowht"></div>                             
                                    <table id="tablebehaviouralpattern" class="table table-striped table-bordered table-hover" >
                                    <thead> 
                                        <tr>  
                                            <th class="center"><?php echo __('lblbehaviour'); ?></th>
                                            <th class="center"><?php echo __('lblbehaviordetails'); ?></th>
                                            <?php
                                            foreach ($languagelist as $langcode) {
                                                ?>
                                                <th class="center"><?php echo __('lblbehaviouralpatterns') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                            <?php } ?>
                                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                                        </tr>  
                                    </thead>
                                    <tbody>
                                        <?php foreach ($behaviouralpattenrecord as $behaviouralpattenrecord1): ?>
                                            <tr>
                                                <td ><?php echo $behaviouralpattenrecord1['0']['behavioral_desc_en']; ?></td>
                                                <td ><?php echo $behaviouralpattenrecord1['0']['behavioral_details_desc_en']; ?></td>
                                                <?php
                                                foreach ($languagelist as $langcode) {
                                                    ?>
                                                    <td ><?php echo $behaviouralpattenrecord1['0']['pattern_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                                <?php } ?>
                                                <td >
                                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit"  class="btn btn-default "   onclick="javascript: return formupdatebehaviouralpattern(('<?php echo $behaviouralpattenrecord1['0']['behavioral_id']; ?>'), ('<?php echo $behaviouralpattenrecord1['0']['behavioral_details_id']; ?>'),
                                                    <?php
                                                    foreach ($languagelist as $langcode) {
                                                        ?>
                                                                        ('<?php echo $behaviouralpattenrecord1['0']['pattern_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                    <?php } ?>
                                                                    ('<?php echo $behaviouralpattenrecord1['0']['id']; ?>'));">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                    </button>
                                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_behavioural_pattens', $behaviouralpattenrecord1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php unset($behaviouralpattenrecord1); ?>
                                    </tbody>
                                </table>
                                </div>

                                
                            </div>
                            <?php if (!empty($behaviouraldetailsrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

                        </div>
                    </div>
                    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontypebehaviouralpattern'/>
                    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfactionbehaviouralpattern'/>
                    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfidbehaviouralpattern'/>
                    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflagbehaviouralpattern'/>
                </div>

            </div>
        </div>
    </div>

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
