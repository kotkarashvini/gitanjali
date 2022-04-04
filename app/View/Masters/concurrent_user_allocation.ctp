<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
    }
</script> 
<?php echo $this->Form->create('concurrent_user_allocation', array('id' => 'concurrent_user_allocation', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="col-lg-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <center><h3 class="box-title headbolder"><?php echo __('Concurrent User Allocation'); ?></h3></center>
        </div>
        <div  class="rowht"></div><div  class="rowht"></div>
        <div class="row center">
            <div class="form-group">
                <label for="user_type" class="col-sm-3 control-label"><?php echo __('Select User Type'); ?>:<span style="color: #ff0000">*</span></label>    
                <div class="col-sm-2">
                    <?php echo $this->Form->input('user_type', array('options' => array($userlist), 'empty' => '--select--', 'id' => 'user_type', 'class' => 'form-control input-sm', 'label' => false)); ?>
                    <span id="user_type_error" class="form-error"><?php //echo $errarr['user_type_error'];        ?></span>
                </div>
                <label for="count_usr " class="col-sm-3 control-label"><?php echo __('Allocate Concurrent Users'); ?>:<span style="color: #ff0000">*</span></label>    
                <div class="col-sm-2">
                    <?php echo $this->Form->input('count_usr', array('options' => array($count_usr), 'empty' => '--select--', 'id' => 'count_usr', 'class' => 'form-control input-sm', 'label' => false)); ?>
                    <?php //echo $this->Form->input("count_usr", array('id' => 'count_usr', 'legend' => false, 'class' => 'form-control input-sm', 'maxlength' => 3, 'label' => false)); ?>
                    <span id="count_usr_error" class="form-error"><?php //echo $errarr['count_usr_error'];      ?></span>
                </div>
                <div class="form-group">
                    <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('Save'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <center><h4 class="box-title headbolder"><?php echo __('User Allocation Details'); ?></h4></center>
        </div>
        <div class="box-body">
            <div id="selectbehavioural">
                <table id="tablearticleparty" class="table table-striped table-bordered table-hover" >
                    <thead >  
                        <tr> 
                            <th class="center width10"><?php echo __('Event Description'); ?></th>
                            <th class="center width10"><?php echo __('User Login'); ?></th>
                            <th class="center width10"><?php echo __('Last Updated Date'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($user_data as $rec): ?>
                            <tr>
                                <td><?php echo $rec[0]['conf_desc_en']; ?></td>
                                <td><?php
                                    if ($rec[0]['conf_bool_value'] == 'Y') {
                                        echo 'Yes';
                                    } else {
                                        echo 'No';
                                    }
                                    ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($rec[0]['updated'])); ?></td>
                            </tr>       
                        <?php endforeach; ?>
                        <?php unset($rec); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




