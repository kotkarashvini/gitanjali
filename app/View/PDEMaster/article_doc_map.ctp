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
<?php echo $this->Form->create('article_doc_map', array('id' => 'article_doc_map', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?> 

            <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>  

        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Article Upload Document Mapping'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/PDEMaster/articledocmap_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">

                    <div class="form-group">
                        <label for="article_id" class="col-sm-2 control-label"><?php echo __('lblselarticle'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $articlelist))); ?>
<!--                            <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>-->
                        </div>
                        <label for="document_id" class="col-sm-2 control-label"><?php echo __('lblselectdocument'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('document_id', array('label' => false, 'id' => 'document_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $documentlist))); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<!--                            <span id="document_id_error" class="form-error"><?php echo $errarr['document_id_error']; ?></span>-->
                        </div>
                        <label for="Is_Required" class="col-sm-2 control-label"><?php echo __('lblisrequired'); ?></label>   
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('is_required', array('label' => false, 'id' => 'is_required', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $arrCategory))); ?>
                            <?php //echo $this->Form->input('is_required', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_required',$val)); ?>
                        </div>


                        <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

                        <div class="col-md-6" align="right" ><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

                        </div>

                    </div>


                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <!--<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>-->
                                  <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <a href="<?php echo $this->webroot; ?>PDEMaster/article_doc_map" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->input('article_doc_map_id', array('label' => false, 'id' => 'article_doc_map_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'maxlength' => '100')) ?>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="tablearticlescreen" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblArticle'); ?></th>
                            <th class="center"><?php echo __('lbdDocumentname'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($articlegrid as $articlegrid1): ?>
                            <tr>
                                <td ><?php echo $articlegrid1[0]['article_desc_en']; ?></td>
                                <td ><?php echo $articlegrid1[0]['document_name_en']; ?></td>
                                <td>
                                   <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'article_doc_map', $articlegrid1[0]['article_doc_map_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'article_doc_map_delete', $articlegrid1[0]['article_doc_map_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($articlegrid1); ?>
                    </tbody>
                </table> 
            </div>
        </div> 

    </div>

    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>