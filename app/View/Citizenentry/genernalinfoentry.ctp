
<script type="text/javascript">
    $(document).ready(function () {
        $('#delay').hide();

        $('[data-toggle="tooltip"]').tooltip();

//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#ref_doc_date,#court_order_date,#link_doc_date,#entry_date_india,.datepicker').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            endDate: '+0d',
            format: "dd-mm-yyyy"
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------
<?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>
            ($('#delay_flag').val() == 'Y') ? $('#delay').show() : $('#delay').hide();
            $('#exec_date,#presentation_date,#entry_date_india').datepicker({
                todayBtn: "linked",
                language: "it",
                autoclose: true,
                todayHighlight: true,
                endDate: '+0d',
                format: "dd-mm-yyyy"
            }).on('changeDate', function () {

                var presentation_date = $('#presentation_date').val();
                var ex_type = $("#doc_execution_type_id").val();
                var article_id = $("#article_id").val();
                if (article_id != null && article_id != 63) {
                    if (ex_type == 2) {
                        var exec_date = $('#entry_date_india').val();
                    } else {
                        var exec_date = $('#exec_date').val();
                    }
                    if (presentation_date != '' && exec_date != '')
                    {

                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_execution_date', {exec_date: exec_date, presentation_date: presentation_date}, function (delay_flag)
                        {

                            $('#delay_flag').val(delay_flag.trim());
                            $('#continue_flag').val('Y');
                            if (delay_flag.trim() == 'Y')
                            {
                                var retVal = confirm("Delay in execution time Do you want to allow ?");
                                if (retVal == true)
                                {
                                    $('#delay').show();
                                    return false;
                                } else
                                {
                                    $('#continue_flag').val('N');
                                    $('#delay_order_no').val('');
                                    $('#delay_remark').val('');
                                    $('#delay').hide();
                                    return false;
                                }
                            } else if (delay_flag.trim() == 'R')
                            {
                                $('#delay').hide();
                                $('#delay_order_no').val('');
                                $('#delay_remark').val('');

                                return true;
                            } else if (delay_flag.trim() == 'N')
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
                } else if (article_id == 63) {
                    $('#continue_flag').val('Y');
                }


            });


<?php } else { ?>
            $('#exec_date').datepicker({
                todayBtn: "linked",
                language: "it",
                autoclose: true,
                todayHighlight: true,
                endDate: '+0d',
                format: "dd-mm-yyyy"
            });
<?php } ?>

//-----------------------------------------------------------------------------------------------------------------------------------------------
        if ($('#article_id').val() != '') {
            if ($('#article_id').val() == 63) {

                $('#continue_flag').val('Y');
            } else {
                $('#continue_flag').val('N');
            }
            getDependentArticle();
            var article_id = $('#article_id').val();
            var title_id = $('#title_id').val();
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $.post('<?php echo $this->webroot; ?>Citizenentry/get_title', {article_id: article_id, csrftoken: csrftoken}, function (data)
            {
                var sc2 = '<option value="">--select--</option>';
                $.each(data, function (index, val) {
                    if (title_id == index)
                        sc2 += "<option value=" + index + " selected>" + val + "</option>";
                });
                $("#title_id option").remove();
                $("#title_id").append(sc2);
            }, 'json');
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#article_id').change(function () {
            if ($('#article_id').val() == 63) {
                $('#continue_flag').val('Y');
            } else {
                $('#continue_flag').val('N');
            }
            if ($('#article_id').val() != '') {
                getDependentArticle();
                var article_id = $('#article_id').val();
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_title', {article_id: article_id, csrftoken: csrftoken}, function (data)
                {
                    var sc2 = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc2 += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#title_id option").remove();
                    $("#title_id").append(sc2);
                }, 'json');
            }
        });
//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#presentation_date,#exec_date').change(function () {
            $(this).datepicker('hide');
        });



