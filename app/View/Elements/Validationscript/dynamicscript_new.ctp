<!--SCRIPT OF VALIDATION THROUGH DATABASE-->
<?php
$lang = CakeSession::read('sess_langauge');
if (is_null($lang)) {
    $lang = 'en';
}

if (isset($fieldlist)) {
    //$this->requestAction(array('controller' => 'Utility', 'action' => 'ValidationReport'), array('pass' => $fieldlist, 'referrer' => array('c' => $this->request->params['controller'], 'a' => $this->request->params['action'])));
    ?>
    <script>
        $(document).bind('_page_ready',
                function () {

    <?php
    foreach ($fieldlist as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
        foreach ($listcontrol as $controltype => $valrule) {
            ?>
            <?php
            $rulearr = explode(",", $valrule);
            $autoflag = 0;
            foreach ($rulearr as $singlerule) {
                ?>
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        if ($error_record['NGDRSErrorCode']['is_autocomplete_flag'] == 'Y') {
                            $autoflag = 1;
                        }
                    }
                }
            }
            if ($autoflag == 1) {
                ?>
                            $('#<?php echo $listkey; ?>').attr('autocomplete', 'off');
                <?php
            }
        }
    }
    ?>

                $('form:first').submit(function (event) {
                if (typeof before_validation_check !== 'undefined' && $.isFunction(before_validation_check)) {
                before_validation_check(event);
                }
    <?php
    foreach ($fieldlist as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
        foreach ($listcontrol as $controltype => $valrule) {
            ?>
            <?php
            $rulearr = explode(",", $valrule);
            foreach ($rulearr as $singlerule) {
                ?>
                <?php
                foreach ($result_codes as $errorkey => $error_record) {
                    if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                        $regex = $error_record['NGDRSErrorCode']['pattern_rule_client']; // GET MATCHING RULE PATTERN
                        if ($controltype == 'select') {
                            generate_logic_selectbox($listkey, $singlerule, $regex, $result_codes, $lang);
                        } else if ($controltype == 'text') {
                            generate_logic_textbox($listkey, $singlerule, $regex, $result_codes, $lang);
                        } else if ($controltype == 'radio') {
                            generate_logic_radiobox($listkey, $singlerule, $regex, $result_codes, $lang);
                        } else if ($controltype == 'checkbox') {
                            generate_logic_checkbox($listkey, $singlerule, $regex, $result_codes, $lang);
                        } else {
                            ?>
                                        alert('LOGIC NOT FOUND FOR <?php echo $controltype; ?>');
                            <?php
                        } // FORM CONTROL TYPE END IF                        
                    }
                }
            } // RULES LOOP END
        } // FORM CONTROL  LOOP END
    } // FIELD LIST  END
    ?>

                if (typeof after_validation_check !== 'undefined' && $.isFunction(after_validation_check)) {
                after_validation_check(event);
                }
    <?php // if (isset($aftervalidation)) {        ?>
                //   after_validation_check();
    <?php //}        ?>

                }); // END FORM SUBMIT




    <?php
    foreach ($fieldlist as $listkey => $listcontrol) {
        foreach ($listcontrol as $controltype => $valrule) {
            $rulearr = explode(",", $valrule);
            if ($controltype == 'select') {
                generate_logic_selectbox_event($listkey, $rulearr, $result_codes, $lang);
            } else if ($controltype == 'text') {
                generate_logic_textbox_event($listkey, $rulearr, $result_codes, $lang);
            } else if ($controltype == 'radio') {
                generate_logic_radiobox_event($listkey, $rulearr, $result_codes, $lang);
            } else if ($controltype == 'checkbox') {
                generate_logic_checkbox_event($listkey, $rulearr, $result_codes, $lang);
            }//FORM CONTROL TYPE END IF
        } // FORM CONTROL TYPE END IF
    }// FIELD LIST  END 
    ?>
                }); // End document ready

                $(function() { $(document).trigger('_page_ready'); }); // shorthand for document.ready
    </script>
    <?php
}

