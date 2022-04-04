 
<?php
echo $this->element("Registration/main_menu");
?><br>
<?php echo $this->Form->create('document_upload', array('id' => 'document_upload', 'autocomplete' => 'off','type'=>'file')); ?>
 <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="row">
    <div class="col-lg-12">
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
                <center><h3 class="box-title headbolder"><?php echo __('lblupload_scanned_document'); ?></h3></center>
            </div>
            <div class="box-body">
                <?php 
                if(empty($scan_upload))
                {?>
                <div class="form-group">
                    <label for="exampleInputFile"><?php echo __('lblselectfile'); ?></label>
                    <?php
                    echo $this->Form->input('upload_file', array('id' => 'upload_file', 'label' => false, 'type' => 'file','class' => 'btn btn-success'));
                    ?>
                </div>
                <?php }else{
                    ?>
                         <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                <?php echo __('lblfile_is_already_uploaded');?>
              </div>
                        <?php
                    
                } ?>
            </div>
            <div class="panel panel-footer center" >
                 <?php 
                if(empty($scan_upload))
                {?>
                <button type="submit" class="btn btn-success"  ><?php echo __('btnsubmit'); ?></button>
                  <?php } ?>
                <?php 
                
                if(!empty($scan_upload))
                {
               
                
                  echo $this->Html->link(
                                __('lbldownload'), array(
                            'disabled' => TRUE,
                            'controller' => 'Registration', // controller name
                            'action' => 'downloadfile', //action name
                            'full_base' => true, $scan_upload['scan_upload']['scan_name'],'Scanning',$documents[0][0]['token_no']), array('class' => 'btn btn-warning', 'target' => '_blank')
                        );
                }
                ?> 
                
                
                
            </div> 
        </div>
    </div>   
</div>

<?php echo $this->Form->end(); ?>
 