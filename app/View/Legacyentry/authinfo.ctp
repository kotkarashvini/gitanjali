<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });

    function get_remark(token) {
        $.post('<?php echo $this->webroot; ?>Citizenentry/get_revert_remark', {token: token}, function (data)
        {
            $("#revertremark").html(data);
        });
    }
    
    function assign_asp(token){
         $.post('<?php echo $this->webroot; ?>Citizenentry/assign_asp_token', {token: token}, function (data)
        {
          
            $("#asplist").html(data);
        });
        
    }
</script>
<?php echo $this->Form->create('genernal_info', array('id' => 'genernal_info', 'autocomplete' => 'off')); ?>

<?php echo $this->element("Registration/main_menu"); ?>
<?php
//echo $this->element("Citizenentry/main_menu");
$doc_lang = $this->Session->read('doc_lang');
//$laug = $this->Session->read("sess_langauge");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
               
                <center><h3 class="box-title headbolder"><?php echo __('lblgeneralinfo1'); ?></h3></center>
                <div class="box-tools pull-right">
                   <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_info_en.html" class="btn btn-small btn-info pull-right" target="_blank"> <?php echo __('Help??'); ?> </a>
                </div>
            
                
                

            </div>
            <div class="box-body">

                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">

                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
<!--                                    <tr>  
                                        <th class="center">Select<?php //echo __('lblSelect'); ?></th>
                                        <th class="center">Pre Reg.No.<?php //echo __('lbltokenno'); ?></th>
                                        
                                        <th class="center">Article<?php //echo __('lblpartyname'); ?></th>
                                         
                                        
                                        <th class="center">Location<?php //echo __('lblpartyname'); ?></th>
                                      <th class="center">Party Name<?php //echo __('lblpartyname'); ?></th>
                                      <th class="center">Final Reg.No.<?php //echo __('lbldocumenttitle'); ?></th>
                                      <th class="center">Status<?php //echo __('lbldocumenttitle'); ?></th>
                                       <th class="center">Pre Reg.Summary<?php //echo __('lbldocumenttitle'); ?></th>
                                    </tr>  -->
                                      <tr>  
                                        <th class="center"><?php echo __('lblSelect'); ?></th>
                                        <th class="center"><?php echo __('lbltokenno'); ?></th>
                                        
                                        <th class="center"><?php echo __('lblarticlename'); ?></th>
                                         
                                        
                                        <th class="center"><?php echo __('lbllocation'); ?></th>
                                      <th class="center"><?php echo __('lblpartyname'); ?></th>
                                      <th class="center"><?php echo __('lblregno'); ?></th>
                                      <th class="center"><?php echo __('lblstatus'); ?></th>
                                       <th class="center"><?php echo __('lblsummery'); ?></th>
                                    </tr>  
                                     
                                </thead>
   <tbody>
                                 <?php
                               if(!empty($demodata)) {
                                 
                                    foreach ($demodata as $demodata1) {
                               
                                    ?>
                                    <tr>
                                     
                                      <td class="width5"><?php echo $this->Html->link("Select", array('controller' => 'Legacyentry', 'action' => 'getid', $demodata1[0]['token_no'])); ?></td>
                                        
                                         <td class="tblbigdata"><?php echo $demodata1[0]['token_no']; ?></td>
                                        <?php if ($doc_lang != 'en') { ?>
                                         <td class="tblbigdata"><?php echo $demodata1[0]['article_desc_ll']; ?></td>
                                         <?php } else {?>
                                         <td class="tblbigdata"><?php echo $demodata1[0]['article_desc_en']; ?></td>
                                          <?php } ?>
                                       <?php if ($doc_lang != 'en') { ?>
                                         <td class="tblbigdata"><?php  echo  str_replace('"', '', ltrim(rtrim($demodata1[0]['location_ll'], "}"), "{")) ?></td>
                                         <?php } else {?>
                                        <td class="tblbigdata"><?php  echo  str_replace('"', '', ltrim(rtrim($demodata1[0]['location_en'], "}"), "{")) ?></td>
                                        <?php } ?>
                                        
                                        <?php if ($doc_lang != 'en') { ?>
                                        <td class="tblbigdata"><?php  echo  str_replace('"', '', ltrim(rtrim($demodata1[0]['party_ll'], "}"), "{"));?></td>
                                        <?php } else {?>
                                         <td class="tblbigdata"><?php  echo  str_replace('"', '', ltrim(rtrim($demodata1[0]['party_en'], "}"), "{"));?></td>
                                         <?php } ?>
                                          <td class="tblbigdata"><?php echo $demodata1[0]['final_doc_reg_no']; ?></td>
                                          <td class="tblbigdata"><?php echo $demodata1[0]['document_status_desc_en']; ?></td>
                                         <td class="width5"><?php echo $this->Html->link('PDF', array('controller' => 'LegacyReportsummary', 'action' => 'abc', $demodata1[0]['token_no'], 'D')); ?></td>
                                    </tr>
<?php }} else{ ?>
                                    <tr><td colspan="9"><?php  echo"No records found! "; ?></td></tr>
                                    <?php } ?>

                            </tbody>
                            </table> 
                        </div>
                        
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
            </div>
        </div>

    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Revert Status</h4>
            </div>
            <div class="modal-body" id="revertremark">


            </div>


        </div>

    </div>
</div>

<div id="aspmodel" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASP List</h4>
            </div>
            <div class="modal-body" id="asplist">


            </div>


        </div>

    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


<!--
<td class="tblbigdata"><?php //echo $demodata1[0]['token_no']; ?></td>
                                         <td class="tblbigdata"><?php //echo $demodata1[0]['article_desc_en']; ?></td>
                                        <td class="tblbigdata"><?php // echo  ltrim(rtrim($demodata1[0]['location'], "}"), "{") ?></td>
                                         <td class="tblbigdata"><?php  //echo  str_replace('"', '', ltrim(rtrim($demodata1[0]['party'], "}"), "{"));?></td>-->