if (isset($fieldlistmultiform)) {
    ?>
    <script>
                $(document).bind('_page_ready', function () {
    <?php foreach ($fieldlistmultiform as $formid => $fieldlist) { ?>
            // Auto Complete OFF
        <?php
        foreach ($fieldlist as $listkey => $listcontrol) {
            foreach ($listcontrol as $controltype => $valrule) {
                ?>
                <?php
                $rulearr = explode(",", $valrule);
                $autoflag = 0;
                foreach ($rulearr as $singlerule) {
                    ?>
                    <?php
                    foreach ($result_codes as $errorkey => $error_record) {
                        if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                            if ($error_record['NGDRSErrorCode']['is_autocomplete_flag'] == 'Y') {
                                $autoflag = 1;
                            }
                        }
                    }
                }
                if ($autoflag == 1) {
                    ?>
                        $('#<?php echo $listkey; ?>').attr('autocomplete', 'off');
                    <?php
                }
            }
        }
        ?>


            $('#<?php echo $formid; ?>').submit(function (event) {
            if (typeof before_validation_check_<?php echo $formid; ?> !== 'undefined' && $.isFunction(before_validation_check_<?php echo $formid; ?>)) {
            before_validation_check_<?php echo $formid; ?>(event);
            }
        <?php
        foreach ($fieldlist as $listkey => $listcontrol) {//$listcontrol->key(select,text) and correspondent value rule for key is(is_alpha,..)
            foreach ($listcontrol as $controltype => $valrule) {
                ?>
                <?php
                $rulearr = explode(",", $valrule);
                foreach ($rulearr as $singlerule) {
                    ?>
                    <?php
                    foreach ($result_codes as $errorkey => $error_record) {
                        if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                            $regex = $error_record['NGDRSErrorCode']['pattern_rule_client']; // GET MATCHING RULE PATTERN
                            if ($controltype == 'select') {
                                generate_logic_selectbox($listkey, $singlerule, $regex, $result_codes, $lang, $formid);
                            } else if ($controltype == 'text') {
                                generate_logic_textbox($listkey, $singlerule, $regex, $result_codes, $lang, $formid);
                            } else if ($controltype == 'radio') {
                                generate_logic_radiobox($listkey, $singlerule, $regex, $result_codes, $lang, $formid);
                            } else if ($controltype == 'checkbox') {
                                generate_logic_checkbox($listkey, $singlerule, $regex, $result_codes, $lang, $formid);
                            } else {
                                ?>
                                    alert('LOGIC NOT FOUND FOR <?php echo $controltype; ?>');
                                <?php
                            } // FORM CONTROL TYPE END IF                            
                        }
                    }
                } // RULES LOOP END
            } // FORM CONTROL  LOOP END
        } // FIELD LIST  END
        ?>
            if (typeof after_validation_check_<?php echo $formid; ?> !== 'undefined' && $.isFunction(after_validation_check_<?php echo $formid; ?>)) {
            after_validation_check_<?php echo $formid; ?>(event);
            }
            }); // END FORM SUBMIT
    <?php } ?>
    <?php
    foreach ($fieldlistmultiform as $formid => $fieldlist) {
        foreach ($fieldlist as $listkey => $listcontrol) {
            foreach ($listcontrol as $controltype => $valrule) {
                $rulearr = explode(",", $valrule);
                if ($controltype == 'select') {
                    generate_logic_selectbox_event($listkey, $rulearr, $result_codes, $lang, $formid);
                } else if ($controltype == 'text') {
                    generate_logic_textbox_event($listkey, $rulearr, $result_codes, $lang, $formid);
                } else if ($controltype == 'radio') {
                    generate_logic_radiobox_event($listkey, $rulearr, $result_codes, $lang, $formid);
                } else if ($controltype == 'checkbox') {
                    generate_logic_checkbox_event($listkey, $rulearr, $result_codes, $lang, $formid);
                }//FORM CONTROL TYPE END IF
            } // FORM CONTROL TYPE END IF
        }// FIELD LIST  END
    }
    ?>
        }); // End document ready
                $(function() { $(document).trigger('_page_ready'); }); // shorthand for document.ready
    </script>
<?php }
?>   
<script>
<?php

// GENERATE LOGIC FOR SELECT CONTROL
function generate_logic_textbox($listkey, $singlerule, $regex, $result_codes, $lang, $formid = NULL) {
    ?>
        var regex =<?php echo $regex; ?>;
                if ($('#<?php echo $listkey; ?>').length > 0 && $('#<?php echo $listkey; ?>').is(':visible')) { // FOR CHECK  DYNAMIC FIELDS

        if (!regex.test($('#<?php echo $listkey; ?>').val())) {
    <?php
    message_display_logic($listkey, $singlerule, $result_codes, $lang);
    ?>
        } else {
    <?php
    message_remove_logic($listkey);
    ?>
        }
        } // END FIELD CHECK
    <?php
}

