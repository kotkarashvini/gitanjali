<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        $('#article_id').change(function () {

            var selectedArticle = $('#article_id option:selected').val();
            $.post('<?php echo $this->webroot; ?>Fees/get_articledependentfeild', {article_id: selectedArticle}, function (data)
            {
                $('input[type=checkbox]').attr('checked', false);
                $("#frmid")[0].reset();
                if (data)
                {
                    var items = data.split(",");
                    for (var i = 0; i < items.length; i++) {
                        $("input[type=checkbox][name='data[frm][fee_item_id][]'][value=" + items[i] + "]").attr("checked", "true");
                    }
                    $('#article_id').val(selectedArticle);
                } else {
                    $('#article_id').val(selectedArticle);
                }

            });
        });
    });
</script>

<?php echo $this->Form->create('frm', array('id' => 'frmid', 'class' => 'form-vertical')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblArticle') . " " . __('lblItemLinkage'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/article_item_link_not_sd_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Select Article" class="control-label col-sm-2" ><?php echo __('lblArticle'); ?></label>   
                        <div class="col-sm-4">  <?php echo $this->Form->input('article_id', array('type' => 'select', 'empty' => '--select--', 'options' => $articlelist, 'label' => false, 'multiple' => false, 'id' => 'article_id', 'class' => 'form-control input-sm')); ?> </div>
                    <span id="article_id_error" class="form-error"><?php //echo $errarr['article_id_error']; ?></span>
                    
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">   
                        <div class="col-sm-2"></div>
                        <label for="Input Items" class="control-label col-sm-2" ><?php echo __('lblInputItems'); ?></label> 
                        <div class="col-sm-4" style="height:25vh;overflow-y: scroll; border: 2px #00529B ridge;padding-left: 3%; "> 
                            <?php echo $this->Form->input('fee_item_id', array('type' => 'select', 'options' => $inputitemlist, 'label' => false, 'multiple' => 'checkbox', 'id' => 'fee_item_id','class' => 'fee_item_id')); ?>
                        <span id="fee_item_id_error" class="form-error"><?php //echo $errarr['fee_item_id_error']; ?></span>
                        </div> 
                    </div>
                </div>
                 <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">                               
                        <?php
                        echo $this->Form->button(__('btnsave'), array('id' => 'btnSaveRule', 'class' => 'btn btn-info')) . "&nbsp;&nbsp;";
                        echo $this->Form->button(__('lblexit'), array('id' => 'btnExit', 'class' => 'btn btn-info'));
                        ?>
                    </div>
                    <div class="hidden Input">
                        <?php
                        echo $this->Form->input('rule_id', array('id' => 'ruleid', 'type' => 'hidden'));
                        echo $this->Form->input('frmaction', array('id' => 'actionid', 'type' => 'hidden'));
                        ?>
                    </div>
                </div>
                <div  class="rowht"></div>
            </div>
        </div>
    </div>
</div>


