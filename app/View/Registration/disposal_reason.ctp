<?php
echo $this->element("Helper/jqueryhelper");
?>

<script type="text/javascript">
    $(document).ready(function () {
<?php if (@$display_flag == 'N') { ?>
            radiobtn = document.getElementById("display_flagN").checked = true;
<?php } else { ?>
            radiobtn = document.getElementById("display_flagY").checked = true;
<?php } ?>

    });
</script>

<script>
    $(document).ready(function () {
        
        $('#table').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>



<?php echo $this->Form->create('disposal_reason', array('id' => 'disposal_reason', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldisposalreason'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Registration/disposal_reason_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="form-group">

                    <div class="col-sm-3">
                        <label for="disposal_id" class="control-label"><?php echo __('lbldisposaltype'); ?><span style="color: #ff0000">*</span></label>
                        <?php echo $this->Form->input('disposal_id', array('label' => false, 'id' => 'disposal_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $disposaltype))); ?>
                        <span id="dept_id_error" class="form-error"></span>
                    </div>


                    <?php
                    //  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lbldisposalreason') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('reason_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'reason_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'reason_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>
                <div class="row" id="list_flag_div">
                    <div class="form-group">
                        <label for="display_flag" class="control-label col-sm-2"><?php echo __('lbldisplayflag'); ?></label>            
                        <div class="col-sm-3"><?php echo $this->Form->input('display_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'display_flag')); ?></div> 
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>


                <?php
                echo $this->Form->input('reason_id', array('label' => false, 'id' => 'reason_id', 'type' => 'hidden'));
                ?>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br>

                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>Registration/disposal_reason" class="btn btn-info"><?php echo __('btncancel'); ?></a>
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
                               <!--<th class="center width10"><?php //echo __('Disposal Type'); ?></th>-->
                                <th class="center"><?php echo __('lbldisposalreason') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                          <th class="center width10"><?php echo __('lbldisposaltype'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($res as $DocumentDisposalReasonsdata) {

                         //   pr($DocumentDisposalReasonsdata);
                            ?>
                            <tr>

                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>

                                   
                                    <td ><?php echo $DocumentDisposalReasonsdata[0]['reason_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                     <td ><?php echo $DocumentDisposalReasonsdata[0]['disposal_desc_en']; ?></td>

                                <td>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'disposal_reason', $DocumentDisposalReasonsdata[0]['reason_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?></a>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_disposal_reason', $DocumentDisposalReasonsdata[0]['reason_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure?')); ?></a>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>