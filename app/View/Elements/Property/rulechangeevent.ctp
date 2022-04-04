<div class="col-md-12 pad">
    <?php
    $this->Form->create('propertyscreennew');
    $lang = CakeSession::read('sess_langauge');
    if (is_null($lang)) {
        $lang = 'en';
    }

    if ($usageitemlist != NULL) {

        if (@$outputfield['0']['evalrule']['tdr_flag'] == 'Y') {
            ?>

            <div class="col-md-12 pad">
                <div class="form-group">
                    <label for="is_tdr_applicable" class="control-label col-sm-3"><?php echo __('lblistdrapplicable'); ?> </label>            
                    <div class="col-sm-3"> <?php echo $this->Form->input('is_tdr_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_tdr_applicable')); ?></div> 
                </div>
            </div>
        <?php } ?>

        <?php
        // print AKAR and TOTAL AREA First And Avoid  Duplicate
        // if State id is 27 - Maharashtra  AKAR and TOTAL AREA are used as property Item
        $totalareaflag = 0;
        $akarflag = 0;
        $usageitemlist_new = array();
        foreach ($usageitemlist as $usageitemlist1) {
            if ($usageitemlist1['itemlist']['usage_param_type_id'] != '5') {
                if ($usageitemlist1['usagelinkcategory']['uasge_param_code'] == 'ABE') {

                    if ($akarflag == 0) {
                        $akarflag = 1;
                        array_unshift($usageitemlist_new, $usageitemlist1);
                    }
                } else if ($usageitemlist1['usagelinkcategory']['uasge_param_code'] == 'ABO' and $totalareaflag == 0) {

                    if ($totalareaflag == 0) {
                        $totalareaflag = 1;
                        array_unshift($usageitemlist_new, $usageitemlist1);
                    }
                } else {
                    array_push($usageitemlist_new, $usageitemlist1);
                }
            }
        }
        ?>

        <?php
        $rule_flag = 0;
        $rule_id_new = "";
        //pr($usageitemlist_new);
        //array_multisort($usageitemlist_new[''], SORT_ASC, SORT_STRING);

        foreach ($usageitemlist_new as $usageitemlist1) {
            //pr($usageitemlist1);
            $rule_id_old = $usageitemlist1['usagelinkcategory']['evalrule_id'];

            if ($rule_id_old != $rule_id_new) {
                $rule_id_new = $usageitemlist1['usagelinkcategory']['evalrule_id'];
                if ($rule_flag == 0) {
                    $rule_flag == 1;
                    ?>
                </fieldset>
                <?php
            }
            ?>

            <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?php echo $usageitemlist1['usage_main']['usage_main_catg_desc_' . $lang]; ?> => <?php echo $usageitemlist1['usage_sub']['usage_sub_catg_desc_' . $lang]; ?> => <?php echo $usageitemlist1['subsub']['usage_sub_sub_catg_desc_' . $lang]; ?></legend>
            <?php } ?>

            <?php
            if ($usageitemlist1['itemlist']['usage_param_type_id'] != '5') {

                if ($usageitemlist1['usagelinkcategory']['item_rate_flag'] == 'Y') {
                    ?>


                    <div class="col-md-12 pad">

                        <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code']; ?>" class="control-label col-sm-3"><?php echo $usageitemlist1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-md-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => '0')); ?></div>


                        <?php
                        if ($usageitemlist1['itemlist']['area_field_flag'] == 'Y') {
                            ?>

                            <div class="col-md-1" ><?php
                                $options = ClassRegistry::init('fillDropdown')->getdropdown('unit', $usageitemlist1['usagelinkcategory']['evalrule_id'], $usageitemlist1['itemlist']['unit_cat_id']);
                                echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit' . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit', 'label' => false, 'class' => 'form-control input-sm'));
                                ?>
                            </div>
                            <?php if ($areatype != '2') { ?>
                                <div class="col-md-1" ><?php
                                    $options = ClassRegistry::init('fillDropdown')->getdropdown('areatype', $usageitemlist1['usagelinkcategory']['evalrule_id']);
                                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype' . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype', 'label' => false, 'class' => 'form-control input-sm'));
                                    ?>
                                </div>
                            <?php }
                            ?>
                            <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted'; ?>" class="control-label col-md-2"><?php echo __('lblConvertedarea'); ?></label>
                            <div class="col-md-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted', array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'converted', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly')); ?></div>
                        <?php }
                        ?>
                        <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id']; ?>" class="control-label col-md-1"><?php echo 'Item Rate'; ?></label>
                        <div class="col-md-1" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $usageitemlist1['0']['item_rate'], 'readonly')); ?></div>
                        <?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'hf', array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'hf', 'type' => 'hidden')); ?>
                    </div>


                    <!--<br>-->
                    <?php
                } else if ($usageitemlist1['itemlist']['is_input_hidden'] == 'N') {
                    //  pr($usageitemlist1);
                    ?>
                    <div class="col-md-12 pad">

                        <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code']; ?>" class="control-label col-md-3">
                            <?php echo $usageitemlist1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?>
                        </label>
                        <div class="col-md-2" >
                            <?php
                            //   pr($usageitemlist1);
                            if ($usageitemlist1['itemlist']['is_list_field_flag'] == 'Y') {
//                                $itemlistoptions = array();
//                                foreach ($listitemsoptions as $options) {
//                                    if ($options['ListItems']['item_id'] == $usageitemlist1['usagelinkcategory']['usage_param_id']) {
//                                        $itemlistoptions[$options['ListItems']['item_desc_id']] = $options['ListItems']['item_desc_' . $lang];
//                                    }
//                                }


                                $options1 = ClassRegistry::init('fillDropdown')->getlistitem($usageitemlist1['usagelinkcategory']['usage_param_id'], $lang);
                                echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('type' => 'select', 'error' => false, 'options' => $options1, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], 'label' => false, 'class' => 'form-control input-sm usage-input-fields'));
                            } else {
                                if (isset($defaultval_flag) || $usageitemlist1['itemlist']['is_string'] == 'Y') {
                                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], 'class' => 'form-control input-sm usage-input-fields', 'type' => 'text'));
                                } else {
                                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], 'class' => 'form-control input-sm usage-input-fields', 'type' => 'text', 'value' => 0));
                                }
                            }
                            ?>
                            <span class="form-error" id="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id']; ?>_error"></span>
                        </div>
                <?php if ($usageitemlist1['usagelinkcategory']['uasge_param_code'] == 'ABE') { ?> 
                            <div class="col-md-2" >
                            <?php
                            $akharoptions = array();
                            foreach ($akar_ranges as $range) {
                                $rangearr = explode("-", $range);
                                array_push($akharoptions, array('name' => $range, 'value' => $rangearr['0']));
                            }
                            echo $this->Form->input("akar_range", array('type' => 'select', 'error' => false, 'options' => $akharoptions, 'id' => "akar_range", 'label' => false, 'class' => 'form-control input-sm usage-input-fields'));
                            ?> 
                                <span class="form-error" id="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id']; ?>_error"></span>

                            </div>

                <?php } ?>

                        <?php
                        if ($usageitemlist1['itemlist']['area_field_flag'] == 'Y') {
                            ?>

                            <div class="col-md-2" ><?php
                    //pr($usageitemlist1);
                    $arrparam['evalrule_id'] = $usageitemlist1['usagelinkcategory']['evalrule_id'];
                    $arrparam['unit_cat_id'] = $usageitemlist1['itemlist']['unit_cat_id'];
                    $arrparam['single_unit_flag'] = $usageitemlist1['itemlist']['single_unit_flag'];
                    $arrparam['unit_id'] = $usageitemlist1['itemlist']['unit_id'];
                    $arrparam['districtwise_unit_change_flag'] = $usageitemlist1['itemlist']['districtwise_unit_change_flag'];
                    $arrparam['district_id'] = @$district_id;
                    $arrparam['usage_param_id'] = $usageitemlist1['usagelinkcategory']['usage_param_id'];

                    $options = ClassRegistry::init('fillDropdown')->getdropdown('unit', $arrparam);
                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit' . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'unit_' . $usageitemlist1['usagelinkcategory']['evalrule_id'], 'label' => false, 'class' => 'form-control input-sm usage-input-fields'));
                            ?>
                                <span class="form-error" id="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "unit_" . $usageitemlist1['usagelinkcategory']['evalrule_id']; ?>_error"></span>

                            </div>
                    <?php
                    if ($usageitemlist1['itemlist']['area_type_flag'] == 'Y') {
                        $options = ClassRegistry::init('fillDropdown')->getdropdown('areatype');

                        if (!empty($options)) {
                            ?>
                                    <div class="col-md-2" ><?php
                                    echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype' . "_" . $usageitemlist1['usagelinkcategory']['evalrule_id'], array('type' => 'select', 'error' => false, 'options' => $options, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . 'areatype_' . $usageitemlist1['usagelinkcategory']['evalrule_id'], 'label' => false, 'class' => 'form-control input-sm usage-input-fields'));
                                    ?>
                                        <span class="form-error" id="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . "areatype_" . $usageitemlist1['usagelinkcategory']['evalrule_id']; ?>_error"></span>

                                    </div>
                            <?php
                        }
                    }
                    ?>

                        <?php }
                        ?>
                    </div>

                <?php
            }
        }
        ?>


        <?php
    }
    ?>
    </fieldset>      


    <div hidden="true">
    <?php
    if (isset($subruleconditions)) {
        if ($subruleconditions != NULL) {
            foreach ($subruleconditions as $subruleconditions1) {
                //pr($subruleconditions1);
                ?>
                    <div class="row">
                        <label for="derivedresult<?php echo $subruleconditions1['subrule']['subrule_id']; ?>" class="control-label col-sm-2"><?php echo $subruleconditions1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-sm-2" ><?php echo $this->Form->input('derivedresult' . $subruleconditions1['subrule']['subrule_id'], array('label' => false, 'id' => 'derivedresult' . $subruleconditions1['subrule']['subrule_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                        <label for="maxvalresult<?php echo $subruleconditions1['subrule']['subrule_id']; ?>" class="control-label col-sm-2"><?php echo __('lblmaxval'); ?> <?php echo $subruleconditions1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-sm-2" ><?php echo $this->Form->input('maxvalresult' . $subruleconditions1['subrule']['subrule_id'], array('label' => false, 'id' => 'maxvalresult' . $subruleconditions1['subrule']['subrule_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                        <label for="finalresult<?php echo $subruleconditions1['subrule']['subrule_id']; ?>" class="control-label col-sm-2"><?php echo __('lblfinal'); ?><?php echo $subruleconditions1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-sm-2" ><?php echo $this->Form->input('finalresult' . $subruleconditions1['subrule']['subrule_id'], array('label' => false, 'id' => 'finalresult' . $subruleconditions1['subrule']['subrule_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                    </div>
                    <br>
                <?php
            }
        } else {
            $slabflag = 0;
            if ($rate != NULL) {
                foreach ($rate as $rate1) {
                    if ($rate1['rate']['slab_rate_flag'] == 'Y') {
                        $slabflag = 1;
                    }
                }
            }


            if ($slabflag == 0) {
                ?>
                    <div class="row">
                        <label for="derivedresult" class="control-label col-sm-2"><?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-sm-2" ><?php echo $this->Form->input('derivedresult', array('label' => false, 'id' => 'derivedresult', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                        <label for="maxvalresult" class="control-label col-sm-2"><?php echo __('lblmaxval'); ?><?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-sm-2" ><?php echo $this->Form->input('maxvalresult', array('label' => false, 'id' => 'maxvalresult', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                        <label for="finalresult" class="control-label col-sm-2"><?php echo __('lblfinal'); ?> <?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                        <div class="col-sm-2" ><?php echo $this->Form->input('finalresult', array('label' => false, 'id' => 'finalresult', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                    </div>
                    <br>
                <?php
            } else {
                if ($rate != NULL) {
                    foreach ($rate as $rate1) {
                        $tofield = $rate1['rate']['range_to'];
                        if ($rate1['rate']['range_to'] == NULL) {
                            $tofield = 'Above';
                        }
                        ?>
                            <div class="row">
                                <label for="derivedresult" class="control-label col-sm-2"><?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?> for <?php echo $rate1['rate']['range_from'] . '-' . $tofield; ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('derivedresult' . $rate1['rate']['range_from'], array('label' => false, 'id' => 'derivedresult' . $rate1['rate']['range_from'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                <label for="maxvalresult" class="control-label col-sm-2"><?php echo __('lblmaxval'); ?> <?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?> for <?php echo $rate1['rate']['range_from'] . '-' . $tofield; ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('maxvalresult' . $rate1['rate']['range_from'], array('label' => false, 'id' => 'maxvalresult' . $rate1['rate']['range_from'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                                <label for="finalresult" class="control-label col-sm-2"><?php echo __('lblfinal'); ?> <?php echo $outputfield[0]['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?> for <?php echo $rate1['rate']['range_from'] . '-' . $tofield; ?></label>
                                <div class="col-sm-2" ><?php echo $this->Form->input('finalresult' . $rate1['rate']['range_from'], array('label' => false, 'id' => 'finalresult' . $rate1['rate']['range_from'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')); ?></div>
                            </div>
                            <br>
                        <?php
                    }
                }
            }
        }
    }
    ?>
    </div>

    <?php
}
?>
</div>