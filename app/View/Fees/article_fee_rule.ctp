<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<script>
    $(document).ready(function () {
        $("#eff_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblArticleFeeRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
        ($('#article_id').val() == 9998) ? ($('.exm_row').show()) : ($('.exm_row').hide());
        //--------------------------------------------------------------------
        var host = "<?php echo $this->webroot; ?>";
        //--------------------------------------------------------------------
        $("#article_id").change(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });
            if ($(this).val()) {
                if ($(this).val().trim() == 9998) {
                    $('.exm_row').show();
                }
                else {
                    $('.exm_row').hide();
                }
                $.post(host + "getArticleDesc", {article_id: Base64.encode($(this).val()), csrftoken: $('#csrftoken').val()}, function (data)
                {
                    if (($("#actionid").val() != 'U') || ($("#rule_desc_en").val().trim().length == 0)) {
                        $("#rule_desc_en").val(data['article_desc_en']);
                        $("#rule_desc_ll").val(data['article_desc_ll']);
                    }
                }, 'json');
            } else {
                $("#rule_desc_en,rule_desc_ll").val('');
            }
        });

        $('#search_rule').keyup(function () {
            var valThis = $(this).val().toLowerCase();
            $('.usage_cat_id input[type="checkbox"]').each(function () {
                var usagecatid = $(this).val();
                var label = $("label[for='exm_article_id" + usagecatid + "']").html().toLowerCase();
                if (label.indexOf(valThis) > -1) {
                    //$(this).show();
                    //$("label[for='usage_cat_id" + usagecatid + "']").show();
                    $("label[for='exm_article_id" + usagecatid + "']").parent('div').show();
                } else {
                    //$(this).hide();
                    //$("label[for='usage_cat_id" + usagecatid + "']").hide();
                    $("label[for='exm_article_id" + usagecatid + "']").parent('div').hide();
                }
            });
        });


        $("#btnExit").click(function () {
            window.location = "<?php echo $this->webroot; ?>";
            return false;
        });
        $("#btnSaveRule").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()))
            });
        });

    });

</script>

