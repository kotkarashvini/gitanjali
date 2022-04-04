<?php
$oldruleid = 0;
$itemcount = 0;
$cmv_flag = 1;
foreach ($ruleitems as $feeitem) {
    $feeitem = $feeitem[0];
    $itemcount++;
    if ($feeitem['fee_param_code'] == 'FAA') {
        $cmv_flag = 0;
    }

    if ($oldruleid != $feeitem['fee_rule_id']) {
        $oldruleid = $feeitem['fee_rule_id'];
        ?>   

        <fieldset class="scheduler-border ">
            <legend class="scheduler-border"><?php
        echo $feeitem['fee_rule_desc_en'];
        ?></legend>
            <?php } ?>

        <div class="col-lg-8">
            <label>
                <?php echo $feeitem['fee_item_desc_en']; ?> 
            </label>
            <?php
            if ($feeitem['fee_item_desc_en'] == 'Gender') {
                echo $this->form->input("", array('type' => 'select', 'name' => 'data[frm][' . $feeitem['fee_param_code'] . ']', 'options' => $genderList, 'label' => false));
            } else {
                echo $this->form->input("", array('type' => 'text', 'name' => 'data[frm][' . $feeitem['fee_param_code'] . ']', 'label' => false));
            }
            ?>
        </div>




<?php } ?>

    <?php
    if ($cmv_flag == 1 && $itemcount > 0) {
        ?>
        <div class = 'row'>
            <div class = 'col-sm-12'>
                <div class = 'form-group'>
                    <br>
                    <div class = 'col-sm-3'><input type = button value = '<?php echo __('lblcalMV'); ?>' onClick = 'caculateMV()' class = 'btn btn-info'></div>
                    <div class = 'col-sm-5'><?php echo $this->Form->input('', array('label' => FALSE, 'readOnly' => TRUE, 'name' => 'data[frm][OMV]')); ?></div>
                </div>
            </div>
        </div>

    <?php
}
?>
</fieldset>