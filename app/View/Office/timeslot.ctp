<?php
echo $this->element("Helper/jqueryhelper");
?>

<script>
    $(document).ready(function () {
        
        $('#table').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>  


<?php echo $this->Form->create('timeslot', array('id' => 'timeslot', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
 <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbltimeslot'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Office/officetimesolt_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="form-group">
                        
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                        </div>
                        <label for="slot_time_minute" class="col-sm-3 control-label"><?php echo __('lbltimeslotsinmin'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('slot_time_minute', array('label' => false, 'id' => 'slot_time_minute', 'class' => 'form-control input-sm', 'type' => 'number', 'maxlength' => '2','min'=>'1','max'=>'60')) ?>
                            <span id="slot_time_minute_error" class="form-error"><?php echo $errarr['slot_time_minute_error']; ?></span>
                        </div>
                    </div>

                <?php
                echo $this->Form->input('slot_id', array('label' => false, 'id' => 'slot_id', 'type' => 'hidden'));
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
                             <a href="<?php echo $this->webroot; ?>Office/timeslot" class="btn btn-info"><?php echo __('btncancel'); ?></a>
                            
                        </div>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-body">
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  

                        <tr> 
                            <?php
                            //foreach ($languagelist as $langcode) {
                                ?>
                                 <th class="center"><?php echo __('lbltimeslotsinmin'); ?></th>
                            <?php //} ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($timeslotrecord as $departmentdata) {
                            ?>
                            <tr>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                               // foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $departmentdata['timeslot']['slot_time_minute']; ?></td>
                                <?php// } ?>

                                <td>
                                     
                                     <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'timeslot', $departmentdata['timeslot']['slot_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?></a>
                                
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'timeslot_delete', $departmentdata['timeslot']['slot_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to delete?')); ?></a>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
            </div>
        </div>
    </div>
 