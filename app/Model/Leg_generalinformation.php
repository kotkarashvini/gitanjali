<?php

class Leg_generalinformation extends AppModel
{
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_legacy_generalinformation';
    public $primaryKey = 'general_info_id';


    public function get_district_id($token)
    {
        $data = $this->query("SELECT district_id FROM ngdrstab_trn_legacy_generalinformation
 WHERE token_no=?", array($token));
        return $data;

    }

    function get_article_id($token_no)
    {
        return $this->query("SELECT article_id FROM ngdrstab_trn_legacy_generalinformation WHERE token_no=?", array($token_no));
    }


    public function get_general_info($lang = NULL, $doc_token_no = NULL)
    {
        return $this->find('all', array(// generalinfoentry=1,5,7
            'fields' => array('Leg_generalinformation.token_no', 'article.article_desc_' . $lang, 'article.article_id', 'Leg_generalinformation.exec_date', 'Leg_generalinformation.no_of_pages'),
            'joins' => array(
                array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('article.article_id=Leg_generalinformation.article_id')), //1                
                //array('table' => 'ngdrstab_trn_stamp_duty', 'alias' => 'sd', 'type' => 'left', 'conditions' => array('sd.token_no=Leg_generalinformation.token_no'))//4
            ),
            'conditions' => array('Leg_generalinformation.token_no' => $doc_token_no)));
    }

    public function getLegacyDocToBeUploadList()
    {
        $tableData = $this->find('all', array('conditions' => array('is_doc_scanned' => 'Y', 'is_doc_uploaded' => false),
            'fields' => array('district.district_name_en', 'district_id', 'taluka_id', 'office.office_name_en','office_id', 'token_no'),
            'joins' => array(
                array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'district',
                    'conditions' => array('district.district_id=Leg_generalinformation.district_id')
                ),
                array('table' => 'ngdrstab_mst_office', 'alias' => 'office',
                    'conditions' => array('office.office_id=Leg_generalinformation.office_id')
                )
            )
        ));
        return $this->formatData($tableData,'Leg_generalinformation','token_no');
    }
}