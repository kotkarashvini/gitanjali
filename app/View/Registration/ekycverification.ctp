<?php if($ret == 'Y') { ?>
<style>
    .MyModel100{

        width:100%;
    }

    .MyModel80{

        width:80%;
    }
    .Margin{
        margin-right:-199px;
    }
</style>
<?php echo $this->Form->create('party', array('id' => 'party', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary"> 
            <div class="box-header with-border">
                <center><h3 class="box-title">EKYC Web Service Response</h3></center>
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="uid" class="col-sm-4 control-label"><b>UID no : </b></label>
                                <div class="col-sm-4"><?php echo $uid; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name" class="col-sm-4 control-label"><b>Name : </b></label>
                                <div class="col-sm-8"><?php echo $name; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name" class="col-sm-4 control-label"><b>DOB : </b></label>
                                <div class="col-sm-4"><?php echo $dob; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name" class="col-sm-4 control-label"><b>Gender : </b></label>
                                <div class="col-sm-8"><?php echo $gender; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name" class="col-sm-4 control-label"><b>Address : </b></label>
                                <div class="col-sm-8"><?php echo $add; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name" class="col-sm-4 control-label"><b>S/O : </b></label>
                                <div class="col-sm-8"><?php echo $co; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <img id="blah" src="data:image/bmp;base64,<?php echo $Pht; ?>" alt="" width="150" height="150"/>
                    </div>
                    <div class="col-sm-2" style="height: 5px;">&nbsp;</div>
                </div>
            </div>
        </div>
         <input type='hidden' value='<?php echo $hfverificationid; ?>' name='hfverificationid' id='hfverificationid'/>
    </div>
</div>

</div><?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
 <?php }?>















