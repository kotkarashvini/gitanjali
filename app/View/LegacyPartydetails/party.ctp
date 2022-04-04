<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
echo $this->element("Citizenentry/property_menu");
?>

<script type="text/javascript">
     function forcancel() {
        window.location.href = "<?php echo $this->webroot; ?>LegacyPartydetails/party/<?php echo $this->Session->read('csrftoken'); ?>";
            }
    </script>

<style type="text/css">
    .mycontent-left {
        border-right: 1px dashed #333;
    }
    
</style>
<?php
echo $this->Html->css('popup');
echo $this->Form->create('Party_details', array('id' => 'Party_details', 'class' => 'form-vertical', 'autocomplete' => 'off'));
$doc_lang = $this->Session->read('doc_lang');
//pr($doc_lang);
?>

<div class="row">

    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title " style="font-weight: bolder"><?php echo __('lblpartydetails'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_party_details_en<?php //echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                <div class="rowht"></div>
                <div class="hr1" style="border: 1px solid black;"></div>

                <?php if ($this->Session->read("manual_flag") == 'Y') { ?>
                    <div class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblmanualregno'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3" ><?php echo $this->Form->input('manual_reg_no', array('label' => false, 'id' => 'manual_reg_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="manual_reg_no_error" class="form-error"><?php echo $errarr['manual_reg_no_error']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblpartytype'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                                 <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array('@' => 'Select',$partytype))); ?>   
                             <span  id="party_type_id_error" class="form-error"><?php echo $errarr['party_type_id_error']; ?></span>
                        </div>
                       
                     
                        
                    </div>
                </div>
                 <div class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                       
                       <label for="" class="col-sm-3 control-label"><?php echo __('lblpartycategory'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                                           <?php echo $this->Form->input('party_catg_id', array('label' => false, 'id' => 'party_catg_id', 'class' => 'form-control input-sm', 'options' => array('@' => 'Select',$party_category))); ?>   
                             <span  id="party_catg_id_error" class="form-error"><?php echo $errarr['party_catg_id_error']; ?></span>
                        </div>
                     
                        
                    </div>
                </div>
              
               <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                         <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') {
                                    ?>
                         <label for="" class="col-sm-3 control-label"><?php echo __('lblpartyfullname'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('party_full_name_ll', array('label' => false, 'id' => 'party_full_name_ll', 'class' => 'form-control input-sm')); ?> 
                             <span  id="party_full_name_ll_error" class="form-error"><?php echo $errarr['party_full_name_ll_error']; ?></span>
                        </div>
                        
                     <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblpartyfullname'); ?>[ENGLISH]:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('party_full_name_en', array('label' => false, 'id' => 'party_full_name_en', 'class' => 'form-control input-sm')); ?> 
                             <span  id="party_full_name_en_error" class="form-error"><?php echo $errarr['party_full_name_en_error']; ?></span>
                        </div>
                        
                    </div>
                </div>
               <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                      
                         <label for="" class="col-sm-3 control-label"><?php echo __('lblage'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('age', array('label' => false, 'id' => 'age', 'class' => 'form-control input-sm')); ?> 
                             <span  id="age_error" class="form-error"><?php echo $errarr['age_error']; ?></span>
                        </div>
                        
                     
                    </div>
                </div>
                     <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbluid'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('uid', array('label' => false, 'id' => 'uid', 'class' => 'form-control input-sm','maxlength' => '12')); ?> 
                             <span  id="uid_error" class="form-error"><?php echo $errarr['uid_error']; ?></span>
                        </div>
                        
                        
                     
                    </div>
                </div>
                     
                          <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                       
                         <label for="" class="col-sm-3 control-label"><?php echo __('lblpancardno'); ?></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'class' => 'form-control input-sm')); ?> 
                             <span  id="pan_no_error" class="form-error"><?php echo $errarr['pan_no_error']; ?></span>
                        </div>
                        
                     
                    </div>
                </div>
               
                 <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <?php if ($doc_lang != 'en') {
                                    ?>
                         <label for="" class="col-sm-3 control-label"><?php echo __('lblfatherfullname'); ?></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('father_full_name_ll', array('label' => false, 'id' => 'father_full_name_ll', 'class' => 'form-control input-sm')); ?> 
                             <span  id="father_full_name_ll_error" class="form-error"><?php echo $errarr['father_full_name_ll_error']; ?></span>
                        </div>
                        
                        <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblfatherfullname'); ?>[ENGLISH]:</label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('father_full_name_en', array('label' => false, 'id' => 'father_full_name_en', 'class' => 'form-control input-sm')); ?> 
                             <span  id="father_full_name_en_error" class="form-error"><?php echo $errarr['father_full_name_en_error']; ?></span>
                        </div>

                    </div>
                </div>
                   <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblgender'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                           <?php echo $this->Form->input('gender_id', array('label' => false, 'id' => 'gender_id', 'class' => 'form-control input-sm','options' => array('@' => 'Select',$gender))); ?> 
                             <span  id="gender_id_error" class="form-error"><?php echo $errarr['gender_id_error']; ?></span>
                        </div>
                   
                    </div>
                </div>
               
                    <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <?php if ($doc_lang != 'en') {
                                    ?>
                      <label for="" class="col-sm-3 control-label"><?php echo __('lblpartyaddress'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                       <?php echo $this->Form->input('address_ll', array('label' => false, 'id' => 'address_ll', 'class' => 'form-control input-sm','type' => 'textarea')); ?> 
                             <span  id="address_ll_error" class="form-error"><?php echo $errarr['address_ll_error']; ?></span>
                        </div>
                        
                        <?php } ?>
                       <label for="" class="col-sm-3 control-label"><?php echo __('lblpartyaddress'); ?>[ENGLISH]:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                       <?php echo $this->Form->input('address_en', array('label' => false, 'id' => 'address_en', 'class' => 'form-control input-sm','type' => 'textarea')); ?> 
                             <span  id="address_en_error" class="form-error"><?php echo $errarr['address_en_error']; ?></span>
                        </div>
                    </div>
                </div> 
                       <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                       
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblpincode'); ?></label>    
                        <div class="col-sm-3" >
                       <?php echo $this->Form->input('pin_code', array('label' => false, 'id' => 'pin_code', 'class' => 'form-control input-sm')); ?> 
                             <span  id="pin_code_error" class="form-error"><?php echo $errarr['pin_code_error']; ?></span>
                        </div>
                    </div>
                </div> 
                   
                    <div  class="rowht"></div>
                    
                     <div  class="rowht"></div>
                       
                <table id="stable" class="table table-striped table-bordered table-condensed">  
                            <thead>  

                                <tr>  
                                    
                                     <th class="center"><?php echo __('lblpartytype'); ?></th>
                                    <th class="center"><?php echo __('lblpartycategory'); ?></th>
                                    <th class="center"><?php echo __('lblpartyfullname'); ?></th>
                                    <th class="center"><?php echo __('lblfatherfullname'); ?></th>
                                    <th class="center"><?php //echo __('Party Type'); ?></th>
                                </tr>  
                            </thead>
                            <tbody id="tablebody1" >     
                               
                               <?php
                               
                               if(!empty($partydata)) {
                                 
                                    foreach ($partydata as $partydata1) {
                               
                                    ?>
                                
                                    <tr>
                                       

                                         <td class="tblbigdata"><?php echo $partydata1[0]['party_type_desc_'.$doc_lang]; ?></td>
                                        <td class="tblbigdata"><?php echo $partydata1[0]['category_name_'.$doc_lang]; ?></td>
                                        <?php if ($doc_lang != 'en') { ?>
                                        <td class="tblbigdata"><?php echo $partydata1[0]['party_full_name_ll']; ?></td>
                                         <?php } else {?>
                                        <td class="tblbigdata"><?php echo $partydata1[0]['party_full_name_en']; ?></td>
                                        <?php } ?>
                                        <?php if ($doc_lang != 'en') { ?>
                                        <td class="tblbigdata"><?php echo $partydata1[0]['father_full_name_ll']; ?></td>
                                         <?php } else {?>
                                        <td class="tblbigdata"><?php echo $partydata1[0]['father_full_name_en']; ?></td>
                                         <?php } ?>
                                          <td class="width5"><?php echo $this->Html->link("Edit", array('controller' => 'LegacyPartydetails', 'action' => 'party', $this->Session->read('csrftoken'),$partydata1[0]['id'])); ?> <?php echo $this->Html->link("Delete", array('controller' => 'LegacyPartydetails', 'action' => 'delete', $this->Session->read('csrftoken'),$partydata1[0]['id'])); ?></td>
                                    </tr>  
                                        <?php }} else{ ?>
                                    <tr><td colspan="8"><?php  echo"No records found! "; ?></td></tr>
                                    <?php } ?>
                            </tbody>

                        </table> 
                    
                    
             
               
                
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