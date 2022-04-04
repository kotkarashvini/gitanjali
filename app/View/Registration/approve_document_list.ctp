<script type="text/javascript">
    $(document).ready(function () {
       $('#doclist').dataTable({
                    "iDisplayLength": 10,
                    "ordering":false,
                            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
                    })
    });
</script> 
<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Document List For Observation'); ?></h3></center>
    </div>

    <div class="box-body">

        <table id="doclist" class="table table-bordred table-striped"> 
            <thead>
            <th><?php echo __('lbltokenno'); ?></th>
            <th><?php echo __('lblArticle'); ?></th> 
            <th><?php echo __('lbltitlename'); ?></th> 
            <th><?php echo __('Date Of Submission'); ?></th>
            <th><?php echo __('lblexecutiondt'); ?></th>            
            <th><?php echo __('Select Document'); ?></th>  
            </thead>
            <tbody>
                <?php            

                if (isset($rpt_data)) {
                    $i=1;
                    foreach ($rpt_data as $document) {
                        $document = $document[0];
                        ?>
                <tr>
                    <td><?php echo $document['token_no']; ?></td>
                    <td><?php echo $document['article_desc_'.$lang]; ?></td> 
                     <td><?php echo $document['articledescription_'.$lang]; ?></td> 
                    <td><?php echo $document['created']; ?></td>
                    <td> <?php  $date=explode(" ",$document['exec_date']); echo $date[0]; ?></td>               
                    
                    <td> 
                        <?php if($i==1){
                            $i++;
                            ?>
                        <a href="<?php echo $this->webroot; ?>Registration/approve_document/<?php echo $document['token_no']; ?>">View Document</a>
                    <?php } ?>
                    
                    </td>
                </tr>

                    <?php
                    }
                }
                ?>
            </tbody>
        </table>


    </div> 
</div>

