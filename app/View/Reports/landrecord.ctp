<script type='text/javascript'>
    $(document).ready(function () {
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
    });

</script>
<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#btnprint').on('click', function () {
            $("#rptdata").removeClass("scrollbar");
            $.print("#rptdata");
            $("#rptdata").addClass("scrollbar");
        });

    });
</script>

<style>
    .scrollbar
    {
        margin-left: 30px;
        float: left;
        height: 300px;
        width: 95%;
        background: #F5F5F5;
        overflow-y: scroll;
        margin-bottom: 25px;
    }
</style>
<?php echo $this->Form->create('landrecord', array('id' => 'landrecord')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Land Record </h3></center>
            </div>
            <div class="box-body">
                <div  class="rowht"></div>  <div  class="rowht"></div>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>       
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                    </div>
                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div>
            </div>
        </div>
        <?php if (!empty($html)) { ?>
            <div class = "box box-primary">
                <div class="box-body">
                    <div  class="rowht"></div>
                    <div id="rptdata" class="scrollbar">
                        <?php echo $html; ?>
                    </div>
                    <div  class="rowht"></div>  <div  class="rowht"></div>
                    <div class="row" style="text-align: center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="btnprint"><?php echo __('lblprint'); ?></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
    <input type="hidden" id="actiontype" value='<?php echo $actiontype; ?>' name="actiontype" class="btn btn-primary">
</div>
<?php echo $this->Form->end(); ?>