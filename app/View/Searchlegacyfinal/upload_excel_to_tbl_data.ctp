<?php
echo $this->Form->create('upload_excel_to_tbl', array('type' => 'file', 'id' => 'upload_excel_to_tbl', 'autocomplete' => 'off'));
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    $(document).ready(function () {
        var host = '<?= $this->webroot ?>';
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

    function check_forall(formid)
    {
        $('#commonformid' + formid).val(formid);
        var flag = $('#commonflag').val();

        if (flag != '') {
            if (flag == 'N')
            {
                $('#commonformid' + formid).val('');
                alert('File Name or File format not suported');
                return false;
            } else
            {
                $('#commonupload' + formid).submit();
            }
        } else {
            alert('please select file to upload');
            return false;
        }
    }
    function checkvalidation(id, doc_id) {

        var file1 = $('#commonfileupload' + id).val();

        var reg = /^[0-9a-zA-Z_\-\.]*$/;
        var a = file1.split(".");
        var fname1 = a[0].split("\\");
        if (!reg.test(fname1[fname1.length - 1])) {
            $('#commonflag').val('N');
            $('#commonfileupload' + id).val('');
            alert('Please select file with alphanumeric name(- and _ are allowed, space is not allowed)');
            return false;
        }

        var fsize = ($("#commonfileupload" + id))[0].files[0].size;
        var size = (fsize / 1000000);

        $.post('<?php echo $this->webroot; ?>Citizenentry/check_filevalidation', {file: file1, doc_id: doc_id}, function (data)
        {

            if (data == 'false')
            {
                $('#commonflag').val('N');
                $('#commonfileupload' + id).val('');
                alert('Format not supported');
                return false;

            } else
            {
                if (data < size)
                {
                    $('#commonflag').val('N');
                    $('#commonfileupload' + id).val('');
                    alert('please upload file with maximum size ' + data + 'MB');
                    return false;
                } else
                {

                    $('#commonflag').val('Y');
                }

            }
        });

    }


</script>
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
                                <?php echo $this->Form->input('batch_no', array('label' => false, 'placeholder' => 'Enter Batch No.', 'type' => "text", 'class' => 'form-control', 'id' => 'batch_no', 'maxlength' => '6', 'pattern' => "\d+", 'required' => TRUE)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr align="center"> 
                            <td align="center">
                                <button type="submit" id="btnupload"   name="action" value="btnupload"  class="btn btn-info"><?php echo "Upload Excel"; ?></button>
                                <button type="submit" id="btnuploaddoc"   name="action" value="btnuploaddoc"  class="btn btn-info"><?php echo "Upload Document"; ?></button>
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
                                    if (!empty($allerr)) {
                                        foreach ($allerr as $allerr1) {
                                            ?>
                                            <tr>
                                                <td class="tblbigdata"><?php echo $allerr1['sheetid']; ?></td>
                                                <td class="tblbigdata"><?php echo $allerr1['number']; ?></td>
                                                <td class="tblbigdata"><?php echo $allerr1['err']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr><td colspan="8"><?php echo"No records found! "; ?></td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </center>
                    </div>

                    <div id="selectconfinfo">
                        <table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
                            <thead >  
                                <tr>  
                                    <th class="center"><?php echo __('lbldocumenttitle'); ?></th>
                                    <th class="center"><?php echo __('lblSelect'); ?></th>
                                    <th class="center"><?php echo __('lbldownload'); ?></th>
                                    <th class="center width10"><?php echo __('lblaction'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if (isset($filenm)) {
                                    for ($j = 0; $j < count($filenm); $j++) {
                                        ?>

                                        <tr>
                                            <td> <?php $filenm[$i][0]['input_fname'] ?></td>
                                        </tr>

                                        <tr>
                                            <?php
                                            echo $this->Form->create('upload', array('type' => 'file', 'id' => 'commonupload' . $j, 'class' => 'form-vertical'));
                                            $doc_id = $filenm[$i][0]['doc_reg_no'];
                                            ?>

                                            <td class="tblbigdata"><?php echo $filenm[$i][0]['input_fname'] . ' '; ?></td>
                                            <td class="tblbigdata" style="width: 25%;"><?php echo $this->Form->input('upload_file', array('label' => false, 'class' => 'Cntrl1', 'id' => 'commonfileupload' . $j, 'type' => 'file', 'onchange' => "checkvalidation($j,$doc_id)")); ?></td>
                                            <td> <?php
                                                if (!empty($upload_fileinfo)) {
                                                    foreach ($upload_fileinfo as $upload) {
                                                        if ($upload['Leg_uploaded_file_trn']['document_id'] == $upload_file1[$j]['upload_document']['document_id']) {
                                                            if ($upload['Leg_uploaded_file_trn']['outupload_fname'] != '') {
                                                                echo $this->Html->link(
                                                                        'Download', array(
                                                                    'disabled' => TRUE,
                                                                    'controller' => 'LegacyDocumentupload', // controller name
                                                                    'action' => 'upload_documents_for_finaldocument', //action name
                                                                    'full_base' => true, $upload['Leg_uploaded_file_trn']['out_fname'])
                                                                );
                                                            }
                                                        }
                                                    }
                                                }
                                                ?></td>
                                    <input type="hidden" name="file_id <?php echo $j; ?>" value="<?php echo $upload_file1[$j]['upload_document']['document_id']; ?>">
                                    <input type="hidden" name="formid" id='commonformid <?php echo $j; ?>' value="<?php echo $formid; ?>">
                                    <input type='hidden'  name='flag' id='commonflag' value="Y"/>
                                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                    <td><input type="button" name="upload" id="commonfilesubmit<?php echo $j; ?>" onclick="javascript: return check_forall('<?php echo $j; ?>');" value="Upload/Update" class="btn btn-warning"/></td>
                                    <?php echo $this->Form->end(); ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="temp_legacy_data">
                        <?= $this->requestAction('Searchlegacyfinal/get_temp_upload_data', array('batch_no' => $batch_no)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer(); ?>