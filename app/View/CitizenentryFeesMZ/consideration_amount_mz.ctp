 

<?php echo $this->Form->create('consideration_amount', array('id' => 'consideration_amount', 'class' => 'form-vertical')); ?>

<?php
$doc_lang = $this->Session->read('doc_lang');
echo $this->element("Registration/main_menu");
echo $this->element("Citizenentry/property_menu");
?>
<script>
    $(document).ready(function () {
        <?php if(!empty($fee)){?>
        $('#myModal').modal('show');
        <?php } ?>

    });
</script>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Notice</h4>
            </div>
            <div class="modal-body">
                <p>If changing Consideration amount fee calculation will removed. You should recalculate the fee.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblconsideration_amount_entry'); ?></h3></center>
                <div class="box-tools pull-right">
                    <!--<a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/final_submit_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>-->
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><b><?php echo __('lbltokenno'); ?>:-</b><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="prop_list_tbl" class="table table-striped table-bordered table-hover"> 
                    <thead >
                        <tr >
                            <th class="center">   <?php echo __('lblpropertydetails'); ?>  </th>
                            <th class="center">   <?php echo __('lblcityvillage'); ?> </th>
                            <th class="center">   <?php echo __('lbllocation'); ?> </th>
                            <th class="center"> <?php echo __('lblusage'); ?>  </th>
                            <th class="center"> <?php echo __('lblvalamount'); ?>  </th>
                            <th class="center" style="width: 30%;">   <?php echo __('lblconsideration_amount'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         foreach ($property_list as $key => $property) {
                            ?>
                        <tr>
                            <td class="tblbigdata">
                                    <?php
                                    $prop_name = "";
                                    foreach ($property_pattern as $key1 => $pattern) {
                                        if ($property['property_details_entry']['property_id'] == $pattern[0]['mapping_ref_val']) {
                                            $prop_name .= "  " . $pattern[0]['pattern_desc_' . $doc_lang] . " : <small>" . $pattern[0]['field_value_' . $doc_lang] . "</small><br>";
                                        }
                                    }

                                    echo substr($prop_name, 1);
                                    ?>
                            </td>
                            <td class="tblbigdata">
                                    <?php echo $property['village']['village_name_' . $lang]; ?>
                            </td>
                            <td class="tblbigdata">
                                    <?php echo $property['level1']['level_1_desc_' . $lang]; ?> =>
                                    <?php echo $property['level1_list']['list_1_desc_' . $lang]; ?>
                            </td>
                            <td class="tblbigdata">
                                    <?php echo $property['evalrule']['evalrule_desc_' . $lang]; ?>
                            </td>
                            <td class="tblbigdata">
 <?php echo $property['valuation']['rounded_val_amt']; ?>
                            </td>
                            <td class="tblbigdata">
  <?php echo $this->Form->input('consideration_amount_'.$property['property_details_entry']['property_id'], array('label' => false, 'id' => 'consideration_amount_'.$property['property_details_entry']['property_id'], 'value' => $property['property_details_entry']['consideration_amount'], 'class' => 'form-control input-sm', 'type' => 'text', )) ?>
                                <span class="form-error" id="<?php echo 'consideration_amount_'.$property['property_details_entry']['property_id'].'_error';?>"></span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <br><center>
                      <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                     <?php echo $this->Form->button('btnsubmit', array('label' => false, 'id' => 'btnsubmit', 'value' => 'Submit', 'class' => 'btn btn-info', 'type' => 'submit' )) ?>

                </center>

            </div>
        </div>
    </div>


</div>
<?php echo $this->Form->end(); ?>
 




