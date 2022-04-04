<script>
 $(document).ready(function () {

    $('#tablearticleparty').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
 });
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(article_id,party_type_id, id) {
        document.getElementById("actiontype").value = '1';
        $('#article_id').val(article_id);
        $('#party_type_id').val(party_type_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

</script>


<?php echo $this->Form->create('article_partymapping', array('id' => 'article_partymapping')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblarticlepartymapping'); ?>  </h3></center>
            <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot;?>helpfiles/admin/article_partymapping_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article_id" class="col-sm-2 control-label"><?php echo __('lblArticle'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $articlelist))); ?>
                            <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $partylist))); ?>
                            <span id="party_type_id_error" class="form-error"><?php echo $errarr['party_type_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                          <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

                    <div class="box-body">
                        <div id="selectbehavioural">
                            <table id="tablearticleparty" class="table table-striped table-bordered table-hover" >
                                <thead >  
                                    <tr>  
                                        <th class="center width10"><?php echo __('lblArticle'); ?></th>
                                        <th class="center width10"><?php echo __('lblpartytype'); ?></th>
                                        <th class="center width10"><?php echo __('lblaction'); ?></th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php foreach ($articleparty as $articleparty1){ ?>
                                        <tr>
                                            <td><?php echo $articleparty1[0]['article_desc_en']; ?></td>
                                            <td><?php echo $articleparty1[0]['party_type_desc_en']; ?></td>
                                            <td>
                                                <button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                                    ('<?php echo $articleparty1[0]['article_id']; ?>'),
                                                                    ('<?php echo $articleparty1[0]['party_type_id']; ?>'),
                                                                    ('<?php echo $articleparty1[0]['id']; ?>'));">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </button>
                                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_articleparty_mapping', $articleparty1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                            </td>
                                        </tr>       
                                    <?php }    ?>
                                    <?php unset($articleparty1); ?>
                                </tbody>
                            </table>
                            <?php if (!empty($articleparty)) { ?>
                                <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                        </div>
                    </div>
                </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>