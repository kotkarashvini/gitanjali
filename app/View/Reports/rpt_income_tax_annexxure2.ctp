<script>
    $(document).ready(function () {
        $('#from').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
        $('#to').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#tabledoc').dataTable({
            "bPaginate": false,
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


<?php echo $this->Form->create('rpt_income_tax_annexxure2', array('id' => 'rpt_income_tax_annexxure2', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Income Tax Report (ANNEXXURE-II)</h3></center>
            </div>
            <div class="box-body">
                <div class="row" id="divDate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('Select Office'); ?> :-</label>   
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('type' => 'select', 'empty' => '--All Offices--', 'options' => $officelist, 'label' => false, 'multiple' => false, 'id' => 'office_id', 'class' => 'form-control input-sm')); ?>
                        </div>
                    </div>     
                </div>

                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?></div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?></div>
                    <div class="row">
                        <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('Submit'); ?> </button></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6"> <?php echo $this->Form->input('report_flag', array('type' => 'hidden', 'value' => 'H', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'listflag')); ?></div>
                </div> 
            </div>

        </div> 
        <?php
        if (!empty($records2)) {
//             pr($records);exit;
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div id="selectdocument" class="table-responsive" >
                        <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>  
                                <tr>  
                                    <th class="center"><?php echo __('Report Serial No.'); ?></th>
                                    <th class="center"><?php echo __('Original Report Serial No.'); ?></th>
                                    <th class="center"><?php echo __('Customer ID'); ?></th>
                                    <th class="center"><?php echo __('Person Name'); ?></th>
                                    <th class="center"><?php echo __('Date of Birth/Incorporation(PC)'); ?></th>
                                    <th class="center"><?php echo __('Fathers Name'); ?></th>
                                    <th class="center"><?php echo __('PAN/Acknowledgement No.'); ?></th>
                                    <th class="center"><?php echo __('Aadhaar Number'); ?></th>
                                    <th class="center"><?php echo __('Identification Type'); ?></th>
                                    <th class="center"><?php echo __('Identification Number'); ?></th>
                                    <th class="center"><?php echo __('Flat/Door/Building'); ?></th>
                                    <th class="center"><?php echo __('Name of Permises/Building/Village'); ?></th>
                                    <th class="center"><?php echo __('Road/Street'); ?></th>
                                    <th class="center"><?php echo __('Area/Locality'); ?></th>
                                    <th class="center"><?php echo __('City/Town'); ?></th>
                                    <th class="center"><?php echo __('State Code'); ?></th>
                                    <th class="center"><?php echo __('Country Code'); ?></th>
                                    <th class="center"><?php echo __('Postal Code/ Zip Code'); ?></th>
                                    <th class="center"><?php echo __('Mobile/Telephone No.'); ?></th>
                                    <th class="center"><?php echo __('Estimated Agriculture income'); ?></th>
                                    <th class="center"><?php echo __('Estimated Non-Agriculture income'); ?></th>
                                    <th class="center"><?php echo __('Remarks'); ?></th>
                                    <th class="center"><?php echo __('Form 60 Acknowledgement Number'); ?></th>
                                    <th class="center"><?php echo __('Transcation Date'); ?></th>
                                    <th class="center"><?php echo __('Transcation ID'); ?></th>
                                    <th class="center"><?php echo __('Transcation Type'); ?></th>
                                    <th class="center"><?php echo __('Transcation Amount'); ?></th>
                                    <th class="center"><?php echo __('Transcation Mode'); ?></th>
                                    <th class="center"><?php echo __('ITDREIN of the SRO'); ?></th>
                                    <th class="center"><?php echo __('SRO office'); ?></th>
                                    <th class="center"><?php echo __('Name of SRO'); ?></th>
                                    <th class="center"><?php echo __('SRO Mobile'); ?></th>
                                    <th class="center"><?php echo __('SRO Address'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php
                                foreach ($records2 as $rec):
                                    $key = "";
                                    $final_strv = (hex2bin(trim($rec[0]['uid'])));
                                    $dec = openssl_decrypt($final_strv, 'bf-ecb', $key, true);
                                    ?>
                                    <tr>
                                        <td ><?php echo $rec[0]['doc_reg_no']; ?></td>
                                        <td ><?php echo $rec[0]['final_doc_reg_no']; ?></td>
                                        <td ><?php echo $rec[0]['token_no']; ?></td>
                                        <td ><?php echo $rec[0]['party_full_name_en']; ?></td>
                                        <td ><?php echo $rec[0]['dob']; ?></td>
                                        <td ><?php echo $rec[0]['father_full_name_en']; ?></td>
                                        <td ><?php echo $rec[0]['pan_no']; ?></td>
                                        <td ><?php echo $dec; ?></td>
                                        <td ><?php echo $rec[0]['identificationtype_desc_en']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $rec[0]['sro_address']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $rec[0]['mobile_no']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $rec[0]['transaction_date']; ?></td>
                                        <td ><?php echo $rec[0]['transaction_id']; ?></td>
                                        <td ><?php echo $rec[0]['transaction_type']; ?></td>
                                        <td ><?php 
                                    $trans_amt = max($rec[0]['consamt'], $rec[0]['valamt']);
                                    echo $trans_amt;
                                    ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $rec[0]['itdrein_number']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $rec[0]['name_of_sro']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $rec[0]['sro_address']; ?></td>
                                    </tr>
    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div  class="rowht"></div>  <div  class="rowht"></div>
                    <div class="row" style="text-align: center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-success" id="excelsheet"><?php echo __('Export To Excel'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php } ?>
    </div> 
</div>
<!--<script>
    $(document).ready(function () {
        var table = $('#tabledoc').DataTable();
        $('#excelsheet').on('click', function () {
            $('<table>').append(table.$('th,tr').clone()).table2excel({
                exclude: ".excludeThisClass",
                name: "income_tax_sheet",
                filename: "income_tax_sheet" //do not include extension
            });
        });
    });
</script>-->


<script>
    $("document").ready(function () {
        excel = new ExcelGen({
            "src_id": "tabledoc",
            "show_header": "true"
        });

        $("#excelsheet").click(function () {
            excel.generate();
        });
    });
</script>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

