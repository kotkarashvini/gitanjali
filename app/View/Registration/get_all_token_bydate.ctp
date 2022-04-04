<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#name_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        
       
    });
    
     function formview(token_no) {
       
          $.post('<?php echo $this->webroot; ?>Registration/view_single_tokenstate', {token_no: token_no}, function (data)
            {
   
         
            $('#rpt_modal_body').html(data);
            $('#myModal_rpt').modal("show");

        });
        return false;

    }
</script>
<?php

    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblalltokenstatus'); ?></h3></center>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="name_list_tbl" class="table table-striped table-bordered table-hover">  
                            <thead >  
                                <tr>
                                    <th><?php echo __('lblsrno'); ?></th> 
                                    <th><?php echo __('lbltokenno'); ?></th>
                                     <th><?php echo __('lblaction'); ?> </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 0;
                                foreach ($alltoken as $token) {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo ++$counter; ?></th>
                                        <td><?php echo $token['trndocumentstatus']['token_no']; ?></td>
                                        
                                        <td> <input type="button" class="btn btn-primary" value="View" onclick="javascript: return formview('<?php echo $token['trndocumentstatus']['token_no']; ?>');"></td>
                                       
                                    </tr> 
                                <?php } ?>
                            </tbody>
                        </table> 

                    </div>
                </div>
            </div>
        </div>
    </div>


<div id="myModal_rpt" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Token Status'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

