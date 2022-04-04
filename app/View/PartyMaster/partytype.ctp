<!-- PAGE RECREATED BY SHAIKH SHAJI IBRAHIM [26-FEB-2020] -->

<script>
    $(document).ready(function () {
    $('#tablepartytype').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });</script>

<script>
            function formadd() {

            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
 

</script>

   <?php echo $this->Form->create('partytype', array('id' => 'partytype')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpartytypeshow'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/PartyMaster/party_type_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                                <label><?php echo __('lblpartytypeshow') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('party_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'party_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'party_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php //echo $errarr['party_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="display_flag" class="col-sm-3"><?php echo __('lbldisplayflag'); ?></label>   
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('display_flag', array('type' => 'radio', 'options' => array('C' => '&nbsp;Active&nbsp;&nbsp;', 'O' => '&nbsp;De-Active'), 'value' => 'C', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'display_flag')); ?>
                        </div>

                        <label for="party_type_flag" class="col-sm-3"><?php echo __('lblpartytypeshow'); ?> Flag.</label>   
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('party_type_flag', array('type' => 'radio', 'options' => array('1' => '&nbsp;Seller&nbsp;&nbsp;', '0' => '&nbsp;Puchaser'), 'value' => '0', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'party_type_flag')); ?>
                        </div>
                         <label for="presenter_flag" class="col-sm-3"><?php echo __('lblispresenterflag'); ?> Flag.</label>   
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('presenter_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;YES&nbsp;&nbsp;', 'N' => '&nbsp;NO'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'presenter_flag')); ?>
                        </div>
                    </div> 
                </div>

                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                      
                        
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                        </button>
<!--                        <button type="submit"  id="btncancel" name="btncancel" class="btn btn-info" onclick="javascript: return formcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>-->
                              <a href="<?php echo $this->webroot; ?>PartyMaster/Partytype" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                    </div>
                </div>
 <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'maxlength' => "255")) ?>

            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectpartytype">
                    <table id="tablepartytype" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    // pr($langcode);
                                    ?>
                                    <th class="center"><?php echo __('lblpartytypeshow') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center"><?php echo __('lbldisplayflag'); ?></th>
                                <th class="center"><?php echo __('lblpartytypeshow'); ?> Flag.</th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($partytyperecord as $partytyperecord1): ?>
                                <tr>
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td class="center"><?php echo $partytyperecord1['partytype']['party_type_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td class="center"><?php echo $partytyperecord1['partytype']['display_flag']; ?></td>
                                    <td class="center"><?php echo $partytyperecord1['partytype']['party_type_flag']; ?></td>       
                                    <td >
                                      
                                         <!--<a href="<?php echo $this->webroot; ?>PartyMaster/Partytype/<?php echo $partytyperecord1['partytype']['party_type_id']; ?>" class="btn-sm btn-success"><span class="fa fa-pencil"></span> </a>-->
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'partytype', $partytyperecord1['partytype']['party_type_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?></a>
                                       
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_partytype', $partytyperecord1['partytype']['party_type_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure?')); ?></a>
                                    </td>
                                <?php endforeach; ?>
                                <?php unset($partytyperecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($partytyperecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>  
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
</div>