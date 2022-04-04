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
                            <td >
                                <?php echo $this->Form->input('batch_no', array('label' => false, 'placeholder' => 'Enter Batch No.','type'=>"text", 'class' => 'form-control','id' => 'batch_no','maxlength'=>'6' ,'pattern'=>"\d+", 'required'=>TRUE)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr align="center"> 
                            <td align="center">
                                <button type="submit" id="btnupload"   name="action" value="btnupload"  class="btn btn-info"><?php echo "Upload Excel"; ?></button>
                           <!-- // <button type="submit" id="btnuploaddoc"   name="action" value="btnuploaddoc"  class="btn btn-info"><?php //echo "Upload Document"; ?></button> -->
                           <button  id="btnuploaddoc" name="action" class="btn btn-info"><?php echo "Upload Document"; ?></button>
                          
                         
                               <button type="button" id="btnview" name="action" class="btn btn-info"><?php echo __("View Excel Data"); ?></button>
                            </td>
                        </tr>
                        <tr align="center" style="display:none"> 
                            <td align="center">
                                <button type="submit" id="btndownload"   name="action" value="btndownload"  class="btn btn-info"><?php echo "upload"; ?></button>
                            </td>
                        </tr>
                    </table>
                    <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                    <div id ='legacy_error_data'>
                        <center>
                            <table id="tableUploadError"  style="width:80%" class="table table-striped table-bordered table-condensed">  
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
                    <div id="temp_legacy_data">
                           <?= $this->requestAction('Searchlegacyfinal/get_temp_upload_data',array('batch_no'=>$batch_no)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<script>
    $(document).ready(function () {
        var host = '<?= $this->webroot?>';
        $('#batch_no').change(function () {
            $('#temp_legacy_data').html('')
            $.post(host + "Searchlegacyfinal/check_duplicate_batch_no", {'batch_no': this.value}, function (data) {
                if (data > 0) {
                    alert('Batch No. ' + $('#batch_no').val() + ' Already Exists');
                    $(this).focus();
                }
            });
        });

        $('#btnview').click(function () {
            $.post(host + "Searchlegacyfinal/get_temp_upload_data", {'batch_no': $('#batch_no').val()}, function (data) {
                if (data != -1) {
                    $('#legacy_error_data').html('');
                    $('#temp_legacy_data').html(data)

                }
            });
        });


        $('#btnuploaddoc').click(function () 
        {
            alert('hiii in click event');
          //  $.post(host + "Searchlegacyfinal/documentup", {'batch_no': $('#batch_no').val()}, function (data) {
           return $this->redirect(array(controller=>'Searchlegacyfinal' ,'action' =>'documentup'));
            });
        });
        


        $(document).on('click', '#btnImport', function () {
            $.post(host + "Searchlegacyfinal/importFromTmpToTrnTables", {'batch_no': $('#batch_no').val()}, function (data) {
                if (data != -1) {
                    $('#temp_legacy_data').html(data)
                    alert('Data imported successfully');
                }
            });
        });

        $(document).on('click', '#btnDelete', function () {
            if (confirm('Are you sure you want to delete temperory data with Batch No.' + $('#batch_no').val())) {
                $.post(host + "Searchlegacyfinal/removeDataFromTmpTables", {'batch_no': $('#batch_no').val()}, function (data) {
                    if (data != -1) {
                        $('#temp_legacy_data').html(data)

                    }
                });
            }
        });
    });
</script>
<?php echo $this->Js->writeBuffer(); ?>