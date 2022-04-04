<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag === 'Y')
    {
    $('#btnadd').html('Save');
    }
//    if ($('#hfhidden1').val() === 'Y')
//    {
//    $('#tablebehavioural').dataTable({
//    "iDisplayLength": 5,
//            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
//    });
//    } else {
//    $('#tablebehavioural').dataTable({
//    "iDisplayLength": 5,
//            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
//    });
//    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#payment_mode_desc_en').focus();
    }
    });</script>

<script>
    $(document).ready(function () {
        $('#tablebehavioural').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>  


<script>
            function formadd() {

            document.getElementById("actiontype").value = '1';
                   //TEST  document.getElementById("hfaction").value = 'S';
            }

    function formupdate(
<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'payment_mode_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>  id) {
            var r=confirm("Are you sure to edit");
    if(r==true){
    document.getElementById("actiontype").value = '1';
<?php foreach ($languagelist as $langcode) { ?>
        $('#payment_mode_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(payment_mode_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>

    $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }
    }
</script> 

<?php echo $this->Form->create('payment_mode', array('id' => 'payment_mode', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpaymentmode'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/fee/payment_mode_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group" >
                         <div class="col-md-2 ">
                        <label for="payment_mode_id" class=" control-label"><?php echo __('lblpaymentmodeid'); ?><span style="color: #ff0000">*</span></label>
                        <?php echo $this->Form->input("payment_mode_id", array('label' => false, 'id' => 'payment_mode_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '6')) ?>
                        <span id="village_code_error" class="form-error"></span>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblpaymentmodename') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('payment_mode_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'payment_mode_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '255'))
                                ?>
                                <span id="<?php echo 'payment_mode_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['payment_mode_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                         
                   
                      
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                      
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group" >
                       
                        <div class="col-md-2 ">
                        <label for="start_date" class=" control-label"><?php echo __('Start date'); ?></label>
                        <?php echo $this->Form->input("start_date", array('label' => false, 'id' => 'start_date', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '6')) ?>
                        <span id="village_code_error" class="form-error"></span>
                    </div>
                        <div class="col-md-2 ">
                        <label for="end_date" class=" control-label"><?php echo __('End date'); ?></label>
                        <?php echo $this->Form->input("end_date", array('label' => false, 'id' => 'end_date', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '6')) ?>
                        <span id="village_code_error" class="form-error"></span>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" >
                          <div class="col-sm-2">
                          <label>
                                    <?php echo __('lblpaymentmodeactive'); ?><span style="color: #ff0000">*</span>
                                </label> 
                                  <?php echo $this->Form->input('active_flag', array('label' => false, 'id' => 'active_flag', 'class' => 'form-control input-sm', 'options' => array( 'empty' => '--select--',$paymentActivation))); ?>
                     </div>
                        <div class="col-sm-2">
                          <label>
                                    <?php echo __('lblpaymentmodeverification'); ?><span style="color: #ff0000">*</span>
                                </label> 
                                  <?php echo $this->Form->input('verification_flag', array('label' => false, 'id' => 'verification_flag', 'class' => 'form-control input-sm', 'options' => array( 'empty' => '--select--',$paymentverification))); ?>
                            <?php //echo $this->Form->input('is_required', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_required',$val)); ?>
                     </div>
                      
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
<!--                        <button id="btnadd" name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php //echo __('lblbtnAdd'); ?>
                        </button>-->
                         <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>
 <a href="<?php echo $this->webroot; ?>Fee/payment_mode" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    
                    
                    <table id="tablebehavioural" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center width10"><?php echo __('lblpaymentmodeid'); ?></th>
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"> <?php echo __('lblpaymentmode') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                    
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($payment_mode as $payment_mode1): ?>
                                <tr>
                                    <td><?php echo $payment_mode1['0']['payment_mode_id']; ?></td>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td ><?php echo $payment_mode1['0']['payment_mode_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                        
                                        <td>
                                        <!--<a href="<?php echo $this->webroot; ?>Fee/payment_mode/<?php echo $payment_mode1['0']['id']; ?>"class="btn-sm btn-success"><span class="fa fa-pencil"></span> </a>-->    
                                        
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'payment_mode', $payment_mode1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>

                                        
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_payment_mode', $payment_mode1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($payment_mode1); ?>
                        </tbody>
                    </table>
                    <?php// if (!empty($payment_mode)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php// } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php// } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php //echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php //echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php// echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




