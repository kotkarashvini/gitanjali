
<?php echo $this->Form->input('formname', array('label' => false, 'type' => 'hidden', 'value' => 'NewCaseadd')); ?>
<div class="box-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo __('case_type'); ?></label>
                <?php echo $this->Form->input('case_type_id', array('label' => false, 'placeholder' => 'case_type_id', 'id' => 'case_type_id', 'type' => 'select', 'class' => 'form-control select-style1', 'options' => array($casetypedesc))); ?>
            </div>

            <div class="form-group">
                <label><?php echo __('case_code'); ?></label>
                <?php echo $this->Form->input('case_code', array('label' => false, 'id' => 'case_code', 'placeholder' => 'case_code', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <label><?php echo __('year'); ?></label>
            <?php
            // Sets the top option to be the current year.
            $currently_selected = date('Y');
            // Year to start available options at
            $earliest_year = 1950;
            // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
            $latest_year = date('Y');
            print '<select class="btn btn-default dropdown-toggle" name="data[NewCaseadd][case_year]" type="button">';
            // Loops over each int[year] from current year, back to the $earliest_year [1950]
            foreach (range($latest_year, $earliest_year) as $i) {
                // Prints the option with the next year in range.
                print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
            }
            print '</select>';
            ?>
            <div class="form-group">
                <label><?php echo __('date_of_filing'); ?></label>
                <?php echo $this->Form->input('date_of_filing', array('label' => false, 'id' => 'date_of_filing', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <!-- /.form-group -->
            <div class="form-group">
                <label><?php echo __('case_belongs_to'); ?></label>
                <?php echo $this->Form->input('case_belongs_to', array('label' => false, 'placeholder' => 'case_belongs_to', 'id' => 'case_belongs_to', 'type' => 'select', 'class' => 'form-control select-style1', 'options' => array($sofficename))); ?>        
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo __('objection_type_id'); ?></label>
                <?php echo $this->Form->input('objection_type_id', array('label' => false, 'placeholder' => 'objection_type_id', 'id' => 'objection_type_id', 'type' => 'select', 'class' => 'form-control select-style1', 'options' => array($objectiontype))); ?>          
            </div>
            <!-- /.form-group -->
            <div class="form-group">
                <label> <?php echo __('stamp_duty'); ?></label>
                <?php echo $this->Form->input('stamp_duty', array('label' => false, 'id' => 'stamp_duty', 'type' => 'text', 'placeholder' => 'stamp_duty', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
            </div>
            <div class="form-group">
                <label><?php echo __('adj_case_no'); ?></label>
                <?php echo $this->Form->input('adj_case_no', array('label' => false, 'placeholder' => 'adj_case_no', 'id' => 'adj_case_no', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <label><?php echo __('year'); ?></label>
            <?php
            // Sets the top option to be the current year.
            $currently_selected = date('Y');
            // Year to start available options at
            $earliest_year = 1950;
            // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
            $latest_year = date('Y');

            print '<select class="btn btn-default dropdown-toggle" name="data[NewCaseadd][case_year]" type="button">';
            // Loops over each int[year] from current year, back to the $earliest_year [1950]
            foreach (range($latest_year, $earliest_year) as $i) {
                // Prints the option with the next year in range.
                print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
            }
            print '</select>';
            ?>
        </div>
        <div class="col-md-6">

            <!-- /.form-group -->
            <div class="form-group">
                <label><?php echo __('old_doc_reg_no'); ?></label>
                <?php echo $this->Form->input('old_doc_reg_no', array('label' => false, 'id' => 'old_doc_reg_no', 'placeholder' => 'old_doc_reg_no', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <!-- /.form-group -->
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo __('old_doc_reg_date'); ?></label>
                <?php echo $this->Form->input('old_doc_reg_date', array('label' => false, 'id' => 'old_doc_reg_date', 'placeholder' => 'old_doc_reg_date', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <!-- /.form-group -->
            <div class="form-group">
                <label><?php echo __('old_doc_office'); ?></label>
                <?php echo $this->Form->input('old_doc_office', array('label' => false, 'id' => 'old_doc_office', 'placeholder' => 'old_doc_office', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <!-- /.form-group -->
        </div>





        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo __('ref_doc_reg_no'); ?></label>
                <?php echo $this->Form->input('ref_doc_reg_no', array('label' => false, 'id' => 'ref_doc_reg_no', 'placeholder' => 'ref_doc_reg_no', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <!-- /.form-group -->
            <div class="form-group">
                <label><?php echo __('ref_doc_reg_date'); ?></label>
                <?php echo $this->Form->input('ref_doc_reg_date', array('label' => false, 'id' => 'ref_doc_reg_date', 'placeholder' => 'ref_doc_reg_date', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>

            <div class="form-group">
                <label><?php echo __('ref_doc_office'); ?></label>
                <?php echo $this->Form->input('ref_doc_office', array('label' => false, 'id' => 'ref_doc_office', 'placeholder' => 'ref_doc_office', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
            </div>
            <!-- /.form-group -->
        </div>
     
            <div class="form-group">
                <button type="submit" class="btn btn-success"><?php echo __('submit'); ?></button> 

                <button type="reset"  value="reset" class="btn btn-warning"><?php echo __('reset'); ?></button>
            </div>

    </div>
    <!-- /.row -->
</div>
<!--<div class="box-border">-->




<?php echo $this->Form->end(); ?>
