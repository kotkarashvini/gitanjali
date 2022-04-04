<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
echo $this->element("Citizenentry/property_menu");
?>

<script type="text/javascript">
     function forcancel() {
        window.location.href = "<?php echo $this->webroot; ?>LegacyFeedetails/fee/<?php echo $this->Session->read('csrftoken'); ?>";
            }
    </script>

<style type="text/css">
    .mycontent-left {
        border-right: 1px dashed #333;
    }
    
</style>

<?php echo $this->Form->create('fee_detailsctp', array('id' => 'fee_detailsctp', 'autocomplete' => 'off')); 

$doc_lang = $this->Session->read('doc_lang');
?>

<div class="row">

    <div class="col-lg-12">
        
        
        
         <div class="box box-primary">
 <div class="box-header with-border">
                <center><h3 class="box-title " style="font-weight: bolder"><?php echo __('FeeDesc');  ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_fee_details_en<?php //echo $laug;  ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                    
                    
                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblfeetype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                             <?php echo $this->Form->input('fee_item_id', array('label' => false, 'id' => 'fee_item_id', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--',$feemaster))); ?> 
                            <span  id="fee_item_id_error" class="form-error"><?php echo $errarr['fee_item_id_error']; ?></span>
                        </div>
                       
                    </div>
                </div>
                  <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblamount'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                           <?php echo $this->Form->input('final_value', array('label' => false, 'class' => 'form-control', 'id' => 'final_value', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="final_value_error" class="form-error"><?php echo $errarr['final_value_error']; ?></span>
                 
                        </div>
                       
                    </div>
                </div>
                  <div  class="rowht"></div> 
                  <div  class="rowht"></div> 
                   <div  class="rowht"></div> 
                  <center>
                      <table id="table"  style="width:80%" class="table table-striped table-bordered table-condensed">  
                            <thead>  

                                <tr>  
                                    
                                     <th class="center"><?php echo __('lblfeetype'); ?></th>
                                    <th class="center"><?php echo __('lblamount'); ?></th>
                                    
                                    <th class="center"><?php //echo __('Party Type'); ?></th>
                                </tr>  
                            </thead>
                            <tbody id="tablebody1" >     
                               
                               <?php
                               
                               if(!empty($fee_data)) {
                                 
                                    foreach ($fee_data as $fee_data1) {
                               
                                    ?>
                                
                                    <tr>
                                       <?php if($doc_lang!='en')  {?>
                                         <td class="tblbigdata"><?php echo $fee_data1[0]['fee_item_desc_ll']; ?></td>
                                      <?php } else{ ?>  
                                         <td class="tblbigdata"><?php echo $fee_data1[0]['fee_item_desc_en']; ?></td>
                                         <?php } ?>
                                        <td class="tblbigdata"><?php echo $fee_data1[0]['final_value']; ?></td>
                                        
                                          <td class="width5"><?php echo $this->Html->link("Edit", array('controller' => 'LegacyFeedetails', $this->Session->read('csrftoken'), 'action' => 'fee',$fee_data1[0]['fee_calc_id'])); ?>
                                              
                                          <?php echo $this->Html->link("Delete", array('controller' => 'LegacyFeedetails', $this->Session->read('csrftoken'), 'action' => 'delete',$fee_data1[0]['fee_calc_id'])); ?></td>
                                    </tr>  
                                        <?php }} else{ ?>
                                    <tr><td colspan="8"><?php  echo"No records found! "; ?></td></tr>
                                    <?php } ?>
                            </tbody>

                        </table> 
               </center>
 </div>
      </div>
        
        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >
                    
                    <input type="hidden"  id="continue_flag">
                      <a href="#" class="pl-3"><button   type="submit"  id="submit_data"  name="action"  value="submit_data" class="btn btn-primary"><?php echo __('btnsubmit'); ?></button></a>
                    <input type="button" id="btnCancel" name="btnCancel" class="btn btn-danger" style="width:155px;" value="<?php echo __('btncancel'); ?>" onclick="javascript: return forcancel();">
                 
                </div>  
            </div>
        </div>
        
    </div>
</div>
<?php echo $this->Form->end(); ?>