
<script>
    $(document).ready(function () {
        $('#tablearticlescreen').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(article_id, minorfun_id, id) {
        document.getElementById("actiontype").value = '1';
        $('#article_id').val(article_id);
        $('#minorfun_id').val(minorfun_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }


</script>   
<?php echo $this->Form->create('article_screen_mapping', array('id' => 'article_screen_mapping', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblarticlescreenmapping'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/article_screen_mapping_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="form-group">
                        <label for="article_id" class="col-sm-2 control-label"><?php echo __('lblselarticle'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $articlelist))); ?>
                            <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                        </div>
                        <label for="minorfun_id" class="col-sm-2 control-label"><?php echo __('lblselminorlist'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('minorfun_id', array('label' => false, 'id' => 'minorfun_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $minorlist))); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="minorfun_id_error" class="form-error"><?php echo $errarr['minorfun_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                &nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="tablearticlescreen" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblArticle'); ?></th>
                            <th class="center"><?php echo __('lblminorlist'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($articlegrid as $articlegrid1): ?>
                            <tr>
                                <td ><?php echo $articlegrid1[0]['article_desc_en']; ?></td>
                                <td ><?php echo $articlegrid1[0]['function_desc_en']; ?></td>
                                <td >
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                    ('<?php echo $articlegrid1[0]['article_id']; ?>'),
                                                    ('<?php echo $articlegrid1[0]['minorfun_id']; ?>'), ('<?php echo $articlegrid1[0]['id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'article_screen_mapping_delete', $articlegrid1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($articlegrid1); ?>
                    </tbody>
                </table> 
                <?php if (!empty($articlegrid)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div> 

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
</div>
