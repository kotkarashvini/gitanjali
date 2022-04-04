

<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title headbolder"><?php echo __('lblloginstatistics'); ?></h3></center>
    </div>
    <div class="box-body">
        <div class="row center" >
            <div class="form-group">
                <div class="col-sm-12" style="height: 150px;">
                    <br><br> <br>
                    <?php echo $this->Html->link("Generate Report", array('controller' => 'Reports', 'action' => 'rpt_login_statistics'), array('class' => 'btn btn-primary')); ?> 
                </div>
            </div>
        </div>
    </div>
</div>