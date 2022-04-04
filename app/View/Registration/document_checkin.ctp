<?php
echo $this->element("Registration/main_menu");
?>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbldockcheckin'); ?></h3></center>
            </div>
            <div class="box-body">
                <table class="table" id="Doclist">
                    <thead>
                        <tr>
                            <th><?php echo __('lblsrno'); ?></th> 
                            <th><?php echo __('lbltokenno'); ?></th>
                            <th><?php echo __('lblarticlename'); ?></th>
                            <th><?php echo __('lblcitizenname'); ?></th>
                            <th><?php echo __('lblstatus'); ?></th>

                            <th><?php echo __('lblaction'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        foreach ($documents as $document) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo ++$counter; ?></th>
                                <td><?php echo $document[0]['token_no']; ?></td>
                                <td><?php echo $document[0]['article_desc_en']; ?></td>
                                <td><?php //echo $document[0]['token_no'];   ?></td>
                                <td> <?php echo $document[0]['check_in_flag']; ?></td>
                                <td>
                                    <?php if ($document[0]['check_in_flag'] == 'N') {
                                        ?>
                                        <a href="<?php echo $this->webroot; ?>Registration/document_checkin/<?php echo $document[0]['token_no']; ?>/Y" class="btn btn-primary"><?php echo __('Check In'); ?></a>
                                    <?php } ?>
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