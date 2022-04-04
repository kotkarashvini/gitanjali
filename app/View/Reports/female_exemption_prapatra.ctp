<script type="text/javascript">
    $(document).ready(function () {

        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#to').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
        
         $('#tabledoc').dataTable({
            "bPaginate": false,
            "ordering": false
        });

    });

</script>
<style>
    .table-responsive
    {
        overflow-y:auto;
        height:400px;
    }
</style>
<?php
echo $this->Form->create('female_exemption_prapatra', array('id' => 'female_exemption_prapatra', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder">Female Exemption Details</h3></center>
            </div>
            <div class="box-body">
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                              ?></span>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                        <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];                                    ?></span>
                    </div>

                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="dwnfrmt" class="control-label col-sm-2"><?php echo __('Download Format'); ?>:<span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('dwnfrmt', array('options' => array($dwnfrmt), 'empty' => '--select--', 'id' => 'dwnfrmt', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="dwnfrmt_error" class="form-error"><?php //echo $errarr['dwnfrmt_error'];         ?></span>
                    </div>
                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit" onclick="javascript: return formadd();"> <?php echo __('lblsearch'); ?> </button></div>
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div> 

            </div>
        </div>

        <?php
        if (!empty($female_exem)) {
            ?>
            <div class="box box-primary">
                <div class="box-body">
<!--                    <div>
                        <center><h4 class = "box-title headbolder"><?php //echo __('Female Exemption Details '); ?></h4></center>
                    </div>-->
                    <div id="selectdocument" class="table-responsive">
                        <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead class="center">  
                                <tr >  
                                    <th><?php echo __('Sr.No.'); ?></th>
                                    <th><?php echo __('Name of Anchal'); ?></th>
                                    <th><?php echo __('Beneficiary Name'); ?></th>
                                    <th><?php echo __('Father/Husband Name'); ?></th>
                                    <th><?php echo __('Address'); ?></th>
                                    <th><?php echo __('Mobile No.'); ?></th>
                                    <th><?php echo __('Aadhar No.'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php
                                $SrNo = 1;

                                foreach ($female_exem as $rec):
                                    $ex = explode(',', $rec[0]['partyuid']);
                                    $mobile = explode(',', $rec[0]['partymobile']);

                                    $key = "";
                                    $final_strv = (hex2bin(trim($ex[0])));
                                    $dec = openssl_decrypt($final_strv, 'bf-ecb', $key, true);
                                    ?>
                                    <tr>
                                        <td ><?php echo $SrNo++; ?></td>
                                        <td ><?php echo $rec[0]['office_name_en']; ?></td>
                                        <td ><?php echo $rec[0]['partyname']; ?></td>
                                        <td ><?php echo $rec[0]['partyfathername']; ?></td>
                                        <td ><?php echo $rec[0]['partyaddress']; ?></td>
                                        <td ><?php echo $mobile[0]; ?></td>
                                        <td ><?php echo $dec; ?></td>
                                    </tr>
                                <?php endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div  class="rowht"></div>  <div  class="rowht"></div>
                    <div class="row" style="text-align: center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i>Downloading">Export To Excel</button>
                                <!--<button type="button" class="btn btn-primary" onclick="javascript: return formadd();">PDF</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<script>
    $("document").ready(function () {
        excel = new ExcelGen({
            "src_id": "tabledoc",
            "show_header": "true"
        });

        $('#load').on('click', function () {
            excel.generate();
            var $this = $(this);
            $this.button('loading');
            setTimeout(function () { $this.button('reset');}, 1000);
        });
    });
</script>
<?php echo $this->Form->end(); ?>