<?php
foreach ($stampconfig as $stamprec) {
    if (isset($stamprec['functions'])) {
        foreach ($stamprec['functions'] as $funrec) {
            if ($funrec['action'] == $this->request->params['action']) {
                $btnaccept_label = $funrec['btnaccept'];
                $stampflag = $stamprec['stamp_flag'];
                $funflag = $funrec['function_flag'];
            }
        }
    }
}

echo $this->element("Registration/main_menu");
?>
<br>

<div class="row">
    <div class="col-lg-12">
        <?php
        $missing = '';
        if (!empty($mandatory_doc_list)) {

            foreach ($mandatory_doc_list as $document_id => $single) {
                $uploadedflag = 'N';
                foreach ($document_list as $uploaded) {
                    if ($uploaded['document']['document_id'] == $document_id && !empty($uploaded['uploaded_file_trn']['out_fname'])) {
                        $uploadedflag = 'Y';
                    }
                }
                if ($uploadedflag == 'N') {
                    $missing.="<li><a>$single</a></li>";
                }
            }
        }

        if (!empty($missing)) {
            ?> 
            <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo __('lblmissing_documents_upload'); ?></h3>
                </div>
                <div class="box-body">
                    <ul class="nav nav-pills nav-stacked">
                        <?php echo $missing; ?>     
                    </ul>
                </div>
            </div>
        <?php } ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?> : <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocpresentation'); ?></h3></center>
            </div>

            <div class="box-body">
                <table class="table table-striped table-bordered table-hover" id="Doclist">
                    <thead>
                        <tr>
                            <th><?php echo __('lblsrno'); ?></th>  
                            <th><?php echo __('lblarticlename'); ?></th>
                            <th><?php echo __('lblpresentername'); ?></th> 
                            <th><?php echo __('lblaction'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $funstatus = 'N';
                        if ($documents[0][0][$funflag] == 'Y') {
                            $funstatus = 'Y';
                        };
                        ?>
                        <?php
                        $counter = 0;
                        foreach ($documents as $document) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo ++$counter; ?></th> 
                                <td><?php echo $document[0]['article_desc_' . $doc_lang]; ?></td> 
                                <td><?php echo $document[0]['party_full_name_' . $doc_lang]; ?></td>     
                                <td>
                                    <?php
                                    if (!empty($regconf_doc_edit)) {
                                        if ($document[0]['document_entry_flag'] == 'N') {
                                            ?>  
                                            <a href="<?php echo $this->webroot; ?>Registration/document_edit/<?php echo $document[0]['token_no']; ?>" class="btn btn-warning"> <span class="glyphicon glyphicon-edit"></span> <?php echo __('lblbtnedit'); ?></a>
                                        <?php } else { ?>
                                            <button class="btn btn-success disabled" ><span class="glyphicon glyphicon-edit"></span>  <?php echo __('lblbtnedit'); ?></button>   
                                            <?php
                                        }
                                    }
                                    ?>

                                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#presummary"><?php echo __('lblrptview'); ?></a>   
                                    <?php
                                    if ($regconf[0]['regconfig']['conf_bool_value'] == 'Y') {
                                        ?>
                                        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#myModal"><?php echo __('lblremark'); ?></a>    
                                    <?php }
                                    ?>   

                                    <?php if (!empty($regconf_doc_upload)) { ?>
                                        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#uploadfiles"><?php echo __('lbluploadedfiles'); ?></a>   

                                        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#upload"><?php echo __('lblupload'); ?></a>                
                                    <?php } ?>   

                                </td>
                            </tr> 
                        <?php } ?>
                    </tbody>
                </table> 
            </div>
            <div class="panel-footer center">
                <?php
                if ($document[0][$funflag] == 'N') {

                    if ($regconf[0]['regconfig']['conf_bool_value'] == 'Y') {
                        ?>
                        <button class="smartbtn smartbtn-success" data-toggle="modal" data-target="#myModal"><?php echo __($btnaccept_label); ?></button>
                    <?php } else {
                        ?>
                        <button class="smartbtn smartbtn-success" onclick="$('#dataentryaccept').submit();" ><?php echo __($btnaccept_label); ?></button>

                        <?php
                    }
                } else {
                    ?>
                    <button class="smartbtn smartbtn-disabled " ><?php echo __($btnaccept_label); ?></button>
                    <?php
                }
                ?>
            </div>
        </div>


    </div>
</div>