<?php
//pr($fieldname);
echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="btn-arrow">

            <a href="<?php echo $this->webroot; ?>Fees/fee_rule_index" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . __('lblList'); ?></a>            
            <a href="<?php echo $this->webroot; ?>Fees/article_fee_rule/<?php echo $this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo __('lblfeerule') . __('lbllevelname'); ?></a>            
            <a href="<?php echo $this->webroot; ?>Fees/article_fee_rule_item_linkage/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo __('lblfeerule') . __('lblItemLinkage'); ?></a>            
            <a href="<?php echo $this->webroot; ?>Fees/linked_feeitems_config/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo "Item Link"; ?></a>  
            <a href="<?php echo $this->webroot; ?>Fees/article_fee_sub_rule/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo __('lblsubrule') ?></a>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">

            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfeerule') . " " . __('lbllevelname'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Fee Rule/article_fee_rule_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-xs-12" id="rule_id_label" style="text-align: center;color: white; background-color: #E6B800"><?php echo $fee_rule; ?> </label>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="financial Year" class="control-label col-sm-2"><?php echo __('lblfineyer'); ?></label>
                            <div class="col-sm-2" >
                                <?php echo $this->Form->input($fieldname[7], array('options' => $finyearList, 'multiple' => false, 'id' => 'finyear_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span id="finyear_id_error" class="form-error"><?php echo $errarr['finyear_id_error']; ?></span>
                            </div>
                            <label for="Effective Date" class="control-label col-sm-2"><?php echo __('lbleffedate'); ?></label>
                            <div class="col-sm-2" >
                                <?php echo $this->Form->input($fieldname[8], array('id' => 'eff_date', 'label' => false, 'class' => 'form-control input-sm', 'readOnly' => true)); ?>
                              <!--<span id="eff_date_error" class="form-error"><?php // echo $errarr['eff_date_error'];   ?></span>-->
                            </div>
                            <label for="refno" class="control-label col-sm-2"><?php echo __('lblReferenceNo'); ?></label>
                            <div class="col-sm-2" >
                                <?php echo $this->Form->input($fieldname[9], array('id' => 'reference_no', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span id="reference_no_error" class="form-error"><?php echo $errarr['reference_no_error']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="Select Article" class="control-label col-sm-2" ><?php echo __('lblArticle'); ?></label>   
                            <div class="col-sm-4">
                                <?php echo $this->Form->input($fieldname[2], array('type' => 'select', 'empty' => '--select--', 'options' => $articlelist, 'label' => false, 'multiple' => false, 'id' => 'article_id', 'class' => 'form-control input-sm')); ?>
                                <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                            </div>                            
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row" >
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="rule_desc_en" class="control-label col-sm-2"><?php echo __('lblruledescen'); ?><span style="color: #ff0000">*</span></label>
                            <?php
                            $i = 1;
                            foreach ($languagelist as $language1) {

                                if ($i % 6 == 0) {

                                    echo "<div class=row>";
                                }
                                ?>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('fee_rule_desc_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'fee_rule_desc_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $language1['mainlanguage']['language_name'], 'maxlength' => "100")) ?>
                                    <span id="<?php echo 'fee_rule_desc_' . $language1['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                        <?php echo $errarr['fee_rule_desc_' . $language1['mainlanguage']['language_code'] . '_error']; ?>
                                    </span>
                                </div>

                                <?php
                                if ($i % 6 == 0) {
                                    if ($i > 1) {
                                        echo "</div><br>";
                                    }
                                }
                                $i++;
                            }
                            ?> 

                        </div>
                    </div>
                </div> 
                <div class="rowht"></div>
                <?php // Exemption Article Id ?>
                <div  class="rowht"></div>   <div  class="rowht"></div> 
                <div class="row exm_row">
                    <div class="col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="panel-title"><?php echo __('lblexemptionappliedon') . " " . __('lblArticle'); ?></li>

                                    <li class="pull-right"> <div class="input-group"> 
                                            <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span> 
                                            <?php echo $this->Form->input('search_rule', array('id' => 'search_rule', 'label' => false, 'placeholder' => 'Search...', 'class' => 'brn btn-search')); ?>
                                        </div> </li>
                                </ul>
                            </div>

                            <div  id="usage-list">
                                <?php echo $this->Form->input('exm_article_id', array('type' => 'select', 'options' => $articlelist, 'label' => false, 'multiple' => 'checkbox', 'id' => 'exm_article_id', 'class' => 'exm_row usage_cat_id')); ?>                                
                                <?php // echo $this->Form->input('fee_item_list', array('type' => 'select', 'options' => $inputItemlist, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => 'usage_cat_id')); ?>
                            </div>
                        </div>                        
                    </div>
                </div>


                <div id="usageRow" hidden="true"  style="border: 2px #FFDACC solid;  border-collapse: separate">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblusamaincat'); ?></label>
                                <label for="usage_sub_catg_id" class="control-label col-sm-4"><?php echo __('lblUsagesubcategoryhead'); ?></label>
                                <label for="usage_sub_sub_catg_id" class="control-label col-sm-6"><?php echo __('lblsubsubcategorydesc'); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-sm-2" ><?php echo $this->Form->input($fieldname[10], array('options' => $maincatlist, 'empty' => '--Select--', 'multiple' => false, 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <div class="col-sm-4" ><?php echo $this->Form->input($fieldname[11], array('type' => 'select', 'empty' => '--Select--', 'id' => 'usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                                <div class="col-sm-6" ><?php echo $this->Form->input($fieldname[12], array('type' => 'select', 'empty' => '--Select--', 'id' => 'usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="locationRow" hidden="true"  style="border: 2px #EDADEE solid;  border-collapse: separate">

                    <table                                 <div  class="rowht"></div>>  
                        <thead >  
                            <tr>
                                <th><?php echo __('lbladmstate'); ?></th>
                                <?php if ($configure['is_div'] == 'Y') { ?>
                                    <th><?php echo __('lblDivision'); ?></th><?php } ?>

                                <?php if ($configure['is_dist'] == 'Y') { ?>
                                    <th><?php echo __('lblDistrict'); ?></th><?php } ?>
                                <th><?php echo __('lblLandType'); ?> </th>   
                                <?php if ($configure['is_zp'] == 'Y') { ?>
                                    <th><?php echo __('lblSubDivision'); ?> </th><?php } ?>
                                <?php if ($configure['is_taluka'] == 'Y') { ?>
                                    <th><?php echo __('lbladmtaluka'); ?></th><?php } ?>
                                <?php if ($configure['is_block'] == 'Y') { ?>
                                    <th><?php echo __('lblCircle'); ?> </th><?php } ?>

                                <th  class="ulb_type"><?php echo __('lblCorporationClass'); ?> </th>
                                <th class="corp_id"><?php echo __('lblcorporation'); ?></th>
                                <th><?php echo __('lblVillage'); ?> </th>                                          
                            </tr> 
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $this->Form->input($fieldname[42], array('options' => $statelist, 'id' => 'state_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                <?php if ($configure['is_div'] == 'Y') { ?>                                            
                                    <td ><?php echo $this->Form->input($fieldname[31], array('options' => $divisionlist, 'empty' => '--select--', 'id' => 'div_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                } else {
                                    echo $this->Form->input($fieldname[31], array('type' => 'hidden', 'value' => '0'));
                                }
                                ?>

                                <?php if ($configure['is_dist'] == 'Y') { ?>
                                    <td ><?php echo $this->Form->input($fieldname[32], array('empty' => '--select--', 'id' => 'dist_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                } else {
                                    echo $this->Form->input($fieldname[32], array('type' => 'hidden', 'value' => '0'));
                                }
                                ?>
<!--<td ><?php echo $this->Form->input($fieldname[36], array('empty' => '--select--', 'id' => 'landtype_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>-->
                                <?php if ($configure['is_zp'] == 'Y') { ?>
                                    <td ><?php echo $this->Form->input($fieldname[33], array('empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                } else {
                                    echo $this->Form->input($fieldname[33], array('type' => 'hidden', 'value' => '0'));
                                }
                                ?>
                                <?php if ($configure['is_taluka'] == 'Y') { ?>
                                    <td ><?php echo $this->Form->input($fieldname[34], array('empty' => '--select--', 'id' => 'tal_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                } else {
                                    echo $this->Form->input($fieldname[34], array('type' => 'hidden', 'value' => '0'));
                                }
                                ?>
                                <?php if ($configure['is_block'] == 'Y') { ?>
                                    <td ><?php echo $this->Form->input($fieldname[35], array('empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php
                                } else {
                                    echo $this->Form->input($fieldname[35], array('type' => 'hidden', 'value' => '0'));
                                }
                                ?>

                            <!--<td  class="ulb_type"> <?php echo $this->Form->input($fieldname[37], array('options' => $corporationlType, 'empty' => '--select--', 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>-->
                                <td  class="corp_id"><?php echo $this->Form->input($fieldname[38], array('empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                <td ><?php echo $this->Form->input($fieldname[39], array('empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
                <div class="row" hidden="true">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="Select feerule" class="control-label col-sm-2" ><?php echo __('lbluserdefineddependency1'); ?></label>   
                            <div class="col-sm-4">  <?php echo $this->Form->input($fieldname[46], array('type' => 'select', 'label' => false, 'options' => $udd1list, 'multiple' => false, 'id' => 'udd1', 'class' => 'form-control input-sm')); ?> </div>
                            <label for="Select feerule" class="control-label col-sm-2" ><?php echo __('lbluserdefineddependency2'); ?></label>   
                            <div class="col-sm-4">  <?php echo $this->Form->input($fieldname[46], array('type' => 'select', 'label' => false, 'options' => $udd2list, 'multiple' => false, 'id' => 'udd2', 'class' => 'form-control input-sm')); ?> </div>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row center">
                    <div class="col-lg-12">
                        <div class="form-group">                               
                            <?php
                            echo $this->Form->button(__('btnsave'), array('id' => 'btnSaveRule', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
                            echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                            ?>
                        </div>
                        <div class="hidden Input">
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
                            echo $this->Form->input('rule_id', array('id' => 'ruleid', 'type' => 'hidden'));
                            echo $this->Form->input('frmaction', array('id' => 'actionid', 'type' => 'hidden'));
                            ?>
                        </div>
                    </div>
                </div>

                <!--</div>-->
            </div>
        </div>
    </div>
</div>