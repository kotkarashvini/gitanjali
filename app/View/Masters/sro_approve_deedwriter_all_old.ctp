


<script type="text/javascript">

    $(document).ready(function () {


        $('#office_id').change(function () {
            window.location.replace("<?php echo $this->webroot;?>Masters/sro_approve_deedwriter_all/" + $('#office_id').val());
        });

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

        $('#myTable1').dataTable({
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
                } else {
                    alert('Deed writer is not Activated.Please try again.');
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
                    alert('Deed writer is deactivated.');
                    window.location.reload();
                    return false;
                }

            }
        });

    }


    function activate_advocate(id)
    {

        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>Masters/approve_advocate",
            data: {'id': id, 'csrftoken': csrftoken},
            success: function (data) {

                if (data == 1)
                {
                    alert('Advocate is Activated.');
                    window.location.reload();
                    return false;
                } else {
                    alert('Advocate is not Activated.Please try again.');
                }
            }
        });

    }

    function deactivate_advocate(id)
    {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot; ?>Masters/reject_advocate",
            data: {'id': id, 'csrftoken': csrftoken},
            success: function (data) {
                if (data == 1)
                {
                    alert('Advocate is Deactivated');
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
                <center><h3 class="box-title headbolder"><?php echo __('User Approval'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="col-md-6">
                    <label><?php echo __('lblofclist'); ?></label>
                        <?php   echo $this->Form->input('office_id', array('id' => 'office_id', 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $office, 'empty' => '--Select--','default'=>$office_id));
                             ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('All Advocate List'); ?></h3></center>
            </div>
            <div class="box-body">
                <table id="myTable1" class="table table-striped table-bordered table-hover">  
                    <thead>  
                        <tr> 
                            <th class="center"><?php echo __('lblusername'); ?></th>  

                            <th class="center"><?php echo __('lblmobileno'); ?></th> 
                            <th class="center"><?php echo __('lblemailid'); ?></th> 
                            <th class="center"><?php echo __('lblcreationdate'); ?></th>
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>  
                    <?php for ($i = 0; $i < count($advocate); $i++) {
                        ?>
                    <tr>

                        <td><?php echo $advocate[$i][0]['full_name']; ?></td>

                        <td><?php echo $advocate[$i][0]['mobile_no'] ?></td>
                        <td><?php echo $advocate[$i][0]['email_id'] ?></td>
                        <td><?php echo date("d-m-Y", strtotime($advocate[$i][0]['created'])); ?></td>
                        <td>
                                <?php if ($advocate[$i][0]['is_advocate_accept_flag'] == 'N') { ?>
                            <button id="active" class="btn btn-primary" onClick="activate_advocate(<?php echo $advocate[$i][0]['id'] ?>);"><?php echo __('lblactivate');?></button>
                                <?php } else { ?>
                            <button id="deactive" class="btn btn-primary" onClick="deactivate_advocate(<?php echo $advocate[$i][0]['id'] ?>);"><?php echo __('lbldeactivate');?></button>
                                <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table> 
            </div>
        </div>
    </div>
</div>


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
                            <button id="active" class="btn btn-primary" onClick="activate(<?php echo $usrdata[$i][0]['id'] ?>);"><?php echo __('lblactivate');?></button>
                                <?php } else { ?>
                            <button id="deactive" class="btn btn-primary" onClick="deactivate(<?php echo $usrdata[$i][0]['id'] ?>);"><?php echo __('lbldeactivate');?></button>
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

