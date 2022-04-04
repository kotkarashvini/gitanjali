
<?php
if (isset($result) and ! empty($result)) {
    foreach ($result as $key => $field) {
        // pr($Pattens);
        ?>   
        <br>
        <div class="row">
            <div class="col-sm-offset-1  col-sm-2">
                <label><?php echo $field[0]['field_name']; ?></label>
            </div>
            <div class="col-sm-2"> 
                <?php
                echo $this->Form->input('id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'name' => 'data[genernalinfoentry][field_id][]', 'value' => $field[0]['articledepfield_id']));
                echo $this->Form->input('value', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'data[genernalinfoentry][field_value_en][]'));
                ?>
            </div> 

            <!--                <div class="col-sm-offset-1  col-sm-2">
                                <label><?php echo $field[0]['pattern_desc_ll']; ?></label>
                            </div>
                            <div class="col-sm-2"> 
            <?php
            echo $this->Form->input('value', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'data[genernalinfoentry][pattern_value_ll][]'));
            ?>
                            </div> -->

        </div> 
    <?php }
}
?>