<?php
echo $this->element("Registration/main_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreprintdocsummeryrpt'); ?></h3></center>
            </div>
    <div class="box-body">
        <?php echo $this->Form->create('reprint_summary_report', array('class' => 'form-inline')); ?>
        <!--<form class="form-inline" >-->  
        <div class="form-group">
            <label for="email"><?php echo __('lblenterdocregno'); ?></label>
            <?php echo $this->Form->input('document_number', array('class' => 'form-control', 'label' => false, 'div' => FALSE)); ?>
            <!--<input type="   email" class="form-control" id="email">-->
        </div> 
        <button type="submit" class="btn btn-default"><?php echo __('lblview'); ?></button>
        <!--</form>-->
        <?php echo $this->Form->end(); ?>
    </div>  
    <?php if (isset($result)) { ?>

        <div class="panel-footer">

            <?php
            if (empty($result) || !empty($check_release)) {
                echo 'No Data Found';
            } else {

                $result = $result[0][0];
//pr();
                ?>


                <table id="mytable" class="table table-bordred table-striped">

                    <thead>
                    <th><?php echo __('lbldocrno'); ?></th>
                    <th><?php echo __('lblArticle'); ?></th>
                    <th><?php echo __('lblpresenter'); ?></th>           
                    <th><?php echo __('lblregdate'); ?></th>

                    <th><?php echo __('lblaction'); ?></th> 

                    </thead>
                    <tbody>

                        <tr>
                            <td><?php echo $result['doc_reg_no']; ?></td>
                            <td><?php echo $result['article_desc_en']; ?></td>
                            <td><?php echo $result['party_full_name_en']; ?></td>
                            <td><?php
                                $date = date_create($result['doc_reg_date']);
                                echo date_format($date, 'd M Y h:s:i a');
                                ?></td> 
                            <td><a href="#"><?php echo __('lbldownload'); ?></a> </td>
                        </tr>
                    </tbody>
                </table>



            <?php } ?>
        </div>

    <?php } ?>  

        </div>
    </div>
</div>

