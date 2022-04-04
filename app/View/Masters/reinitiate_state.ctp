<script>
function formadd() {
  
  var r = confirm("Do you want to Reinitiate State/ UT Setup");
  if (r == true) {
    // submit form
        var r2 = confirm("Are you sure, you want to Reinitiate State/ UT Setup. This will delete State/ UT level Information.");
        if (r2 == true) {
            $( "#reinitiate_state" ).submit();
        }
        else{
            return false;
        }
  } else {
     return false;
  }

}
</script>
<?php
//echo $this->element("Master/language_main_menu");
echo $this->Form->create('reinitiate_state', array('id' => 'reinitiate_state', 'type' => 'file'));
?>

<div class="rowht"></div><div class="rowht"></div>
    <div class="row center">
        <div class="form-group">
            <div class="col-lg-12">
                 <div class="box box-primary">
                    <div class="box-header with-border">
                        <center><h3 class="box-title headbolder"><?php echo 'Reinitiate State/ UT Setup'; ?></h3></center>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 tdselect">

                                    <button id="btnadd"  class="btn btn-info " onclick="javascript: return formadd();">
                                    &nbsp;&nbsp; <?php echo 'Reinitiate State/ UT Setup'; ?></button>
                                    <br><br>
                                    <b>
                                    <font color='red'>
                                    After clicking on 'Reinitiate State/ UT Setup' button, it will delete State/ UT level Information.
                                    </font>
                                    </b>
                              </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>