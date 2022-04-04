
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblinprocess'); ?></h3></center>
                <div class="box-tools pull-right">
                    <!--<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><?php echo __('lblstamphierarchy'); ?></button>-->
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive"> 

                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lbldocktype'); ?></th>
                                <th><?php echo __('Mutation Status'); ?></th>
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            if (isset($result)) {
                                foreach ($result as $documents) {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo ++$counter; ?></th>
                                        <td><?php echo $documents[0]['token_no']; ?></td>
                                        <td><?php echo $documents[0]['article_desc_en']; ?></td>

                                        <td>
<?php echo $documents[0]['mutation_flag']; ?>
                                        </td>
                                        <td>  
                                            <a href="<?php echo $this->webroot; ?>GAWebService/mutation_manually/<?php echo $documents[0]['token_no']; ?>" class="btn btn-primary"><?php echo __('lblSelect'); ?></a>
                                        </td>
                                    </tr> 
                                <?php }
                            }
                            ?>
                        </tbody>
                    </table> 

                </div>
            </div>
        </div>
    </div>
</div>

