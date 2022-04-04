<?php
echo $this->element("Helper/jqueryhelper");
?>

<script type="text/javascript">
    $(document).ready(function () {


        $('#table').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

<?php if(@$titlewise_book_number_flag=='N'){?>
        $('#booknumber').show();
<?php }else{?>
     $('#booknumber').hide();
<?php } ?>  
        
        $('#titlewise_book_number_flag').change(function () {
            if (document.getElementById("titlewise_book_number_flagY").checked == true) {
                  $('#booknumber').hide(); 
            } else {
             
                $('#booknumber').show();
            }
        });





<?php if ($only_one_party_flag == 'Y') { ?>

            radiobtn = document.getElementById("only_one_party_flagY").checked = true;
<?php } else { ?>
            radiobtn = document.getElementById("only_one_party_flagN").checked = true;
<?php } ?>

<?php if ($home_visit_flag == 'Y') { ?>
            radiobtn = document.getElementById("home_visit_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("home_visit_flagN").checked = true;
<?php } ?>

<?php if ($dock_expiry_applicable_flag == 'Y') { ?>
            radiobtn = document.getElementById("dock_expiry_applicable_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("dock_expiry_applicable_flagN").checked = true;
<?php } ?>

<?php if ($e_reg_applicable_flag == 'Y') { ?>
            radiobtn = document.getElementById("e_reg_applicable_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("e_reg_applicable_flagN").checked = true;
<?php } ?>


<?php if ($e_file_applicable_flag == 'Y') { ?>
            radiobtn = document.getElementById("e_file_applicable_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("e_file_applicable_flagN").checked = true;
<?php } ?>

<?php if ($property_applicable_flag == 'Y') { ?>
            radiobtn = document.getElementById("property_applicable_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("property_applicable_flagN").checked = true;
<?php } ?>

<?php if ($template_applicable_flag == 'Y') { ?>
            radiobtn = document.getElementById("template_applicable_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("template_applicable_flagN").checked = true;
<?php } ?>

<?php if ($leave_licence_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("leave_licence_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("leave_licence_flag_flagN").checked = true;
<?php } ?>

<?php if ($use_common_rule_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("use_common_rule_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("use_common_rule_flag_flagN").checked = true;
<?php } ?>

<?php if (@$display_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("display_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("display_flag_flagN").checked = true;
<?php } ?>




<?php if ($index1_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("index1_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index1_flag_flagN").checked = true;
<?php } ?>

<?php if ($index2_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("index2_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index2_flag_flagN").checked = true;
<?php } ?>

<?php if ($index3_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("index3_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index3_flag_flagN").checked = true;
<?php } ?>

<?php if ($index4_flag_flag == 'Y') { ?>
            radiobtn = document.getElementById("index4_flag_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index4_flag_flagN").checked = true;
<?php } ?>

<?php if ($index_reg_flag1_flag == 'Y') { ?>
            radiobtn = document.getElementById("index_reg_flag1_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index_reg_flag1_flagN").checked = true;
<?php } ?>

<?php if ($index_reg_flag2_flag == 'Y') { ?>
            radiobtn = document.getElementById("index_reg_flag2_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index_reg_flag2_flagN").checked = true;
<?php } ?>

<?php if ($index_reg_flag3_flag == 'Y') { ?>
            radiobtn = document.getElementById("index_reg_flag3_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index_reg_flag3_flagN").checked = true;
<?php } ?>

<?php if ($index_reg_flag4_flag == 'Y') { ?>
            radiobtn = document.getElementById("index_reg_flag4_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("index_reg_flag4_flagN").checked = true;
<?php } ?>

<?php if ($titlewise_book_number_flag == 'Y') { ?>
            radiobtn = document.getElementById("titlewise_book_number_flagY").checked = true;

<?php } else { ?>
            radiobtn = document.getElementById("titlewise_book_number_flagN").checked = true;
<?php } ?>

    });
</script>



<?php echo $this->Form->create('article', array('id' => 'article', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblArticle'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/article_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-1"></div>
                        <?php
                        //  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblarticlename') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('article_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'article_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'article_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>                         
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                    </div>
                </div>

                <div class="row">&nbsp;</div>
                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                                   
                        <div class="col-sm-3">
                            <label for="artical_code" class="control-label"><?php echo __('lblarticlecode'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input("article_code", array('label' => false, 'id' => 'article_code', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="article_code_error" class="form-error"></span>
                        </div>  
                                  
                        <div class="col-sm-3">
                            <label for="display_order" class="control-label"><?php echo __('lblDisplayOrder'); ?><span style="color: #ff0000">*</span></label>  
                            <?php echo $this->Form->input("display_order", array('label' => false, 'id' => 'display_order', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="display_order_error" class="form-error"></span>
                        </div> 


                    </div> 
                </div>



                <div  class="rowht">&nbsp;</div>
                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="only_one_party_flag" class="control-label col-sm-2"><?php echo __('lblonlyoneparty'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1">
                            <?php echo $this->Form->input('only_one_party', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'only_one_party_flag')); ?>
                        </div> 

                        <label for="home_visit_flag" class="control-label col-sm-2"><?php echo __('lblhomevisit'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('home_visit', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'home_visit_flag')); ?></div>                       
                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="dock_expiry_applicable_flag" class="control-label col-sm-2"><?php echo __('dock_expiry_applicable'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('dock_expiry_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'dock_expiry_applicable_flag')); ?></div> 

                        <label for="e_reg_applicable_flag" class="control-label col-sm-2"><?php echo __('lbleregistration'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('e_reg_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'e_reg_applicable_flag')); ?></div> 

                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="e_file_applicable_flag" class="control-label col-sm-2"><?php echo __('lble_file_applicable'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('e_file_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'e_file_applicable_flag')); ?></div> 


                        <label for="property_applicable_flag" class="control-label col-sm-2"><?php echo __('property_applicable'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('property_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'property_applicable_flag')); ?></div> 

                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="template_applicable_flag" class="control-label col-sm-2"><?php echo __('lbltemplate_applicable_flag'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('template_applicable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'template_applicable_flag')); ?></div> 


                        <label for="leave_licence_flag_flag" class="control-label col-sm-2"><?php echo __('lblleave_licence_flag'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('leave_licence_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'leave_licence_flag_flag')); ?></div> 

                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="use_common_rule_flag_flag" class="control-label col-sm-2"><?php echo __('use_common_rule_flag'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('use_common_rule_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'use_common_rule_flag_flag')); ?></div> 

                        <label for="display_flag_flag" class="control-label col-sm-2"><?php echo __('lbldisplayflag'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('display_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'display_flag_flag')); ?></div> 

                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="index1_flag_flag" class="control-label col-sm-2"><?php echo __('lblindex1'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index1_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index1_flag_flag')); ?></div> 

                        <label for="index2_flag_flag" class="control-label col-sm-2"><?php echo __('lblindex2'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index2_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index2_flag_flag')); ?></div>   
                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="index3_flag_flag" class="control-label col-sm-2"><?php echo __('lblindex3'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index3_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index3_flag_flag')); ?></div> 

                        <label for="index4_flag_flag" class="control-label col-sm-2"><?php echo __('lblindex4'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index4_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index4_flag_flag')); ?></div>   
                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="index_reg_flag1_flag" class="control-label col-sm-2"><?php echo __('lblindexregister1'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index_reg_flag1', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index_reg_flag1_flag')); ?></div> 

                        <label for="index_reg_flag2_flag" class="control-label col-sm-2"><?php echo __('lblindexregister2'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index_reg_flag2', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index_reg_flag2_flag')); ?></div>   
                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group">
                        <label for="index_reg_flag3_flag" class="control-label col-sm-2"><?php echo __('lblindexregister3'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index_reg_flag3', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index_reg_flag3_flag')); ?></div> 

                        <label for="index_reg_flag3_flag" class="control-label col-sm-2"><?php echo __('lblindexregister4'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('index_reg_flag4', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'index_reg_flag4_flag')); ?></div>   
                    </div> 
                </div>

                <div class="row" id="list_flag_div">
                    <div class="col-md-1"></div>
                    <div class="form-group" id="titlewise_book_number_flag">
                        <label for="titlewise_book_number_flag" class="control-label col-sm-2"><?php echo __('lbltitlewisebookno'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-1"><?php echo $this->Form->input('titlewise_book_number', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'titlewise_book_number_flag')); ?></div> 

                        <div class="row" id="booknumber">
                            <label for="book_number" class="control-label col-sm-2"><?php echo __('lblbookno'); ?><span style="color: #ff0000">*</span></label>            
                            <div class="col-sm-1"><?php echo $this->Form->input("book_number", array('label' => false, 'id' => 'book_number', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="book_number_error" class="form-error"></span>
                            </div> 
                        </div>

                    </div> 
                </div>

                <?php
                echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'type' => 'hidden'));
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
                            
                              <a href="<?php echo $this->webroot; ?>PDEMaster/article" class="btn btn-info "><?php echo __('btncancel'); ?></a>
							
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
                            <th class="center"><?php echo __('lblarticlecode') ?></th>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblarticlename') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center"><?php echo __('lblDisplayOrder'); ?></th>
                            <th class="center"><?php echo __('lbldisplayflag'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($article as $articledata) {
                            ?>
                            <tr>
                                <td ><?php echo $articledata['article']['article_code']; ?></td>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $articledata['article']['article_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                <td ><?php echo $articledata['article']['display_order']; ?></td>
                                  <td ><?php echo $articledata['article']['display_flag']; ?></td>
                                <td>
                                     <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'article', $articledata['article']['article_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?>
                                     <?php if($articledata['article']['to_be_deleted_flag']=='Y'){ ?>
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_article', $articledata['article']['article_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-default"), array('Are you sure?')); ?>
                                     <?php } ?>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>