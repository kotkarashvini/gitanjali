<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of division
 *
 * @author Acer
 */
class office extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_office';
    public $primaryKey = 'office_id';

//    var $virtualFields = array(
//        'name' => "CONCAT(division.division_name)"
//    );


    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_office';
        $duplicate['PrimaryKey'] = 'office_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'office_name_' . $language['mainlanguage']['language_code']);
        }
//        array_push($fields, 'village_code');
//        array_push($fields, 'census_code');

        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist, $adminLevelConfig) {

        $fieldlist = array();

        // $fieldlist['state_id']['select'] = 'is_select_req';

        $fieldlist['dept_id']['select'] = 'is_select_req';
        
         foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['office_name_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumericspace';
            } else {
                $fieldlist['office_name_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
            }
        }
        
        


        if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
            $fieldlist['division_id']['select'] = 'is_select_req';
        }
        $fieldlist['district_id']['select'] = 'is_select_req';
        if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') {
            $fieldlist['subdivision_id']['select'] = 'is_select_req';
        }
        $fieldlist['taluka_id']['select'] = 'is_select_req';
        if ($adminLevelConfig['adminLevelConfig']['is_subdiv'] == 'Y') {
            $fieldlist['subdivision_id']['select'] = 'is_select_req';
        }
        if ($adminLevelConfig['adminLevelConfig']['is_circle'] == 'Y') {
            $fieldlist['circle_id']['select'] = 'is_select_req';
        }
        $fieldlist['village_id']['select'] = 'is_select_req';


        //$fieldlist['reporting_office_id']['select'] = 'is_select';



        $fieldlist['flat']['text'] = 'is_positiveinteger';
        $fieldlist['building']['text'] = 'is_alphanumspacedashdotslash';
        $fieldlist['road']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';
        $fieldlist['locality']['text'] = 'is_alphanumspacedashdotcommaroundbrackets';
        //$fieldlist['city']['text'] = 'is_alphaspace';
        $fieldlist['pincode']['text'] = 'is_pincode_empty';
        $fieldlist['officc_contact_no']['text'] = 'is_mobileindian';
        $fieldlist['office_email_id']['text'] = 'is_email';


        $fieldlist['hierarchy_id']['select'] = 'is_select_req';
        $fieldlist['shift_id']['select'] = 'is_select_req';
        $fieldlist['slot_id']['select'] = 'is_select_req';
         

//        $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
//        $fieldlist['corp_id']['select'] = 'is_select_req';

       

//pr($fieldlist);
        return $fieldlist;
    }

    function get_officedetails_for_appointment($office_id) {
        $options1['conditions'] = array('office.office_id' => trim($office_id));
        $options1['joins'] = array(array('table' => 'ngdrstab_mst_timeslot', 'alias' => 'slot', 'type' => 'INNER', 'conditions' => array('office.slot_id=slot.slot_id')));
        $options1['fields'] = array('office.office_id', 'slot.slot_time_minute', 'office.shift_id', 'office.tatkal_slot_id', 'office.is_virtual_office');
        $office = $this->find('all', $options1);
        return $office;
    }

    function get_officedetails_for_tatkalappointment($office_id) {
        $options1['conditions'] = array('office.office_id' => trim($office_id));
        $options1['joins'] = array(array('table' => 'ngdrstab_mst_timeslot', 'alias' => 'slot', 'type' => 'INNER', 'conditions' => array('office.tatkal_slot_id=slot.slot_id')));
        $options1['fields'] = array('office.office_id', 'slot.slot_time_minute');
        $office = $this->find('all', $options1);
        return $office;
    }

    function get_officedetails_for_appointment_dashboard() {
        $options1['joins'] = array(
            array('table' => 'ngdrstab_mst_timeslot', 'alias' => 'slot', 'type' => 'INNER', 'conditions' => array('office.slot_id=slot.slot_id')));
        $options1['fields'] = array('office.office_id', 'office.office_name_en', 'slot.slot_time_minute');
        $office = $this->find('all', $options1);
        return $office;
    }

    function get_officedetails_for_govappointment($office_id) {
        $options1['conditions'] = array('office.office_id' => trim($office_id));
        $options1['joins'] = array(array('table' => 'ngdrstab_mst_timeslot', 'alias' => 'slot', 'type' => 'INNER', 'conditions' => array('office.gov_appt_slot_id=slot.slot_id')));
        $options1['fields'] = array('office.office_id', 'slot.slot_time_minute');
        $office = $this->find('all', $options1);
        return $office;
    }

}
