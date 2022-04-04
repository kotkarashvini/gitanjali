<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divratefactor').slideDown(1000);
        }
        else {
            $('#divratefactor').hide();
        }
        $('#tableratefactor').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, contypid, depid, ratefact) {
        $('#hfid').val(id);
        $('#constructiontype_id').val(contypid);
        $('#depreciation_id').val(depid);
        $('#rate_factor').val(ratefact);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

//    function formdelete(id) {
//        var result = confirm("Are you sure you want to delete this record?");
//        if (result) {
//            document.getElementById("actiontype").value = '3';
//            $('#hfid').val(id);
//        } else {
//            return false;
//        }
//    }
</script>

<?php echo $this->Form->create('ratefactor', array('id' => 'ratefactor', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblratefactorconstructionandagelinkage'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Rate Factor/ratefactor_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="constructiontype_id " class="col-sm-2 control-label"><?php echo __('lblconstuctiontye'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('constructiontype_id', array('label' => false, 'id' => 'constructiontype_id', 'class' => 'form-control input-sm', 'options' => array($constuctiontype), 'empty' => '--Select--')); ?>
                            <span id="constructiontype_id_error" class="form-error"><?php echo $errarr['constructiontype_id_error']; ?></span>
                        </div>
                        <label for="depreciation_id " class="col-sm-2 control-label"><?php echo __('lbldepreciation'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('depreciation_id', array('label' => false, 'id' => 'depreciation_id', 'class' => 'form-control input-sm', 'options' => array($depreciation), 'empty' => '--Select--')); ?>
                            <span id="depreciation_id_error" class="form-error"><?php echo $errarr['depreciation_id_error']; ?></span>
                        </div>
                        <label for="rate_factor" class="col-sm-2 control-label"><?php echo __('lblratefactor'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('rate_factor', array('label' => false, 'id' => 'rate_factor', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10')) ?>
                            <span id="rate_factor_error" class="form-error"><?php echo $errarr['rate_factor_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info "    onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp; &nbsp;<?php echo __('lblbtnAdd'); ?></button>
                        <button id="btnadd" name="btncancel" class="btn btn-info "    onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body" id="divratefactor">
                <table id="tableratefactor" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblconstuctiontye'); ?></th>
                            <th class="center"><?php echo __('lbldepreciation'); ?></th>
                            <th class="center"><?php echo __('lblratefactor'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($ratefactor); $i++) { ?>
                        <tr>
                            <td ><?php echo $ratefactor[$i][0]['construction_type_desc_' . $laug]; ?></td>
                            <td ><?php echo $ratefactor[$i][0]['deprication_type_desc_' . $laug]; ?></td>
                            <td ><?php echo $ratefactor[$i][0]['rate_factor']; ?></td>
                            <td >
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                        ('<?php echo $ratefactor[$i][0]['id']; ?>'),
                                                        ('<?php echo $ratefactor[$i][0]['constructiontype_id']; ?>'),
                                                        ('<?php echo $ratefactor[$i][0]['depreciation_id']; ?>'),
                                                        ('<?php echo $ratefactor[$i][0]['rate_factor']; ?>'));">
                                    <span class="glyphicon glyphicon-pencil"></span></button>

        <!--                                    <button id="btndelete" name="btndelete" class="btn btn-default "  onclick="javascript: return formdelete(('<?php echo $ratefactor[$i][0]['id']; ?>'));">
                                                <span class="glyphicon glyphicon-remove"></span></button>-->
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'ratefactor_delete', $ratefactor[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($ratefactor)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>
        <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    </div>
</div>