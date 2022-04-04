<!--SCRIPT OF VALIDATION THROUGH DATABASE-->
<?php
if (isset($fieldlist1)) {
    ?>
    <script>
        $(document).ready(function () {
            // alert();
            //----------------------- FORM SUBMIT --------------------------------------------------------------------------------------------------------------------------------------------------------------       
            $('form:first').submit(function (event) {
                //  alert('kalyani');
                var result = "";
    <?php
    foreach ($fieldlist1 as $listkey => $listcontrol) {
        ?>
                    var <?php echo $listkey; ?> = $("#<?php echo $listkey; ?>").val();
        <?php
    }
    foreach ($fieldlist1 as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
        foreach ($listcontrol as $controltype => $valrule) {
            ?>
            <?php
            $rulearr = explode(",", $valrule);
            foreach ($rulearr as $singlerule) {
                ?>
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                                    var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                    }
                }
                ?>
                            // result = <?php echo $valrule; ?>(<?php echo $listkey; ?>);
                <?php if ($controltype == 'select' OR $controltype == 'text') { ?>
                                if (!regex.test($('#<?php echo $listkey; ?>').val())) {
                <?php } else if ($controltype == 'radio') {
                    ?>   var frmid = $('form:first').attr('id');
                                        // alert($('form:first').attr('id'));      
                                        if (typeof ($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()) === 'undefined' || !regex.test($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val())) {
                                    //  alert('IN');
                <?php }
                ?>
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        ?>
                                        //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                        $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                        //  alert('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                        $("#<?php echo $listkey; ?>").parent().addClass("field-error");

                                        $("#<?php echo $listkey; ?>").focus();

                                        return false;
                        <?php
                    }
                }
                ?>
                            }
                            else {


                                $('#<?php echo $listkey; ?>_error').html('');
                                $("#<?php echo $listkey; ?>").removeClass("field-error");
                //                              return false;

                            }


                <?php
            }
        }
    }
    ?>
                //  return false;
            });
            //---------------------Form element events(ON KEY events)-----------------------------------------------------------------------------------------------------------------
    <?php
    foreach ($fieldlist1 as $listkey => $listcontrol) {
        foreach ($listcontrol as $controltype => $valrule) {
            $rulearr = explode(",", $valrule);
            //  foreach ($rulearr as $singlerule) {

            if ($controltype == 'text') {
                $event = "keyup";
            } else if ($controltype == 'select') {
                $event = "change";
            } else if ($controltype == 'radio') {
                $event = "";
            }
            if (!empty($event)) {
                ?>
                        $('#<?php echo $listkey; ?>').<?php echo $event; ?>(function (event)
                        {//for checking function is_alpha or etc
                <?php
                foreach ($rulearr as $singlerule) {

                    foreach ($result_codes as $errorkey => $error_record) {
                        if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                            ?>
                                        var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>;<?php
                        }
                    }
                    ?>
                                if (!regex.test($('#<?php echo $listkey; ?>').val()))
                                {
                    <?php
                    foreach ($result_codes as $errorkey => $error_record) {
                        if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                            ?>
                                            $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $laug]; ?>');
                                            event.preventDefault();
                                            // alert();
                                            return;
                            <?php
                        }
                    }
                    ?>
                                } else {
                                    $('#<?php echo $listkey; ?>_error').html('');

                                    $("#<?php echo $listkey; ?>").parent().removeClass("field-error");


                                }
                <?php } ?>
                        });

                <?php
            }
        }
    }
    ?>
        });
    </script>
<?php } ?>