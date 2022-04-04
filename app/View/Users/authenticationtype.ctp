<?php
echo $this->element("Helper/jqueryhelper");
?>



<?php echo $this->Form->create('authenticationtype', array('id' => 'authenticationtype', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblauthenticationtype'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Users/authenticationtype_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12">

                    <div class="row">                    
                        <div class="col-md-3">
                            <label><?php echo __('lblauthenticationtypeid') ?><span style="color: #ff0000">*</span></label>    

                            <?php if(isset($editflag)){
                            echo $this->Form->input('user_auth_type_id', array('label' => false, 'id' => 'user_auth_type_id', 'type' => 'text', 'class' => 'form-control','readonly' => 'true'));
                            }else{
                                echo $this->Form->input('user_auth_type_id', array('label' => false, 'id' => 'user_auth_type_id', 'type' => 'text', 'class' => 'form-control')); 
                            }?>  
                            <span id="<?php echo 'user_auth_type_id_error'; ?>" class="form-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <?php
//  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblauthenticationtypedesc') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label> 
                                
                                 <?php 
                                 
                                 if(isset($editflag)){
                                     if($langcode['mainlanguage']['language_code']=='en'){
                                     echo $this->Form->input('auth_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'auth_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100','readonly' => 'true')) ;
                                 }else{
                                      echo $this->Form->input('auth_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'auth_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')); 
                                 }
                                 }else{
                                      echo $this->Form->input('auth_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'auth_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100'));
                                 }?>
                                <?php //echo $this->Form->input('auth_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'auth_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'auth_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>

                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                    </div>

                    <div class="row center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <br>
                                 <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info " dis>
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " disabled>
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                                <a href="<?php echo $this->webroot; ?>Users/authenticationtype" class="btn btn-info"><?php echo __('btncancel'); ?></a>
                            </div>
                        </div>
                    </div>
                </div> 
                  <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
                <?php //echo $this->Form->input('id', array('label' => false, 'id' => 'user_auth_type_id', 'type' => 'text', 'class' => 'form-control'));?>
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
                        foreach ($languagelist as $langcode) {
                            ?>
                            <th class="center"><?php echo __('lblauthenticationtype') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                        <?php } ?>
                         <th class="center"><?php echo __('lblauthenticationtypeid'); ?></th>
                        <th class="center width10"><?php echo __('lblaction'); ?></th>

                    </tr>  
                </thead>
                <tbody>

                    <?php
                    foreach ($authenticationtype as $authenticationtype1) {
                        ?>
                        <tr>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                            <th class="center"><?php echo $authenticationtype1[0]['auth_type_desc_' . $langcode['mainlanguage']['language_code']]; ?></th>
                            <?php } ?>
                                <th class="center width10"><?php echo $authenticationtype1[0]['user_auth_type_id']; ?></th>
                            <td >
                             <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'authenticationtype', $authenticationtype1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>
                            <?php //echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_authenticationtype', $authenticationtype1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-default"), array('Are you sure?')); ?>
                            
                            
                            </td>  </tr> 
                    <?php } ?>

                </tbody>

            </table> 
                </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#table').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
