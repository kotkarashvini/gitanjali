<script>

    $(document).ready(function () {
        //items save multiple time
        $('#btnsave').click(function () {
            var itemsvalue = "";
            var items_id = "";
            var unit_val = "";
            var areatype_val = "";
            var property_id = $("#hfid").val();

            var main_id = $("#main_id option:selected").val();
            var sub_id = $("#sub_id option:selected").val();
            var sub_sub_id = $("#sub_sub_id option:selected").val();

            $('#ItemListDiv input[type="text"]').each(function () {

                itemsvalue += "*" + $(this).val();
                items_id += "*" + $(this).attr('id');

                unit_val += "*" + $("#unit" + $(this).attr('id') + "unit option:selected").val();
                areatype_val += "*" + $("#area" + $(this).attr('id') + "areatype option:selected").val();

            });
            $.post("<?php echo $this->webroot; ?>itemssave", {items_id: items_id, itemsvalue: itemsvalue, unit_val: unit_val, areatype_val: areatype_val, property_id: property_id, main_id: main_id, sub_id: sub_id, sub_sub_id: sub_sub_id}, function (data)
            {

            },'json');

        })




    });

</script>   

<?php
echo $this->Html->css('popup');

$tokenval = $this->Session->read("Selectedtoken");
?>
<?php
//pr($valarray);
?>
<?php
echo $this->Form->create('property_details');
if ($usageitemlist != NULL) {
    foreach ($usageitemlist as $usageitemlist1) {
        if ($usageitemlist1['itemlist']['usage_param_type_id'] != '5') {
            if ($usageitemlist1['usagelinkcategory']['item_rate_flag'] == 'Y') {
                ?>
                <div class="row">
                    <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code']; ?>" class="control-label col-sm-3"><?php echo $usageitemlist1['subsub']['usage_sub_sub_catg_desc_en'] . ' : ' . $usageitemlist1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                    <div class="col-sm-2" ><?php echo $this->Form->input('ui' . $usageitemlist1['usagelinkcategory']['usage_param_id'], array('label' => false, 'id' => 'ui' . $usageitemlist1['usagelinkcategory']['usage_param_id'], 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                    <?php if ($usageitemlist1['itemlist']['area_field_flag'] == 'Y') { ?>

                        <div class="col-sm-1" ><?php
                            $options = ClassRegistry::init('fillDropdown')->getdropdown('unit');
                            echo $this->Form->input('unit' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'unit', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => 'unit' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'unit', 'label' => false, 'class' => 'form-control input-sm'));
                            ?>
                        </div>
                        <?php if ($areatype != '2') { ?>
                            <div class="col-sm-1" ><?php
                                $options = ClassRegistry::init('fillDropdown')->getdropdown('areatype');
                                echo $this->Form->input('area' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'areatype', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => 'area' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'areatype', 'label' => false, 'class' => 'form-control input-sm'));
                                ?>
                            </div>
                        <?php }
                        ?>

                    <?php }
                    ?>
                    <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id']; ?>" class="control-label col-sm-1"><?php echo 'Item Rate'; ?></label>
                    <div class="col-sm-1" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['uasge_param_code'] . $usageitemlist1['usagelinkcategory']['usage_param_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $usageitemlist1['0']['item_rate'], 'readonly')); ?></div>
                    <?php echo $this->Form->input('ucode' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'hf', array('label' => false, 'id' => 'ucode' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'hf', 'type' => 'hidden')); ?>
                </div>
                <br>
                <?php
            } else {
                
                //pr($usageitemlist1['usagelinkcategory']['usage_param_id']);exit;
                ?>
                <div class="row">
                    <label for="<?php echo $usageitemlist1['usagelinkcategory']['uasge_param_code']; ?>" class="control-label col-sm-3"><?php echo $usageitemlist1['subsub']['usage_sub_sub_catg_desc_en'] . ' : ' . $usageitemlist1['itemlist']['usage_param_desc_' . $this->Session->read("sess_langauge")]; ?></label>
                    <div class="col-sm-2" ><?php echo $this->Form->input($usageitemlist1['usagelinkcategory']['usage_param_id'], array('label' => false, 'id' => $usageitemlist1['usagelinkcategory']['usage_param_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $valarray[$usageitemlist1['usagelinkcategory']['usage_param_id']])); ?></div>
                    <?php if ($usageitemlist1['itemlist']['area_field_flag'] == 'Y') { ?>

                        <div class="col-sm-2" ><?php
                            $options = ClassRegistry::init('fillDropdown')->getdropdown('unit');
                            echo $this->Form->input('unit' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'unit', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => 'unit' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'unit', 'label' => false, 'class' => 'form-control input-sm'));
                            ?>
                        </div>
                        <?php if ($areatype != '2') { ?>
                            <div class="col-sm-2" ><?php
                                $options = ClassRegistry::init('fillDropdown')->getdropdown('areatype');
                                echo $this->Form->input('area' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'areatype', array('type' => 'select', 'error' => false, 'options' => $options, 'id' => 'area' . $usageitemlist1['usagelinkcategory']['usage_param_id'] . 'areatype', 'label' => false, 'class' => 'form-control input-sm'));
                                ?>
                            </div>
                        <?php }
                        ?>

                    <?php }
                    ?>
                </div>
                <br>
                <?php
            }
        }
    }
    ?>
    <div class="row">
        <div class="col-sm-6"></div>
        <button id="btnsave" name="btnsave" class="btn btn-info " style="text-align: center; width: 7%" type="button"><?php echo __('btnsave'); ?></button>
    </div>
    <!--</div>-->
    <!--<hr style="border: 1px #000 solid;">-->
    <?php
}
?>



