<?php

class Feedback extends AppModel {

    //put your code here.
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_feedback';

    function getAllFeedback() {
        $records = $this->find('all', array('order' => 'created_date'));
        if ($records) {
            return $this->formatData($records, 'Feedback', 'id');
        }
        return;
    }

    function getFeedbackByDateRange($from, $to) {
        $from = $from ? $from : date('Y-m-d');
        $to = $to ? $to : date('Y-m-d');
        $records = $this->find('all', array('conditions' => array('created_date::DATE BETWEEN ' . $from . ' AND ' . $to . ' '), 'order' => 'created_date'));
        if ($records) {
            return $this->formatData($records, 'Feedback', 'id');
        }
        return;
    }

}
