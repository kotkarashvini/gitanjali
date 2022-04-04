

<?php
//pr($attributes12);exit;
if ($attributes12 != NULL) {
    foreach ($attributes12 as $attribute1) {
        ?>     
        <div class="row">
            <div class="col-sm-1"></div>
            <label for="<?php echo $attribute1[0]['eri_attribute_name']; ?>" class="control-label col-sm-3"><?php echo $attribute1[0]['eri_attribute_name']; ?></label>
            <div class="col-sm-2" ><?php echo $this->Form->input($attribute1[0]['eri_attribute_name'], array('label' => false, 'id' => $attribute1[0]['eri_attribute_name'], 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>

        </div>
    <?php }
}
?>

