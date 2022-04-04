
<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>

   <?php echo $this->Form->create('DocumentNumber', array('id' => 'DocumentNumber', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocrno'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Registration/documentnumber.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">

                <div class="col-md-12">
                    <div class='row margin-bottom'>
                        <div class="col-md-3">
                            <label><?php echo __('lblformatfield'); ?><span> : </span></label>
                            <label><?php echo (isset($this->request->data['DocumentNumber']['format_field'])!=1?'':$this->request->data['DocumentNumber']['format_field']); ?></label>
                        </div>
                        <div class="col-md-3">
                            <label><?php echo __('lblformatfielddesc'); ?><span> : </span></label>    
                            <label><?php echo (isset($this->request->data['DocumentNumber']['format_field_desc'])!=1?'':$this->request->data['DocumentNumber']['format_field_desc']); ?></label>                                               
                        </div>
                        </div>
                    <div class='row margin-bottom'>
                    <div class="col-md-3">
                            <label><?php echo __('lblisrequired'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('format_field_flag', array('label' => false, 'id' => 'format_field_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $arrCategory))); ?>
                            <span id="<?php echo 'format_field_flag' . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <div class="col-md-3">
                            <label><?php echo __('lblDisplayOrder'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('display_order', array('label' => false, 'id' => 'display_order', 'class' => 'form-control input-sm', 'type' => 'number', 'maxlength' => '2', 'min' => '1')) ?>
                            <span id="<?php echo 'display_order' . '_error'; ?>" class="form-error"></span>                         
                        </div>
                        <div class="col-md-3">
                            <label><?php echo ('Hierarchical Order'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('h_order', array('label' => false, 'id' => 'h_order', 'class' => 'form-control input-sm', 'type' => 'number', 'maxlength' => '2', 'min' => '1')) ?>
                            <span id="<?php echo 'h_order' . '_error'; ?>" class="form-error"></span>                         
                        </div>
                        </div>
                    <div class='row margin-bottom'>
                    <div class="col-md-3" >
                            <label><?php echo ('Is Static'); ?><span> : </span></label>   
                            <label><?php echo (isset($this->request->data['DocumentNumber']['is_static'])!=1?'':$this->request->data['DocumentNumber']['is_static']); ?></label>                   
                        </div>
                    <div class="col-md-3" style='display:<?php echo (isset($this->request->data['DocumentNumber']['is_static'])!=1?'none':$this->request->data['DocumentNumber']['is_static']=='N' ? 'none' : 'block') ?>'>
                            <label><?php echo __('lblstaticval'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('static_value', array('label' => false, 'id' => 'static_value', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '1','disabled' => (isset($this->request->data['DocumentNumber']['is_static'])!=1?true:$this->request->data['DocumentNumber']['is_static']=='N' ? true : false))) ?>
                            <span id="<?php echo 'static_value' . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    </div>
                    <div class='row margin-bottomv'>
                    <div class="col-md-3">
                            <label><?php echo ('Is Padding Required'); ?><span> : </span></label>   
                            <label><?php echo (isset($this->request->data['DocumentNumber']['padding_flag'])!=1?'':$this->request->data['DocumentNumber']['padding_flag']); ?></label>                          
                        </div>
                    <div class="col-md-3" style='display:<?php echo (isset($this->request->data['DocumentNumber']['padding_flag'])!=1?'none':$this->request->data['DocumentNumber']['padding_flag']=='N' ? 'none' : 'block') ?>'>
                            <label><?php echo ('Padding Length'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('padding_length', array('label' => false, 'id' => 'padding_length', 'class' => 'form-control input-sm', 'type' => 'number', 'maxlength' => '2','disabled' => (isset($this->request->data['DocumentNumber']['padding_flag'])!=1?true:$this->request->data['DocumentNumber']['padding_flag']=='N' ? true : false))) ?>
                            <span id="<?php echo 'padding_length' . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <div class="col-md-3" style='display:<?php echo (isset($this->request->data['DocumentNumber']['padding_flag'])!=1?'none':$this->request->data['DocumentNumber']['padding_flag']=='N' ? 'none' : 'block') ?>'>
                            <label><?php echo ('Padding Character'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('padding_char', array('label' => false, 'id' => 'padding_char', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '1','disabled' => (isset($this->request->data['DocumentNumber']['padding_flag'])!=1?true:$this->request->data['DocumentNumber']['padding_flag']=='N' ? true : false))) ?>
                            <span id="<?php echo 'padding_char' . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    </div>
                    <div class='row margin-bottomv'>
                    <div class="col-md-12 center">
                            <label><?php echo ('Document Number will be like, '); ?></label>
                            <label style="color:brown;"><?php echo ($DocNoWillBe); ?></label>                          
                        </div>
                    </div>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>

                <?php
                echo $this->Form->input('format_field_id', array('label' => false, 'id' => 'format_field_id', 'type' => 'hidden'));
                ?>

                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <br>
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } ?>
                            
                            <a href="<?php echo $this->webroot; ?>Registration/documentnumber" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
                            <!--<th class="center"><?php echo __('lbldocrno'); ?></th>-->
                           
                                <th class="center"><?php echo __('lblformatfield') ; ?></th>
                                <th class="center"><?php echo __('lblformatfielddesc') ; ?></th>
                                <th class="center"><?php echo __('lblisrequired') ; ?></th>
                                <th class="center"><?php echo __('lblorder') ; ?></th>
                                <th class="center"><?php echo ('Is Static') ; ?></th>
                                <th class="center"><?php echo __('lblstaticval') ; ?></th>
                                <th class="center"><?php echo ('Is Padding Required') ; ?></th>
                                <th class="center"><?php echo ('Padding Length') ; ?></th>
                                <th class="center"><?php echo ('Padding Character') ; ?></th>
                                <th class="center"><?php echo ('Hierarchical Order') ; ?></th>
                  
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($DocumentNumberFormatResult as $DocumentNumberFormat) {
                            ?>
                            <tr>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['format_field']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['format_field_desc']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['format_field_flag']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['display_order']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['is_static']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['static_value']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['padding_flag']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['padding_length']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['padding_char']; ?></td>
                                    <td ><?php echo $DocumentNumberFormat['DocumentNumber']['h_order']; ?></td>
                                
                                <td>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'documentnumber', $DocumentNumberFormat['DocumentNumber']['format_field_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>

                                    <!--<?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'DocumentNumberFormat_Delete', $DocumentNumberFormat['DocumentNumber']['format_field_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-default"), array('Are you sure to Delete?')); ?>-->
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>