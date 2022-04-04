
<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>

   <?php echo $this->Form->create('occupationlist', array('id' => 'occupationlist', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbloccupation'); ?></h3></center>
                <div class="box-tools pull-right">
                   <a  href="<?php echo $this->webroot; ?>helpfiles/PartyMaster/occupation_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">


                <div class="col-md-12">
                    <?php
                    //  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lbloccupation') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('occupation_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'occupation_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                            <span id="<?php echo 'occupation_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>

                <?php
                echo $this->Form->input('occupation_id', array('label' => false, 'id' => 'occupation_id', 'type' => 'hidden'));
                ?>

                <div class="col-md-12">
                    <div class="col-md-3">
                          <div class="row center">
                        <div class="form-group">
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

                            <a href="<?php echo $this->webroot; ?>PartyMaster/occupationlist" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
                            <!--<th class="center"><?php echo __('lbloccupation'); ?></th>-->
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lbloccupation') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($OccupationListResult as $Occupation) {
                            ?>
                            <tr>
                                
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $Occupation['occupation']['occupation_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                <td>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'occupationlist', $Occupation['occupation']['occupation_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>

                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'Occupation_Delete', $Occupation['occupation']['occupation_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>