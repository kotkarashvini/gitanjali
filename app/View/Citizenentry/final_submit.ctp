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

<?php echo $this->Form->create('final_submit', array('id' => 'final_submit', 'class' => 'form-vertical')); ?>

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
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/final_submit_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><b><?php echo __('lbltokenno'); ?>:-</b><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                        
                        <?php
						if($mutation_fee_webservice!='')
						{
						?>
						<label onclick="javascript:myFunction(`<?php echo $mutation_fee_webservice;?>`)" class="btn btn-small btn-info">Generate Mutation Fee pdf</label>
						<?php
						}
						?>
						<?php
						if($reg_fee_webservice!='')
						{
						?>
						<label class="btn btn-small btn-info" onclick="javascript:myFunction(`<?php echo $reg_fee_webservice;?>`)">Generate Registration Fee pdf</label>
						<?php
						}
						?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div id="selectconfinfo" class="table-responsive">
                    <?php if ($flag == 0) { ?>
                        <h1 class="headbolder center" style="color: red"><?php echo __('lblconpletealllevels'); ?></h1> 
                        <?php
                    } else {
                        if ($submitted == 'Y') {
                            ?>
                            <h1 class="headbolder center" style="color: red"><?php echo __('lbldockalreadysubmited') . $office_name; ?></h1> 
                        <?php } else { ?>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                               
                                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                <div class="col-sm-1"></div>
                                <label for="" class="col-sm-2 control-label"> <input type="submit" value="<?php echo __('lblsubmitapplication'); ?>" /></label>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden'  name='flag' id='flag'/>

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




