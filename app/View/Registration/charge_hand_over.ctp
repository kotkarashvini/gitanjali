<?php echo $this->element("Helper/jqueryhelper"); ?>
<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<script>
    $("document").ready(function () {
        $("#doclist").dataTable();
        $('#from_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        $('#to_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        $('#curr_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });

        $('#from_office_id').change(function (e) {
            var from_office_id = $(this).val();
            if (from_office_id !== '')
            {
                $.postJSON('<?php echo $this->webroot; ?>Registration/office_user_list', {office_id: from_office_id}, function (data)
                {
                    var sc = '<option>--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#from_user_id option").remove();
                    $("#from_user_id").append(sc);
                });
            } else {
                var sc = '<option>--select--</option>';
                $("#from_user_id option").remove();
                $("#from_user_id").append(sc);
            }
        });

        $('#to_office_id').change(function (e) {
            var to_office_id = $(this).val();
            if (to_office_id !== '')
            {
                $.postJSON('<?php echo $this->webroot; ?>Registration/office_user_list', {office_id: to_office_id}, function (data)
                {
                    var sc = '<option>--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#to_user_id option").remove();
                    $("#to_user_id").append(sc);
                });
            } else {
                var sc = '<option>--select--</option>';
                $("#to_user_id option").remove();
                $("#to_user_id").append(sc);
            }
        });

    });
</script>



<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <?php echo $this->Form->create('charge_hand_over'); ?> 
            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblchargehandover'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Registration/charge_hand_over_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="from_office_id"><?php echo __('lblfromoffice'); ?></label>
                            <?php echo $this->Form->input('from_office_id', array('label' => false, 'id' => 'from_office_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $office))) ?>                         
                            <span class="form-error" id="from_office_id_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="from_user_id"><?php echo __('lblfromuser'); ?></label>
                            <?php echo $this->Form->input('from_user_id', array('label' => false, 'id' => 'from_user_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--',))) ?>                         
                            <span class="form-error" id="from_user_id_error"></span>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="to_office_id"><?php echo __('lbltooffice'); ?></label>
                            <?php echo $this->Form->input('to_office_id', array('label' => false, 'id' => 'to_office_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $office))) ?>                         
                            <span class="form-error" id="to_office_id_error"></span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="to_user_id"><?php echo __('lbltouser'); ?></label>
                            <?php echo $this->Form->input('to_user_id', array('label' => false, 'id' => 'to_user_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--',))) ?>                         
                            <span class="form-error" id="to_user_id_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="from_date"><?php echo __('lblfromdate'); ?></label>
                            <?php echo $this->Form->input('from_date', array('type' => 'text', 'id' => 'from_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
                            <span class="form-error" id="from_date_error"></span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="to_date"><?php echo __('lbltodate'); ?></label>
                            <?php echo $this->Form->input('to_date', array('type' => 'text', 'id' => 'to_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
                            <span class="form-error" id="to_date_error"></span>
                        </div> 
                    </div>  
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group center">
                            <button type="submit" class="btn btn-primary"><?php echo __('btnsubmit'); ?></button> 
                            <button type="reset" class="btn btn-primary"><?php echo __('lblreset'); ?></button> 
                        </div>
                    </div>
                </div> 

                <?php echo $this->Form->end(); ?>
            </div>
        </div>

        <div class="box box-primary">
            <?php echo $this->Form->create('charge_hand_over_search', array('class' => 'form-inline inline-search-form')); ?> 

            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblchargehandoverlist'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="form-group">
                            <label for="lbldate"><?php echo __('lblseldate'); ?></label>
                            <?php echo $this->Form->input('curr_date', array('id' => 'curr_date', 'class' => 'form-control', 'label' => false, 'div' => FALSE, 'readonly' => TRUE)); ?> 
                        </div>  
                        <div class="form-group">
                            <button type="submit" class=""><?php echo __('lblsearch'); ?></button>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="rowht"></div><div class="rowht"></div><div class="rowht"></div>
                    <table id="doclist" class="table table-bordred table-striped"> 
                        <thead>
                        <th><?php echo __('lblfromoffice'); ?></th>
                        <th><?php echo __('lblfromuser'); ?></th>
                        <th><?php echo __('lbltoffice'); ?></th> 
                        <th><?php echo __('lbltouser'); ?></th>
                        <th><?php echo __('lblfromdate'); ?></th>
                        <th><?php echo __('lbltodate'); ?></th>
                        <th><?php echo __('lblaction'); ?></th>  
                        </thead>
                        <tbody>
                            <?php
                            if (isset($ChargeHandOver)) {
                                foreach ($ChargeHandOver as $record) {
                                    $record = $record[0];
                                    ?>
                                    <tr>
                                        <td><?php echo $record['from_office']; ?></td>
                                        <td><?php echo $record['from_user']; ?></td>
                                        <td><?php echo $record['to_office']; ?></td>
                                        <td><?php echo $record['to_user']; ?></td>
                                        <td><?php
                                            $date = date_create($record['from_date']);
                                            echo date_format($date, 'd M Y');
                                            ?></td>
                                        <td><?php
                                            $date = date_create($record['to_date']);
                                            echo date_format($date, 'd M Y');
                                            ?></td>
                                        <td>
                                            <?php
                                            echo $this->Html->link(__('lblbtndelete'), array('controller' => 'Registration', 'action' => 'charge_hand_over', $record['hand_over_id']), array('class' => 'btn btn-danger', 'escape' => false));
                                            ?>

                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


