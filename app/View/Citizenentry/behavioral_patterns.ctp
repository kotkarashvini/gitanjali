<?php
$flag = 1;
$doc_lang = $this->Session->read('doc_lang');
if(!empty($BehavioralPatterns)){
foreach ($BehavioralPatterns as $key => $Pattens) {
    $values_en = '';
    $values_ll = '';
    foreach ($trnbehavioral as $behavioral) {

        if ($Pattens[0]['field_id'] == $behavioral['TrnBehavioralPatterns']['field_id']) {
            $values_en = $behavioral['TrnBehavioralPatterns']['field_value_en'];
            $values_ll = $behavioral['TrnBehavioralPatterns']['field_value_ll'];
        }
    }
    if ($flag) {
        $flag = 0;
        ?>   
        <div class="box-header with-border">
            <h3 class="box-title headbolder" ><?php echo $Pattens[0]['behavioral_desc_display_' . $doc_lang]; ?></h3>
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
                    echo $this->Form->input('value', array('label' => false, 'id' => 'field_ll' . $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_details][pattern_value_ll][]', 'value' => $values_ll));
                    ?>
                    <span id="<?php echo 'field_ll' . $Pattens[0]['field_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                             ?></span>
                </div> 
            <?php } ?>
            <label class="col-sm-3 control-label"><?php echo $Pattens[0]['pattern_desc_en']; ?>:-<span style="color: #ff0000" ><?php echo $Pattens[0]['is_required'] ?></span></label>

            <div class="col-sm-3"> 
                <?php
                echo $this->Form->input('id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'name' => 'data[property_details][pattern_id][]', 'value' => $Pattens[0]['field_id']));
                echo $this->Form->input('value', array('label' => false, 'id' => 'field_en' . $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_details][pattern_value_en][]', 'value' => $values_en));
                ?>
                <span id="<?php echo 'field_en' . $Pattens[0]['field_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                             ?></span>
            </div> 

        </div>
    </div> 
<?php }} 
?>