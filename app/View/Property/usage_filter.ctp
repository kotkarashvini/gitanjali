<?php

if (isset($usagecategory)) {
    $this->Form->create("propertyscreennew");
    echo $this->Form->input('usage_cat_id', array('type' => 'select', 'options' => $usagecategory, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => ' usage_cat_id'));
}
?>