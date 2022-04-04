<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {
        $('#tablearticlescreen').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });


    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
//         document.getElementById("#document_id").value = 'S';
//        
    }



</script>   
<?php echo $this->Form->create('upload_documententry', array('id' => 'upload_documententry', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">
        
        <div class="note">
             <?php echo __('lblnote'); ?> 
            <ul>
                <li>
                  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>  
                </li>
                <li>
                   <?php echo __('lblalldocumentsizeinmb'); ?>  
                </li>
            </ul>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocumententry'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot;  ?>helpfiles/PDEMaster/uploadocmst_<?php echo $lang;  ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12"> 
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lblenteruploaddocname') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('document_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'document_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'document_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['developed_land_types_desc_' . $langcode['mainlanguage']['language_code'] . '_error'];                             ?></span>
                            <?php //echo $errarr['developed_land_types_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>
               

                    <div class="col-md-12"> 
                        <div class="rowht"></div><div  class="rowht"></div>
                        <div class="col-md-3">
                            <label for="Filesize" ><?php echo __('lbluploadedfilessize'); ?><span style="color: #ff0000">(MB)* </span></label>
                             <?php echo $this->Form->input('file_size', array('label' => false, 'id' => 'file_size', 'type' => 'text', 'class' => 'form-control input-sm', 'maxlength' => '4')); ?>
                            <span id="<?php echo 'file_size_error'; ?>" class="form-error"></span>
                           
                        </div>
                       


                    </div>

<?php echo $this->Form->input('document_id', array('label' => false, 'id' => 'document_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'maxlength' => '100')) ?>
                

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>PDEMaster/upload_documententry" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>

                    </div>
                </div>
            </div>
            <?php //echo $this->Form->input('article_doc_map_id', array('label' => false, 'id' => 'article_doc_map_id' , 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                  <div id="selectbehavioural">
                <table id="tablearticlescreen" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                             <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbluploaddocname') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                                <?php } ?>
<!--                            <th class="center"><?php //echo 'Document Upload Name'; ?></th>-->
                            <th class="center"><?php echo __('lbluploadedfilessize'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($articlegrid as $articlegrid1): ?>
                            <tr>
                                 <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $articlegrid1[0]['document_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                <!--<td ><?php// echo $articlegrid1[0]['document_name_en']; ?></td>-->
                                <td ><?php echo $articlegrid1[0]['file_size']; ?></td>
                                <td >

                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'upload_documententry', $articlegrid1[0]['document_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'upload_documententry_delete', $articlegrid1[0]['document_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($articlegrid1); ?>
                    </tbody>
                </table> 
            </div>
            </div>
        </div> 

    </div>

</div>