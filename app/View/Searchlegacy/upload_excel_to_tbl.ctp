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
                                <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo "Upload"; ?></button>
                            </td>
                        </tr>
                    </table>
               </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


