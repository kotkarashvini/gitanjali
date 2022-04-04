<script type="text/javascript">
    $(document).ready(function () {
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('.datepicker').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
         var type_val=  $('#fieldval_FBP').val();
         if(type_val){
        
         $.post('<?php echo $this->webroot; ?>Citizenentry/getdependent_article_level1field', {article_id: $('#article_id option:selected').val(), code: 'FBP',type_val:type_val}, function (data) {
            $("#depfd1").html(data);
            $(document).trigger('_page_ready'); 
        });
              
         }
      
         $('#fieldval_FBP').change(function () {
             var type_val=  $('#fieldval_FBP').val();
             if(type_val){
            
		$.post('<?php echo $this->webroot; ?>Citizenentry/getdependent_article_level1field', {article_id: $('#article_id option:selected').val(), code: 'FBP',type_val:type_val}, function (data) {
            $("#depfd1").html(data);
            $(document).trigger('_page_ready'); 
        });
    }
			   
		  });  
    });
</script>

<?php $this->Form->create('genernalinfoentry', array('id' => 'genernalinfoentry', 'autocomplete' => 'off')); ?>
<?php
$doc_lang = $this->Session->read('sess_langauge');
if(!empty($result)){?>
     <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblarticaldepfields'); ?></h3>
            </div>
<?php 

foreach ($result as $result1) {
    ?> 

    <div  class="rowht"></div>
    <div class="row">
        <label for="" class="col-sm-3 control-label"><?php echo $result1[0]['fee_item_desc_' . $doc_lang]; ?> </label>    
        <?php if ($result1[0]['list_flag'] == 'Y') {
            ?>
            <div class="col-sm-3" >
                <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm', 'type' => 'select', 'empty'=>'---select----','options' => (($items_list[$result1[0]['fee_param_code']]) ? $items_list[$result1[0]['fee_param_code']] : NULL), 'value' => $result1[0]['articledepfield_value'])); ?>
                <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];    ?></span>
            </div>
        <?php } else if ($result1[0]['is_date'] == 'Y') { ?>
            <div class="col-sm-3" >
                <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm datepicker', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];    ?></span>
            </div>
        <?php } else { ?>
            <div class="col-sm-3 ">
                <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm ', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];    ?></span>
            </div>

        <?php }
        ?>
        
    </div>
    
    <?php
}
}
?>
    
   

            <div class="box-body">
                <div id="depfd1">


                </div>  
            </div>
      


