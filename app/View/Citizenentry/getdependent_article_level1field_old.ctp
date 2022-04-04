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



        $('#fieldval_FBS').val(1);
        var period = parseInt($('#fieldval_FAE').val());

        $('#fieldval_FBT').blur(function () {


            var field_val = parseInt($('#fieldval_FBT').val());

            var a = field_val + 1;
            if (field_val > period) {
                alert('Please enter month less than agreement period ');
                $('#fieldval_FBT').val(null);
                return false;
            } else {
                if (period >= a) {
                    if (!isNaN(a)) {

                        $('#fieldval_FBW').val(a);
                    } else {
                        $('#fieldval_FBW').val(null);
                    }
                }else{
                    $('#fieldval_FBW').val(null);
                }
            }
//
//
        });
        $('#fieldval_FBX').blur(function () {
            var field_val = parseInt($('#fieldval_FBX').val());
           
            if($('#fieldval_FBT').val()){

            var b = parseInt(field_val) + 1;

            if (field_val > period || field_val < $('#fieldval_FBW').val()) {
                alert('Please enter month between ' + $('#fieldval_FBW').val() + ' and ' + period);
                $('#fieldval_FBX').val(null);
                return false;
            } else {

                if (period >= b) {
                    if (!isNaN(b)) {

                        $('#fieldval_FBZ').val(b);
                    } else {
                        $('#fieldval_FBZ').val(null);
                    }
                }
                else{
                    $('#fieldval_FBZ').val(null);
                }

            }
        }else{
            $('#fieldval_FBT').focus();
            $('#fieldval_FBX').val(null);
           
            return false;
        }

        });
        $('#fieldval_FCA').blur(function () {
             if($('#fieldval_FBX').val()){

            var field_val = parseInt($('#fieldval_FCA').val());

            var c = parseInt(field_val) + 1;

            if (field_val > period || field_val < $('#fieldval_FBZ').val()) {
                alert('Please enter month between ' + $('#fieldval_FBZ').val() + ' and ' + period);
                $('#fieldval_FCA').val(null);
                return false;
            } else {

                if (period >= c) {
                    if (!isNaN(c)) {

                        $('#fieldval_FCC').val(c);
                    } else {
                        $('#fieldval_FCC').val(null);
                    }
                }
                 else{
                    $('#fieldval_FCC').val(null);
                }
            }

             }else{
            $('#fieldval_FBX').focus();
             $('#fieldval_FCA').val(null);
            return false;
        }

        });
        $('#fieldval_FCD').blur(function () {
            
            if($('#fieldval_FCA').val()){

            var field_val = parseInt($('#fieldval_FCD').val());

            var d = parseInt(field_val) + 1;

            if (field_val > period || field_val < $('#fieldval_FCC').val()) {
                alert('Please enter month between ' + $('#fieldval_FCC').val() + ' and ' + period);
                $('#fieldval_FCD').val(null);
                return false;
            } else {
                if (period >= d) {
                    if (!isNaN(d)) {

                        $('#fieldval_FCF').val(d);
                    } else {
                        $('#fieldval_FCF').val(null);
                    }
                }
                 else{
                    $('#fieldval_FCF').val(null);
                }
            }

           }else{
            $('#fieldval_FCA').focus();
             $('#fieldval_FCD').val(null);
            return false;
        }

        });


  $('#fieldval_FCG').blur(function () {
      
        if($('#fieldval_FCD').val()){
            return true;
      }else{
            $('#fieldval_FCD').focus();
             $('#fieldval_FCG').val(null);
            return false;
        }
  });

    });
</script>

<?php $this->Form->create('genernalinfoentry', array('id' => 'genernalinfoentry', 'autocomplete' => 'off')); ?>
<?php
$doc_lang = $this->Session->read('sess_langauge');
if (!empty($result)) {
    ?>

    <?php
    foreach ($result as $result1) {
        ?> 

        <div  class="rowht"></div>
        <?php if ($result1[0]['separate_table_flag'] == 'Y') { ?>
            <label for="" class="col-sm-2 control-label"><?php echo $result1[0]['fee_item_desc_' . $doc_lang]; ?> </label>    
            <?php if ($result1[0]['list_flag'] == 'Y') {
                ?>
                <div class="col-sm-2" >
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm', 'type' => 'select', 'options' => (($items_list[$result1[0]['fee_param_code']]) ? $items_list[$result1[0]['fee_param_code']] : NULL), 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];       ?></span>
                </div>
            <?php } else if ($result1[0]['is_date'] == 'Y') { ?>
                <div class="col-sm-2" >
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm datepicker', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];       ?></span>
                </div>
            <?php } else { ?>
                <div class="col-sm-2 ">
                    <?php
                    if (trim($result1[0]['readonly_flag']) == 'Y') {
                        echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm ', 'type' => 'text', 'readonly' => 'readonly', 'value' => $result1[0]['articledepfield_value']));
                    } else {
                        echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm ', 'type' => 'text', 'value' => $result1[0]['articledepfield_value']));
                    }
                    ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];       ?></span>
                </div>

            <?php }
            ?>


            <div  class="rowht"></div>
        <?php } else { ?>
            <label for="" class="col-sm-3 control-label"><?php echo $result1[0]['fee_item_desc_' . $doc_lang]; ?> </label>    
            <?php if ($result1[0]['list_flag'] == 'Y') {
                ?>
                <div class="col-sm-3" >
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm', 'type' => 'select', 'options' => (($items_list[$result1[0]['fee_param_code']]) ? $items_list[$result1[0]['fee_param_code']] : NULL), 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];      ?></span>
                </div>
            <?php } else if ($result1[0]['is_date'] == 'Y') { ?>
                <div class="col-sm-3" >
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm datepicker', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];      ?></span>
                </div>
            <?php } else { ?>
                <div class="col-sm-3 ">
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm ', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];       ?></span>
                </div>

            <?php }
            ?>




        <?php }
        ?>
        <div  class="rowht"></div>



    <?php } ?>
    <div  class="rowht"></div>
<?php }
?>

<!--   <table width="100%">
    <tr>
        
        <td>ssss</td>
        <td><input type="textbox"></td>
       <td>ssss</td>
        <td><input type="textbox"></td>
        <td>ssss</td>
        <td><input type="textbox"></td>
        <td>ssss</td>
        <td><input type="textbox"></td>
   </tr>
</table>-->

