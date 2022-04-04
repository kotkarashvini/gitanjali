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
<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbldocumentdisposal'); ?></h3></center>
    </div>

    <div class="box-body">
        <?php echo $this->Form->create('Documents_disposal', array('class' => 'form-inline inline-search-form')); ?>      
        <div class="form-group">
            <label for="from_date"><?php echo __('lblfromdate'); ?></label>
            <?php echo $this->Form->input('from_date', array('id' => 'from_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
        </div> 
        <div class="form-group">
            <label for="to_date"><?php echo __('lbltodate'); ?></label>
            <?php echo $this->Form->input('to_date', array('id' => 'to_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
        </div> 
        <!--        <div class="form-group">
                    <label for="type"><?php echo __('lblregtype'); ?></label>
                    <label><input type="radio" name="data[Documents_disposal][type]" checked value="1"><?php echo __('lblcompleted'); ?></label>             
                    <label><input type="radio" name="data[Documents_disposal][type]" value="2"><?php echo __('lblinprocess'); ?></label> 
                </div>         -->
        <button type="submit" class="btn btn-primary"><?php echo __('lblview'); ?></button>       
        <?php echo $this->Form->end(); ?>
    </div> 
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblresultdoclists'); ?></h3></center>
    </div>

    <div class="box-body">  
        <table id="doclist" class="table table-bordred table-striped"> 
            <thead>
            <th><?php echo __('lbldocrno'); ?></th>
            <th><?php echo __('lblArticle'); ?></th>
            <th><?php echo __('lblpresenter'); ?></th> 
            <th><?php echo __('lbltitlename'); ?></th>
            <th><?php echo __('lblregdate'); ?></th>
            <!--<th><?php //echo __('lblstatus');   ?></th>-->
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
                            <td><?php echo $document['title_name']; ?></td>
                            <td><?php
                                $date = date_create($document['doc_reg_date']);
                                echo date_format($date, 'd M Y h:s:i a');
                                ?></td>

                <!--<td><?php //echo $status[$document[$col_flag]];   ?></td>-->
                            <td>
                                <?php ?>
                                <!--                                    <a href="#">Download</a> -->
                                <?php ?>

                            </td>
                        </tr>

                    <?php
                    }
                }
                ?>


            </tbody>
        </table>
        <?php if (isset($from_date) && isset($to_date)) { ?>
            <center><a href="<?php echo $this->webroot; ?>Registration/disposal_all_report/<?php echo strtotime($from_date); ?>/<?php echo strtotime($to_date); ?>"><?php echo __('Download Report'); ?></a></center>
<?php } ?>   
    </div>      
</div>