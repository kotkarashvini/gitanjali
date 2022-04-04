 

<?php //echo $this->Form->create('printgeneralinfo', array('id' => 'printgeneralinfo', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("Registration/main_menu");
?>

<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#summary1print').on('click', function () {
            $.print("#rptRegSummary1");
        });
    });
</script>
<div class="col-sm-12" id="rptRegSummary1">
    <div class="row">
        <div class="col-lg-12" >

            <div class="box box-primary" >
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblgeneralinfo'); ?></h3></center>
                </div>
                <div class="box-body" >
                    <div class="row">
                        <div class="col-sm-12"  >
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lbllocaldataentry'); ?></td>
                                        <td style="text-align: center;"><?php echo $result[0][0]['language_name']; ?></td>
                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblnoofpages'); ?></td>
                                        <td style="text-align: center;"><?php echo $result[0][0]['no_of_pages']; ?></td>
                                    </tr>
                                    <tr>
                                    <div class="form-group">
                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblArticle'); ?></td>    
                                        <td style="text-align: center;"><?php echo $result[0][0]['article_desc_' . $lang]; ?></td>
                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lbldocumenttitle'); ?></td>   
                                        <td style="text-align: center;"><?php echo $result[0][0]['articledescription_' . $lang]; ?></td>
                                        </tr>
                                        <tr>
                                        <div class="form-group">
                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblexecutiondt'); ?></td>  
                                            <td style="text-align: center;"><?php
                                                if (!empty($result[0][0]['exec_date'])) {
                                                    $date = date_create($result[0][0]['exec_date']);
                                                    echo date_format($date, 'd M Y');
                                                }
                                                ?></td>
                                            <td style="text-align: center;font-weight:bold;"><?php echo __('lblexecutiontype'); ?></td>
                                            <td style="text-align: center;"><?php echo $result[0][0]['execution_type_' . $lang]; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrefdocno'); ?></td>     
                                                <td style="text-align: center;"><?php echo $result[0][0]['ref_doc_no']; ?></td> 
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrefregdocdate'); ?></td>     
                                                <td style="text-align: center;"><?php echo $result[0][0]['ref_doc_date']; ?></td> 
                                            </tr>
                                             <tr>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('Sro Remark'); ?></td>     
                                                <td style="text-align: center;" colspan="3"><?php echo $result[0][0]['sro_remark']; ?></td> 
                                              </tr>
                                            
                                            
                                            </table> </div>
                                        <div class="col-sm-1"></div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <center><h3 class="box-title headbolder"><?php echo __('lblpropertyscreenhead'); ?></h3></center>
                        </div>
                        <?php if (isset($prop_add) && $prop_add != NULL) { ?>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12"  >
                                        <div class="col-sm-1"></div> 
                                        <div class="col-sm-10">
                                            <?php if ($prop_add != null) { ?>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <tr>
                                                        <?php
                                                        $flag = $flag1 = 0;
                                                        foreach ($prop_add as $key => $value) {
                                                            $flag++;
                                                            if ($flag == 3) {
                                                                echo "  </tr>  <tr> ";
                                                                $flag = 0;
                                                                $flag1 = 0;
                                                            }
                                                            ?>

                                                            <td style="text-align: center;font-weight:bold;"><?php echo $value['pattern']['pattern_desc_' . $lang]; ?></td>
                                                            <td style="text-align: center;"><?php echo $value['TrnBehavioralPatterns']['field_value_' . $doc_lang]; ?></td>

                                                            <?php
                                                            $flag1++;
                                                        }
                                                        if ($flag1 == 1) {
                                                            echo "  <td>  </td> <td>  </td> ";
                                                        }
                                                        ?>
                                                    </tr>
                                                </table>
                                            <?php } ?>
                                            <?php if ($attributes != null) { ?>
                                                <table class="table table-bordered" id="prop_attribute_tbl_p">
                                                    <thead>
                                                        <tr>
                                                            <th class="center">
                                                                <?php echo __('lblattriname'); ?> 
                                                            </th>
                                                            <th class="center">
                                                                <?php echo __('lblattrivalue'); ?> 
                                                            </th>
                                                            <th>
                                                                <?php echo __('lblattrivalue_part1'); ?>
                                                            </th>  
                                                            <th>
                                                                <?php echo __('lblattrivalue_part2'); ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($attributes as $key => $prop_attribute) {
                                                            ?>
                                                            <tr>
                                                                <th>
                                                                    <?php echo $prop_attribute[0]['eri_attribute_name_en']; ?>
                                                                </th>
                                                                <th>
                                                                    <?php echo $prop_attribute[0]['paramter_value']; ?>
                                                                </th>
                                                                <th>
                                                                    <?php echo $prop_attribute[0]['paramter_value1']; ?>
                                                                </th>
                                                                <th>
                                                                    <?php echo $prop_attribute[0]['paramter_value2']; ?>
                                                                </th>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-1"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } if (isset($valid) && $valid != NULL) { 
                            foreach ($valid as $key=>$valid1) {
                                $num = ++$key;
                                ?>
                        <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12"  >
                                        <div class="col-sm-1"></div> 
                                        <div class="col-sm-10"><?php echo "Property :- ".$num; ?></div>
                                        <div class="col-sm-1"></div>
                                    </div>
                                </div>
                             <div class="row">
                                    <div class="col-sm-12"  >
                                        <div class="col-sm-1"></div> 
                                        <div class="col-sm-10"><?php echo $this->requestAction(array('controller' => 'Reports', 'action' => 'rptview', 'V', base64_encode($valid1[0]['val_id']))); ?></div>
                                        <div class="col-sm-1"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } } else { ?>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12"  >
                                        No Record Found                                 
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <center><h3 class="box-title headbolder"><?php echo __('lblpartydetails'); ?></h3></center>
                        </div>
                        <?php
                        if (isset($party_record)) {
                           // pr($party_record);
                            for ($i = 0; $i < count($party_record); $i++) {
                                ?>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12"  >
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-10">
                                                <h4>Party <?php echo $i + 1; ?> </h4>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpartytype'); ?></td>    
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['party_type_desc_' . $lang]; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpartycategory'); ?></td>   
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['category_name_' . $lang]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpartyfullname'); ?></td>
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['party_full_name_' . $lang]; ?></td>
                                                        <td style="text-align: center;font-weight:bold;">Age</td>
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['age']; ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblfatherfullname'); ?></td>
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['father_full_name_' . $lang]; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblgrandfatherfullname'); ?></td>
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['grand_father_fullname_' . $lang]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblispresenter'); ?></td>
                                                        <td style="text-align: center;"><?php
                                                            if ($party_record[$i][0]['is_presenter'] == 'Y') {
                                                                $result = 'YES';
                                                            } else {
                                                                $result = 'NO';
                                                            }
                                                            echo $result;
                                                            ?></td><td style="text-align: center;font-weight:bold;"><?php echo __('lblisexecuter'); ?></td>
                                                        <td style="text-align: center;"><?php
                                                            if ($party_record[$i][0]['is_executer'] == 'Y') {
                                                                $result = 'YES';
                                                            } else {
                                                                $result = 'NO';
                                                            }
                                                            echo $result;
                                                            ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lblgender'); ?></td>  
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['gender_desc_' . $lang]; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblmobileno'); ?></td>     
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['mobile_no']; ?></td>
                                                    </tr>
                                                    
                                                    <tr> 
                                                        <?php
                                                        $flag = $flag1 = 0;
                                                        foreach ($pattern_data as $key => $value) {
                                                            if ($party_record[$i][0]['party_id'] == $value['TrnBehavioralPatterns']['mapping_ref_val']) {
                                                                $flag++;
                                                                if ($flag == 3) {
                                                                    echo "  </tr>  <tr> ";
                                                                    $flag = 0;
                                                                    $flag1 = 0;
                                                                }
                                                                ?>
                                                                <td style="text-align: center;font-weight:bold;"><?php echo $value['pattern']['pattern_desc_' . $lang]; ?></td>
                                                                <td style="text-align: center;"><?php echo $value['TrnBehavioralPatterns']['field_value_' . $doc_lang]; ?></td>
                                                                <?php
                                                                $flag1++;
                                                            }
                                                        }
                                                        if ($flag1 == 1) {
                                                            echo "  <td>  </td> <td>  </td> ";
                                                        }
                                                        ?>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <?php if ($party_record[$i][0]['uid'] != NULL) { ?>
                                                            <td style="text-align: center; font-weight:bold;"><?php echo __('lbluid'); ?></td>

                                                            <td style="text-align: center;"><?php echo $this->requestAction(array('controller' => 'Registration', 'action' => 'dec', ($party_record[$i][0]['uid']))); ?></td>

                                                        <?php } else { ?>
                                                            <td  style="visibility:hidden;"></td>
                                                            <td style="visibility:hidden;"></td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php if ($party_record[$i][0]['pan_no'] != NULL) { ?>
                                                            <td style="text-align: center;font-weight:bold;"><?php echo __('lblpancardno'); ?></td>

                                                            <td style="text-align: center;"><?php echo$party_record[$i][0]['pan_no']; ?></td>

                                                        <?php } else { ?>
                                                            <!--<hr>-->
                                                            <td  style="visibility:hidden;"></td>
                                                            <td  style="visibility:hidden;"></td>
                                                            <?php
                                                        }
                                                        ?>

                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; font-weight:bold;"><?php echo __('Occupation'); ?></td>  
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['occupation_name_' . $lang]; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('Caste'); ?></td>     
                                                        <td style="text-align: center;"><?php echo $party_record[$i][0]['caste_name']; ?></td>
                                                    </tr>
                                                    

                                                </table> 

                                            </div>
                                            <div class="col-sm-1"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <center><h3 class="box-title headbolder"><?php echo __('lblinterfierlist'); ?></h3></center>
                        </div>
                        <?php
                        if (isset($identifier)) {
                            for ($i = 0; $i < count($identifier); $i++) {
                                ?>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12"  >
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-10">
                                                <h4>Identifier <?php echo $i + 1; ?> </h4>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpartytype'); ?></td>    
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['party_type']['party_type_desc_' . $lang]; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblidentificationfullname'); ?></td>   
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['identification']['identification_full_name_' . $lang]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lbldob'); ?></td>
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['identification']['dob']; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblgender'); ?></td>
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['gender']['gender_desc_' . $lang]; ?></td>
                                                    </tr>
                                                    <tr> 
                                                        <?php
                                                        $flag = $flag1 = 0;
                                                        foreach ($identifier_add as $key => $value) {
                                                            if ($identifier[$i]['identification']['identification_id'] == $value['TrnBehavioralPatterns']['mapping_ref_val']) {
                                                                $flag++;
                                                                if ($flag == 3) {
                                                                    echo "  </tr>  <tr> ";
                                                                    $flag = $flag1 = 0;
                                                                }
                                                                ?>
                                                                <td style="text-align: center;font-weight:bold;"><?php echo $value['pattern']['pattern_desc_' . $lang]; ?></td>
                                                                <td style="text-align: center;"><?php echo $value['TrnBehavioralPatterns']['field_value_' . $doc_lang]; ?></td>
                                                                <?php
                                                                $flag1++;
                                                            }
                                                        }
                                                        if ($flag1 == 1) {
                                                            echo "  <td>  </td> <td>  </td> ";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lblemailid'); ?></td>  
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['identification']['email_id']; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblmobileno'); ?></td>
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['identification']['mobile_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lbluid'); ?></td>  
                                                        <td style="text-align: center;"><?php echo $this->requestAction(array('controller' => 'Registration', 'action' => 'dec', ($identifier[$i]['identification']['uid_no']))); ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpancardno'); ?></td>
                                                        <td style="text-align: center;"><?php echo $identifier[$i]['identification']['pan_no']; ?></td>
                                                    </tr>
                                                </table> 
                                            </div>
                                            <div class="col-sm-1"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <center><h3 class="box-title headbolder"><?php echo __('lblwitnesslists'); ?></h3></center>
                        </div>
                        <?php
                        if (isset($witness)) {
                            for ($i = 0; $i < count($witness); $i++) {
                                ?>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12"  >
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-10">
                                                <h4>witness <?php echo $i + 1; ?> </h4>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpartytype'); ?></td>    
                                                        <td style="text-align: center;"><?php //echo $witness[$i]['party_type']['party_type_desc_' . $lang];       ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblwitnessfullname'); ?></td>   
                                                        <td style="text-align: center;"><?php echo $witness[$i]['witness']['witness_full_name_' . $lang]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lbldob'); ?></td>
                                                        <td style="text-align: center;"><?php echo $witness[$i]['witness']['dob']; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblgender'); ?></td>
                                                        <td style="text-align: center;"><?php echo $witness[$i]['gender']['gender_desc_' . $lang]; ?></td>
                                                    </tr>
                                                    <tr> 
                                                        <?php
                                                        $flag = $flag1 = 0;
                                                        if (!empty($witness_add)) {
                                                            foreach ($witness_add as $key => $value) {
                                                                if ($witness[$i]['witness']['witness_id'] == $value['TrnBehavioralPatterns']['mapping_ref_val']) {
                                                                    $flag++;
                                                                    if ($flag == 3) {
                                                                        echo "  </tr>  <tr> ";
                                                                        $flag = $flag1 = 0;
                                                                    }
                                                                    ?>
                                                                    <td style="text-align: center;font-weight:bold;"><?php echo $value['pattern']['pattern_desc_' . $lang]; ?></td>
                                                                    <td style="text-align: center;"><?php echo $value['TrnBehavioralPatterns']['field_value_' . $doc_lang]; ?></td>
                                                                    <?php
                                                                    $flag1++;
                                                                }
                                                            }
                                                        }
                                                        if ($flag1 == 1) {
                                                            echo "  <td>  </td> <td>  </td> ";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lblemailid'); ?></td>  
                                                        <td style="text-align: center;"><?php echo $witness[$i]['witness']['email_id']; ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblmobileno'); ?></td>
                                                        <td style="text-align: center;"><?php echo $witness[$i]['witness']['mobile_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; font-weight:bold;"><?php echo __('lbluid'); ?></td>  
                                                        <td style="text-align: center;"><?php echo $this->requestAction(array('controller' => 'Registration', 'action' => 'dec', ($witness[0]['witness']['uid_no']))); ?></td>
                                                        <td style="text-align: center;font-weight:bold;"><?php echo __('lblpancardno'); ?></td>
                                                        <td style="text-align: center;"><?php echo $witness[$i]['witness']['pan_no']; ?></td>
                                                    </tr>
                                                </table> 
                                            </div>
                                            <div class="col-sm-1"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <center><h3 class="box-title headbolder"><?php echo __('lblstampduty'); ?></h3></center>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12"  >
                                    <!--<div class="col-sm-1"></div>--> 
                                    <div class="col-sm-12"><?php echo $this->requestAction(array('controller' => 'Fees', 'action' => 'view_sd_calc', $token, NULL, $lang)); ?></div>
                                    <!--<div class="col-sm-1"></div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <center><h3 class="box-title headbolder"><?php // echo __('lblpayment'); ?></h3></center>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-1"></div> 
                                    <div class="col-sm-10"><?php // echo $this->requestAction(array('controller' => 'Reports', 'action' => 'summary1_report', base64_encode($token), 'V')); ?></div>
                                    <div class="col-sm-1"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>-->

            <!--Uploaded file list div-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">    


                        <center>  
                            <div id="uploadfiles">
                                <div>

                                    <!-- Modal content-->
                                    <div>
                                        <div class="modal-header">
                                            <!--                                            <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                                            <h4 class="modal-title"><b><?php echo __('lbluploadedfileslist'); ?></b></h4>
                                        </div>
                                        <div class="modal-body"> 

                                            <table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
                                                <thead>  
                                                    <tr>  
                                                        <th class="center"><?php echo __('lbldocumenttitle'); ?></th>
                                                        <th class="center"><?php echo __('lblaction'); ?></th>
                                                    </tr>  
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($document_list)) {

                                                        foreach ($document_list as $upFile) {
                                                            ?>
                                                            <tr>
                                                                <td class="tblbigdata"><?php echo $upFile['document']['document_name_' . $lang]; ?></td>

                                                                <td> <?php
                                                                    if ($upFile['uploaded_file_trn']['document_id'] == $upFile['document']['document_id']) {
                                                                        if ($upFile['uploaded_file_trn']['out_fname'] != '') {
                                                                            echo $this->Html->link(
                                                                                    'Download', array(
                                                                                'disabled' => TRUE,
                                                                                'controller' => 'Registration', // controller name
                                                                                'action' => 'downloadfile', //action name
                                                                                'full_base' => true, $upFile['uploaded_file_trn']['out_fname'], 'Uploads', $token), array('target' => '_blank')
                                                                            );
                                                                        }
                                                                    }
                                                                    ?></td>

                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>


                                        </div>

                                    </div>

                                </div>
                            </div>

                        </center>    
                    </div> </div> </div> 
            <!--=========Checklist Code=======================================================================================================================-->
            <?php
            foreach ($stampconfig as $stamprec) {
                if (isset($stamprec['functions'])) {
                    foreach ($stamprec['functions'] as $funrec) {
//            if ($funrec['action'] == $this->request->params['action']) {
                        if ($funrec['action'] == "document_checklist") {
                            $btnaccept_label = $funrec['btnaccept'];
                            $stampflag = $stamprec['stamp_flag'];
                            $funflag = $funrec['function_flag'];
                        }
                    }
                }
            }
            ?>

            <!--==============================================================================================================================================-->            


        </div>      
        <div class="well center">
            <?php if (!empty($regconfchecklist)) { ?>
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModalchecklist"><?php echo __('lblaccept'); ?></button>
            <?php } else { ?>
                <a href="<?php echo $this->webroot; ?>Registration/document_checkin/<?php echo $token; ?>" class="btn btn-success btn-lg"><?php echo __('lblaccept'); ?></a>   
            <?php } ?>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?php echo __('lblrevertback'); ?></button>
            <button type="button" class="btn btn-info btn-lg" id="summary1print"><?php echo __('lblprint'); ?></button>
        </div>     



        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <?php echo $this->form->create("document_entry", array('url' => array('controller' => 'Registration', 'action' => 'printgeneralinfo', $token), 'id' => 'dataentryaccept')); ?>
                <?php echo $this->form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lbldocrejectremak'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?php echo __('lblrevertback_reasons'); ?></label>
                            <?php
                            echo $this->form->input('revertback_id', array('id' => 'revertback_id', 'class' => 'form-control input-sm', 'label' => false, 'options' => $Reasons, 'empty' => '--Select--'));
                            ?>   <span class="form-error" id="revertback_id_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo __('lblenterrmk'); ?></label>
                            <?php echo $this->form->input("document_entry_remark", array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'id' => 'document_entry_remark')); ?>
                            <span class="form-error" id="document_entry_remark_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"  ><?php echo __('lblrevertback'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
                <?php echo $this->form->end(); ?>
            </div>
        </div>
        <div id="myModalchecklist" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <?php echo $this->Form->create('final_stamp', array('id' => 'final_stamp')); ?>
                <?php echo $this->form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lblsrochecklistdetails'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th> <?php echo __('lblsrno'); ?></th>
                                    <th><?php echo __('lblsrochecklistdetails'); ?></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                $checkbtn = '';
                                foreach ($Srochecklist as $single) {
                                    $c++;
                                    $checked = "";
                                    $checkbtn = $documents[0][0][$funflag];
//    pr($funflag);
//    pr($documents);exit;
                                    if ($documents[0][0][$funflag] == 'Y') {
                                        $checked = "checked";
                                    }
                                    ?>
                                    <tr>
                                        <th width="10%"><?php echo $c; ?></th>
                                        <th>
                                <div class="checklist<?php echo $single[0]['checklist_id'] ?>">
                                    <label>
                                        <input type="checkbox" name="data[final_stamp][checklist<?php echo $single[0]['checklist_id'] ?>]" value="<?php echo $single[0]['checklist_id'] ?>" <?php echo $checked; ?> >
                                        <?php echo $single[0]['checklist_desc_' . $lang] ?>  
                                    </label><br>
                                    <span class="form-error" id="checklist<?php echo $single[0]['checklist_id'] ?>_error"></span>
                                </div>
                                </th>

                                </tr>

                            <?php } ?>
                            <tr>
                                <td colspan="2"></td>
                            </tr> 
                            <?php
                            if ($c == 0) {
                                ?>
                                <tr>
                                    <td colspan="2"><?php echo __('lblrecordnotfound'); ?></td>
                                </tr>   
                                <?php
                            }
                            ?>

                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"  ><?php echo __('lblaccept'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
                <?php echo $this->form->end(); ?>
            </div>
        </div>
