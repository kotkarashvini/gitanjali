<?php
echo $this->element("Registration/main_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
            <center><h3 class="box-title headbolder"><?php echo __('lblreport'); ?></h3></center>  
        </div>

        <ul class="list-group">
            <li class="list-group-item"><a href="#"><?php echo __('lblcashbook'); ?></a></li>
            <li class="list-group-item"><a href="<?php echo $this->webroot; ?>Registration/reprint_summary_report"><?php echo __('lblreprintdocsummeryrpt'); ?>
                </a></li>
            <li class="list-group-item"><a href="<?php echo $this->webroot; ?>Registration/search_registration_summary"><?php echo __('lblsearchregsummery'); ?></a></li>
        </ul> 

        </div>
    </div>
</div>


