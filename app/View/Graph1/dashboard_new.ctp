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
echo $this->Form->create('dashboard_new', array('id' => 'dashboard_newid', 'class' => 'form-vertical'));
?>

    <div class="row">
    <div class="col-md-12">
        <div class="btn-arrow">

            <a href="<?php echo $this->webroot; ?>Graph/reg_doc_registered" class="btn btn-success btn-arrow-right"><?php echo 'Registered documents'; ?></a>            
            <a href="<?php echo $this->webroot; ?>Graph/reg_doc_submitted/" class="btn bg-maroon btn-arrow-right"><?php echo 'Submitted documents'; ?></a>            
            <a href="<?php echo $this->webroot; ?>Graph/reg_doc_fee_calculation/" class="btn btn-success btn-arrow-right"><?php echo 'Account Head wise Collection'; ?></a>            
            <a href="<?php echo $this->webroot; ?>Graph/districtwise/" class="btn btn-success btn-arrow-right"><?php echo 'District wise Collection'; ?></a>
           

        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

