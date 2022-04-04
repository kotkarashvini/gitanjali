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
    $('#tablebehavioural').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tablebehavioural').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#category_name_en').focus();
    }
    });</script>

<script>
           

  
</script> 

<?php echo $this->Form->create('party_category', array('id' => 'party_category', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpartycategoryshow'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/PartyMaster/category_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-2">
                                <label>
                                    <?php echo __('lblpartycategory') . "  " .   $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>   
                            </div>
                            <div class="col-md-2">
                                <?php
                                echo $this->Form->input('category_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'category_name_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '100'))
                                ?>
                                <span id="<?php echo 'category_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['category_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group center">
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
 <a href="<?php echo $this->webroot; ?>PartyMaster/party_category" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                    </div>
                </div>
            </div>
                <?php
                                echo $this->Form->input('category_id' , array('label' => false, 'id' => 'category_id' ,'class' => 'form-control input-sm','type' => 'hidden'))  ?>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebehavioural" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblcategory') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($categoryrecord as $categoryrecord1): ?>
                                <tr>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td><?php echo $categoryrecord1['party_category']['category_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td>
                                        <!--<a href="<?php echo $this->webroot; ?>PartyMaster/party_category/<?php echo $categoryrecord1['party_category']['category_id']; ?>" class="btn-sm btn-success"><span class="fa fa-pencil"></span> </a>-->    
                                       
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'party_category', $categoryrecord1['party_category']['category_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?></a>                                        
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_category', $categoryrecord1['party_category']['category_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($categoryrecord1); ?>
                        </tbody>
                    </table>
                    <?php //if (!empty($feetyperecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php //} else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php// } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php //echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php //echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php //echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php// echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




