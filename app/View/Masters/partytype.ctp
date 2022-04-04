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
    function formcancel() {

    document.getElementById("actiontype").value = '2';
    }
    function formupdate(
<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'party_type_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    display_flag, party_type_flag, id){
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#party_type_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(party_type_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#display_flag').val(display_flag);
            $('#party_type_flag').val(party_type_flag);
            $('input:radio[name="data[partytype][display_flag]"][value=' + display_flag + ']').attr('checked', true);
            $('input:radio[name="data[partytype][party_type_flag]"][value=' + party_type_flag + ']').attr('checked', true);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
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
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/partytype_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                                    <?php echo $errarr['party_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="From_Date" class="col-sm-3"><?php echo __('lbldisplayflag'); ?></label>   
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('display_flag', array('type' => 'radio', 'options' => array('C' => '&nbsp;Active&nbsp;&nbsp;', 'O' => '&nbsp;De-Active'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'display_flag')); ?>
                        </div>

                        <label for="From_Date" class="col-sm-3"><?php echo __('lblpartytypeshow'); ?> Flag.</label>   
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('party_type_flag', array('type' => 'radio', 'options' => array('1' => '&nbsp;Seller&nbsp;&nbsp;', '0' => '&nbsp;Puchaser'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'party_type_flag')); ?>
                        </div>
                    </div> 
                </div>

                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button type="submit"  id="btncancel" name="btncancel" class="btn btn-info" onclick="javascript: return formcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>


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
                                        <button id="btnupdate" name="btnupdate" type="button" class="btn btn-default "   onclick="javascript: return formupdate(
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                                    ('<?php echo $partytyperecord1['partytype']['party_type_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                                ('<?php echo $partytyperecord1['partytype']['display_flag']; ?>'), ('<?php echo $partytyperecord1['partytype']['party_type_flag']; ?>'), ('<?php echo $partytyperecord1['partytype']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default "     onclick="javascript: return formdelete(
                                                                                                        ('<?php echo $partytyperecord1['partytype']['id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_partytype', $partytyperecord1['partytype']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
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