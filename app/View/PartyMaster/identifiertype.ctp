
<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>

   <?php echo $this->Form->create('Identifier_Type', array('id' => 'Identifier_Type', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblidentifiertypeheader'); ?></h3></center>
                
                 <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/PartyMaster/identifire_en.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
                <div class="box-tools pull-right">
                    <!--<a  href="<?php echo $this->webroot; ?>helpfiles/PDE/document_title_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>-->
                </div> 
            </div>
            <div class="box-body">


                <div class="col-md-12">
                    <?php
                    //  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lblidentifiertypeheader') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '200')) ?>
                            <span id="<?php echo 'desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>

                <?php
                echo $this->Form->input('type_id', array('label' => false, 'id' => 'type_id', 'type' => 'hidden'));
                ?>
                <div  class="row center">
                <div class="col-md-12"  >
                    <div class="col-md-3">
                        <div class="form-group" >
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

                            <a href="<?php echo $this->webroot; ?>PartyMaster/identifiertype" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-body">
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  

                        <tr> 
                            <!--<th class="center"><?php echo __('lblidentifiertypeheader'); ?></th>-->
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblidentifiertypeheader') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($identifierTypeList as $identifierType) {
                            ?>
                            <tr>
                                
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $identifierType['identifire_type']['desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                <td>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'identifiertype', $identifierType['identifire_type']['type_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>

                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'identifiertype_delete', $identifierType['identifire_type']['type_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>