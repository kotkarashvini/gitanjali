
<?php
echo $this->Form->create($form_name, array('id' => 'Itemsfrmid', 'class' => 'form-vertical'));
if ($optional_fees) {
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="col-sm-4"></div>
                <div class="col-sm-8"><?php echo $this->Form->input('subrule_id', array('type' => 'select', 'options' => $optional_fees, 'id' => 'subrule_id', 'multiple' => 'checkbox', 'label' => false, 'class' => 'subrule_list subrule_id')); ?>
                    <span id="subrule_id_error" class="form-error"><?php //echo $errarr['party_fname_ll_error'];                                  ?></span>
                </div>                
            </div>
        </div>
    </div>
    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
<?php } ?>

<?php
$sc = '';
$cmv_flag = 1;
$gender = $itemcount = 0;


if ($itemdata) {
    foreach ($itemdata as $val) {


        $feeItem = $val['item'];
        $itemcount++;
        if ($feeItem['fee_param_code'] == 'FAA') {
            $cmv_flag = 0;
        }
        ?>
        <?php if ($val['conf_article_feerule_items']['is_hidden'] == 'N') { ?>
            <div class = 'row'>
                <div class = 'col-sm-12'>
                    <div class = 'form-group'>
                        <div class = 'col-sm-2'></div>
                        <?php if ($feeItem['list_flag'] == 'Y') { ?>
                            <div class = 'col-sm-3' style = 'background-color:#E8E8E8;'><label for = '<?php echo $feeItem['fee_param_code']; ?>' > <?php echo $feeItem['fee_item_desc_' . $lang]; ?> </label></div>
                            <div class = 'col-sm-3' style = 'background-color:#aab2b2;'>

                                <?php echo $this->Form->input($feeItem['fee_param_code'], array('type' => 'select', 'id' => $feeItem['fee_param_code'], 'label' => FALSE, 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : NULL), 'options' => (($items_list[$feeItem['fee_param_code']]) ? $items_list[$feeItem['fee_param_code']] : NULL))); ?>
                                <span id="<?php echo $feeItem['fee_param_code']; ?>_error" class="form-error"> </span>

                            </div>
                        <?php } else {
                            ?>
                            <?php
                            if ($exmption == 'Y') {
                                if ($feeItem['fee_param_code'] == 'FAA') {
                                    $type = 'hidden';
                                } else {
                                    ?>
                                    <div class = 'col-sm-2'><label for = '<?php echo $feeItem['fee_param_code']; ?>' > <?php echo $feeItem['fee_item_desc_' . $lang]; ?> </label></div>
                                    <?php
                                    $type = 'text';
                                }
                                ?>

                                <div class = 'col-sm-3'>

                                    <?php echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : $val['value']['articledepfield_value']), 'maxlength' => 15)); ?> 
                                    <span id="<?php echo $feeItem['fee_param_code']; ?>_error" class="form-error"> </span>

                                </div>

                            <?php } else { ?> 

                                <?php if ($feeItem['display_flag'] == 'Y') {
                                    ?>
                                    <div class = 'col-sm-2'><label for = '<?php echo $feeItem['fee_param_code']; ?>' > <?php echo $feeItem['fee_item_desc_' . $lang]; ?> </label></div>
                                <?php } else { ?>
                                    <div class = 'col-sm-2'><label for = '<?php echo $feeItem['fee_param_code']; ?>' > </label></div>
                                <?php } ?>
                                <div class = 'col-sm-3'>
                                    <!--For Hide fee rule items from stamp duty-->
                                    <?php
                                    if ($feeItem['display_flag'] == 'Y') {
                                        $type = 'text';
                                    } else {
                                        $type = 'hidden';
                                    }
                                    if ($feeItem['fee_param_code'] == 'FAA') {
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : 0), 'maxlength' => 15, 'readonly' => 'readonly'));
                                    } else if ($feeItem['fee_param_code'] == 'FAJ') {
                                        foreach ($itemvalue as $value) {
                                            echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($value[0]['no_of_pages']) ? $value[0]['no_of_pages'] : 0), 'maxlength' => 15, 'readonly' => 'readonly'));
                                        }
                                    } else if ($feeItem['fee_param_code'] == 'FCX') {
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($total_party) ? $total_party : 0), 'maxlength' => 15, 'readonly' => 'readonly'));
                                    } else if ($feeItem['fee_param_code'] == 'FCQ') {
                                        if ($flag == 0) {
                                            foreach ($gov_body_result as $result) {
                                                echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($result['developed_land_types_id']) ? $result['developed_land_types_id'] : 0), 'maxlength' => 15, 'readonly' => 'readonly'));
                                            }
                                        } else {
                                            echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : 0), 'maxlength' => 15));
                                        }
                                    } else if ($feeItem['fee_param_code'] == 'FCK') {
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($gender_new) ? $gender_new : 0), 'maxlength' => 15));
                                    } else if ($feeItem['fee_param_code'] == 'FCL') {
                                        if ($flag == 0) {
                                            echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($area) ? $area : 0), 'maxlength' => 15, 'readonly' => 'readonly'));
                                        } else {
                                            echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : 0), 'maxlength' => 15));
                                        }
                                    } else if ($feeItem['fee_param_code'] == 'FAS') { //FCY=Party Shares
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($party_count_new) ? $party_count_new : 0), 'maxlength' => 15));
                                    } else if ($feeItem['fee_param_code'] == 'FDF') { //FDF=No of Properies
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($property1_count_new) ? $property1_count_new : 0), 'maxlength' => 15));
                                    } else if ($feeItem['fee_param_code'] == 'FDG') {//FDG=Agreement Date for punjab
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'id' => $feeItem['fee_param_code'], 'maxlength' => 15));
                                    } else {
                                        echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => $type, 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : 0), 'maxlength' => 15));
                                    }
                                    ?> 
                                    <!--No. of khata(FBJ) textbox hide and fetch no. of pages(FAJ)-->
                                    <span id="<?php echo $feeItem['fee_param_code']; ?>_error" class="form-error"> </span>
                                </div>
                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
            <div  class="rowht">&nbsp;</div>

        <?php } else {
            ?>
            <?php if ($feeItem['list_flag'] == 'Y') { ?>
                <div class = 'row'>
                    <div class = 'col-sm-12'>
                        <div class = 'form-group'>
                            <div class = 'col-sm-2'></div>

                            <div class = 'col-sm-3' style = 'background-color:#E8E8E8;'><label for = '<?php echo $feeItem['fee_param_code']; ?>' > <?php echo $feeItem['fee_item_desc_' . $lang]; ?> </label></div>
                            <div class = 'col-sm-3' style = 'background-color:#aab2b2;'>

                                <?php
                                echo $this->Form->input($feeItem['fee_param_code'], array('type' => 'select', 'id' => $feeItem['fee_param_code'], 'disabled', 'style' => 'cursor: not-allowed;', 'label' => FALSE, 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : NULL), 'options' => (($items_list[$feeItem['fee_param_code']]) ? $items_list[$feeItem['fee_param_code']] : NULL)));
                                echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => 'hidden', 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : 0), 'maxlength' => 15));
                                ?>


                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo $this->Form->input($feeItem['fee_param_code'], array('label' => FALSE, 'type' => 'hidden', 'id' => $feeItem['fee_param_code'], 'value' => (($val['value']['articledepfield_value']) ? $val['value']['articledepfield_value'] : 0), 'maxlength' => 15));
            }
            ?>

            </div>
            </div>
            </div>

            <?php
        }







        unset($val);
        if ($cmv_flag == 1 && $itemcount > 0) {
            ?>
            <!--        <hr>
                    <div class = 'row'>
                        <div class = 'col-sm-12'>
                            <div class = 'form-group'>
                                <div class = 'col-sm-2'></div>
                                <div class = 'col-sm-3'></div>
                                <div class = 'col-sm-5'><?php // echo $this->Form->input('OMV', array('label' => FALSE, 'readOnly' => TRUE));                                 ?></div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>-->
            <?php
        }
    }
}
?>

