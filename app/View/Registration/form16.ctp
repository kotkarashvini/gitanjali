<?php
echo $this->element("Registration/main_menu");
?>

<div class="panel panel-info">
    <!--      <div class="panel-heading">
              <div class="title1">Public Data Entry :</div>
        </div> -->
    <div class="panel-body">
        <fieldset class="scheduler-border2">
            <legend class="scheduler-border2"> <?php echo __('lblgeneralinfo'); ?> </legend>

            <table class="table table-striped table-bordered">
                <thead>

                    <tr>
                        <th>                             
                            <?php echo __('lblArticle'); ?>                                                                       
                        </th>
                        <th>                             
                            <?php echo __('lbldocumenttitle'); ?>                                                                       
                        </th>
                        <th>                             
                            <?php echo __('lbllocallanguage'); ?>                                                                      
                        </th>
                        <th>                             
                            <?php echo __('lblnoofpages'); ?>                                                                    
                        </th>
                    </tr>  

                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo $documents['article_desc_' . $lang] ?>                                                                                                      
                        </td>
                        <td>
                            <?php echo $documents['title_name'] ?>                                                                                                      
                        </td>
                        <td>
                            <?php echo $documents['language_name'] ?>                                                                                               
                        </td>
                        <td>

                            <?php echo $documents['no_of_pages'] ?>      
                        </td>

                    </tr>  
                </tbody>
            </table>

        </fieldset>   

        <fieldset class="scheduler-border2">
            <legend class="scheduler-border2"><?php echo __('lblpropertydetails'); ?> </legend>
            <table id="prop_list_tbl" class="table table-striped table-bordered table-hover"> 
                <thead >
                    <tr >
                        <th>   <?php echo __('lblpropertydetails'); ?>  </th>
                        <th>   <?php echo __('lbllocation'); ?> </th>
                        <th>
                            <?php echo __('lblusage'); ?>
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propertylist as $key => $property) { ?>
                        <tr>
                            <td>
                                <?php
                                $prop_name = "";
                                foreach ($pattens as $key1 => $pattern) {
                                    if ($property[0]['id'] == $pattern[0]['mapping_ref_val']) {
                                        $prop_name.= " &nbsp; <b>" . $pattern[0]['pattern_desc_' . $lang] . " : </b> <small>" . $pattern[0]['field_value_' . $lang] . "</small> ";
                                    }
                                }

                                echo substr($prop_name, 1);
                                ?>
                            </td>
                            <td>
                                <?php echo $property[0]['village_name_' . $lang]; ?>
                            </td>
                            <td>
                                <?php echo $property[0]['evalrule_desc_' . $lang]; ?>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </fieldset>   

        <fieldset class="scheduler-border2">
            <legend class="scheduler-border2"><?php echo __('lblpartyinfo'); ?> </legend>
            <table id="" class="table table-striped table-bordered table-hover"> 
                <thead >



                    <tr>  <th colspan="2">   <?php echo __('lblpartyname'); ?>  </th> 
                        <th colspan="2">   <?php echo __('lblpartytype'); ?>  </th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partylist as $key => $party) { ?>
                        <tr>
                            <th style="width:50%;">
                                <?php echo $party[0]['salutation_desc_en'] . "  " . $party[0]['party_full_name_en'] ?>
                            </th>
                            <th>
                                <?php echo $party[0]['salutation_desc_' . $doc_lang] . "  " . $party[0]['party_full_name_' . $doc_lang] ?>
                            </th>
                            <th>
                                <?php echo $party[0]['party_type_desc_' . $doc_lang] ?>
                            </th>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </fieldset>   

        <fieldset class="scheduler-border2">
            <legend class="scheduler-border2"> <?php echo __('lblwitnessinfo'); ?></legend>
            <table id="" class="table table-striped table-bordered table-hover"> 
                <thead >
                    <tr >
                        <th colspan="2">   <?php echo __('lblwitness'); ?>  </th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($witnesslist as $key => $witness) { ?>
                        <tr>
                            <th style="width:50%;">
                                <?php echo $witness[0]['salutation_desc_en'] . "  " . $witness[0]['witness_full_name_en'] ?>
                            </th>
                            <th>
                                <?php echo $witness[0]['salutation_desc_' . $doc_lang] . "  " . $witness[0]['witness_full_name_ll'] ?>
                            </th>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>



        </fieldset>   

        <fieldset class="scheduler-border2">
            <legend class="scheduler-border2"> <?php echo __('lblpaymetinfo'); ?></legend> 

            <table  class="table table-striped table-bordered table-hover" >
                <thead >  
                    <tr>  
                        <th style=" width: 10%;"><?php echo __('lblpaymode'); ?></th>
                        <th style=" width: 10%;"><?php echo __('lblpayername'); ?></th>
                        <th style="width: 10%;"><?php echo __('lbldepamt'); ?> </th> 
                    </tr>  
                </thead>
                <tbody>
                    <?php
                    $amount = 0;
                    foreach ($paymentlist as $paydetails) {
                        $paydetails = $paydetails[0];
                        // pr($paydetails);

                        if (isset($payment_mode[$paydetails['payment_mode_id']])) {
                            $amount+=$paydetails['pamount'];
                            ?>
                            <tr>
                                <td ><?php echo $paydetails['payment_mode_desc_' . $lang]; ?></td>
                                <td ><?php echo $paydetails['payee_fname_en'] . " " . $paydetails['payee_mname_en'] . " " . $paydetails['payee_lname_en']; ?></td>
                                <td ><?php echo $paydetails['pamount']; ?></td> 
                            <tr>
                            <?php }
                        };
                        ?>
                        <td> </td> 
                        <th > <?php echo __('lblTotal'); ?></th> 
                        <th ><?php echo $amount; ?></th> 
                </tbody>
            </table> 
<?php //pr($paymentlist);  ?>


        </fieldset>    
    </div> 
</div>