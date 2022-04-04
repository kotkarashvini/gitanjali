
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>
<div class="nav-tabs-custom">
     <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Newcase'); ?></h3></center>
            </div>
    <ul class="nav nav-tabs">
        <li ><a href="#home" data-toggle="tab"><span class="fa fa-list"></span> <?php echo __('Home'); ?></a></li>
        <li class="active"><a href="#add" data-toggle="tab"><span class="fa fa-plus "></span><?php echo __('Add'); ?></a></li>
        <!--<li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>-->
    </ul>
    <div class="tab-content">
        <div class="tab-pane" id="home">
            <b>Homepage:</b>
            <div class="panel" id="datadiv"></div>
        </div>
        <div class="tab-pane active" id="add">
            <?php echo $this->Element('NewCase/addcase'); ?>
        </div>
        <div class="tab-pane" id="tab_3">
        </div>
    </div>
</div>



<?php //echo $this->Element('Helpfiles/NewCase'); ?>

