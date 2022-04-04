<?php

echo $this->Form->create('frm');
echo $this->Form->input('fee_rule_list', array('type' => 'select', 'id' => 'feeRuleId', 'label' => false, 'multiple' => 'checkbox', 'class' => 'usage_cat_id', 'options' => $feeRuleList));
?>