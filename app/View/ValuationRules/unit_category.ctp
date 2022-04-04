<?php
echo $this->element("Helper/jqueryhelper");
?>


<script>
    $(document).ready(function () {

        $('#table').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
       
       function update(){
           alert("hii")
       }
       
       
    });</script>
<?php //echo $this->element("BlockLevel/main_menu"); ?>

<?php echo $this->Form->create('unit_category', array('id' => 'unit_category', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblunitcategory'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblunitcategory'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/unit_category_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">

                <!--<div class="row">-->
                <div class="form-group">
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-5">
                            <label><?php echo __('lblunitcategory') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('unit_cat_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'unit_cat_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-lg', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'unit_cat_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php //echo $errarr['developed_land_types_desc_' . $langcode['mainlanguage']['language_code'] . '_error'];                             ?></span>
                           
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>
               
                <?php
                echo $this->Form->input('unit_cat_id', array('label' => false, 'id' => 'unit_cat_id', 'type' => 'hidden'));
                ?>

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br> <br>
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>ValuationRules/unit_category" class="btn btn-info "><?php echo __('btncancel'); ?></a>

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
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblunitcategory') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <!--<tr>--> 


                        <?php
                        foreach ($unitcatdata as $unitcatdata1) {
                            ?>
                            <tr>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $unitcatdata1[0]['unit_cat_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

                                <td>
                                    <!--<a href="<?php echo $this->webroot; ?>Property/attribute_property/<?php echo $unitcatdata1[0]['unit_cat_id']; ?>" class="btn-sm btn-default"><span class="fa fa-pencil"></span> </a>-->    
                                    
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'unit_category', $unitcatdata1[0]['unit_cat_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_unit_category', $unitcatdata1[0]['unit_cat_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure Delete?')); ?></a>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>