<?php
foreach ($documents as $document) {
    if ($funstatus == 'N') {
        ?>
        <!-- Modal Start -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <?php echo $this->form->create("presentation", array('id' => 'dataentryaccept')); ?>
                <?php echo $this->form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lbldocpresenrmk'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?php echo __('lblenterrmk'); ?></label>
                            <?php echo $this->form->input("document_entry_remark", array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'value' => 'OK', 'id' => 'document_entry_remark')); ?>
                            <span class="form-error" id="document_entry_remark_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"  ><?php echo __('lblaccepted'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
                <?php echo $this->form->end(); ?>
            </div>
        </div>

        <!-- Modal End -->
    <?php } else { ?>

        <!-- Modal Start -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lbldocpresenrmk'); ?></h4>
                    </div>
                    <div class="modal-body"> 
                        <p>
                            <?php
                            echo $document[0]['document_entry_remark'];
                            ?>

                        </p>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal End -->  
        <?php
    }
}
?>
<!-- Modal Start -->
<div id="presummary" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lbldocksummery'); ?></h4>
            </div>
            <div class="modal-body"> 

                <?php
                echo $presummary;
                ?>


            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>

<!-- Modal End -->  


<div id="uploadfiles" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lbluploadedfileslist'); ?></h4>
            </div>
            <div class="modal-body"> 

                <table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lbldocumenttitle'); ?></th>
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($document_list)) {

                            foreach ($document_list as $upFile) {
                                ?>
                                <tr>
                                    <td class="tblbigdata"><?php echo $upFile['document']['document_name_' . $lang]; ?></td>

                                    <td> <?php
                                        if ($upFile['uploaded_file_trn']['document_id'] == $upFile['document']['document_id']) {
                                            if ($upFile['uploaded_file_trn']['out_fname'] != '') {
                                                echo $this->Html->link(
                                                        'Download', array(
                                                    'disabled' => TRUE,
                                                    'controller' => 'Registration', // controller name
                                                    'action' => 'downloadfile', //action name
                                                    'full_base' => true, $upFile['uploaded_file_trn']['out_fname'], 'Uploads'), array('target' => '_blank')
                                                );
                                            }
                                        }
                                        ?></td>

                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>


            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>



<div id="upload" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create("fileupload", array('id' => 'fileupload', 'type' => 'file')); ?>
            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lbluploadfiles'); ?></h4>
            </div>
            <div class="modal-body"> 
                <div class="form-group">
                    <label for="document_id"><?php echo __('lblselectdocument'); ?></label>
                    <?php
                    echo $this->Form->input('document_id', array('id' => 'document_id', 'class' => 'form-control input-sm', 'label' => false, 'options' => $upload_doc_list, 'empty' => '--Select--'));
                    ?>      

                </div>
                <div class="form-group">
                    <label for="exampleInputFile"><?php echo __('lblselectfile'); ?></label>
                    <?php
                    echo $this->Form->input('upload_file', array('id' => 'upload_file', 'label' => false, 'type' => 'file'));
                    ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success pull-left"  ><?php echo __('btnsubmit'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>

    </div>
</div>





<div id="condonation_order" class="my-popup modal fade in" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content row">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo __('lblcondonation_order'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create('condonation_order', array('url' => array('controller' => 'Registration', 'action' => 'document_presentation'), 'id' => 'condonation_order', 'class' => 'form-vertical')); ?>   
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                <div class="form-group col-sm-12">
                    <label><?php echo __('lblorder_number'); ?></label>
                    <?php
                    echo $this->Form->input('order_number', array('id' => 'order_number', 'class' => 'form-control input-sm', 'label' => false));
                    ?> 
                    <span class="form-error" id="order_number_error"></span>
                </div>
                <div class="form-group col-sm-12">
                    <label><?php echo __('lblorder_date'); ?></label>
                    <?php
                    echo $this->Form->input('order_issue_date', array('id' => 'order_issue_date', 'class' => 'form-control input-sm', 'label' => false));
                    ?>  
                    <span class="form-error" id="order_issue_date_error"></span>
                </div>						 
                <div class="form-group col-sm-12">
                    <label><?php echo __('lblremark'); ?></label>
                    <?php
                    echo $this->Form->input('order_remark', array('id' => 'order_remark', 'type' => 'textarea', 'class' => 'form-control input-sm', 'label' => false));
                    ?> 
                    <span class="form-error" id="order_remark_error"></span>
                </div>
                <div class="form-group col-sm-12">
                    <button type="submit" class="btn btn-default pull-right"><?php echo __('btnsubmit'); ?></button>
                </div>
                <?php echo $this->Form->end(); ?>   
            </div>

        </div>

    </div>
</div>
<script>

    $(document).ready(function () {

        $('#order_issue_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });

<?php
if ($document[0][$funflag] == 'N') {
    if (isset($condonation_order_flag) && $condonation_order_flag == 1) {
        ?>
                $('.my-popup').modal('show');
    <?php
    }
}
?>
    });
</script>