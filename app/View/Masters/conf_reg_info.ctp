<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {

//        if ($('#hfhidden1').val() === 'Y')
//        {
//            $('#tableconfinfo').dataTable({
//                "iDisplayLength": 10,
//                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//            });
//        } else {
//            $('#tableconfinfo').dataTable({
//                "iDisplayLength": 10,
//                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//            });
//        }

        $('#conf_val_label').hide();

<?php
$c = count($conf_info);
for ($i = 0; $i < $c; $i++) {

    if ($conf_info[$i]['conf_reg_bool_info']['is_boolean'] == 'Y') {
        ?>
                $('#conf_val1_' +<?php echo $conf_info[$i]['conf_reg_bool_info']['reginfo_id'] ?>).hide();
                $('#conf_val_' +<?php echo $conf_info[$i]['conf_reg_bool_info']['reginfo_id']; ?>).show();
    <?php } else { ?>
                $('#conf_val1_' +<?php echo $conf_info[$i]['conf_reg_bool_info']['reginfo_id'] ?>).show();
                $('#conf_val_' +<?php echo $conf_info[$i]['conf_reg_bool_info']['reginfo_id']; ?>).hide();
        <?php
    }
}
?>
    });

    function toggleY(id)
    {
        $('#conf_val_' + id).show();
        $('#conf_val1_' + id).hide();
    }

    function toggleN(id)
    {
        $('#conf_val1_' + id).show();
        $('#conf_val_' + id).hide();
    }
</script>


<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <div style="float: left;">
                    <?php echo $this->Html->link(__('Download'), array('controller' => 'Reports', 'action' => 'confreg'), array('class' => 'btn btn-primary', 'style="float:right"')); ?>
                </div> 
                <center><h3 class="box-title headbolder"><?php echo __('lblconfregboolinfo'); ?></h3></center>

                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/conf_reg_info_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div id="selectconfinfo" class="table-responsive">
                    <table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lbldesc'); ?> <?php echo " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblisboolian'); ?></th>
                                <th class="center width10" id=""><?php echo __('lblconfval'); ?></th>
                                <th class="center width10" id="conf_val_label"><?php echo __('lblconfval'); ?></th>
                                <th class="center width10"><?php echo __('lblinfoval'); ?></th>
                                <th class="center width5"><?php echo __('lblDisplayOrder'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php
//  creating dyanamic text boxes using same array of config language
                            $i = 0;
                            foreach ($conf_info as $conf_info) {
                                ?>
                                <tr>
                                    <?php echo $this->Form->create('config_boolean', array('id' => 'config_boolean', 'autocomplete' => 'off')); ?>
                                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td class="tblbigdata"><?php echo $conf_info['conf_reg_bool_info']['conf_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td class="tblbigdata"><input type="radio" value="Y" name="is_boolean_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" id="is_booleanY_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" onclick="javascript: return toggleY('<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>');"   <?php if ($conf_info['conf_reg_bool_info']['is_boolean'] == 'Y') { ?> checked <?php } ?> >Yes
                                        <input type="radio" value="N" name="is_boolean_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" id="is_booleanN_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>"  onclick="javascript: return toggleN('<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>');"  <?php if ($conf_info['conf_reg_bool_info']['is_boolean'] == 'N') { ?> checked <?php } ?>>No.</td>
                                    <td class="tblbigdata" id="conf_val1_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" ></td>
                                    <td class="tblbigdata"  id="conf_val_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>">
                                        <input type="radio" value="Y" name="conf_bool_value_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" id="conf_bool_valueY_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>"   <?php if ($conf_info['conf_reg_bool_info']['conf_bool_value'] == 'Y') { ?> checked <?php } ?>>Yes
                                        <input type="radio" value="N" name="conf_bool_value_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" id="conf_bool_valueN_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" <?php if ($conf_info['conf_reg_bool_info']['conf_bool_value'] == 'N') { ?> checked <?php } ?>>No.</td>
                                    <td class="tblbigdata"><input type="textbox" name="info_value" id="info_value_<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" value="<?php echo $conf_info['conf_reg_bool_info']['info_value']; ?>"/></td>
                                    <td class="tblbigdata"><input type="textbox" name="display_order" id="info_value_<?php echo $conf_info['conf_reg_bool_info']['display_order']; ?>" value="<?php echo $conf_info['conf_reg_bool_info']['display_order']; ?>"/></td>

                                    <td class="width10"><button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();"><?php echo __('btnsave'); ?></button></td>
                            <input type="hidden" name="info_id" value="<?php echo $conf_info['conf_reg_bool_info']['reginfo_id']; ?>" />
                            <?php echo $this->Form->end(); ?>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php if (!empty($conf_info)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div> 
    </div>
    <input type='hidden' value='<?php // echo$actiontypeval;                            ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php //echo $hfactionval;                            ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php //echo $hfid;                          ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php //echo $hfupdateflag;                         ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




