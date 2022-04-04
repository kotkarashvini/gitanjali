<script>
    $("document").ready(function () {

        $("#doclist").dataTable();
        $('[data-toggle="tooltip"]').tooltip();   

<?php if (isset($check_release) && !empty($check_release)) { ?>
           
           $('#openModal').modal('show');
<?php } ?>

    });
</script>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
    <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocrelease'); ?></h3></center>  
    </div>
    <div class="box-body">
        <?php echo $this->Form->create('document_release', array('class' => 'form-inline')); ?>
               <div class="form-group">
            <label for="email"><?php echo __('lblenterdocregno'); ?></label>
            <?php echo $this->Form->input('document_number', array('class' => 'form-control', 'label' => false, 'div' => FALSE,'id' => 'document_number')); ?>
          <span id="document_number_error" class="form-error"><?php echo $errarr['document_number_error']; ?></span>
        </div> 
        <button type="submit" class="btn btn-default"><?php echo __('lblsearch'); ?></button>
     
        <?php echo $this->Form->end(); ?>
    </div>  
    <?php if (isset($result)) { ?>

        <div class="panel-footer">

            <?php
            if (empty($result) || !empty($check_release)) {
                echo 'No Data Found';
            } else {

                $result = $result[0][0];

                ?>


                <table id="mytable" class="table table-bordred table-striped">

                    <thead>
                    <th><?php echo __('lbldocrno'); ?></th>
                    <th><?php echo __('lblArticle'); ?></th>
                    <th><?php echo __('lblpresenter'); ?></th>           
                    <th><?php echo __('lblregdate'); ?></th>
                     
                    <th><?php echo __('lblview'); ?></th> 
                    <th><?php echo __('lblrelease'); ?></th>
                    </thead>
                    <tbody>

                        <tr>
                            <td><?php echo $result['doc_reg_no']; ?></td>
                            <td><?php echo $result['article_desc_en']; ?></td>
                            <td><?php echo $result['party_full_name_en']; ?></td>
                            <td><?php echo $result['doc_reg_date']; ?></td> 
                              <td><a href="#">download</a> </td>
                            <td> <button class="btn btn-danger btn-xs"   data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-edit"></span></button> </td>
                        </tr>
                    </tbody>
                </table>



            <?php } ?>
        </div>

    <?php } ?>

</div>


 <div class="box box-primary">
    <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreleasedoclist'); ?></h3></center>  
    </div>   
    <div class="panel-body">  
        <table id="doclist" class="table table-bordred table-striped"> 
            <thead>
            <th><?php echo __('lbldocrno'); ?></th>
            <th><?php echo __('lblArticle'); ?></th>
            <th><?php echo __('lblpresenter'); ?></th>           
            <th><?php echo __('lblregdate'); ?></th>
            <th><?php echo __('lblreleaseddate'); ?></th>
            <th><?php echo __('lblremark'); ?></th>  
            </thead>
            <tbody>
                <?php
                foreach ($released_list as $document) {
                    $document = $document[0];
                    ?>
                    <tr>
                        <td><?php echo $document['doc_reg_no']; ?></td>
                        <td><?php echo $document['article_desc_en']; ?></td>
                        <td><?php echo $document['party_full_name_en']; ?></td>
                        <td><?php  
                                $date = date_create($document['doc_reg_date']);
                                echo    date_format($date, 'd M Y h:s:i a'); 
                                ?> </td>
                        <td><?php  
                                $date = date_create($document['release_date']);
                                echo    date_format($date, 'd M Y h:s:i a'); 
                                ?></td>
                        <td><a href="#" data-toggle="tooltip" data-placement="top"  title="<?php echo htmlentities($document['document_release_remark']); ?>">Remark</a> </td>
                   </tr>
<?php } ?>
            </tbody>
        </table>
    </div>      
</div>

<?php if (isset($result) && !empty($result) && empty($check_release)) { ?>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
    <?php echo $this->Form->create('document_release'); ?>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('lbldockreleasedremark'); ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo $this->Form->input('document_number', array('type' => 'hidden', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'value' => $result['doc_reg_no'])); ?>
    <?php echo $this->Form->textarea('document_release_remark', array('class' => 'form-control', 'label' => false, 'div' => FALSE)); ?>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default"><?php echo __('btnsubmit'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                </div>
            </div>
    <?php echo $this->Form->end(); ?>
        </div>
    </div>
<?php } ?>




<!--<a href="#openModal" id="popup-dialog" class="hidden"><?php echo __('lblopenmodel'); ?></a>--> 
<!--<div id="openModal" class="modalDialog">
    <div class="modalBody">
        <a href="#close" title="Close" class="close">X</a>
        <h2><?php echo __('lblalert'); ?></h2>
        <p><?php echo __('lbldocalreadyreleaselist'); ?></p>
        <a class="btn-pure-1" href="#close"><?php echo __('lblok'); ?></a>
    </div>
</div>-->
 <div id="openModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
    <?php echo $this->Form->create('document_release'); ?>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('lbldockreleasedremark'); ?></h4>
                </div>
                <div class="modal-body">
                  
       
        <p><?php echo __('lbldocalreadyreleaselist'); ?></p>
     
                </div>
                <div class="modal-footer">
                    <!--<button type="submit" class="btn btn-default"><?php echo __('btnsubmit'); ?></button>-->
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                </div>
            </div>
    <?php echo $this->Form->end(); ?>
        </div>
    </div>



</div>
</div>

