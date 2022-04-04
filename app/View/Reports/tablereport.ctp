
<script>
    $(document).ready(function () {
        $("#btnview").click(function () {
            //$("select[name='data[tabledesc][table_name]'] option:selected").text();
            //$("#table_name").val($("#table_name option:selected").text());
            //return false;
        });
    });
</script>
<?php echo $this->form->create('tabledesc');?>
 <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
 
<div class="panel-body">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align: center;"><b><?php echo __('lbltabrpt'); ?></b></div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3"><?php echo __('lblseltabname'); ?>:</div>
                            <div class="col-sm-2"><?php echo $this->form->input('table_name', array('label' => false,'type' => 'select', 'id' => 'table_name', 'empty' => '--select--', 'options' => $tablelist,'class' => 'form-control input-sm', 'div' => true, 'required' => true));?></div>
                            <div class="col-sm-3"><?php echo $this->form->button('Generate PDF', array('class' => 'btn btn-info', 'align' => 'center', 'id' => 'btnview', 'style' => 'transform-origin: 48% 50%;')) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end();?>
