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


<?php echo $this->Form->create('rpt_income_tax_annexxure1', array('id' => 'rpt_income_tax_annexxure1', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Income Tax Report (ANNEXXURE-I)</h3></center>
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
        if (!empty($records)) {
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
                                    <th class="center"><?php echo __('Transaction Date'); ?></th>
                                    <th class="center"><?php echo __('Transaction Identity'); ?></th>
                                    <th class="center"><?php echo __('Transaction Type'); ?></th>
                                    <th class="center"><?php echo __('Transaction Amount'); ?></th>
                                    <th class="center"><?php echo __('Property Type'); ?></th>
                                    <th class="center"><?php echo __('Whether property is within municipal limits'); ?></th>
                                    <th class="center"><?php echo __('Property Address'); ?></th>
                                    <th class="center"><?php echo __('City/Town'); ?></th>
                                    <th class="center"><?php echo __('Postal Code'); ?></th>
                                    <th class="center"><?php echo __('State Code'); ?></th>
                                    <th class="center"><?php echo __('Country Code'); ?></th>
                                    <th class="center"><?php echo __('Stamp Value'); ?></th>
                                    <th class="center"><?php echo __('Remarks'); ?></th>
                                    <th class="center"><?php echo __('Transaction Relation(PC)'); ?></th>
                                    <th class="center"><?php echo __('Transaction Amount Related to the person(PC)'); ?></th>
                                    <th class="center"><?php echo __('Person Name'); ?></th>
                                    <th class="center"><?php echo __('Person Type(PC)'); ?></th>
                                    <th class="center"><?php echo __('Gender(PC)'); ?></th>
                                    <th class="center"><?php echo __('PAN (PC)'); ?></th>
                                    <th class="center"><?php echo __('Aadhaar Number(PC)'); ?></th>
                                    <th class="center"><?php echo __('Form 60 Acknowledgement(PC)'); ?></th>
                                    <th class="center"><?php echo __('Identification Type(PC)'); ?></th>
                                    <th class="center"><?php echo __('Identification Number(PC)'); ?></th>
                                    <th class="center"><?php echo __('Date of Birth/Incorporation(PC)'); ?></th>
                                    <th class="center"><?php echo __('Nationality/Country of Incorporation(PC)'); ?></th>
                                    <th class="center"><?php echo __('Address of Person(PC-L)'); ?></th>
                                    <th class="center"><?php echo __('City/Town(PC-L)'); ?></th>
                                    <th class="center"><?php echo __('Pin Code(PC-L)'); ?></th>
                                    <th class="center"><?php echo __('State(PC-L)'); ?></th>
                                    <th class="center"><?php echo __('Country(PC-L)'); ?></th>
                                    <th class="center"><?php echo __('Primary STD Code(PC)'); ?></th>
                                    <th class="center"><?php echo __('Primary Phone Number(PC)'); ?></th>
                                    <th class="center"><?php echo __('Primary Mobile Number(PC)'); ?></th>
                                    <th class="center"><?php echo __('Secondary STD Code(PC)'); ?></th>
                                    <th class="center"><?php echo __('Secondary Phone Number(PC)'); ?></th>
                                    <th class="center"><?php echo __('Secondary Mobile Number(PC)'); ?></th>
                                    <th class="center"><?php echo __('Email(PC)'); ?></th>
                                    <th class="center"><?php echo __('ITDREIN of the SRO'); ?></th>
                                    <th class="center"><?php echo __('SRO office'); ?></th>
                                    <th class="center"><?php echo __('Name of SRO'); ?></th>
                                    <th class="center"><?php echo __('SRO Mobile'); ?></th>
                                    <th class="center"><?php echo __('SRO Address'); ?></th>
                                    <th class="center"><?php echo __('[State code + Previous Regn. No]'); ?></th>
                                </tr>  
                            </thead>

                            <tbody>
                                <?php
                                foreach ($records as $records1):
//                                    pr($records1);

                                    $key = "";
                                    $final_strv = (hex2bin(trim($records1[0]['uid'])));
                                    $dec = openssl_decrypt($final_strv, 'bf-ecb', $key, true);
                                    
                                    ?>
                                    <tr>
                                        <td ><?php echo $records1[0]['doc_reg_no']; ?></td>
                                        <td ><?php echo $records1[0]['final_doc_reg_no']; ?></td>
                                        <td ><?php echo $records1[0]['final_stamp_date']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['article_desc_en']; ?></td>
                                        <td ><?php
                            $trans_amt = max($records1[0]['consamt'], $records1[0]['valamt']);
                            echo $trans_amt;
                                    ?></td>
                                        <td ><?php echo $records1[0]['proptype']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['propaddress']; ?></td>
                                        <td ><?php echo $records1[0]['city']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['state_code']; ?></td>
                                        <td ><?php echo $records1[0]['country_code']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['sdfee']; ?></td>
                                        <td ><?php echo $records1[0]['party_full_name_en']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['gender_desc_en']; ?></td>

                                        <td ><?php echo $records1[0]['pan_no']; ?></td>
                                        <td ><?php echo $dec; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['identificationtype_desc_en']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['dob']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['partyaddress']; ?></td>

                                        <td ><?php echo $records1[0]['partycity']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['p_mobile']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['email_id']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['office_name_en']; ?></td>
                                        <td ><?php echo ''; ?></td>
                                        <td ><?php echo $records1[0]['officc_contact_no']; ?></td>
                                        <td ><?php echo $records1[0]['sroaddress']; ?></td>
                                        <td ><?php echo ''; ?></td>
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
                name: "income_tax_annexxure1",
                filename: "income_tax_annexxure1" //do not include extension
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

