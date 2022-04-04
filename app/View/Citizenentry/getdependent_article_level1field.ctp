<script type="text/javascript">
    $(document).ready(function () {
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('.datepicker').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "yyyy-mm-dd"
        });
//        
        

   $('#fieldval_FAW').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
             dayOfWeekDisabled: [0, 6],
           
           
            format: "yyyy-mm-dd"
        }).on('changeDate', function () {
          
             var d = $(this).val(); 
             
             var period =$('#fieldval_FAE').val();
             
           $.post('<?php echo $this->webroot; ?>Citizenentry/add_month_in_date', {date: d,period:period}, function (data)
                            {

                                $('#fieldval_FBR').val(data);
                            });
    
         
//            $('#fieldval_FAW').val(todate);
        });


        $('#fieldval_FBS').val(1);
        var period = parseInt($('#fieldval_FAE').val());

        $('#fieldval_FBT').blur(function () {
             var period =$('#fieldval_FAE').val();
            if ($('#fieldval_FBU').val()) {
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
                    } else {
                        $('#fieldval_FBW').val(null);
                    }
                }

            } else {
                alert('Enter Rent');
                $('#fieldval_FBU').focus();
                return false;

            }
//
//
        });

        //rent1 blur
        $('#fieldval_FBU').blur(function () {
            var field_val = parseInt($('#fieldval_FBT').val());
           var period =$('#fieldval_FAE').val();

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
                } else {
                    $('#fieldval_FBW').val(null);
                }
            }
            
             calculate_rent();
        });
        
        
        $('#fieldval_FBX').blur(function () {
          
        var period =$('#fieldval_FAE').val();
             
                  if ($('#fieldval_FBV').val()) {
                   
                      $('#fieldval_FBY').val(parseInt($('#fieldval_FBU').val())+(parseInt($('#fieldval_FBU').val())*(parseInt($('#fieldval_FBV').val())/100)));
                     calculate_rent();
                  }
            
            if ($('#fieldval_FBY').val()) {

                var field_val = parseInt($('#fieldval_FBX').val());

                if ($('#fieldval_FBT').val()) {

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
                        else {
                            $('#fieldval_FBZ').val(null);
                        }

                    }
                } else {
                    $('#fieldval_FBT').focus();
                    $('#fieldval_FBX').val(null);

                    return false;
                }

            } else {
                alert('Enter Rent');
                $('#fieldval_FBY').focus();
                return false;

            }

        });


        //Rent2 blur
        $('#fieldval_FBY').blur(function () {
            var field_val = parseInt($('#fieldval_FBX').val());
            var period =$('#fieldval_FAE').val();

            if ($('#fieldval_FBT').val()) {

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
                    else {
                        $('#fieldval_FBZ').val(null);
                    }

                }
            } else {
                $('#fieldval_FBT').focus();
                $('#fieldval_FBX').val(null);

                return false;
            }
            
           
            calculate_rent();
        });
        
        $('#fieldval_FCA').blur(function () {
            var period =$('#fieldval_FAE').val();
             
            
             if ($('#fieldval_FBV').val()) {
                   
                      $('#fieldval_FCB').val(parseInt($('#fieldval_FBY').val())+(parseInt($('#fieldval_FBY').val())*(parseInt($('#fieldval_FBV').val())/100)));
                     calculate_rent();
                  }
            
            
            if ($('#fieldval_FCB').val()) {
                if ($('#fieldval_FBX').val()) {

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
                        else {
                            $('#fieldval_FCC').val(null);
                        }
                    }

                } else {
                    $('#fieldval_FBX').focus();
                    $('#fieldval_FCA').val(null);
                    return false;
                }
            } else {
                alert('Enter Rent');
                $('#fieldval_FCB').focus();
                return false;

            }

        });

        //Rent3 blur

        $('#fieldval_FCB').blur(function () {
       var period =$('#fieldval_FAE').val();

            if ($('#fieldval_FBX').val()) {

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
                    else {
                        $('#fieldval_FCC').val(null);
                    }
                }

            } else {
                $('#fieldval_FBX').focus();
                $('#fieldval_FCA').val(null);
                return false;
            }
            
            calculate_rent();
        });


        $('#fieldval_FCD').blur(function () {
var period =$('#fieldval_FAE').val();

             if ($('#fieldval_FBV').val()) {
                   
                      $('#fieldval_FCE').val(parseInt($('#fieldval_FCB').val())+(parseInt($('#fieldval_FCB').val())*(parseInt($('#fieldval_FBV').val())/100)));
                     calculate_rent();
                  }
            if ($('#fieldval_FCE').val()) {

                if ($('#fieldval_FCA').val()) {

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
                        else {
                            $('#fieldval_FCF').val(null);
                        }
                    }

                } else {
                    $('#fieldval_FCA').focus();
                    $('#fieldval_FCD').val(null);
                    return false;
                }
            } else {
                alert('Enter Rent');
                $('#fieldval_FCE').focus();
                return false;

            }

        });
