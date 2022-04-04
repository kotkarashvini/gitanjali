<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
?>
<script>
    $(document).ready(function () {
       // $('#party_upload').hide();

//         $('#commonfileupload').bind('change', function () {
//
//            //this.files[0].size gets the size of your file.
//
//            
//        });


    });

    function check_forall(formid)
    {
//alert("Hii");
        $('#commonformid' + formid).val(formid);
        var flag = $('#commonflag').val();
        //pr(flag);
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
        }
        else {
            alert('please select file to upload');
            return false;
        }




    }
   function checkvalidation(id,doc_id) {
     
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

        $.post('<?php echo $this->webroot; ?>Citizenentry/check_filevalidation', {file: file1,doc_id:doc_id}, function (data)
        {

            if (data == 'false')
            {
                $('#commonflag').val('N');
                $('#commonfileupload' + id).val('');
                alert('Format not supported');
                return false;

            }
            else
            {
                if (data < size)
                {
                    $('#commonflag').val('N');
                    $('#commonfileupload' + id).val('');
                    alert('please upload file with maximum size ' + data + 'MB');
                    return false;
                }
                else
                {

                    $('#commonflag').val('Y');
                }

            }
        });

    }
    
    function forcancel() {
        window.location.href = "<?php echo $this->webroot; ?>LegacyDocumentupload/upload/<?php echo $this->Session->read('csrftoken'); ?>";
            }

</script>



<?php
//echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
$doc_lang = $this->Session->read('doc_lang');
?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbluploaddock'); ?></h3></center>
            </div>
            <div class="box-tools pull-right">
                <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/upload_document_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
            </div>
            <div class="box-body">
                <?php if ($this->Session->read("Leg_Selectedtoken") != '') { ?>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?>:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Leg_Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
              
        </div>

     
    
        <div class="box box-primary">
            <div class="box-body">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblcommonuploaddoc'); ?></h3></center>
                </div>
                 <div class="col-sm-8">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('lblrequiredoc'); ?></br>
                                                 <p style="color: red;"><b><?php echo __('lblnote'); ?>2:&nbsp;</b><?php echo __('lbluploadfilename'); ?></br>
                                                
                                        </div>
                                    </div>
                                </div>
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
                            for ($j = 0; $j < count($upload_file1); $j++) {
                                ?>
                                <tr>
                                    <?php echo $this->Form->create('upload', array('type' => 'file', 'id' => 'commonupload' . $j, 'class' => 'form-vertical'));
                                    $doc_id = $upload_file1[$j]['upload_document']['document_id'];
                                    ?>
                                    
                                    <td class="tblbigdata"><?php echo $upload_file1[$j]['upload_document']['document_name_en'].' '; ?><?php if($upload_file1[$j]['ad']['is_required']=='Y'){?><span style="color: #ff0000">*</span><?php } ?></td>
                                    <td class="tblbigdata" style="width: 25%;"><?php echo $this->Form->input('upload_file', array('label' => false,'class'=>'Cntrl1', 'id' => 'commonfileupload' . $j, 'type' => 'file', 'onchange' => "checkvalidation($j,$doc_id)")); ?></td>
                                    <td> <?php
                                        if (!empty($upload_fileinfo)) {
                                            foreach ($upload_fileinfo as $upload) {
                                                if ($upload['Leg_uploaded_file_trn']['document_id'] == $upload_file1[$j]['upload_document']['document_id']) {
                                                    if ($upload['Leg_uploaded_file_trn']['out_fname'] != '') {
                                                        echo $this->Html->link(
                                                                'Download', array(
                                                            'disabled' => TRUE,
                                                            'controller' => 'LegacyDocumentupload', // controller name
                                                            'action' => 'downloadfile', //action name
                                                            'full_base' => true, $upload['Leg_uploaded_file_trn']['out_fname'])
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                        ?></td>
                            <input type="hidden" name="file_id<?php echo $j; ?>" value="<?php echo $upload_file1[$j]['upload_document']['document_id']; ?>">
                            <input type="hidden" name="formid" id='commonformid<?php echo $j; ?>' value="<?php echo $formid; ?>">
                            <input type='hidden'  name='flag' id='commonflag' value="Y"/>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                            <td><input type="button" name="upload" id="commonfilesubmit<?php echo $j; ?>" onclick="javascript: return check_forall('<?php echo $j; ?>');" value="Upload/Update" class="btn btn-warning"/></td>
                                <?php echo $this->Form->end(); ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>


<?php echo $this->Js->writeBuffer(); ?>