//-----------------------------------------------------------------------------------------------------------------------------------------------
        $('#btnNext').click(function () {
<?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>

                check_delaydocument();
                $('#continue_flag').val('Ý');

                if ($('#continue_flag').val() == 'N')
                {
                    alert('Sorry! You are not allowed to continue Not accepted delayed document.');
                    return false;
                } else {
                    $("#genernalinfoentry").submit();
                }
<?php } else { ?>

                $("#genernalinfoentry").submit();
<?php } ?>
        });

		

//----------------------------------------------------------------------------------------------------------------------------------------------

        $("#viewdocuments").click(function () {
            var article_sel=$("#article_id").val();
            //alert(article_sel);
            var article_title_sel=$("#title_id").val();
           // alert(article_title_sel);
            if(article_sel!='' && article_title_sel!='' && article_title_sel!=null && article_sel!=null)
            {
                    $.post("<?php echo $this->webroot; ?>Citizenentry/getdocs",
                    {
                        article_sel: article_sel,
                        article_title_sel: article_title_sel,
                        csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                    },
                    function (data, status) {
                        //$("#rate_modal_body").html(data);
                        // $('#ratetbl').DataTable({"bSort": false});
                        // $('#rateModal').modal('show');

                        $("#doc_modal_body").html(data);
                        $('#docModal').modal('show');
                    });
            
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
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

            $.post('<?php echo $this->webroot; ?>Citizenentry/setdoc_lang', {lang: lang, csrftoken: csrftoken}, function (data)
            {
                // alert('<?php echo $this->Session->read('doc_lang'); ?>');
                location.reload();
            });
        });

//var dist = $("#district_id option:selected").val();
//if(dist !='empty'){
//  dist_change_event(dist);
//                           village_change_dist_event(dist);
//}
        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();

            dist_change_event(dist);
            village_change_dist_event(dist);

        });

        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            taluka_change_event(tal);
        });

        $('#village_id').change(function () {
            var village = $("#village_id option:selected").val();
            village_change_event(village);


        });

        $('#office_id').change(function () {

<?php if (is_numeric($this->Session->read('Selectedtoken'))) { ?>

                var retVal = confirm("By changing submission office details all uploaded files get deleted,Do you want to change?");
                if (retVal == true)
                {
                    return true;
                }
<?php } ?>

        });





    });
