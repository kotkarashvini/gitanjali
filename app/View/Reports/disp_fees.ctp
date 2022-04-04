<script type="text/javascript">
    $(document).ready(function () {

//        $("#date").hide();
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

		$('#btnNext').click(function () {
		 var article_id = $('#article_id').val();
		// alert(article_id);
		 var fromdt = $('#from').val();
		// alert(fromdt);
		 var todt = $('#to').val();
		// alert(todt);
			$.post('<?php echo $this->webroot; ?>Fees/dispfeerpt', {article_id: article_id, fromdt: fromdt, todt: todt}, function (data) {
				$('#depfd').empty();
                $("#depfd").append(data);
				
			});
		});
		
			
    });


	
</script>
<?php
echo $this->Form->create('disp_fees', array('id' => 'disp_fees', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder">Office wise Details</h3></center>
            </div>
            <div class="box-body">
                <div class="row" >
					 <div class="col-lg-12">
						<div class="form-group">
								<label for="" class="col-sm-2 control-label"><b><?php echo 'Select Article'; ?> :-</b><span style="color: #ff0000"></span></label>   
								<div class="col-sm-2">
									<?php echo $this->Form->input('article_id', array('type' => 'select', 'empty' => '--select--', 'options' => $articlelist, 'label' => false, 'multiple' => false, 'id' => 'article_id', 'class' => 'form-control input-sm')); ?>
								</div>
								<label for="" class="col-sm-2 control-label"><b><?php echo 'Select Date'; ?> :-</b><span style="color: #ff0000"></span></label>   
								<div class="col-sm-2">
									From :&nbsp; <?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
								</div>
								<div class="col-sm-2">
									To :&nbsp; <?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
								</div>
								<div class="col-sm-2">
								 <input type="button" id="btnNext" name="btnNext" class="btnsave" style="width:155px;" value="<?php echo 'Search'; ?>">
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box box-primary">
            <div class="box-body">
               
                <div class="row" >
                    <div class="form-group">
                        <div id="depfd">

                        </div>
                    </div>
                </div>

            </div>
        </div>
	</div>
</div>
<?php echo $this->Form->end(); ?>