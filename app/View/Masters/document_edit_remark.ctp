<script type="text/javascript">
    $(document).ready(function () {

        $('#tabledoc').dataTable({
            "bPaginate": false,
            "ordering": false
        });
    });

    function formsave() {
        document.getElementById("actiontype").value = '2';
    }
    
    function formsearch() {
        document.getElementById("actiontype").value = '1';
    }

    function details(token_no, flag) {
        $.post('<?php echo $this->webroot; ?>Reports/rpt_reg_summary2_20', {token_no: token_no, flag: flag}, function (data)
        {
            $('#rptRegSummary2partial').html(data);
            $('#myModal').modal('show');
        });
    }

</script>
<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#summary2partialprint').on('click', function () {
            $.print("#rptRegSummary2partial");
        });
        $('#summary2fullprint').on('click', function () {
            $.print("#rptRegSummary2full");
        });
    });
</script>
<div id="documentviewsummary" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo __('lbldocregsumm2'); ?></h4>
            </div>
            <div class="modal-body center" id="rptRegSummary2partial">
                <p>Data Loading...!!!!</p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <!--<button type="button" class="btn btn-success" id="summary2partialprint"><?php //echo __('lblprint');  ?></button>-->
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
</div> 

<div id="documentrevert" class="modal fade" role="dialog">
    <?php echo $this->Form->create('document_edit_revert', array('id' => 'document_edit')); ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Edit Document Remark'); ?> </h4>
            </div>
            <div class="modal-body" >
                <div class="form-group">
                    <label><?php echo __('lblremark'); ?></label>
                    <?php echo $this->Form->input('doc_edit_remark', array('label' => false, 'id' => 'doc_edit_remark', 'type' => 'textarea', 'class' => 'form-control')); ?>
                    <span class="form-error" id="doc_edit_remark_error"><?php //echo $errarr['doc_edit_remark_error'];  ?></span>
                </div>
            </div>
            <div class="modal-footer">                 
                <button type="submit" class="btn btn-default pull-left" name="document_edit" onclick="javascript: return formsave();"><?php echo __('Save'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>

</div> 

<?php
echo $this->Form->create('document_edit_remark', array('id' => 'document_edit_remark', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div>
                        <center><h4 class = "box-title headbolder"><?php echo __('Document Entry Edit'); ?></h4></center>
                    </div>
                    <div  class="rowht"></div>
                    <div class="col-sm-1"></div><div class="col-sm-1"></div>
                    <div class="form-group">
                        <label for="tok_no" class="col-sm-2 control-label"><?php echo __('Token Number'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('tok_no', array('label' => false, 'id' => 'tok_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="tok_no_error" class="form-error"><?php //echo $errarr['tok_no_error'];  ?></span>
                        </div>
                        <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit" onclick="javascript: return formsearch();"> <?php echo __('lblsearch'); ?> </button></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!empty($tokengrid)) {
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div id="selectdocument">
                        <table id="tabledoc" class="table table-striped table-bordered table-hover">
                            <thead class="center">  
                                <tr >  
                                    <th><?php echo __('Office Name'); ?></th>
                                    <th><?php echo __('Summary Document'); ?></th>
                                    <th><?php echo __('Action'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php
                                foreach ($tokengrid as $rec):
                                    ?>
                                    <tr>
                                        <td ><?php echo $rec[0]['office_name_en']; ?></td>
                                        <td ><button type="button" class="btn btn-info " data-toggle="modal" data-target="#documentviewsummary" onclick="javascript: return details('<?php echo $rec[0]['token_no']; ?>', 'K');"> <?php echo __('View Partial Summary'); ?></button></td>
                                        <td ><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#documentrevert"> <?php echo __('Update'); ?></button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
</div>
<?php echo $this->Form->end(); ?>
