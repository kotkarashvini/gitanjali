<?php

class Surveyno extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_survey_no';

    //madhuri code start
    function get_location($survey_no, $lang, $village_id) {
        try {
            return($this->query("select s.survey_no,s.level1_list_id,l.list_1_desc_$lang from ngdrstab_mst_survey_no s,ngdrstab_mst_loc_level_1_prop_list l
                where s.level1_list_id=l.prop_level1_list_id and s.survey_no='" . $survey_no . "' and s.village_id=" . $village_id));
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_zone($survey_no, $lang, $village_id) {
        try {
        return($this->query("select s.survey_no,s.level1_id,l.level_1_desc_$lang from ngdrstab_mst_survey_no s,ngdrstab_mst_location_levels_1_property l
                where s.level1_id=l.level_1_id  and s.survey_no='" . $survey_no . "' and s.village_id=" . $village_id));
         } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //madhuri code start
}
