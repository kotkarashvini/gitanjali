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


<?php echo $this->Form->create('rpt_income_tax_sro', array('id' => 'rpt_income_tax_sro', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Income Tax</h3></center>
            </div>
            <div class="box-body">
                 
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
        if (!empty($itax)) {
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div id="selectdocument" class="table-responsive" >
                        <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead>  
                                <tr>  
                                    <th class="center"><?php echo __('Token No.'); ?></th>
                                    <th class="center"><?php echo __('Party Type'); ?></th>
                                    <th class="center"><?php echo __('Party Name'); ?></th>
                                    <th class="center"><?php echo __('Party Father Name'); ?></th>
                                    <th class="center"><?php echo __('Party Address'); ?></th>
                                    <th class="center"><?php echo __('PAN No.'); ?></th>
                                    <th class="center"><?php echo __('Property Type'); ?></th>
                                    <th class="center"><?php echo __('Village Name'); ?></th>
                                    <th class="center"><?php echo __('Road Vicinity'); ?></th>
                                    <th class="center"><?php echo __('Level1 Desc. Name'); ?></th>
                                    <th class="center"><?php echo __('Construction Type'); ?></th>
                                    <th class="center"><?php echo __('REGD.ST Value'); ?></th>
                                    <th class="center"><?php echo __('REGD Value'); ?></th>
                                    <th class="center"><?php echo __('Deed No.'); ?></th>
                                    <th class="center"><?php echo __('DOE'); ?></th>
                                    <th class="center"><?php echo __('Property Address'); ?></th>
                                </tr>  
                            </thead>

                            <tbody>
                                <?php
                                foreach ($itax as $records1):
                                    ?>
                                    <tr>
                                        <td ><?php echo $records1[0]['token_no']; ?></td>
                                        <td ><?php echo $records1[0]['party_type_desc_en']; ?></td>
                                        <td ><?php echo $records1[0]['party_full_name_en']; ?></td>
                                        <td ><?php echo $records1[0]['father_full_name_en']; ?></td>
                                        <td ><?php echo $records1[0]['address_en']; ?></td>
                                        <td ><?php echo $records1[0]['pan_no']; ?></td>
                                        <td ><?php echo $records1[0]['usage_sub_catg_desc_en']; ?></td>
                                        <td ><?php echo $records1[0]['village_name_en']; ?></td>
                                        <td ><?php echo $records1[0]['list_1_desc_en']; ?></td>
                                        <td ><?php echo $records1[0]['level_1_desc_en']; ?></td>
                                        <td ><?php echo $records1[0]['construction_type_desc_en']; ?></td>
                                        <td ><?php echo $records1[0]['regd_st_val']; ?></td>
                                        <td ><?php echo $records1[0]['regd_value']; ?></td>
                                        <td ><?php echo $records1[0]['final_doc_reg_no']; ?></td>
                                        <td ><?php echo date('d-M-Y', strtotime($records1[0]['final_stamp_date']));?></td>
                                        <td ><?php echo $records1[0]['prop_address']; ?></td>
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

