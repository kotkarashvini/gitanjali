<script>
    $(document).ready(function () {

$('#table').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
        });

</script>
<?php echo $this->Form->create('role_creation', array('id' => 'role_creation', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblrole'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Users/rolecreation_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    
                    <div class="row">

                        <!--                        <div class="col-sm-2">
                                                    <label for="module_id" class="control-label"><?php echo __('lbladmmodule'); ?> <span class="star">*</span></label>
                        <?php //echo $this->Form->input('module_id', array('options' => $module, 'empty' => '--select--', 'id' => 'module_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                                                    <span class="form-error" id="district_id_error"></span>
                                                </div>-->

                        <br>
                        <div class="col-sm-2">
                            <label for="role_id" class="control-label"><?php echo __('lblenterroleid'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('role_id', array('label' => false, 'id' => 'role_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '8')) ?>
                            <span id="role_id_error" class="form-error"><?php echo $errarr['role_id_error']; ?></span>
                        </div>
                    </div>


                    <!--<div class="row">-->
                    <div class="row">
                        <?php
//  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <label><?php echo __('lblrole_desc') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('role_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'role_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'role_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['role_name_' . $langcode['mainlanguage']['language_code'] . '_error'];                                        ?></span>
                                <?php //echo $errarr['role_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                    </div>



                    


                    <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>


                    <div class="row center">
                        <div class="form-group">
                            <div class="col-sm-12 tdselect">
                                <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>                               
                                
                              <a href="<?php echo $this->webroot; ?>Users/role_creation" class="btn btn-info "><?php echo __('btncancel'); ?></a>
                  
                            </div>
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
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr> 
                              <th class="center"><?php echo __('lblrollid'); ?></th>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblrole_desc') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody> 
                        <?php
                        foreach ($role_creation as $role_creation1) {
                            ?>
                            <tr>
                                 <td><?php echo $role_creation1[0]['role_id']; ?></td>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $role_creation1[0]['role_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                <td>
                                     
                                      <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'role_creation', $role_creation1[0]['role_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?></a>
                               
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_role_creation', $role_creation1[0]['role_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?></a>
                                </td>  
                            </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>