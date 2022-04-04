<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<script type="text/javascript">

    $(document).ready(function () {
        if (!navigator.onLine)
        {
            // document.body.innerHTML = 'Loading...';
            // window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward()
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }
        $('#myTable').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, -1], [5, 10, "All"]]
        });

    });

    function activate(id)
    {
       
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>Masters/approve_deed_writer",
            data: {'id': id, 'csrftoken': csrftoken},
            success: function (data) {
                
                if (data == 1)
                {
                    alert('Deed writer is Accepted.');
                    window.location.reload();
                    return false;
                }
                else {
                    alert('Deed writer is not Accepted.Please try again.');
                }
            }
        });

    }

    function deactivate(id)
    {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>Masters/reject_deed_writer",
            data: {'id': id, 'csrftoken': csrftoken},
            success: function (data) {
                if (data == 1)
                {
                    alert('Deed writer is Rejected.');
                    window.location.reload();
                    return false;
                }
               
            }
        });

    }



</script>


<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblalldeedwriter'); ?></h3></center>
            </div>
            <div class="box-body">
                <table id="myTable" class="table table-striped table-bordered table-hover">  
                    <thead>  
                        <tr> 
                            <th class="center"><?php echo __('lblusername'); ?></th>  
                          
                            <th class="center"><?php echo __('lblmobileno'); ?></th> 
                            <th class="center"><?php echo __('lblemailid'); ?></th> 
                            <th class="center"><?php echo __('lblcreationdate'); ?></th>
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>  
                    <?php for ($i = 0; $i < count($usrdata); $i++) {
                        ?>
                        <tr>

                            <td><?php echo $usrdata[$i][0]['full_name']; ?></td>
                          
                            <td><?php echo $usrdata[$i][0]['mobile_no'] ?></td>
                            <td><?php echo $usrdata[$i][0]['email_id'] ?></td>
                            <td><?php echo date("d-m-Y", strtotime($usrdata[$i][0]['created'])); ?></td>
                            <td>
                                <?php if ($usrdata[$i][0]['deed_write_accept_flag'] == 'N') { ?>
                                    <button id="active" class="btn btn-primary" onClick="activate(<?php echo $usrdata[$i][0]['id'] ?>);">Accept</button>
                                <?php } else { ?>
                                    <button id="deactive" class="btn btn-primary" onClick="deactivate(<?php echo $usrdata[$i][0]['id'] ?>);">Reject</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<script language="JavaScript" type="text/javascript">
    var message = "Right Click Not Allowed";
    function rtclickcheck(keyp)
    {
        if (navigator.appName == "Netscape" && keyp.which == 3)
        {
            alert(message);
            return false;
        }
        if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2)
        {
            alert(message);
            return false;
        }
    }
    document.onmousedown = rtclickcheck;
</script>

