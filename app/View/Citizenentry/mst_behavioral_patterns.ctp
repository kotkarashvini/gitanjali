<?php

$flag = 1;
$lang = $this->Session->read('sess_langauge');
if(isset($BehavioralPatterns) && !empty($BehavioralPatterns)){
foreach ($BehavioralPatterns as $key => $Pattens) {
    
    foreach ($trnbehavioral as $behavioral) {

        if ($Pattens[0]['field_id'] == $behavioral['TrnBehavioralPatterns']['field_id']) {
            $values['en'] = $behavioral['TrnBehavioralPatterns']['field_value_en'];
            $values['ll'] = $behavioral['TrnBehavioralPatterns']['field_value_ll'];
            $values['ll1'] = $behavioral['TrnBehavioralPatterns']['field_value_ll1'];
            $values['ll2'] = $behavioral['TrnBehavioralPatterns']['field_value_ll2'];
            $values['ll3'] = $behavioral['TrnBehavioralPatterns']['field_value_ll3'];
            $values['ll4'] = $behavioral['TrnBehavioralPatterns']['field_value_ll4'];
               
        }
    }
    if ($flag) {
        $flag = 0;
        ?>   
<div class="box-header with-border">
    <h3 class="box-title headbolder" ><?php echo $Pattens[0]['behavioral_desc_display_' . $lang]; ?></h3>
</div>
    <?php } ?>
<div  class="rowht"></div>
<div class="row">

            <?php 
            echo $this->Form->input('id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'name' => 'data[property_details][pattern_id][]', 'value' => $Pattens[0]['field_id']));
   
             foreach ($languagelist as $singlelang){    
                                            ?>
    <div class="col-md-2">
        <div class="form-group">
            <label><?php echo $Pattens[0]['pattern_desc_'.$singlelang['mainlanguage']['language_code']]; ?><span style="color: #ff0000" ><?php echo $Pattens[0]['is_required'] ?></span></label>
   <?php
                    echo $this->Form->input('value', array('label' => false, 'id' => 'field_'.$singlelang['mainlanguage']['language_code'] ."_". $Pattens[0]['field_id'], 'class' => 'form-control', 'type' => 'text', 'name' => 'data[property_details][pattern_value_'.$singlelang['mainlanguage']['language_code'].'][]', 'value' => @$values[$singlelang['mainlanguage']['language_code']]));
                    ?>
            <span id="<?php echo 'field_'.$singlelang['mainlanguage']['language_code']."_". $Pattens[0]['field_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                             ?></span>
        </div>
    </div>
            <?php
                }
                
          ?> 
</div>
 
<?php } }

?>