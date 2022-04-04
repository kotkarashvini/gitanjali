
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblscanpendingdock'); ?></h3></center>
            </div>
            <div class="box-body">
                
                    <?php  
                   $laststampflag='';
                   foreach ($stamp_conf as $stamp) {
                       if($stamp['is_last']=='Y')
                       {
                           $laststampflag=$stamp['stamp_flag'];
                       }
                   }
                            
                    ?>
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lbldocktype'); ?></th>
                                <th><?php echo __('lblpresentername'); ?></th>
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            foreach ($alldocuments as $documents) {
                                ?>
                                <tr>
                                    <th scope="row"><?php echo ++$counter; ?></th>
                                    <td><?php echo $documents[0]['token_no']; ?></td>
                                    <td><?php echo $documents[0]['article_desc_'.$lang]; ?></td>
                                    <td><?php echo $documents[0]['party_full_name_'.$lang];    ?></td>
                                    <td> 

                                       <?php if ($documents[0][$laststampflag] == 'Y' && $documents[0]['document_scan_flag']=='N') 
                                            {?>
                                                <a href="<?php echo $this->webroot;?>Registration/scan/<?php echo $documents[0]['token_no']; ?>/O" class="btn btn-success"><?php echo __('lblscan'); ?></a>  
                                            <?php } else if ($documents[0][$laststampflag] == 'Y' && $documents[0]['document_scan_flag']=='Y'){ ?>
                                               <a href="" class="btn btn-success"><?php echo __('lbldownload'); ?></a>    
                            <?php }else{
                                ?>
                                 <button type="button"  class="btn btn-default disabled"><?php echo __('lblscan'); ?></button>               
                                               <?php
                            }?>
 
 
                                    </td>
                                </tr> 
                            <?php } ?>
                        </tbody>
                    </table> 

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });

</script>