// END FUNCTION SELECT CONTROL
// GENERATE LOGIC FOR SELECT CONTROL
function generate_logic_selectbox($listkey, $singlerule, $regex, $result_codes, $lang, $formid = NULL) {
    ?>
        var regex =<?php echo $regex; ?>;
                if ($('#<?php echo $listkey; ?>').length > 0 && $('#<?php echo $listkey; ?>').children('option').length > 1 && $('#<?php echo $listkey; ?>').is(":visible")) { // FOR CHECK  DYNAMIC FIELDS  EXIST
        if (!regex.test($('#<?php echo $listkey; ?>').val())) {
    <?php
    message_display_logic($listkey, $singlerule, $result_codes, $lang);
    ?>
        } else {
    <?php
    message_remove_logic($listkey);
    ?>
        }
        } // END FIELD CHECK
    <?php
}

// END FUNCTION SELECT CONTROL
// GENERATE LOGIC FOR Radio CONTROL
function generate_logic_radiobox($listkey, $singlerule, $regex, $result_codes, $lang, $formid = NULL) {
    ?>
        var regex =<?php echo $regex; ?>;
    <?php if (is_null($formid)) { ?>
            var frmid = $('form:first').attr('id');
    <?php } else { ?>
            var frmid = '<?php echo $formid; ?>';
    <?php } ?>
        if ($('#<?php echo $listkey; ?>').length > 0) { // FOR CHECK  DYNAMIC FIELDS  EXIST
        if (typeof ($('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val()) === 'undefined' || !regex.test($('#<?php echo $listkey; ?>').val())) {
    <?php
    message_display_logic($listkey, $singlerule, $result_codes, $lang);
    ?>
        } else {
    <?php
    message_remove_logic($listkey);
    ?>
        }
        } // END FIELD CHECK
    <?php
}

// END FUNCTION SELECT CONTROL
// GENERATE LOGIC FOR Check  CONTROL
function generate_logic_checkbox($listkey, $singlerule, $regex, $result_codes, $lang, $formid = NULL) {
    foreach ($result_codes as $errorkey => $error_record) {
        if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
            ?> var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>; // GET MATCHING RULE PATTERN
            <?php
        }
    }
    ?>
        if ($('.<?php echo $listkey; ?> input[type=checkbox]').length == 0){
         var checkvalues = "1";    
        }else if ($('.<?php echo $listkey; ?> input[type=checkbox]:checked').length == 0){
        var checkvalues = "";
        } else{
        var checkvalues = "1";
        }
        
        $.each($('.<?php echo $listkey; ?> input[type=checkbox]:checked'), function(){
        if (!regex.test($(this).val()))
        {
        checkvalues = $(this).val();
        }
        });
                if (!regex.test(checkvalues) ){
                   // alert('hi');
    <?php
    message_display_logic($listkey, $singlerule, $result_codes, $lang);
    ?>
        } else{
    <?php
    message_remove_logic($listkey);
    ?>
        }

    <?php
}

// END FUNCTION SELECT CONTROL
// GENERATE LOGIC FOR TEXT BOX EVENT
function generate_logic_textbox_event($listkey, $rulearr, $result_codes, $lang, $formid = NULL) {
    ?>$('#<?php echo $listkey; ?>').on('change keyup paste', function (event){
    <?php
    foreach ($rulearr as $singlerule) {
        foreach ($result_codes as $errorkey => $error_record) {
            if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                ?>  var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>; // GET MATCHING RULE PATTERN                    
                <?php if ($error_record['NGDRSErrorCode']['maxlength_flag'] == 'Y' && is_numeric($error_record['NGDRSErrorCode']['maxlength_value'])) { ?>
                            if ($('#<?php echo $listkey; ?>').val().length > <?php echo $error_record['NGDRSErrorCode']['maxlength_value'] ?>){
                            $('#<?php echo $listkey; ?>').val($('#<?php echo $listkey; ?>').val().substr(0,<?php echo $error_record['NGDRSErrorCode']['maxlength_value'] ?>));
                            }
                <?php } ?>
                        if (!regex.test($('#<?php echo $listkey; ?>').val()))  { <?php
                message_display_logic($listkey, $singlerule, $result_codes, $lang);
                ?> } else {
                <?php
                message_remove_logic($listkey);
                ?>
                        }
                <?php
            }
        }
    }
    ?>
            }); // END JS EVENT
    <?php
}

