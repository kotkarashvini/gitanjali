<script type="text/javascript">
    $(document).ready(function () {

        $('#officeid').hide();
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

        $('#to').change(function () {
            var from = $("#from").val();
            var to = $("#to").val();

            if (Date.parse(from) > Date.parse(to)) {
                alert("Invalid Date Range");
                $('#to').val('');
                return false;
            }
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
        height:380px;
    }
</style>

<?php
echo $this->Form->create('rpt_fee_register_officewise', array('id' => 'rpt_fee_register_officewise', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class="box-title headbolder"> <?php echo __('JARS Figuers For All Districts'); ?> </h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="fees" class="control-label col-sm-2"><?php echo __('Get Record By Date:'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                                                  ?></span>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                        <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];                                                        ?></span>
                    </div>
                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 
            </div>
        </div>
        <?php
        if (!empty($fee_book)) {
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div>
                        <center><h4 class = "box-title headbolder"><?php
                                echo __('JARS Figures Between ');
                                echo $from;
                                ?><?php
                                echo __(' To ');
                                echo $to;
                                ?></h4></center>
                    </div>
                    <div id="selectdocument" class="table-responsive">
                        <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                            <thead class="center">  
                                <tr >  
                                    <th><?php echo __('District'); ?></th>
                                    <th><?php echo __('Stamp Collected in Rs.'); ?></th>
                                    <th><?php echo __('Fees Collected in Rs.'); ?></th>
                                    <th><?php echo __('SP Fee Collected in Rs.'); ?></th>
                                    <th><?php echo __('Total(Stamp+Fees+SP)'); ?></th>
                                    <th><?php echo __('Total Deeds'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php foreach ($fee_book as $rec): ?>
                                    <tr>
                                        <td ><?php echo $rec[0]['office_name_en']; ?></td>
                                        <td ><?php echo $rec[0]['sd']; ?></td>
                                        <td ><?php echo $rec[0]['a1']; ?></td>
                                        <td ><?php echo $rec[0]['sp']; ?></td>
                                        <td ><?php
                                            $total = $rec[0]['sd'] + $rec[0]['a1'] + $rec[0]['sp'];
                                            echo $total;
                                            $t[] = $total;
                                            ?></td>
                                        <td ><?php echo $rec[0]['token']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><?php echo __('<b>Total</b>'); ?></td>

                                    <td> <?php
                                        $stamp = array();
                                        foreach ($fee_book as $data) {
                                            $row = $data[0]['sd'];
                                            $stamp[] = $row;
                                        }
                                        $rs = "&#8377;";
                                        echo '<b>' .$rs . array_sum($stamp). '</b>';
                                        ?> </td>

                                    <td><?php
                                        $a1 = array();
                                        foreach ($fee_book as $data) {
                                            $row = $data[0]['a1'];
                                            $a1[] = $row;
                                        }
                                        $rs = "&#8377;";
                                        echo '<b>' .$rs . array_sum($a1). '</b>';
                                        ?></td>

                                    <td><?php
                                        $spfee = array();
                                        foreach ($fee_book as $data1) {
                                            $r = $data1[0]['sp'];
                                            $spfee[] = $r;
                                        }
                                        $rs = "&#8377;";
                                        echo '<b>'.$rs . array_sum($spfee). '</b>';
                                        ?></td>

                                    <td><?php
                                        $rs = "&#8377;";
                                        echo '<b>'.$rs . array_sum($t). '</b>';
                                        ?></td>

                                    <td>  <?php
                                        $deed = array();
                                        foreach ($fee_book as $data1) {
                                            $d = $data1[0]['token'];
                                            $deed[] = $d;
                                        }
                                        echo '<b>'. array_sum($deed). '</b>';
                                        ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="box-body" id="frebate">

                        <label for="" class="col-sm-3 control-label"><b><?php echo __('Total Stamp Collection'); ?></b>:<?php
                            $stamp = array();
                            foreach ($fee_book as $data) {
                                $row = $data[0]['sd'];
                                $stamp[] = $row;
                            }
                            $rs = "&#8377;";
                            echo $rs . array_sum($stamp);
                            ?></label>

                        <label for="" class="col-sm-3"><b><?php echo __('Total Fees Collection'); ?></b>:<?php
                            $a1 = array();
                            foreach ($fee_book as $data) {
                                $row = $data[0]['a1'];
                                $a1[] = $row;
                            }
                            $rs = "&#8377;";
                            echo $rs . array_sum($a1);
                            ?></label>

                        <label for="" class="col-sm-3 control-label"><b><?php echo __('Total SP Fees Collection'); ?></b>:<?php
                            $spfee = array();
                            foreach ($fee_book as $data1) {
                                $r = $data1[0]['sp'];
                                $spfee[] = $r;
                            }
                            $rs = "&#8377;";
                            echo $rs . array_sum($spfee);
                            ?></label>

                        <label for="" class="col-sm-3 control-label"><b><?php echo __('Total Stamp+Fees+SP Fees'); ?></b>:<?php
                            $rs = "&#8377;";
                            echo $rs . array_sum($t);
                            ?></label>
                        <label for="" class="col-sm-3 control-label"><b><?php echo __('Total Deeds Registerd'); ?></b>:<?php
                            $deed = array();
                            foreach ($fee_book as $data1) {
                                $d = $data1[0]['token'];
                                $deed[] = $d;
                            }
                            echo array_sum($deed);
                            ?></label>
                    </div>

                    <div  class="rowht"></div>  <div  class="rowht"></div>
                    <div class="row" style="text-align: center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i>Downloading">Export To Excel</button>
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
            setTimeout(function () {
                $this.button('reset');
            }, 1000);
        });
    });

</script>
<?php echo $this->Form->end(); ?>