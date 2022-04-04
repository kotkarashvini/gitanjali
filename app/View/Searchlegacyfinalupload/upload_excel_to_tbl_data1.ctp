<?php
echo $this->Form->create('upload_excel_to_tbl', array('type' => 'file','id' => 'upload_excel_to_tbl', 'autocomplete' => 'off'));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo "Upload excel"; ?></h3></center>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <table  width="50%" align="center">  
                        <tr align="center">  
                            <td align="center">
                                <?php echo $this->Form->input('upload_file', array('label' => false, 'class' => 'Cntrl1', 'id' => 'commonfileupload', 'type' => 'file')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr align="center"> 
                            <td align="center">
                                <button type="submit" id="btnupload"   name="action" value="btnupload"  class="btn btn-info"><?php echo "Upload Excel"; ?></button>
                                <button type="submit" id="btnsave"  name="action" value="btnsave" class="btn btn-info"><?php echo "Save"; ?></button>
                            </td>
                            
                        </tr>
                        
                        <tr align="center"> 
                            <td align="center">
                                <button type="submit" id="btndownload"   name="action" value="btndownload"  class="btn btn-info"><?php echo "upload"; ?></button>
                                
                            </td>
                            
                        </tr>
                           
                    </table>
                     <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                     <center>
                    <table id="table"  style="width:80%" class="table table-striped table-bordered table-condensed">  
                            <thead>  

                                <tr>  
                                    <th class="center"><?php echo __('Sheet Id'); ?></th>
                                     <th class="center"><?php echo __('Registration Number'); ?></th>
                                    <th class="center"><?php echo __('Error'); ?></th>
                                    
                                </tr>  
                            </thead>
                            <tbody id="tablebody1" >     
                               
                               <?php
                               
                               if(!empty($allerr)) {
                                 
                                    foreach ($allerr as $allerr1) {
                               
                                    ?>
                                
                                    <tr>
                                        <td class="tblbigdata"><?php echo $allerr1['sheetid']; ?></td>
                                        <td class="tblbigdata"><?php echo $allerr1['number']; ?></td>
                                        <td class="tblbigdata"><?php echo $allerr1['err']; ?></td>
                                    </tr>  
                                        <?php }} else{ ?>
                                    <tr><td colspan="8"><?php  echo"No records found! "; ?></td></tr>
                                    <?php } ?>
                            </tbody>

                        </table>    
                     </center>
               </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


