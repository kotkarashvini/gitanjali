
<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create('article_mapping', array('id' => 'article_mapping')); ?>
        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title center">
                    <center> <?php echo __('lblregscreenandarticlemapping'); ?> </center> 
                </div>

            </div>
            <div class="box-heading">

            </div>
            <div class="panel-body">  
                <div class="row">
                    <div class="col-md-6">
                        <!--                            <div class="form-inline">-->

                        <div class="form-group">
                            <label>Screen Name</label>
                            <?php echo $this->Form->input('subsubmenu_id', array('div' => FALSE, 'id' => 'subsubmenu_id', 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $screens, 'empty' => '--Select--')); ?>
                        </div>


                        <!--                </div>-->

                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="panel-title"><?php echo __('lblarticlelist'); ?></li>

                                    <li class="pull-right"> <div class="input-group"> 
                                            <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span> 
                                            <?php echo $this->Form->input('search_rule', array('id' => 'search_rule', 'label' => false, 'placeholder' => 'Search...', 'class' => 'brn btn-search')); ?>
                                        </div> </li>
                                </ul>

                            </div>

                            <div class="usage-list" id="usage-list">
                                <?php echo $this->Form->input('article', array('type' => 'select', 'options' => $article, 'id' => 'article', 'multiple' => 'checkbox', 'label' => false, 'class' => 'usage_cat_id')); ?>


                            </div>
                            <div class="panel-footer">  <span class="form-error" id="usage_cat_id_error"></span></div>
                        </div>

                    </div>  
                </div>  



            </div>
            <div class="panel-footer center"> 
                <div class="form-group"> 

                    <?php echo $this->Form->submit('Submit', array('label' => false, 'id' => 'csrftoken', 'Class' => 'btn btn-primary btn-sm', 'type' => 'submit', 'name' => 'otpsubmit')); ?>
                </div>
            </div>
        </div>


        <?php echo $this->Form->end(); ?>

    </div>
</div>
<script>
    $(document).ready(function () {


        $('#search_rule').keyup(function () {
            var valThis = $(this).val().toLowerCase();
            $('.usage_cat_id input[type="checkbox"]').each(function () {
                var article = $(this).val();
                var label = $("label[for='article" + article + "']").html().toLowerCase();
                if (label.indexOf(valThis) > -1) {
                      $("label[for='article" + article + "']").parent('div').show();
                } else {
                       $("label[for='article" + article + "']").parent('div').hide();
                }
            });
        });

        $('#subsubmenu_id').change(function () {
            $.post("<?php echo $this->webroot; ?>Registration/screen_mapping_article",
                    {
                        csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>',
                        subsubmenu_id: $("#subsubmenu_id").val(),
                    },
                    function (data, status) {
                        if (data != '') {
                            var maydata = JSON.parse(data);
                            $(':checkbox').prop('checked', false);
                            $.each(maydata.fields, function (index, val) {
                                $("#article" + val).prop('checked', true);
                            });
                        } else {
                            $(':checkbox').prop('checked', false);
                        }
                    });
        });

    });
</script>