<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
echo $this->element("Citizenentry/property_menu");
?>

<script type="text/javascript">
     function forcancel() {
        window.location.href = "<?php echo $this->webroot; ?>LegacyAuthorized/authorized/<?php echo $this->Session->read('csrftoken'); ?>";
            }
    </script>

<style type="text/css">
    .mycontent-left {
        border-right: 1px dashed #333;
    }
    
</style>

<?php echo $this->Form->create('authorizedctp', array('id' => 'authorizedctp', 'autocomplete' => 'off')); ?>
<div class="row">

    <div class="col-lg-12">
        
        
        
         <div class="box box-primary">
 <div class="box-header with-border">
                <center><h3 class="box-title " style="font-weight: bolder"><?php //echo __('lblgeneralinfo');  ?>Authorization Details</h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_auth_en<?php //echo $laug;  ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
             
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <?php if ($this->Session->read("Leg_Selectedtoken") != '') { ?>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                        <div class="col-sm-7">
                                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Leg_Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="box-header with-border">
                     <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbldoyouauthorized'); ?></label>    
                        <div class="col-sm-3 " >
                           <?php echo $this->Form->input('authorized_flag', array('type' => 'radio', 'options' => array('Y' => 'Yes','N' => 'No'),'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'authorized_flag')); ?>
                        </div>
                       
                    </div>
                </div>
                    
                    <div  class="rowht">&nbsp;</div>
                        <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblremark'); ?></label>    
                        <div class="col-sm-3 " >
                           <?php echo $this->Form->input('authorized_remark', array('label' => false, 'class' => 'form-control', 'id' => 'authorized_remark', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="authorized_remark_error" class="form-error"><?php echo $errarr['authorized_remark_error']; ?></span>
                        </div>
                       
                    </div>
                </div>
                </div>
              
           
 </div>
      </div>
        
        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >
                    
                    <input type="hidden"  id="continue_flag">
                      <div class="form-group">
                                 
                               <?php if($gen_info['Leg_generalinformation']['authorized_flag']=='N') {?>
                               <a href="#" class="pl-3"><button   type="submit"  id="submit_data"  name="action"  value="submit_data" class="btn btn-primary"><?php echo __('btnsubmit'); ?></button></a>
                               <?php  }else { ?>
                               <a href="#" class="pl-3"><button   type="submit" disabled  id="submit_data"  name="action"  value="submit_data" class="btn btn-primary"><?php echo __('btnsubmit'); ?></button></a>
                          <?php } ?>
                               
                               <input type="button" id="btnCancel" name="btnCancel" class="btn btn-danger" style="width:155px;" value="<?php echo __('btncancel'); ?>" onclick="javascript: return forcancel();">
                            </div>
                    
                    
<!--                      <a href="#" class="pl-3"><button   type="submit"  id="submit_data"  name="action"  value="submit_data" class="btn btn-primary"><?php //echo __('btnsubmit'); ?></button></a>-->
                    
                 
                </div>  
            </div>
        </div>
        
    </div>
</div>
<?php echo $this->Form->end(); ?>