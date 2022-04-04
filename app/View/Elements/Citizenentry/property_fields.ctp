<?php

$flag = 1;
$doc_lang = $this->Session->read('doc_lang');
if(!empty($PropertyFields)){
foreach ($PropertyFields as $key => $Pattens) {
    $values_en = '';
    $values_ll = '';
   if(isset($TrnPropertyFields)){
    foreach ($TrnPropertyFields as $Fields) {
        if ($Pattens[0]['field_id'] == $Fields['TrnPropertyFields']['field_id']) {
            $values_en = $Fields['TrnPropertyFields']['field_value_en'];
            $values_ll = $Fields['TrnPropertyFields']['field_value_ll'];
        }
    }
    }
    if ($flag) {
        $flag = 0;
        ?>   
<div class="box-header with-border">
    <h3 class="box-title headbolder" >Property Dependent Fields</h3>
</div>
    <?php } ?>
<div  class="rowht"></div>
<div class="row">
    <div class="form-group">
            <?php if ($doc_lang != 'en') {
                ?>
        <label class="col-sm-2 control-label"><?php echo $Pattens[0]['pattern_desc_ll']; ?><span style="color: #ff0000" ><?php echo $Pattens[0]['is_required'] ?></span></label>

        <div class="col-sm-3"> 
                    <?php
                    echo $this->Form->input('value', array('label' => false, 'id' => 'field_ll' . $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_fields][field_value_ll][]', 'value' => $values_ll));
                    ?>
            <span id="<?php echo 'field_ll' . $Pattens[0]['field_id']; ?>_error" class="form-error"> </span>
        </div> 
            <?php } ?>
        <label class="col-sm-3 control-label"><?php echo $Pattens[0]['field_desc_en']; ?>:-<span style="color: #ff0000" ><?php echo $Pattens[0]['is_required'] ?></span></label>

        <div class="col-sm-3"> 
                <?php
                echo $this->Form->input('id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'name' => 'data[property_fields][field_id][]', 'value' => $Pattens[0]['field_id']));
                echo $this->Form->input('value', array('label' => false, 'id' => 'field_en' . $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_fields][field_value_en][]', 'value' => $values_en));
                 ?>
            <span id="<?php echo 'field_en' . $Pattens[0]['field_id']; ?>_error" class="form-error"></span>
        </div> 

    </div>
</div> 
<?php }} 
?>
<script>

    $(document).bind('_propfields_event',
            function () {
                <?php 
                if(!empty($PropertyFields)){
foreach ($PropertyFields as $key => $Pattens) {
                     if($Pattens[0]['is_date']=='Y' ){
                       ?>
                $('#<?php echo 'field_en' . $Pattens[0]['field_id'];  ?>').datepicker({
                    todayBtn: "linked",
                    language: "it",
                    autoclose: true,
                    todayHighlight: true,
                    format: "<?php echo @$Pattens[0]['date_format']?>",
                    startDate: '<?php echo @$Pattens[0]['start_date']?>',
                    endDate: '<?php echo @$Pattens[0]['end_date']?>'
                });
                <?php                       
                     }
                }}
                ?>

            });
</script>