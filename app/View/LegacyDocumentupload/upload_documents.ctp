<noscript>
    <meta http-equiv="refresh" content="1; URL=cterror.html"/>
</noscript>
<?php
    echo $this->Html->script('dataTables.bootstrap');
    echo $this->Html->script('jquery.dataTables');
    $doc_lang = $this->Session->read('doc_lang');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder" align = 'center'><?php echo __('lbluploaddock'); ?></h3>
            </div>
            <div class="box-tools pull-right hidden">
                <a href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/upload_document_<?php echo $doc_lang; ?>.html"
                   class="btn btn-small btn-info" target="_blank"> <i
                        class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
            </div>
            <div class="box-body">
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <p style="color: red;"><b><?php echo __('lblnote'); ?>
                                        1:&nbsp;</b><?php echo __('lblrequiredoc'); ?></br>

                                <p style="color: red;"><b><?php echo __('lblnote'); ?>
                                        2:&nbsp;</b><?php echo __('lbluploadfilename'); ?></br>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="selectconfinfo">
                    <input type="hidden" name = 'token_no' value = >
                    <table id="tableconfinfo" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center"><?php echo __('lblsrno'); ?></th>
                            <th class="center"><?php echo __('lbloffice'); ?></th>
                            <th class="center"><?php echo __('lbldocregno'); ?></th>
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $srNo = 1;
                        foreach ($docsToBeUploaded as $upload) {
                            $token_no = $upload['token_no'];
                            ?>
                            <tr>
                                <td class="center"><?= ($srNo++); ?></td>
                                <td class="center"><?= $upload['office_name_en'];?></td>
                                <td class="center"><?= $upload['token_no']; ?></td>
                                <td class="center"><input type="submit" name="upload" id="commonfilesubmit" value="<?php echo __('lblupload'); ?>" class="btn btn-warning"/> </td>
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




