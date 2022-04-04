
<script type="text/javascript">
    $(document).ready(function () {
        $('#delay').hide();
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#ref_doc_date,#court_order_date,#link_doc_date,.datepicker').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------
<?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>
            ($('#delay_flag').val() == 'Y') ? $('#delay').show() : $('#delay').hide();
            $('#exec_date,#presentation_date').datepicker({
                todayBtn: "linked",
                language: "it",
                autoclose: true,
                todayHighlight: true,
                format: "dd-mm-yyyy"
            }).on('changeDate', function () {
                var presentation_date = $('#presentation_date').val();
                var exec_date = $('#exec_date').val();
                if (presentation_date != '' && exec_date != '')
                {
                    $.post('<?php echo $this->webroot; ?>Citizenentry/check_execution_date', {exec_date: exec_date, presentation_date: presentation_date}, function (delay_flag)
                    {
                        $('#delay_flag').val(delay_flag);
                        $('#continue_flag').val('Y');
                        if (delay_flag == 'Y')
                        {
                            var retVal = confirm("Delay in execution time Do you want to allow ?");
                            if (retVal == true)
                            {
                                $('#delay').show();
                                return false;
                            }
                            else
                            {
                                $('#continue_flag').val('N');
                                $('#delay_order_no').val('');
                                $('#delay_remark').val('');
                                $('#delay').hide();
                                return false;
                            }
                        }
                        else if (delay_flag == 'R')
                        {
                            $('#delay').hide();
                            $('#delay_order_no').val('');
                            $('#delay_remark').val('');

                            return true;
                        }
                        else if (delay_flag == 'N')
                        {
                            $('#continue_flag').val('N');
                            $('#delay_order_no').val('');
                            $('#delay_remark').val('');
                            $('#delay').hide();
                            alert('Sorry Delayed document not accepted!')
                            return false;
                        }
                    });
                }
            });
<?php } else { ?>
            $('#exec_date').datepicker({
                todayBtn: "linked",
                language: "it",
                autoclose: true,
                todayHighlight: true,
                format: "dd-mm-yyyy"
            });
<?php } ?>

//-----------------------------------------------------------------------------------------------------------------------------------------------
        if ($('#article_id').val() != '') {
            getDependentArticle();
            var article_id = $('#article_id').val();
            var title_id = $('#title_id').val();
            $.getJSON('<?php echo $this->webroot; ?>Citizenentry/get_title', {article_id: article_id}, function (data)
            {
                var sc2 = '';
                $.each(data, function (index, val) {
                    if (title_id == index)
                        sc2 += "<option value=" + index + " selected>" + val + "</option>";
                });
                $("#title_id option").remove();
                $("#title_id").append(sc2);
            });
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#article_id').change(function () {
            getDependentArticle();
            var article_id = $('#article_id').val();
            $.getJSON('<?php echo $this->webroot; ?>Citizenentry/get_title', {article_id: article_id}, function (data)
            {
                var sc2 = '';
                $.each(data, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#title_id option").remove();
                $("#title_id").append(sc2);
            });
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#presentation_date,#exec_date').change(function () {
            $(this).datepicker('hide');
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#btnNext').click(function () {
            if ($('#continue_flag').val() == 'N')
            {
                alert('Sorry! You are not allowed to continue Not accepted delayed document.');
                return false;
            } else {
                $("#genernalinfoentry").submit();
            }
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------        
        tougleCourtOrderDate($("#doc_execution_type_id").val());
        $("#doc_execution_type_id").change(function () {
            tougleCourtOrderDate($(this).val());
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------        
        $('#local_language_id').change(function () {
            var lang = $('#local_language_id').val();

            $.post('<?php echo $this->webroot; ?>Citizenentry/setdoc_lang', {lang: lang}, function (data)
            {
                // alert('<?php echo $this->Session->read('doc_lang'); ?>');
                location.reload();
            });
        });
    });
//---------------------------------------------End of JQ-----------------------------------------------------------------------------------------
    function getDependentArticle() {
        $.post('<?php echo $this->webroot; ?>Citizenentry/getdependent_article', {article_id: $('#article_id option:selected').val()}, function (data) {
            $("#depfd").html(data);
        });
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
    function tougleCourtOrderDate(doc_exe_type_id) {
        if (doc_exe_type_id == 3) {
            $("#court_order_div").show();
        }
        else {
            $("#court_order_date").val('');
            $("#court_order_div").hide();
        }
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

</script>
<?php echo $this->Html->css('popup'); ?>
<?php echo $this->Form->create('genernalinfoentry', array('id' => 'genernalinfoentry', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("Registration/main_menu");
?>
<?php
echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
//pr($errarr);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblgeneralinfo'); ?></h3></center>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('lblengdatarequired'); ?></p>
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>2:&nbsp;</b><?php echo __('lblrefdocnofetchparty'); ?></p>
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>3:&nbsp;</b><?php echo __('lbllinkdocregno'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbllocaldataentry'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('local_language_id', array('type' => 'select', 'label' => false, 'id' => 'local_language_id','style' => 'cursor: not-allowed;','disabled', 'class' => 'form-control input-sm', 'options' => $language, 'default' => $this->Session->read('doc_lang_id'), 'value' => $lang_id)); ?>
                            <span id="local_language_id_error" class="form-error"><?php echo $errarr['local_language_id_error']; ?></span>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblnoofpages'); ?></label>    
                        <div class="col-sm-3" ><?php echo $this->Form->input('no_of_pages', array('label' => false, 'id' => 'no_of_pages', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="no_of_pages_error" class="form-error"><?php echo $errarr['no_of_pages_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblArticle'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($article))); ?>
                            <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbldocumenttitle'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('title_id', array('label' => false, 'id' => 'title_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($documenttitle))); ?>
                            <span id="title_id_error" class="form-error"><?php echo $errarr['title_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblexecutiontype'); ?> </label>   
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('doc_execution_type_id', array('label' => false, 'id' => 'doc_execution_type_id', 'class' => 'form-control input-sm', 'options' => array($document_execution_type))); ?>
                              <!--<span id="doc_execution_type_id_error" class="form-error"><?php // echo $errarr['doc_execution_type_id_error'];    ?></span>-->
                            <?php
                            if (is_numeric($Selectedtoken)) {
                                echo $this->Form->input('general_info_id', array('label' => false, 'type' => 'hidden', 'id' => 'general_info_id', 'class' => 'form-control input-sm'));
                            }
                            ?>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblnameofdockwriter'); ?></label>    
                        <div class="col-sm-3" ><?php echo $this->Form->input('doc_writer_name', array('label' => false, 'id' => 'doc_writer_name', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $username, 'readonly')); ?>
                        <span id="doc_writer_name_error" class="form-error"><?php echo $errarr['doc_writer_name_error'];    ?></span>
                        </div>

                    </div>
                </div>

                <div class="row" id="court_order_div">
                    <div  class="rowht">&nbsp;</div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-laref_doc_nobel"><?php echo __('lblCourtOrderDate'); ?></label>    
                        <div class="col-sm-3" ><?php echo $this->Form->input('court_order_date', array('label' => false, 'id' => 'court_order_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                          <!--<span id="court_order_date_error" class="form-error"><?php //echo $errarr['court_order_date_error'];    ?></span>-->
                        </div>                       
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblexecutiondt'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('exec_date', array('label' => false, 'id' => 'exec_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                         <span id="exec_date_error" class="form-error"><?php echo $errarr['exec_date_error'];   ?></span>
                        </div>
                        <?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblpresentationdate'); ?></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('presentation_date', array('label' => false, 'id' => 'presentation_date', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                             <!--<span id="presentation_date_error" class="form-error"><?php // echo $errarr['presentation_date_error'];   ?></span>-->
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

                <div class="row" id="delay">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbldelayorderno'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('delay_order_no', array('label' => false, 'id' => 'delay_order_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                          <!--<span id="delay_order_no_error" class="form-error"><?php //echo $errarr['delay_order_no_error'];   ?></span>-->
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbldelayremark'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('delay_remark', array('label' => false, 'id' => 'delay_remark', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                          <!--<span id="delay_remark_error" class="form-error"><?php //echo $errarr['delay_remark_error'];   ?></span>-->
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lblsearcholdnamebyparty'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('search_type', array('label' => false, 'id' => 'search_type', 'class' => 'form-control input-sm', 'options' => array($search_type))); ?>
                            <!--<span id="ref_doc_no_error" class="form-error"><?php //echo $errarr['ref_doc_no_error'];   ?></span>-->
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lblrefdocno'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('ref_doc_no', array('label' => false, 'id' => 'ref_doc_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                         <span id="ref_doc_no_error" class="form-error"><?php echo $errarr['ref_doc_no_error'];   ?></span>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblrefregdocdate'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('ref_doc_date', array('label' => false, 'id' => 'ref_doc_date', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                        <span id="ref_doc_date_error" class="form-error"><?php echo $errarr['ref_doc_date_error'];   ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <?php if($advocate_name_flag=='Y'){ ?>
                <div class="row">
                    <div class="form-group">
                        <?php
                        $doc_lang = $this->Session->read('doc_lang');

                        if (!empty($doc_lang) && $doc_lang != 'en') {
                            ?>
                            <label for="adv_name_ll" class="col-sm-3 control-label"><?php echo __('lbladvocatename_' . $this->Session->read('doc_lang')); ?></label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('adv_name_ll', array('label' => false, 'id' => 'adv_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <!--<span id="adv_name_ll_error" class="form-error"><?ph//p echo $errarr['adv_name_ll_error']; ?></span>-->
                            </div>
                        <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbladvtname'); ?>[ENGLISH]</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('adv_name_en', array('label' => false, 'id' => 'adv_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                         <span id="adv_name_en_error" class="form-error"><?php echo $errarr['adv_name_en_error'];   ?></span>
                        </div>

                    </div>
                </div>   
                <?php } ?>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbllnkdocno'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('link_doc_no', array('label' => false, 'id' => 'link_doc_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                         <span id="link_doc_no_error" class="form-error"><?php echo $errarr['link_doc_no_error'];   ?></span>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbldocdate'); ?></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('link_doc_date', array('label' => false, 'id' => 'link_doc_date', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                        <span id="link_doc_date_error" class="form-error"><?php echo $errarr['link_doc_date_error'];   ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblofficename'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('link_office_id', array('type' => 'select', 'label' => false, 'id' => 'link_office_id', 'class' => 'form-control input-sm', 'options' => $office)); ?>
                            <span id="local_language_id_error" class="form-error"><?php echo $errarr['local_language_id_error']; ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblarticaldepfields'); ?></h3>
            </div>
            <div class="box-body">
                <div id="depfd">


                </div>  
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >
                    <?php if (isset($delay)) { ?>
                        <?php echo $this->Form->input('delay_flag', array('label' => false, 'id' => 'delay_flag', 'class' => 'form-control input-sm', 'value' => $delay, 'type' => 'hidden')); ?>

                    <?php } else { ?>
                        <?php echo $this->Form->input('delay_flag', array('label' => false, 'id' => 'delay_flag', 'class' => 'form-control input-sm', 'type' => 'hidden')); ?>

                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                  
                    <input type="hidden"  id="continue_flag">
                    <button type="reset"  id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
                    <button type="button" id="btnNext" name="btnNext" class="btn btn-info"><?php echo __('btnnext'); ?></button>
                </div>  
            </div>
        </div>


    </div>
</div>
<?php unset($_SESSION['doc_lang_id']); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

