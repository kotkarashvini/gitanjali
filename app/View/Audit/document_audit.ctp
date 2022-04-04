<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        $('#table_id').change(function () {
              var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            var table_id = $('#table_id').val();
            $.post('<?php echo $this->webroot; ?>Audit/get_column', {table_id: table_id, token: 'Y',csrftoken:csrftoken}, function (data)
            {
                $("#columnlist").html(data);

            });
        });
    });
</script>

<?php echo $this->Form->create('auditscreen', array('id' => 'auditscreen', 'class' => 'form-vertical')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblaudittrial'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Select Table" class="control-label col-sm-2" ><?php echo __('lblseltabname'); ?></label>   
                        <div class="col-sm-4">  <?php echo $this->Form->input('table_id', array('type' => 'select', 'empty' => '--select--', 'options' => $tablelist, 'label' => false, 'multiple' => false, 'id' => 'table_id', 'class' => 'form-control input-sm')); ?> </div>
                     <span id="table_id_error" class="form-error"><?php echo $errarr['table_id_error']; ?></span>
                    </div>
                </div>
                <div  class="rowht"></div>
            </div>
        </div>
    </div>
</div>
<div id="columnlist"></div>


