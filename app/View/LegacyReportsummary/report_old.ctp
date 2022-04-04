
<?php echo $this->Form->create('pre_registration_docket', array('id' => 'pre_registration_docket', 'class' => 'form-vertical', 'autocomplete' => 'off')); ?>
<?php
echo $this->element("Registration/main_menu");
//echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
?>
<style>
    .watermark { position: absolute;opacity: 0.25; padding-top: 500px; transform: rotate(-20deg); font-size: 4em; width:70%; text-align: center;z-index: 0; color: gray;}
</style>

<div class="row">
    <div id="contentbg">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">                         
                    <h4> <?php echo $this->html->link('Download PDF', array('controller' => 'LegacyReportsummary', 'action' => 'abc', $doc_token_no, 'D')); ?> </h4>
                    <?php // if($role_id == '999960') { ?>
                        <!--<h4> <?php // echo $this->html->link('Agreement Deed', array('controller' => 'CidcoReports', 'action' => 'cidco_agreementdeed_docket', base64_encode($doc_token_no), 'D')); ?> </h4>-->
                    <?php // } ?>
                    <div class='watermark'><?php echo __('lblwatermark'); ?></div>
                    <?php
                    //echo $design;
                    $result = $this->requestAction(array('controller' => 'LegacyReportsummary', 'action' => 'abc', $doc_token_no, 'V'));
                    echo $result;
                    ?>
                </div>   
                
            </div>
        </div>
    </div>
</div>

