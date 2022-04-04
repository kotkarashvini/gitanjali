<?php
echo $this->element("Helper/jqueryhelper");
?>

<?php //echo $this->element("BlockLevel/main_menu"); ?>

<script>
    $(document).ready(function () {

        $('#table').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });
</script> 
<?php echo $this->Form->create('rate_factor', array('id' => 'rate_factor1', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldeprecitionfactor'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/rate_factor_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="col-sm-2">
                        <label for="construction_type_id" class="control-label"><?php echo __('lblconstuctiontye'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('constructiontype_id', array('options' => $constructiontype, 'empty' => '--select--', 'id' => 'constructiontype_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                        <span class="form-error" id="constructiontype_id_error"></span>
                    </div>
                    <div class="col-sm-2">
                        <label for="deprication_type_id" class="control-label"><?php echo __('lbldepreciation'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('depreciation_id', array('options' => $depreciation, 'empty' => '--select--', 'id' => 'depreciation_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                        <span class="form-error" id="depreciation_id_error"></span>
                    </div>
                    <?php
                    // if (isset($result)) {
                    echo $this->Form->input('rate_factor_id', array('label' => false, 'id' => 'rate_factor_id', 'type' => 'hidden'));
                    //  }
                    ?>
                    <div class="col-sm-2">
                        <label for="deprication_type_id" class="control-label"><?php echo __('lblratefactor'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('rate_factor', array('label' => false, 'id' => 'rate_factor', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span class="form-error" id="rate_factor_error"></span>
                    </div>


                </div>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br>
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>
                            <a href="<?php echo $this->webroot; ?>ValuationRules/rate_factor" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
        <div class="box box-primary">

            <div class="box-body">

                <div class="responstable">
                    <table id="table" class="table table-striped table-bordered table-condensed">  
                        <thead>  
                            <tr> 
                                <th class="center"><?php echo __('lblconstuctiontye'); ?></th> 
                                <th class="center"><?php echo __('lbldepreciation'); ?></th> 
                                <th class="center"><?php echo __('lblratefactor'); ?></th> 
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php
                            foreach ($ratefactors as $ratefactor) {
                                // pr($ratefactor);exit;
                                ?>
                                <tr>
                                    <td class="tblbigdata"><?php echo $ratefactor['ctype']['construction_type_desc_' . $laug]; ?></td>
                                    <td class="tblbigdata"><?php echo $ratefactor['dtype']['deprication_type_desc_' . $laug]; ?></td>

                                    <td class="tblbigdata"><?php echo $ratefactor['ratefactor']['rate_factor']; ?></td>
                                    <td>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'rate_factor', $ratefactor['ratefactor']['rate_factor_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?></a>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_rate_factor', $ratefactor['ratefactor']['rate_factor_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?></a>
                                    </td>  </tr> 
                            <?php } ?>

                        </tbody>

                    </table> 
                </div>

            </div>
        </div>




    </div>
</div>