// END FUNCTION  TEXT BOX EVENT
// GENERATE LOGIC FOR SELECT BOX EVENT
function generate_logic_selectbox_event($listkey, $rulearr, $result_codes, $lang, $formid = NULL) {
    ?>
            $('#<?php echo $listkey; ?>').change(function (event)
            {
    <?php
    foreach ($rulearr as $singlerule) {
        foreach ($result_codes as $errorkey => $error_record) {
            if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                ?>
                        var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>; // GET MATCHING RULE PATTERN                   
                                if (!regex.test($('#<?php echo $listkey; ?>').val()))
                        { <?php
                message_display_logic($listkey, $singlerule, $result_codes, $lang);
                ?>
                        } else {
                <?php
                message_remove_logic($listkey);
                ?>
                        }
                <?php
            }
        }
    }
    ?>
            }); // END JS EVENT
    <?php
}

// END FUNCTION  SELECT BOX EVENT
// GENERATE LOGIC FOR RADIO BOX EVENT
function generate_logic_radiobox_event($listkey, $rulearr, $result_codes, $lang, $formid = NULL) {
    ?>
            $('#<?php echo $listkey; ?>').on('click change', function (event)
            {
    <?php
    foreach ($rulearr as $singlerule) {

        foreach ($result_codes as $errorkey => $error_record) {
            if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                ?> var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>; // GET MATCHING RULE PATTERN
                <?php
            }
        }
        ?>
        <?php if (is_null($formid)) { ?>
                    var frmid = $('form:first').attr('id');
        <?php } else { ?>
                    var frmid = '<?php echo $formid; ?>';
        <?php } ?>
                var lastSelected = $('input:radio[name="data[frmid][<?php echo $listkey ?>]"]:checked').val();
                        if (!regex.test(lastSelected))
                { <?php
        message_display_logic($listkey, $singlerule, $result_codes, $lang);
        ?>
                } else {
        <?php
        message_remove_logic($listkey);
        ?>
                }
        <?php
    }
    ?>
            }); // END JS EVENT
    <?php
}

// END FUNCTION  RADIO BOX EVENT
// GENERATE LOGIC FOR RADIO BOX EVENT
function generate_logic_checkbox_event($listkey, $rulearr, $result_codes, $lang, $formid = NULL) {
    ?>

            $('.<?php echo $listkey; ?> input[type="checkbox"]').on('change', function (event)
            {
    <?php
    foreach ($rulearr as $singlerule) {

        foreach ($result_codes as $errorkey => $error_record) {
            if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
                ?> var regex =<?php echo $error_record['NGDRSErrorCode']['pattern_rule_client']; ?>; // GET MATCHING RULE PATTERN
                <?php
            }
        }
        ?>

                if ($('.<?php echo $listkey; ?> input[type=checkbox]:checked').length == 0){
                var checkvalues = "";
                } else{
                var checkvalues = "1";
                }
                $.each($('.<?php echo $listkey; ?> input[type=checkbox]:checked'), function(){
                if (!regex.test($(this).val()))
                {
                checkvalues = $(this).val();
                }
                });
                        if (!regex.test(checkvalues))
                {
        <?php
        message_display_logic($listkey, $singlerule, $result_codes, $lang);
        ?>
                } else{
        <?php
        message_remove_logic($listkey);
        ?>
                }
        <?php
    }
    ?>
            }); // END JS CONTROL EVENT


    <?php
}

// END  check BOX EVENT FUNCTION
// GENERATE LOGIC FOR CONTROL MESSAGE DISPALY
function message_display_logic($listkey, $singlerule, $error_codes, $lang) {
    foreach ($error_codes as $errorkey => $error_record) {
        if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {
            ?>
                    $('#<?php echo $listkey; ?>_error').html('<?php echo $error_record['NGDRSErrorCode']['error_messages_' . $lang]; ?>');
                            $("#<?php echo $listkey; ?>").parent().addClass("field-error");
                            $("#<?php echo $listkey; ?>").focus();
                            return false;
            <?php
        }
    }
}

// END FUNCTION  MESSAGE DISPALY
// GENERATE LOGIC FOR CONTROL MESSAGE REMOVE
function message_remove_logic($listkey) {
    ?>
            $('#<?php echo $listkey; ?>_error').html('');
                    $("#<?php echo $listkey; ?>").parent().removeClass("field-error");
    <?php
}

// END FUNCTION  MESSAGE REMOVE
?>
</script>