//rent 4
        $('#fieldval_FCE').blur(function () {
            var period =$('#fieldval_FAE').val();
            if ($('#fieldval_FCA').val()) {

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
                    else {
                        $('#fieldval_FCF').val(null);
                    }
                }

            } else {
                $('#fieldval_FCA').focus();
                $('#fieldval_FCD').val(null);
                return false;
            }
            
           calculate_rent();
        });


        $('#fieldval_FCG').blur(function () {
            
             if ($('#fieldval_FBV').val()) {
                   
                      $('#fieldval_FCH').val(parseInt($('#fieldval_FCE').val())+(parseInt($('#fieldval_FCE').val())*(parseInt($('#fieldval_FBV').val())/100)));
                     calculate_rent();
                  }

            if ($('#fieldval_FCH').val()) {
                if ($('#fieldval_FCD').val()) {
                    return true;
                } else {
                    $('#fieldval_FCD').focus();
                    $('#fieldval_FCG').val(null);
                    return false;
                }
            } else {
                alert('Enter Rent');
                $('#fieldval_FCH').focus();
                return false;

            }
        });

        //rent blur
        $('#fieldval_FCH').blur(function () {
            calculate_rent();
        });



    });
    
    function calculate_rent(){
                var rent1=$('#fieldval_FBU').val();
                var rent2=$('#fieldval_FBY').val();
                var rent3=$('#fieldval_FCB').val();
                var rent4=$('#fieldval_FCE').val();
                var rent5=$('#fieldval_FCH').val();
                if(rent1){
                    
                $('#fieldval_FBQ').val(rent1);
                }
                
                 if(rent1 && rent2 ){
                     var rent = (parseInt(rent1)+ parseInt(rent2)  )/2;
                $('#fieldval_FBQ').val(Math.round(rent));
                }
                
                if(rent1 && rent2 && rent3){
                     var rent = (parseInt(rent1)+ parseInt(rent2) + parseInt(rent3)  )/3;
                $('#fieldval_FBQ').val(Math.round(rent));
                }
                
               
                
                if(rent1 && rent2 && rent3 && rent4){
                     var rent = (parseInt(rent1)+ parseInt(rent2) + parseInt(rent3) +parseInt(rent4) )/4;
                $('#fieldval_FBQ').val(Math.round(rent));
                }
                
                if(rent1 && rent2 && rent3 && rent4 && rent5){
                   var rent = (parseInt(rent1)+ parseInt(rent2) + parseInt(rent3) +parseInt(rent4) +parseInt(rent5))/5;
                $('#fieldval_FBQ').val(Math.round(rent));
                }
              
                
        
    }
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
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];         ?></span>
                </div>
            <?php } else if ($result1[0]['is_date'] == 'Y') { ?>
                <div class="col-sm-2" >
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm datepicker', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];         ?></span>
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
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];         ?></span>
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
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];        ?></span>
                </div>
            <?php } else if ($result1[0]['is_date'] == 'Y') { ?>
                <div class="col-sm-3" >
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm datepicker', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];        ?></span>
                </div>
            <?php } else { ?>
                <div class="col-sm-3 ">
                    <?php echo $this->Form->input('fieldval_' . $result1[0]['fee_param_code'], array('label' => false, 'id' => 'fieldval_' . $result1[0]['fee_param_code'], 'class' => 'form-control input-sm ', 'type' => 'text', 'value' => $result1[0]['articledepfield_value'])); ?>
                    <span id="fieldval_<?php echo $result1[0]['fee_param_code']; ?>_error" class="form-error"><?php //echo $errarr['fieldval_'. $result1[0]['fee_param_code'].'_error'];         ?></span>
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

