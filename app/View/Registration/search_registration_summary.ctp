<?php
echo $this->element("Registration/main_menu");
?>
<script>
    $("document").ready(function () {
        $("#doclist").dataTable();
        $('#from_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        $('#to_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
    });
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblsearchregsummery'); ?> </h3></center>  
            </div>
    <div class="box-body">
        <?php echo $this->Form->create('search_registration_summary', array('class' => 'form-inline inline-search-form')); ?>      
        <div class="form-group">
            <label for="from_date"><?php echo __('lblfromdate'); ?></label>
            <?php echo $this->Form->input('from_date', array('id' => 'from_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
        </div> 
        <div class="form-group">
            <label for="to_date"><?php echo __('lbltodate'); ?></label>
            <?php echo $this->Form->input('to_date', array('id' => 'to_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
        </div> 
        <div class="form-group">
            <label for="type"><?php echo __('lblregtype'); ?></label>
            <label><input type="radio" name="data[search_registration_summary][type]" checked value="1"><?php echo __('lblcompleted'); ?></label>             
            <label><input type="radio" name="data[search_registration_summary][type]" value="2"><?php echo __('lblinprocess'); ?></label> 
        </div>         
        <button type="submit" class=""><?php echo __('lblview'); ?></button>       
        <?php echo $this->Form->end(); ?>
    </div> 
</div>


    <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblresultdoclists'); ?></h3></center>  
    </div>   
    <div class="panel-body">  
        <table id="doclist" class="table table-bordred table-striped"> 
            <thead>
            <th><?php echo __('lbldocrno'); ?></th>
            <th><?php echo __('lblArticle'); ?></th>
            <th><?php echo __('lblpresenter'); ?></th> 
            <th><?php echo __('lblofficename'); ?></th>
            <th><?php echo __('lblregdate'); ?></th>
            <th><?php echo __('lblstatus'); ?></th>
            <th><?php echo __('lblaction'); ?></th>  
            </thead>
            <tbody>
                <?php
                $status = array('Y' => 'Complete', 'N' => 'In Process');
                if (isset($released_list)) {
                    foreach ($released_list as $document) {
                        $document = $document[0];
                        ?>
                        <tr>
                            <td><?php echo $document['doc_reg_no']; ?></td>
                            <td><?php echo $document['article_desc_en']; ?></td>
                            <td><?php echo $document['party_full_name_en']; ?></td>
                            <td><?php echo $document['office_name_en']; ?></td>
                            <td><?php
                                $date = date_create($document['doc_reg_date']);
                                echo date_format($date, 'd M Y h:s:i a');
                                ?></td>
                            <td><?php echo $status[$document[$check_stamp_flag]]; ?></td>
                            <td>
                                <?php if ($document[$check_stamp_flag] == 'Y') { ?>
                                    <a href="#">Download</a> 
                                <?php } ?>

                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>      
</div>
</div>
</div>