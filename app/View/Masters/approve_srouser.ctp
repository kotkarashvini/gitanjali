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
        //alert(id);exit; // 2719
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>Masters/approve_sro",
            data: {'id': id, 'csrftoken': csrftoken},
            success: function (data) {

                if (data == 1)
                {
                    alert('SRO User Activated.');
                    window.location.reload();
                    return false;
                }
                else {
                    alert('SRO User is not Activated.Please try again.');
                }
            }
        });

    }

    function deactivate(id)
    {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>Masters/reject_sro",
            data: {'id': id, 'csrftoken': csrftoken},
            success: function (data) {
                if (data == 1)
                {
                    alert('SRO User Deactivated.');
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
                <center><h3 class="box-title headbolder"><?php echo __('Activate/Deactivate SRO Users'); ?></h3></center>
            </div>
            <div class="box-body">
                <table id="myTable" class="table table-striped table-bordered table-hover">  
                    <thead>  
                        <tr> 
                            <th class="center"><?php echo __('lblsrno'); ?></th>
                            <th class="center"><?php echo __('lblusername'); ?></th>  
                            <th class="center"><?php echo __('lblmobileno'); ?></th> 
                            <th class="center"><?php echo __('lblemailid'); ?></th> 
                            <th class="center"><?php echo __('lblcreationdate'); ?></th>
<!--                            <th class="center"><?php// echo __('lblselofc'); ?></th> -->
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>  
                    <?php 
                    $i=1;
                    foreach ($usrdata as $data) {
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $data[0]['full_name']; ?></td>
                            <td><?php echo $data[0]['mobile_no'] ?></td>
                            <td><?php echo $data[0]['email_id'] ?></td>
                            <?php if($data[0]['created'] != '') :?>
                            <td><?php echo date("d-m-Y", strtotime($data[0]['created']));else:?></td> <td>&nbsp;</td><?php endif?>
<!--                            <td><div class="col-sm-6 control-label" > <?php //echo $this->Form->input('office_id', array('options' => array(), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm chosen-select', 'options' => array($office), 'label' => false)); ?>
                                    <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];?></span>
                                </div>
                            </td>-->
                            <td>
                                <?php if ($data[0]['activeflag'] == 'N') { ?>
                                    <button id="active" class="btn btn-primary" onClick="activate(<?php echo $data[0]['id'] ?> );">Activate</button>
                                <?php } else { ?>
                                    <button id="deactive" class="btn btn-primary" onClick="deactivate(<?php echo $data[0]['id'] ?>);">Deactivate</button>
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

