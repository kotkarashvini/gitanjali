


<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->element("NewCase/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Report LIST'); ?></h3></center>
            </div>
            <div class="box-body">
                <!--<div class="row">-->
               
                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <th class="center"><?php echo __('Case_ID'); ?></th>
                                         <th class="center"><?php echo __('Reports'); ?></th>
                                    </tr>  
                                </thead>
                                <?php
                                foreach ($noticerecord as $noticerecord1):
                                    ?>
                                <tr>

                
                                  <td class="width10"><?php echo $noticerecord1['0']['case_id']; ?></td>
                                    <td>
                                            <?php
                                            echo $this->Html->link('Report 1', array('controller' => 'NewCase', 'action' => 'sample_report', $noticerecord1[0]['case_id']));
                                            ?>
                                    </td>  
                                </tr>
                                <?php endforeach;
                                ?>
                            </table> 
                        </div>
                        <div id="menu1" class="tab-pane fade">
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>










