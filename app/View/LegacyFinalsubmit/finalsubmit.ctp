<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
?>
<script>
    $(document).ready(function () {


    });
    
    function myFunction(strrv){
	//alert('in');
	//alert(strrv);
	var strgiven="data:application/pdf;base64,"+strrv;
	//alert(strgiven);
	window.open(strgiven,"_blank");
	
	//window.open("data:application/pdf;base64,"+strrv,"_blank"
}

</script>

<?php echo $this->Form->create('final_submit', array('id' => 'final_submit', 'class' => 'form-vertical', 'autocomplete' => 'off')); ?>

<?php
$doc_lang = $this->Session->read('doc_lang');
echo $this->element("Registration/main_menu");
echo $this->element("Citizenentry/property_menu");
$mutation_fee_webservice=$this->Session->read('str_final');
$reg_fee_webservice=$this->Session->read('str_reg_final');

?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblfinalsubmission'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_final_submit_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><b><?php echo __('lbltokenno'); ?>:-</b><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Leg_Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div id="selectconfinfo" class="table-responsive">
                    
                            <div class="form-group">
                                 <div class="col-sm-2"></div>
                               <?php if($gen_info['Leg_generalinformation']['last_status_id']==1) {?>
                                
                                <label for="" class="col-sm-2 control-label"> <input type="submit" value="<?php echo __('btnsubmit'); ?>" /></label>
                               <?php  }else { ?>
                                <label for="" class="col-sm-2 control-label"> <input type="submit" disabled value="<?php echo __('btnsubmit'); ?>" /></label>
                          <?php } ?>
                            </div>
                      
                    
                </div>
            </div>
        </div>
    </div>
    <input type='hidden'  name='flag' id='flag'/>

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




