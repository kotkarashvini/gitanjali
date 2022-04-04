<script>
    $(document).ready(function () {
        $('#doclist').DataTable();
    });

</script>

<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbldisposalreview'); ?></h3></center>
    </div>

    <div class="box-body">  
        <table id="doclist" class="table table-bordred table-striped"> 
            <thead>
            <th><?php echo __('lbldocrno'); ?></th>
            <th><?php echo __('lblofficename'); ?></th>
            <th><?php echo __('lblArticle'); ?></th>
            <th><?php echo __('lblpresenter'); ?></th> 

            <th><?php echo __('lblregdate'); ?></th>

            <th><?php echo __('lblaction'); ?></th>  
            </thead>
            <tbody>
                <?php
                $count = 0;
                if (isset($disposaldoc)) {
                    foreach ($disposaldoc as $document) {
                        $document = $document[0];
                        $count++;
                        $flag = 0;
                        foreach ($DocumentDisposalEntry as $DisposalEntry) {
                            if ($document['token_no'] == $DisposalEntry['DocumentDisposalEntry']['token_no'] && $flag == 0) {
                                $flag = 1;
                                ?>
                                <tr>
                                    <td><?php echo $document['doc_reg_no']; ?></td>
                                    <td><?php echo $document['office_name_' . $lang]; ?></td>

                                    <td><?php echo $document['article_desc_' . $lang]; ?></td>
                                    <td><?php echo $document['party_full_name_' . $doc_lang]; ?></td>

                                    <td><?php
                                        $date = date_create($document['doc_reg_date']);
                                        echo date_format($date, 'd M Y h:s:i a');
                                        ?></td>

                                    <td>
                                        <button type="button" class="btn btn-info icon-btn" data-toggle="modal" data-target="#Modal_view<?php echo $document['app_id']; ?>"><span class="glyphicon glyphicon-check img-circle text-success"></span>  <?php echo __('lblview'); ?></button>  
                                        <button type="button" class="btn btn-info icon-btn" data-toggle="modal" data-target="#Modal_accept<?php echo $document['app_id']; ?>"><span class="glyphicon glyphicon-check img-circle text-success"></span>  <?php echo __('lblaction'); ?></button>  

                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>      
</div>



<?php
if (isset($disposaldoc)) {
    foreach ($disposaldoc as $document) {
        $document = $document[0];
        ?>
        <div id="Modal_view<?php echo $document['app_id']; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lbldisposalmethod'); ?> </h4>

                    </div>
                    <div class="modal-body" >
                        <table class="table table-responsive table-bordered">
                            <tbody>
                                <?php
                                foreach ($DocumentDisposalEntry as $DisposalEntry) {
                                    if ($document['token_no'] == $DisposalEntry['DocumentDisposalEntry']['token_no']) {
                                        ?>
                                        <tr class="bg-success">
                                            <th width="10%"><?php echo __('lbldisposal'); ?></th>   <th> <?php echo $DisposalEntry['DocumentDisposal']['disposal_desc_' . $lang]; ?>  </th>
                                        </tr>
                                        <tr>
                                            <th> <?php echo __('lblremark'); ?></th> <th> <?php echo $DisposalEntry['DocumentDisposalEntry']['disposal_remark']; ?>  </th>
                                        </tr> 
                                        <tr>
                                            <th> <?php echo __('lblforwartouser'); ?></th> <th> <?php echo $DisposalEntry['User']['full_name']; ?>  </th>
                                        </tr>
                                        <tr>
                                            <th> <?php echo __('lblstatus'); ?></th> <th> <?php
                                                if ($DisposalEntry['DocumentDisposalEntry']['disposal_close_status'] == 'N') {
                                                    echo __('lblopen');
                                                } else if ($DisposalEntry['DocumentDisposalEntry']['disposal_close_status'] == 'Y') {
                                                    echo __('lblclose');
                                                }
                                                ?>  </th>
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
        <?php
    }
}
?>
<?php
if (isset($disposaldoc)) {
    foreach ($disposaldoc as $document) {
        $document = $document[0];
        ?>
        <div id="Modal_accept<?php echo $document['app_id']; ?>" class="modal fade" role="dialog">
            <?php echo $this->Form->create('disposal_review', array('id' => 'disposal_review' . $document['app_id'], 'url' => array('controller' => 'Registration', 'action' => 'document_disposal_review'))); ?>
            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken' . $document['app_id'], 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
            <?php echo $this->Form->input('app_id', array('label' => false, 'id' => 'app_id' . $document['app_id'], 'type' => 'hidden', 'value' => $document['app_id'])); ?>

            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lbldisposalmethod'); ?> </h4>

                    </div>
                    <div class="modal-body" >
                        <div class="form-group">
                            <?php
                            echo $this->Form->input('disposal_id', array('id' => 'disposal_id' . $document['app_id'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $DocumentDisposal, 'empty' => '--Select--'));
                            ?>      
                            <span class="form-error" id="disposal_id<?php echo $document['app_id']; ?>_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo __('lblremark'); ?></label>
                            <?php echo $this->Form->input('disposal_remark', array('label' => false, 'id' => 'disposal_remark' . $document['app_id'], 'type' => 'textarea', 'class' => 'form-control')); ?>
                            <span class="form-error" id="disposal_remark<?php echo $document['app_id']; ?>_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo __('lblstatus'); ?></label>
                            <?php
                            echo $this->Form->input('disposal_close_status', array('id' => 'disposal_close_status' . $document['app_id'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => array('N' => __('lblopen'), 'Y' => __('lblclose')), 'empty' => '--Select--'));
                            ?>      
                            <span class="form-error" id="disposal_close_status<?php echo $document['app_id']; ?>_error"></span>
                        </div>

                    </div>
                    <div class="modal-footer">    
                        <button type="button" class="btn btn-default pull-left" name="dispose" onclick="ConfirmDialog<?php echo $document['app_id']; ?>('Are you sure to close disposal and deliver document ?');"><?php echo __('lbldispose'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>

        </div> 
        <?php
    }
}
?>


<script>

<?php
if (isset($disposaldoc)) {
    foreach ($disposaldoc as $document) {
        $document = $document[0];
        ?>
            function ConfirmDialog<?php echo $document['app_id']; ?>(message) {
                if ($("#disposal_close_status<?php echo $document['app_id']; ?>").val() === 'Y')
                {
                    $('<div></div>').appendTo('body')
                            .html('<div><h6>' + message + '?</h6></div>')
                            .dialog({
                                modal: true, title: 'Confirm Dialog', zIndex: 10000, autoOpen: true,
                                width: 'auto', resizable: false,
                                buttons: {
                                    Yes: function () {
                                        $("#disposal_review<?php echo $document['app_id']; ?>").submit();
                                        $(this).dialog("close");
                                    },
                                    No: function () {
                                        $(this).dialog("close");
                                        $(this).remove();
                                    }
                                },
                                close: function (event, ui) {
                                    $(this).remove();
                                }
                            });

                } else {
                    $("#disposal_review<?php echo $document['app_id']; ?>").submit();
                }

            }
            ;

        <?php
    }
}
?>



</script>