<script>
    $(document).ready(function () {

        $('#fromyear').change(function () {
            var fromyear = $("#fromyear option:selected").text();
            $('#hffromyear').val(fromyear);
        });

        $('#toyear').change(function () {
            var fromyear = $("#fromyear option:selected").text();
            var toyear = $("#toyear option:selected").text();
            if (fromyear > toyear) {
                alert("Please Check Year...!!!!");
                $('#toyear').val('');
                return false;
            } else {
                $('#hftoyear').val(toyear);
            }
        });

    });
</script>
<?php echo $this->Form->create('searchindex', array('id' => 'search')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder">Application for Search</h3></center>
            </div>
            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('options' => array($District), 'empty' => '--select--', 'id' => 'district_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>

                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label">Date Of Application</label>
                        <label for="application_date" class="col-sm-3 control-label"><?php echo $todaydate; ?></label>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label">Name Of Applicant<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('applicant_name', array('label' => false, 'id' => 'applicant_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="applicant_name_error" class="form-error"><?php echo $errarr['applicant_name_error']; ?></span>
                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>

                        <label for="district_id" class="col-sm-2 control-label">Search For Years<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3" style="padding-left: 0px;">
                            <div class="col-sm-5">
                                <?php echo $this->Form->input('fromyear', array('options' => array($year), 'empty' => '--select--', 'id' => 'fromyear', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span  id="fromyear_error" class="form-error"><?php echo $errarr['fromyear_error']; ?></span>

                            </div>
                            <label for="district_id" class="col-sm-2 control-label" style="text-align: center;">To</label>
                            <div class="col-sm-5">
                                <?php echo $this->Form->input('toyear', array('options' => array($year), 'empty' => '--select--', 'id' => 'toyear', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span  id="toyear_error" class="form-error"><?php echo $errarr['toyear_error']; ?></span>

                            </div>
                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label">Email ID<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="email_id_error" class="form-error"><?php echo $errarr['email_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label">Phone<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>

                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('address_en', array('label' => false, 'id' => 'address_en', 'class' => 'form-control input-sm', 'type' => 'textarea')) ?>
                            <span  id="address_en_error" class="form-error"><?php echo $errarr['address_en_error']; ?></span>

                        </div>
                    </div>
                </div><br><br>
                <div class="row" style="text-align: center">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button id="btnnext" name="btnnext" class="btn btn-info" style="text-align: center;" type="submit">
                                Next </button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $hffromyear; ?>' name='hffromyear' id='hffromyear'/>
    <input type='hidden' value='<?php echo $hftoyear; ?>' name='hftoyear' id='hftoyear'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>