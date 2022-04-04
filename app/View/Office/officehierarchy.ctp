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


<?php echo $this->Form->create('officehierarchy', array('id' => 'officehierarchy', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
 <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofficehierarchy'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/officehierarchynew_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">



                <!--<div class="row">-->
                <div class="form-group">
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lblofficehierarchy') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('hierarchy_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'hierarchy_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'hierarchy_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['hierarchy_desc_' . $langcode['mainlanguage']['language_code'] . '_error'];                            ?></span>
                            <?php //echo $errarr['hierarchy_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>
                
                
<!--                <div class="row">
                    <div class="form-group">
                        <label for="contact_person" class="col-sm-3"><?php echo __('lblsortid'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('sort_level_id', array('label' => false, 'id' => 'sort_level_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20')); ?>
                            <span id="sort_level_id_error" class="form-error"><?php echo $errarr['sort_level_id_error']; ?></span>
                        </div>
                    </div>
                </div>-->
                <!--</div>-->


                <?php
              //  pr($result);exit;
             //   if (isset($result)) {
                    echo $this->Form->input('hierarchy_id', array('label' => false, 'id' => 'hierarchy_id', 'type' => 'hidden'));
              //  }
                ?>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>


                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <div  class="rowht"></div>
                            <div  class="rowht"></div>
                           <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                             <a href="<?php echo $this->webroot;?>Office/officehierarchy" class="btn btn-info"><?php echo __('btncancel'); ?></a>
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
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblofficehierarchy') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <!--<tr>--> 


                        <?php
                        foreach ($hierarchydata as $hierarchydata1) {
                            ?>
                            <tr>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $hierarchydata1[0]['hierarchy_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

                                <td>
                                     
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'officehierarchy', $hierarchydata1[0]['hierarchy_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?></a>
                              
                             
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_officehierarchy', $hierarchydata1[0]['hierarchy_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure?')); ?></a>
                              
                                </td>  
                            </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
            </div>
        </div>
    </div>
 