//---------------------------------------------End of JQ-----------------------------------------------------------------------------------------
    function getDependentArticle() {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post('<?php echo $this->webroot; ?>Citizenentry/getdependent_article', {article_id: $('#article_id option:selected').val(), csrftoken: csrftoken}, function (data) {
            $("#depfd").html(data);
            $(document).trigger('_page_ready');
        });
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
    function tougleCourtOrderDate(doc_exe_type_id) {
        if (doc_exe_type_id == 3) {
            $("#court_order_div").show();
            $("#entry_date_div").hide();
            $("#entry_date_india").val('');
        } else if (doc_exe_type_id == 2) {
            $("#entry_date_div").show();
            $("#court_order_date").val('');
            $("#court_order_div").hide();

        } else {
            $("#court_order_date").val('');
            $("#court_order_div").hide();
            $("#entry_date_div").hide();
            $("#entry_date_india").val('');

        }
    }
    function forcancel() {

        window.location.href = "<?php echo $this->webroot; ?>Citizenentry/genernalinfoentry/<?php echo $this->Session->read('csrftoken'); ?>";
            }
//-----------------------------------------------------------------------------------------------------------------------------------------------



            function dist_change_event(dist_id) {
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                $.post("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist_id, csrftoken: csrftoken}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.taluka, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#taluka_id").prop("disabled", false);
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);

                }, 'json');
            }


            function taluka_change_event(tal)
            {
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal, csrftoken: csrftoken}, function (data)
                {

                    var sc = '<option value="">--select--</option>';
                    $.each(data.village, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#village_id option").remove();
                    $("#village_id").append(sc);
                }, 'json');

                $.post('<?php echo $this->webroot; ?>Citizenentry/get_office_list', {tal: tal, csrftoken: csrftoken}, function (data1)
                {

                    var sc1 = '<option value="">--select--</option>';
                    $.each(data1.office, function (index1, val1) {

                        sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                    });

                    $("#office_id option").remove();
                    $("#office_id").append(sc1);
                }, 'json');
            }
            function  village_change_event(village) {
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                var tal = $("#taluka_id option:selected").val();
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_office_list', {tal: tal, village: village, csrftoken: csrftoken}, function (data)
                {


                    var sc = '<option value="">--select--</option>';
                    $.each(data.office, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#office_id option").remove();
                    $("#office_id").append(sc);
                }, 'json');
            }

            //dist office sro
            function  village_change_dist_event(dist) {
                var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                //var tal = $("#taluka_id option:selected").val();
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_office_list_dist', {dist: dist, csrftoken: csrftoken}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.office, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#office_id option").remove();
                    $("#office_id").append(sc);
                }, 'json');
            }

            function check_delaydocument() {
                var article_id = $("#article_id").val();
                if (article_id != null && article_id != 63) {
                    var presentation_date = $('#presentation_date').val();
                    var ex_type = $("#doc_execution_type_id").val();
                    if (ex_type == 2) {
                        var exec_date = $('#entry_date_india').val();
                    } else {
                        var exec_date = $('#exec_date').val();
                    }

                    if (presentation_date != '' && exec_date != '')
                    {

                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_execution_date', {exec_date: exec_date, presentation_date: presentation_date}, function (delay_flag)
                        {

                            $('#delay_flag').val(delay_flag);
                            $('#continue_flag').val('Y');

                            if (delay_flag.trim() == 'Y')
                            {
                                var retVal = confirm("Delay in execution time Do you want to allow ?");
                                if (retVal == true)
                                {
                                    $('#delay').show();
                                    return false;
                                } else
                                {
                                    $('#continue_flag').val('N');
                                    $('#delay_order_no').val('');
                                    $('#delay_remark').val('');
                                    $('#delay').hide();
                                    return false;
                                }
                            } else if (delay_flag.trim() == 'R')
                            {
                                $('#delay').hide();
                                $('#delay_order_no').val('');
                                $('#delay_remark').val('');
                                $('#continue_flag').val('Y');

                                return true;
                            } else if (delay_flag.trim() == 'N')
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
                } else if (article_id == 63) {
                    $('#continue_flag').val('Y');
                }


            }


</script>

<style type="text/css">
    .mycontent-left {
        border-right: 1px dashed #333;
    }
</style>
<?php
echo $this->Html->css('popup');
echo $this->Form->create('genernalinfoentry', array('id' => 'genernalinfoentry', 'class' => 'form-vertical'));
echo $this->element("Registration/main_menu");
echo $this->element("Citizenentry/property_menu");
?>

<div class="row">

    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title " style="font-weight: bolder"><?php echo __('lblgeneralinfo'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/genernalinfoentry_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <?php if ($this->Session->read("Selectedtoken") != '') { ?>

                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-sm-5 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                            <div class="col-sm-7">
                                                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('lblengdatarequired'); ?></br>
                                                <b><?php echo __('lblnote'); ?>2:&nbsp;</b><?php echo __('lblrefdocnofetchparty'); ?></br>
                                                <b><?php echo __('lblnote'); ?>3:&nbsp;</b><?php echo __('lbllinkdocregno'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rowht"></div>
                <div class="hr1" style="border: 1px solid black;"></div>
                <!--                <hr style="border: 1px solid black;">-->
                <?php if ($this->Session->read("manual_flag") == 'Y') { ?>
                    <div class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblmanualregno'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3" ><?php echo $this->Form->input('manual_reg_no', array('label' => false, 'id' => 'manual_reg_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="manual_reg_no_error" class="form-error"><?php echo $errarr['manual_reg_no_error']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbllocaldataentry'); ?></label>    
                        <div class="col-sm-3 right-border">
                            <?php echo $this->Form->input('local_language_id', array('type' => 'select', 'label' => false, 'id' => 'local_language_id', 'style' => 'cursor: not-allowed;', 'disabled', 'class' => 'form-control input-sm', 'options' => $language, 'default' => $this->Session->read('doc_lang_id'), 'value' => $lang_id)); ?>
                            <span id="local_language_id_error" class="form-error"><?php echo $errarr['local_language_id_error']; ?></span>
                        </div>
                        <?php if ($display_no_of_pages == 'Y') { ?>
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblnoofpages'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3" ><?php echo $this->Form->input('no_of_pages', array('label' => false, 'id' => 'no_of_pages', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="no_of_pages_error" class="form-error"><?php echo $errarr['no_of_pages_error']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblArticle'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 right-border">
                            <?php if ($submission_flag == 'N') { ?>
                                <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($article))); ?>
                                <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                            <?php } else { ?>
                                <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'disabled', 'empty' => '--Select--', 'options' => array($article))); ?>
                                <?php echo $this->Form->input('article_id', array('label' => false, 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'type' => 'hidden')); ?>
                            <?php } ?>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbldocumenttitle'); ?></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('title_id', array('label' => false, 'id' => 'title_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($documenttitle))); ?>
                            <!--<span id="title_id_error" class="form-error"><?php // echo $errarr['title_id_error'];                                ?></span>-->
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblexecutiontype'); ?> <span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3 right-border">
                            <?php echo $this->Form->input('doc_execution_type_id', array('label' => false, 'id' => 'doc_execution_type_id', 'class' => 'form-control input-sm', 'options' => array($document_execution_type))); ?>
                              <!--<span id="doc_execution_type_id_error" class="form-error"><?php // echo $errarr['doc_execution_type_id_error'];                                   ?></span>-->
                            <?php
                            if (is_numeric($Selectedtoken)) {
                                echo $this->Form->input('general_info_id', array('label' => false, 'type' => 'hidden', 'id' => 'general_info_id', 'class' => 'form-control input-sm'));
                            }
                            ?>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblnameofdockwriter'); ?></label>  
                        <?php if (!empty($username)) { ?>
                            <div class="col-sm-3" ><?php echo $this->Form->input('doc_writer_name', array('label' => false, 'id' => 'doc_writer_name', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $username, 'readonly')); ?>
                            <?php } else { ?>
                                <div class="col-sm-3" ><?php echo $this->Form->input('doc_writer_name', array('label' => false, 'id' => 'doc_writer_name', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $username)); ?>
                                <?php } ?>
                                <span id="doc_writer_name_error" class="form-error"><?php echo $errarr['doc_writer_name_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row" id="court_order_div">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-laref_doc_nobel"><?php echo __('lblCourtOrderDate'); ?></label>    
                            <div class="col-sm-3 right-border" ><?php echo $this->Form->input('court_order_date', array('label' => false, 'id' => 'court_order_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                              <!--<span id="court_order_date_error" class="form-error"><?php //echo $errarr['court_order_date_error'];                                    ?></span>-->
                            </div>                       
                        </div>
                    </div>
                    <div class="row" id="entry_date_div">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-laref_doc_nobel"><?php echo __('Entry Date in India'); ?></label>    
                            <div class="col-sm-3 right-border" ><?php echo $this->Form->input('entry_date_india', array('label' => false, 'id' => 'entry_date_india', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                              <!--<span id="court_order_date_error" class="form-error"><?php //echo $errarr['court_order_date_error'];                                    ?></span>-->
                            </div>                       
                        </div>
                    </div>
                    <div  class="rowht"></div>                   
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblexecutiondt'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3 right-border" >
                                <?php if (isset($exe_date)) { ?>
                                    <?php echo $this->Form->input('exec_date', array('label' => false, 'id' => 'exec_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'value' => $exe_date, 'type' => 'text')); ?>
                                <?php } else { ?>
                                    <?php echo $this->Form->input('exec_date', array('label' => false, 'id' => 'exec_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                                <?php } ?>
                                <span id="exec_date_error" class="form-error"><?php echo $errarr['exec_date_error']; ?></span>
                            </div>
                            <?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>
                                <?php if ($this->Session->read("manual_flag") != 'Y') { ?>
                                    <label for="" class="col-sm-3 control-label"><?php echo __('lblpresentationdate'); ?></label>    
                                    <div class="col-sm-3" >
                                        <?php echo $this->Form->input('presentation_date', array('label' => false, 'id' => 'presentation_date', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'disabled', 'type' => 'text', 'value' => date('d-m-Y'))); ?>
                                     <!--<span id="presentation_date_error" class="form-error"><?php // echo $errarr['presentation_date_error'];                                  ?></span>-->
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div  class="rowht"></div>   
                    <div class="row" id="delay">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbldelayorderno'); ?></label>    
                            <div class="col-sm-3 right-border" >
                                <?php echo $this->Form->input('delay_order_no', array('label' => false, 'id' => 'delay_order_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                              <!--<span id="delay_order_no_error" class="form-error"><?php //echo $errarr['delay_order_no_error'];                                  ?></span>-->
                            </div>
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbldelayremark'); ?></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('delay_remark', array('label' => false, 'id' => 'delay_remark', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                              <!--<span id="delay_remark_error" class="form-error"><?php //echo $errarr['delay_remark_error'];                                  ?></span>-->
                            </div>
                        </div>
                    </div>
                    <div  class="rowht"></div>   
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblrefdocno'); ?></label>    
                            <div class="col-sm-3 right-border" >
                                <?php echo $this->Form->input('ref_doc_no', array('label' => false, 'id' => 'ref_doc_no', 'class' => 'form-control input-sm', 'type' => 'text', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Is required to fetch party name from old document')); ?>
                                <span id="ref_doc_no_error" class="form-error"><?php echo $errarr['ref_doc_no_error']; ?></span>
                            </div>
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblrefregdocdate'); ?></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('ref_doc_date', array('label' => false, 'id' => 'ref_doc_date', 'class' => 'form-control input-sm', 'type' => 'text', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Is required to fetch party name from old document')); ?>
                                <span id="ref_doc_date_error" class="form-error"><?php echo $errarr['ref_doc_date_error']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <?php if ($advocate_name_flag == 'Y') { ?>
                        <div class="row">
                            <div class="form-group">
                                <?php
                                $doc_lang = $this->Session->read('doc_lang');

                                if (!empty($doc_lang) && $doc_lang != 'en') {
                                    ?>
                                    <label for="adv_name_ll" class="col-sm-3 control-label"><?php echo __('lbladvocatename_' . $this->Session->read('doc_lang')); ?></label>    
                                    <div class="col-sm-3 ">
                                        <?php echo $this->Form->input('adv_name_ll', array('label' => false, 'id' => 'adv_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <!--<span id="adv_name_ll_error" class="form-error"><? ph//p echo $errarr['adv_name_ll_error'];   ?></span>-->
                                    </div>
                                <?php } ?>
                                <label for="" class="col-sm-3 control-label"><?php echo __('lbladvtname'); ?>[ENGLISH]</label>
                                <div class="col-sm-3 right-border">
                                    <?php echo $this->Form->input('adv_name_en', array('label' => false, 'id' => 'adv_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="adv_name_en_error" class="form-error"><?php echo $errarr['adv_name_en_error']; ?></span>
                                </div>

                            </div>
                        </div>   
                    <?php } ?>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbllnkofficename'); ?></label>    
                            <div class="col-sm-3 right-border">
                                <?php echo $this->Form->input('link_office_id', array('type' => 'select', 'label' => false, 'id' => 'link_office_id', 'empty' => '----select----', 'class' => 'form-control input-sm', 'options' => $office, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Is  previous registration number for this property')); ?>
                                <span id="local_language_id_error" class="form-error"><?php echo $errarr['local_language_id_error']; ?></span>
                            </div>

                            <!--                        -->
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbllnkdocno'); ?></label>    
                            <div class="col-sm-3 right-border" >
                                <?php echo $this->Form->input('link_doc_no', array('label' => false, 'id' => 'link_doc_no', 'class' => 'form-control input-sm', 'type' => 'text', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Is  previous registration number for this property')); ?>
                                <span id="link_doc_no_error" class="form-error"><?php echo $errarr['link_doc_no_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbldocdate'); ?></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('link_doc_date', array('label' => false, 'id' => 'link_doc_date', 'class' => 'form-control input-sm', 'type' => 'text', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Is  previous registration number for this property')); ?>
                                <span id="link_doc_date_error" class="form-error"><?php echo $errarr['link_doc_date_error']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php if ($proceduretype_flag == 'Y') { ?>
                        <div class="row">
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lblproceduretype'); ?></label>    
                                <div class="col-sm-3 right-border">
                                    <?php echo $this->Form->input('procedure_id', array('type' => 'select', 'label' => false, 'id' => 'procedure_id', 'empty' => '----select----', 'class' => 'form-control input-sm', 'options' => $proceduretype)); ?>
                                    <span id="procedure_id_id_error" class="form-error"><?php //echo $errarr['local_language_id_error'];  ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>

            <div class="box box-primary">

                <div class="box-body">
                    <div class="box-header with-border">
                        <h3 class="box-title headbolder" ><?php echo __('Document Submission Office Details'); ?></h3>

                        <div class="hr1" style="border: 1px solid black;"></div>
                        <div  class="rowht"></div>
                        <div class="row">
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?> <span style="color: #ff0000">*</span></label>   
                                <div class="col-sm-3 right-border">
                                    <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'empty' => '--select--', 'class' => 'form-control input-sm', 'options' => array($districtdata))); ?>
                                    <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];             ?></span>
                                </div>
                                <?php if ($circle == 'Y') { ?>
                                    <label for="" class="col-sm-3 control-label"><?php echo __('lbladmtaluka'); ?><?php if ($tal_compulsary == 'Y') { ?><span style="color: #ff0000">*</span><?php } ?></label></label>    
                                    <div class="col-sm-3" > <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'id' => 'taluka_id', 'class' => 'form-control input-sm chosen-select', 'options' => array($taluka), 'empty' => '--select--', 'label' => false)); ?>
                                        <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];             ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div  class="rowht"></div>
                        <div class="row">
                            <div class="form-group">
                                <?php if ($village_flag == 'Y') { ?>
                                    <label for="" class="col-sm-3 control-label"><?php echo __('lblcityvillage'); ?> </label>   
                                    <div class="col-sm-3 right-border">
                                        <?php echo $this->Form->input('village_id', array('type' => 'select', 'label' => false, 'id' => 'village_id', 'empty' => '----select----', 'class' => 'form-control input-sm', 'options' => $villagelist)); ?>
                                        <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];             ?></span>
                                    </div>
                                <?php } ?>
                                <?php if (trim($this->Session->read("session_usertype")) == 'C') { ?>

                                    <label for="" class="col-sm-3 control-label"><?php echo __('lblofficename'); ?><span style="color: #ff0000">*</span></label>    
                                    <div class="col-sm-3" > <?php echo $this->Form->input('office_id', array('options' => array(), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm chosen-select', 'options' => array($office), 'label' => false)); ?>
                                        <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];            ?></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <?php echo $this->Form->input('office_id', array('label' => false, 'type' => 'hidden', 'value' => $this->session->read('office_id'))); ?>
            <?php } ?>
            <div class="box box-primary">
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
                        <input type="button" id="btnNext" name="btnNext" class="btnsave" style="width:155px;" value="<?php echo __('lblsaveandnext'); ?>">
                        <input type="button" id="btnCancel" name="btnCancel" class="btnsave" style="width:155px;" value="<?php echo __('btncancel'); ?>" onclick="javascript: return forcancel();">
						<?php
						if($upload_doc_title_flag=='Y'){
						?>
						<button type="button"  class="btn btn-primary btn-sm"  id="viewdocuments"><span class="fa fa-search"></span><?php echo 'View Documents'; ?> </button>                      
						<?php
						}
						?>
                    </div>  
                </div>
            </div>
        </div>
    </div>
	
	
	<div id="docModal" class="modal fade" role="dialog">
    <div class="modal-dialog2">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Required Upload Document List</h4>
            </div>
            <div class="modal-body" id="doc_modal_body">
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

    <?php unset($_SESSION['doc_lang_id']); ?>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Js->writeBuffer(); ?>

