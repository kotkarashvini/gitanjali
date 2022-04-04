<script>
    $(document).ready(function () {
        
        $('#table').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>

<?php echo $this->Form->create('document_status', array('id' => 'document_status', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocumentstatusdesc'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Registration/document_status_description_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="form-group">
                    <?php
                    //  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lbldocumentstatusdesc') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('document_status_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'document_status_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'document_status_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>
               
                
                
                <?php
                echo $this->Form->input('id', array('label' => false, 'id' => 'id', 'type' => 'hidden'));
                ?>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br>
                            
                            <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                            
                            <a href="<?php echo $this->webroot; ?>Registration/document_status" class="btn btn-info"><?php echo __('btncancel'); ?></a>
                        </div>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="col-md-12">
            <div class="responstable">
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  

                        <tr> 
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lbldocumentstatusdesc') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($document_status_description as $DocumentDisposaldata) {
                           
                            ?>
                            <tr>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $DocumentDisposaldata['document_status_description']['document_status_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

                                <td>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'document_status', $DocumentDisposaldata['document_status_description']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_document_status', $DocumentDisposaldata['document_status_description']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>