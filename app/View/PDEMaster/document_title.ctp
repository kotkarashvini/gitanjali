<?php
echo $this->element("Helper/jqueryhelper");
?>


<script>
    $(document).ready(function () {
        $('#table').DataTable();
        $('#booknorow').hide();
        if ($.isNumeric($('#article_id').val())) {
            var article_id = $('#article_id').val();
            $.post('<?php echo $this->webroot; ?>PDEMaster/getbookno', {article_id: article_id}, function (data)
            {
                if (data == 1) {
                    $('#booknorow').show();
                } else {
                    $('#booknorow').hide();
                }
            });
        }



        $('#article_id').change(function () {
            var article_id = $('#article_id').val();
            $.post('<?php echo $this->webroot; ?>PDEMaster/getbookno', {article_id: article_id}, function (data)
            {
                if (data == 1) {
                    $('#booknorow').show();
                } else {
                    $('#booknorow').hide();
                }
            });
        });
    });
</script>



<?php echo $this->Form->create('document_title', array('id' => 'document_title', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocumenttitle'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/PDEMaster/document_title_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12"> 
                    <div class="col-sm-3">
                        <label for="" class="control-label"><?php echo __('lblarticlename'); ?><span style="color: #ff0000">*</span></label> 
                        <?php echo $this->Form->input('article_id', array('options' => array($articlelist), 'empty' => '--select--', 'id' => 'article_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="article_id_error" class="form-error"></span>
                    </div> 
                </div>

                <div class="col-md-12" id="booknorow">
                    <div class="col-sm-3">
                        <label for="" class="control-label"><?php echo __('lblbookno'); ?><span style="color: #ff0000">*</span></label>
                        <?php echo $this->Form->input('book_number', array('label' => false, 'id' => 'book_number', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="book_number_error" class="form-error"></span>
                    </div> 
                </div>

                <div class="col-md-12">
                    <?php
                    //  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lbldocumenttitle') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('articledescription_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'articledescription_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'articledescription_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"></span>                         
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>

                <?php
                echo $this->Form->input('articledescription_id', array('label' => false, 'id' => 'articledescription_id', 'type' => 'hidden'));
                ?>

                <div class="col-md-12">
                    <div class="col-md-3">
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

                            <a href="<?php echo $this->webroot; ?>PDEMaster/document_title" class="btn btn-info "><?php echo __('btncancel'); ?></a>
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
                            <th class="center"><?php echo __('lblarticlename'); ?></th>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lbldocumenttitle') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                                <!--<th class="center width10"><?php // echo __('lblbookno'); ?></th>-->
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        foreach ($document as $document_titledata) {
                            ?>
                            <tr>
                                <td><?php echo $document_titledata['article']['article_desc_' . $laug]; ?></td>                               

                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $document_titledata['articledescdetails']['articledescription_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                    <!--<td><?php // echo $document_titledata['articledescdetails']['book_number']; ?></td>--> 
                                <td>
                                    <!--<a href="<?php // echo $this->webroot;               ?>Office/document_title/<?php // echo $document_titledata['document_title']['articledescription_id'];               ?>" class="btn-sm btn-default"><span class="fa fa-pencil"></span> </a>-->    

                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'document_title', $document_titledata['articledescdetails']['articledescription_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?>


                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_document_title', $document_titledata['articledescdetails']['articledescription_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?>
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>