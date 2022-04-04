<?php

App::uses('Sanitize', 'Utility');

class RateController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);

//        $this->Security->unlockedActons = array();
        $this->Auth->allow('rate_update', 'rategrid_update');

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    //================= Upadate Rate ========================================

    public function rategrid() {
        array_map([$this, 'loadModel'], ['rate', 'Usagemainmain', 'Usagesub', 'UnitMapping']);
        $raterecord = NULL;
        $lang = $this->Session->read("sess_langauge");
        $usage_main = $this->Usagemainmain->find('list', array('fields' => array('Usagemainmain.usage_main_catg_id', 'Usagemainmain.usage_main_catg_desc_' . $lang)));

        if (isset($_POST['district']) && is_numeric($_POST['district']) && isset($_POST['taluka']) && is_numeric($_POST['taluka'])) {
            $district_id = $_POST['district'];
            $taluka_id = $_POST['taluka'];
            $raterecord = $this->rate->query("select distinct a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                            a.developed_land_types_id,c.developed_land_types_desc_en,b.ulb_type_id,
                                            b.corp_id,g.governingbody_name_en,
                                            a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                            a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                            u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en                                              
                                            from ngdrstab_mst_rate a
                                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                            inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                            inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                            inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                            inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                            inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                            inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                            inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                                where finyear_id=6 and  a.district_id = $district_id and a.taluka_id = $taluka_id order by a.district_id");
        } else if (isset($_POST['district']) && is_numeric($_POST['district'])) {
            $district_id = $_POST['district'];
            $raterecord = $this->rate->query("select  a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                                a.developed_land_types_id,c.developed_land_types_desc_en,b.ulb_type_id,
                                                b.corp_id,g.governingbody_name_en,
                                                a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                                a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                                u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en                                              
                                                from ngdrstab_mst_rate a
                                                inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                                inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                                inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                                inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                                inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                                inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                                inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                                inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                                where finyear_id=6 and a.district_id = $district_id order by a.district_id");
        }
        $this->set(compact('lang', 'raterecord', 'usage_main'));
    }

    public function rate() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain']);
            $talukadata = $corplistdata = $hfid = $hfupdateflag = NULL;

            $user_id = $this->Auth->User("user_id");
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
//           
            $this->set('districtdata', Null);
            $distmapping = $this->rate->query("select district_id from ngdrstab_temp_user_dist_mapping where user_id= $user_id");
            if (!empty($distmapping)) {
                if ($distmapping[0][0]['district_id'] == 99999) {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
                } else {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid, 'district_id' => $distmapping[0][0]['district_id']), 'order' => array('district_name_en' => 'ASC'))));
                }
            } else {
//                 echo 1;exit;
                $this->Session->setFlash(__("User Not Valid To The Changes...!"));
                // $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
            }


//            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
            $this->set('landtypedata', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('developed_land_types_desc_en' => 'ASC'))));
            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('usage_main', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));


            $this->set(compact('lang', 'raterecord', 'talukadata', 'corplistdata', 'hfid', 'hfupdateflag'));
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function update_rate() {
        $this->autoRender = FALSE;
        array_map([$this, 'loadModel'], ['rate']);
        if (isset($_POST['prop_rate']) and is_numeric($_POST['prop_rate'])) {

            $stateid = $this->Auth->User("state_id");
            $this->rate->id = $_POST['id'];
            $record['rate']['state_id'] = $stateid;
            $record['rate']['user_id'] = $this->Auth->User('user_id');
            $record['rate']['created_date'] = date('Y/m/d H:i:s');
            $record['rate']['req_ip'] = $_SERVER['REMOTE_ADDR'];
            $record['rate']['prop_rate'] = $_POST['prop_rate'];

            if ($this->rate->save($record)) {
                echo $_POST['prop_rate'];
            } else {
                echo 'F';
            }
        }
    }

    //============== upadate unit================================================   

    function unit() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain']);
            $talukadata = $corplistdata = $hfid = $hfupdateflag = NULL;

            $user_id = $this->Auth->User("user_id");
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
//           
            $this->set('districtdata', Null);
            $distmapping = $this->rate->query("select district_id from ngdrstab_temp_user_dist_mapping where user_id= $user_id");
//           pr($distmapping);exit;
            if (!empty($distmapping)) {
                if ($distmapping[0][0]['district_id'] == 99999) {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
                } else {
//                    echo $distmapping[0][0]['district_id'];exit;

                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid, 'district_id' => $distmapping[0][0]['district_id']), 'order' => array('district_name_en' => 'ASC'))));
                }
            } else {
//                 echo 1;exit;
                $this->Session->setFlash(__("User Not Valid To The Changes...!"));
                // $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
            }


            //$this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
            $this->set('landtypedata', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('developed_land_types_desc_en' => 'ASC'))));
            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('usage_main', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));


            $this->set(compact('lang', 'raterecord', 'talukadata', 'corplistdata', 'hfid', 'hfupdateflag'));
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function unitgrid() {
        array_map([$this, 'loadModel'], ['rate', 'Usagemainmain', 'Usagesub', 'UnitMapping']);
        $raterecord = NULL;
        $lang = $this->Session->read("sess_langauge");
        $usage_main = $this->Usagemainmain->find('list', array('fields' => array('Usagemainmain.usage_main_catg_id', 'Usagemainmain.usage_main_catg_desc_' . $lang)));

        if (isset($_POST['district']) && is_numeric($_POST['district']) && isset($_POST['taluka']) && is_numeric($_POST['taluka'])) {
            $district_id = $_POST['district'];
            $taluka_id = $_POST['taluka'];
            $raterecord = $this->rate->query("select distinct a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                            a.developed_land_types_id,c.developed_land_types_desc_en,b.ulb_type_id,
                                            b.corp_id,g.governingbody_name_en,
                                            a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                            a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                            u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en                                              
                                            from ngdrstab_mst_rate a
                                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                            inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                            inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                            inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                            inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                            inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                            inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                            inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                                where finyear_id=6 and  a.district_id = $district_id and a.taluka_id = $taluka_id");
        } else if (isset($_POST['district']) && is_numeric($_POST['district'])) {
            $district_id = $_POST['district'];
            $raterecord = $this->rate->query("select  a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                                a.developed_land_types_id,c.developed_land_types_desc_en,b.ulb_type_id,
                                                b.corp_id,g.governingbody_name_en,
                                                a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                                a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                                u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en                                              
                                                from ngdrstab_mst_rate a
                                                inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                                inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                                inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                                inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                                inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                                inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                                inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                                inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                                where finyear_id=6 and a.district_id = $district_id");
        }
        $this->set(compact('lang', 'raterecord', 'usage_main'));
    }

    function update_unit() {
        $this->autoRender = FALSE;
        array_map([$this, 'loadModel'], ['rate']);
        if (isset($_POST['prop_unit']) and is_numeric($_POST['prop_unit'])) {



            $stateid = $this->Auth->User("state_id");

            $this->rate->id = $_POST['id'];
            $record['rate']['state_id'] = $stateid;
            $record['rate']['user_id'] = $this->Auth->User('user_id');
            $record['rate']['created_date'] = date('Y/m/d H:i:s');
            $record['rate']['req_ip'] = $_SERVER['REMOTE_ADDR'];
            $record['rate']['prop_unit'] = $_POST['prop_unit'];

            if ($this->rate->save($record)) {
                echo $_POST['prop_unit'];
            } else {
                echo 'F';
            }
        }
    }

    function get_unit() {
        try {


            if (isset($_GET['sub_cat']) and is_numeric($_GET['sub_cat'])) {

                $this->autoRender = FALSE;
                $this->loadModel('UnitMapping');
                //$doc_lang = $this->Session->read('doc_lang');
                $lang = $this->Session->read("sess_langauge");
                $unit = ClassRegistry::init('UnitMapping')->find('list', array(
                    'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                    'joins' => array(
                        array(
                            'table' => 'ngdrstab_mst_unit',
                            'alias' => 'unit',
                            'type' => 'INNER',
                            'conditions' => array(
                                'unit.unit_id=UnitMapping.unit_id'
                            )
                        ),
                    ),
                    'conditions' => array(
                        'UnitMapping.usage_sub_catg_id' => $_GET['sub_cat'],
                    ), 'order' => 'sr_no ASC'
                        )
                );

                return json_encode($unit);
            } else {
                if (isset($this->params->params['pass']) && !empty($this->params->params['pass'])) {
                    $mainid = $this->params->params['pass'][0];
                    $subid = $this->params->params['pass'][1];

                    if (is_numeric($mainid) && is_numeric($subid)) {
                        $this->autoRender = FALSE;
                        $this->loadModel('UnitMapping');
                        //$doc_lang = $this->Session->read('doc_lang');
                        $lang = $this->Session->read("sess_langauge");
                        $unit = ClassRegistry::init('UnitMapping')->find('list', array(
                            'fields' => array('unit.unit_id', 'unit.unit_desc_' . $lang),
                            'joins' => array(
                                array(
                                    'table' => 'ngdrstab_mst_unit',
                                    'alias' => 'unit',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'unit.unit_id=UnitMapping.unit_id'
                                    )
                                ),
                            ),
                            'conditions' => array(
                                'UnitMapping.usage_main_catg_id' => $mainid,
                                'UnitMapping.usage_sub_catg_id' => $subid,
                            ), 'order' => 'sr_no ASC'
                                )
                        );

                        return ($unit);
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //================== Rate Save ==============================================

    public function get_taluka_name() {
        //echo 1;exit;
        try {
            $lang = $this->Session->read("sess_langauge");

            if (isset($_GET['district'])) {
                $district = $_GET['district'];
                $taluka_id = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.taluka_id'), 'conditions' => array('damblkdpnd.district_id' => array($district))));
                $talukaname = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('taluka.taluka_id' => $taluka_id), 'order' => array('taluka.taluka_name_en' => 'ASC')));

                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['taluka'] = $talukaname;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($talukaname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            pr($e);
        }
    }

    public function get_corp_list() {
        //echo 1;exit;
        try {
            $lang = $this->Session->read("sess_langauge");

            if (isset($_GET['corp']) && isset($_GET['dist'])) {
                $ulb_type_id = $_GET['corp'];
                $district_id = $_GET['dist'];
//                $corp_id = ClassRegistry::init('corporationclass')->find('list', array('fields' => array('corp_id'), 'conditions' => array('ulb_type_id' => array($ulb_type_id))));
                $corplistname = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('ulb_type_id' => $ulb_type_id, 'district_id' => $district_id), 'order' => array('governingbody_name_en' => 'ASC')));

                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['corplist'] = $corplistname;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($corplistname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            pr($e);
        }
    }

    function get_subcat() {
        try {
            if (isset($_GET['main_cat']) and is_numeric($_GET['main_cat'])) {

                $this->autoRender = FALSE;
                $this->loadModel('Usagemain');
                //$doc_lang = $this->Session->read('doc_lang');
                $doc_lang = $this->Session->read("sess_langauge");
                $usage_main_catg_id = $_GET['main_cat'];
                $subid = ClassRegistry::init('Usagemain')->find('list', array('fields' => array('Usagemain.usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => array($usage_main_catg_id))));
                $usagesubname = ClassRegistry::init('Usagesub')->find('list', array('fields' => array('Usagesub.usage_sub_catg_id', 'Usagesub.usage_sub_catg_desc_en'), 'conditions' => array('usage_sub_catg_id' => $subid)));
                echo json_encode($usagesubname);
            } else {
                echo json_encode('csrf');
                return $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_village() {
        try {
            $this->loadModel('VillageMapping');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");

            if (isset($_GET['district']) && is_numeric($_GET['district']) && isset($_GET['taluka']) && is_numeric($_GET['taluka'])) {

                $district_id = $_GET['district'];
                $taluka_id = $_GET['taluka'];
                $village_name = trim(strtoupper($_GET['village']));
                $checkvillagename = $this->VillageMapping->Find('all', array('conditions' => array('upper(village_name_en) like ' => $village_name, 'district_id' => $district_id, 'taluka_id' => $taluka_id)));
                if ($checkvillagename != NULL) {
                    echo json_encode('Y');
                    exit;
                } else {
                    echo json_encode('N');
                    exit;
                }
                echo json_encode($villagelist);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//    public function rate_save() {
//        try {
//            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain', 'mainlanguage']);
//            $talukadata = $corplistdata = $hfid = NULL;
//            $hfupdateflag = 'S';
//
//            $user_id = $this->Auth->User("user_id");
//            $lang = $this->Session->read("sess_langauge");
//            $stateid = $this->Auth->User("state_id");
//
//            $this->set('districtdata', Null);
//            $distmapping = $this->rate->query("select district_id from ngdrstab_temp_user_dist_mapping where user_id= $user_id");
//            if (!empty($distmapping)) {
//                if ($distmapping[0][0]['district_id'] == 99999) {
//                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
//                } else {
//                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid, 'district_id' => $distmapping[0][0]['district_id']), 'order' => array('district_name_en' => 'ASC'))));
//                }
//            } else {
//                $this->Session->setFlash(__("User Not Valid To The Changes...!"));
//                // $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
//            }
////            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
//            $this->set('landtypedata', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('developed_land_types_desc_en' => 'ASC'))));
//            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('class_description_en' => 'ASC'))));
//            $this->set('usage_main', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));
//            $raterecord = $this->rate->query("select a.id,a.village_id,b.village_name_en,a.level1_id , i.level_1_desc_en,a.developed_land_types_id,
//                                                a.prop_level1_list_id,d.list_1_desc_en,a.developed_land_types_id,a.taluka_id,b.ulb_type_id,b.corp_id,
//                                                a.district_id
//                                                from ngdrstab_conf_lnk_village_location_mapping a
//                                                inner  join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id                        
//                                                inner  join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.prop_level1_list_id                        
//                                                inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id limit 100");
////            pr($raterecord);exit;
//            $this->set(compact('lang', 'raterecord', 'talukadata', 'corplistdata', 'hfid', 'hfupdateflag'));
//            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
//                    array(
//                        'table' => 'ngdrstab_conf_language',
//                        'alias' => 'conf',
//                        'type' => 'inner',
//                        'foreignKey' => false,
//                        'conditions' => array('conf.language_id = mainlanguage.id')
//                    )), 'order' => 'conf.language_id ASC'
//            ));
//            $this->set('languagelist', $languagelist);
//
//            $fieldlist = array();
////            is_alphaspace
////            
////is_alphanumspacedashdotslashroundbrackets
//            $fieldlist['district_id']['select'] = 'is_select_req';
//            $fieldlist['taluka_id']['select'] = 'is_select_req';
//            $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
//            $fieldlist['ulb_type_id']['select'] = 'is_select_req';
//            $fieldlist['corp_id']['select'] = 'is_select_req';
//            $fieldlist['village_name_en']['text'] = 'is_required,is_alphaspace';
//            $fieldlist['level_1_desc_en']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
//            $fieldlist['list_1_desc_en']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
//            $fieldlist['lr_code']['text'] = 'is_alphanumeric';
//            $fieldlist['segment_no']['text'] = 'is_alphanumeric';
//            $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
//            $fieldlist['usage_sub_catg_id']['select'] = 'is_select_req';
//            $fieldlist['prop_unit']['select'] = 'is_select_req';
//            $fieldlist['prop_rate']['text'] = 'is_required,is_numeric';
//
//
//            $this->set('fieldlist', $fieldlist);
//            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
//
//
//            if ($this->request->is('post')) {
////pr($this->request->data);exit;
//
//
//                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
//                $json = $file->read(true, 'r');
//                $json2array = json_decode($json, TRUE);
//                $this->set('talukadata', $json2array['taluka']);
//                $this->set('corplistdata', $json2array['corplist']);
//                $this->request->data['rate']['state_id'] = $stateid;
//                $this->request->data['rate']['user_id'] = $this->Auth->User('user_id');
//                $this->request->data['rate']['created_date'] = date('Y/m/d H:i:s');
//                $this->request->data['rate']['req_ip'] = $_SERVER['REMOTE_ADDR'];
//
//
//
//                if ($this->request->data['hfupdateflag'] == 'Y') {
//                    $this->request->data['rate']['id'] = $this->request->data['hfid'];
//                    $actionvalue = "Updated";
//                } else {
//                    $actionvalue = "Saved";
//                }
//
//                $district_id = $this->request->data['rate']['district_id'];
//                $taluka_id = $this->request->data['rate']['taluka_id'];
//                $developed_land_types_id = $this->request->data['rate']['developed_land_types_id'];
//                $ulb_type_id = $this->request->data['rate']['ulb_type_id'];
//                $corp_id = $this->request->data['rate']['corp_id'];
//                $village_name = trim(strtoupper($this->request->data['rate']['village_name_en']));
//                $level_1_desc_en = trim(strtoupper($this->request->data['rate']['level_1_desc_en']));
//                $list_1_desc_en = trim(strtoupper($this->request->data['rate']['list_1_desc_en']));
//                $village_id = $level_1_id = $prop_level1_list_id = null;
//                $checkvillagename = $this->VillageMapping->Find('all', array('conditions' => array('upper(village_name_en) like ' => $village_name, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id)));
//                if ($checkvillagename != NULL) {
//                    $village_id = $checkvillagename[0]['VillageMapping']['village_id'];
//                } else {
//                    $this->request->data['rate']['village_name_ll'] = $this->request->data['rate']['village_name_en'];
//
//                    $this->VillageMapping->save($this->request->data['rate']);
//                    $lastinsertid1 = $this->VillageMapping->getLastInsertId();
//                    $villagerecord = $this->VillageMapping->Find('all', array('conditions' => array('id' => $lastinsertid1)));
//                    $village_id = $villagerecord[0]['VillageMapping']['village_id'];
//                }
//
//                $this->request->data['Levels_1_property']['state_id'] = $stateid;
//                $this->request->data['Levels_1_property']['user_id'] = $this->Auth->User('user_id');
//                $this->request->data['Levels_1_property']['created_date'] = date('Y/m/d H:i:s');
//                $this->request->data['Levels_1_property']['req_ip'] = $_SERVER['REMOTE_ADDR'];
//                $this->request->data['Levels_1_property']['district_id'] = $district_id;
//                $this->request->data['Levels_1_property']['taluka_id'] = $taluka_id;
//                $this->request->data['Levels_1_property']['village_id'] = $village_id;
//                $this->request->data['Levels_1_property']['level_1_desc_en'] = $this->request->data['rate']['level_1_desc_en'];
//                $this->request->data['Levels_1_property']['level_1_desc_ll'] = $this->request->data['rate']['level_1_desc_en'];
//                $this->request->data['Levels_1_property']['developed_land_types_id'] = $developed_land_types_id;
//
//                $checklevel1name = $this->Levels_1_property->Find('all', array('conditions' => array('upper(level_1_desc_en) like ' => $level_1_desc_en, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'village_id' => $village_id)));
//                if ($checklevel1name != NULL) {
//                    $level_1_id = $checklevel1name[0]['Levels_1_property']['level_1_id'];
//                } else {
//                    $this->Levels_1_property->save($this->request->data['Levels_1_property']);
//                    $lastinsertid2 = $this->Levels_1_property->getLastInsertId();
//                    $level1record = $this->Levels_1_property->Find('all', array('conditions' => array('id' => $lastinsertid2)));
//                    $level_1_id = $level1record[0]['Levels_1_property']['level_1_id'];
//                }
//
//                $this->request->data['Level1']['state_id'] = $stateid;
//                $this->request->data['Level1']['user_id'] = $this->Auth->User('user_id');
//                $this->request->data['Level1']['created_date'] = date('Y/m/d H:i:s');
//                $this->request->data['Level1']['req_ip'] = $_SERVER['REMOTE_ADDR'];
//                $this->request->data['Level1']['district_id'] = $district_id;
//                $this->request->data['Level1']['taluka_id'] = $taluka_id;
//                $this->request->data['Level1']['village_id'] = $village_id;
//                $this->request->data['Level1']['level_1_id'] = $level_1_id;
//                $this->request->data['Level1']['list_1_desc_en'] = $this->request->data['rate']['list_1_desc_en'];
//                $this->request->data['Level1']['list_1_desc_ll'] = $this->request->data['rate']['list_1_desc_en'];
//                $this->request->data['Level1']['developed_land_types_id'] = $developed_land_types_id;
//                $this->request->data['Level1']['corp_id'] = $corp_id;
//
//                $checklevel1listname = $this->Level1->Find('all', array('conditions' => array('upper(list_1_desc_en) like ' => $list_1_desc_en, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'village_id' => $village_id, 'corp_id' => $corp_id, 'level_1_id' => $level_1_id)));
//                if ($checklevel1listname != NULL) {
//                    $prop_level1_list_id = $checklevel1listname[0]['Level1']['prop_level1_list_id'];
//                } else {
//
//
//                    $this->Level1->save($this->request->data['Level1']);
//                    $lastinsertid3 = $this->Level1->getLastInsertId();
//                    $level1listrecord = $this->Level1->Find('all', array('conditions' => array('id' => $lastinsertid3)));
//                    $prop_level1_list_id = $level1listrecord[0]['Level1']['prop_level1_list_id'];
//                }
//
//                $this->request->data['levelmapping']['state_id'] = $stateid;
//                $this->request->data['levelmapping']['user_id'] = $this->Auth->User('user_id');
//                $this->request->data['levelmapping']['created_date'] = date('Y/m/d H:i:s');
//                $this->request->data['levelmapping']['req_ip'] = $_SERVER['REMOTE_ADDR'];
//                $this->request->data['levelmapping']['district_id'] = $district_id;
//                $this->request->data['levelmapping']['taluka_id'] = $taluka_id;
//                $this->request->data['levelmapping']['village_id'] = $village_id;
//                $this->request->data['levelmapping']['level1_id'] = $level_1_id;
//                $this->request->data['levelmapping']['prop_level1_list_id'] = $prop_level1_list_id;
//                $this->request->data['levelmapping']['developed_land_types_id'] = $developed_land_types_id;
//                $errarr = $this->validatedata($this->request->data['rate'], $fieldlist);
//
//                //pr($errarr);exit;
//                if ($this->ValidationError($errarr)) {
//                    if ($this->levelmapping->save($this->request->data['levelmapping'])) {
//
//                        $this->request->data['rate']['village_id'] = $village_id;
//                        $this->request->data['rate']['level1_id'] = $level_1_id;
//                        $this->request->data['rate']['level1_list_id'] = $prop_level1_list_id;
//                        $this->request->data['rate']['finyear_id'] = 6;
//                        $this->request->data['rate']['add_flag'] = 'Y';
//
//                        $checkrate = $this->rate->Find('all', array('conditions' => array('district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id, 'village_id' => $village_id, 'level1_id' => $level_1_id, 'level1_list_id' => $prop_level1_list_id, 'segment_no' => $this->request->data['rate']['segment_no'], 'usage_main_catg_id' => $this->request->data['rate']['usage_main_catg_id'], 'usage_sub_catg_id' => $this->request->data['rate']['usage_sub_catg_id'])));
//                        if ($checkrate != NULL) {
//                            $this->Session->setFlash(__("Rate is already exist for this Location...!!!!"));
//                        } else {
//                            if ($this->rate->save($this->request->data['rate'])) {
//                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
////                                    $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
//                            } else {
//                                $this->Session->setFlash(__("Record Not $actionvalue "));
//                            }
//                        }
//                    }
//                }
//                //$this->Session->setFlash(__("Improper fields goes to server "));
//            }
//        } catch (Exception $exc) {
////            pr($exc);
////            exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
//        }
//    }

    
    public function rate_save() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain', 'mainlanguage']);
            $talukadata = $corplistdata = $hfid = NULL;
            $hfupdateflag = 'S';

            $user_id = $this->Auth->User("user_id");
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $this->set('stateid', $stateid);
            $this->set('districtdata', Null);
           // pr($user_id);exit;
            $distmapping = $this->rate->query("select district_id from ngdrstab_temp_user_dist_mapping where user_id= $user_id");
            if (!empty($distmapping)) {
                if ($distmapping[0][0]['district_id'] == 99999) {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
                } else {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid, 'district_id' => $distmapping[0][0]['district_id']), 'order' => array('district_name_en' => 'ASC'))));
                }
            } else {
                $this->Session->setFlash(__("User Not Valid To The Changes...!"));
                 $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
            }
//            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
            $this->set('landtypedata', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('developed_land_types_desc_en' => 'ASC'))));
            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('usage_main', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));
            $this->set('constuctiontype', ClassRegistry::init('constructiontype')->find('list', array('fields' => array('construction_type_id', 'construction_type_desc_' . $lang), 'order' => array('construction_type_desc_en' => 'ASC'))));
            $raterecord = $this->rate->query("select a.id,a.village_id,b.village_name_en,a.level1_id , i.level_1_desc_en,a.developed_land_types_id,
                                                a.prop_level1_list_id,d.list_1_desc_en,a.developed_land_types_id,a.taluka_id,b.ulb_type_id,b.corp_id,
                                                a.district_id
                                                from ngdrstab_conf_lnk_village_location_mapping a
                                                inner  join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id                        
                                                inner  join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.prop_level1_list_id                        
                                                inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id limit 100");
//            pr($raterecord);exit;
            $this->set(compact('lang', 'raterecord', 'talukadata', 'corplistdata', 'hfid', 'hfupdateflag'));
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $fieldlist = array();
//            is_alphaspace
//            
//is_alphanumspacedashdotslashroundbrackets
            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
            $fieldlist['ulb_type_id']['select'] = 'is_select_req';
            $fieldlist['corp_id']['select'] = 'is_select_req';
            $fieldlist['village_name_en']['text'] = 'is_required,is_alphanumericdotdash';
            $fieldlist['level_1_desc_en']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['list_1_desc_en']['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets';
            $fieldlist['lr_code']['text'] = 'is_alphanumeric';
            $fieldlist['segment_no']['text'] = 'is_alphanumeric';
             $fieldlist['mauja_code']['text'] = 'is_alphanumdashslashspace';
            $fieldlist['word_no']['text'] = 'is_alphanumdashslashspace';
            $fieldlist['hadbast_no']['text'] = 'is_alphanumeric';
            $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
            $fieldlist['usage_sub_catg_id']['select'] = 'is_select_req';
            $fieldlist['prop_unit']['select'] = 'is_select_req';
            $fieldlist['prop_rate']['text'] = 'is_required,is_numeric';
             $fieldlist['lg_code']['text'] = 'is_alphanumeric';


            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post')) {
//pr($this->request->data);exit;


                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $this->set('talukadata', $json2array['taluka']);
                $this->set('corplistdata', $json2array['corplist']);
                $this->request->data['rate']['state_id'] = $stateid;
                $this->request->data['rate']['user_id'] = $this->Auth->User('user_id');
                $this->request->data['rate']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['rate']['req_ip'] = $_SERVER['REMOTE_ADDR'];



                if ($this->request->data['hfupdateflag'] == 'Y') {
                    $this->request->data['rate']['id'] = $this->request->data['hfid'];
                    $actionvalue = "Updated";
                } else {
                    $actionvalue = "Saved";
                }

                $district_id = $this->request->data['rate']['district_id'];
                $taluka_id = $this->request->data['rate']['taluka_id'];
                $developed_land_types_id = $this->request->data['rate']['developed_land_types_id'];
                $ulb_type_id = $this->request->data['rate']['ulb_type_id'];
                $corp_id = $this->request->data['rate']['corp_id'];
                $village_name = trim(strtoupper($this->request->data['rate']['village_name_en']));
                $level_1_desc_en = trim(strtoupper($this->request->data['rate']['level_1_desc_en']));
                $list_1_desc_en = trim(strtoupper($this->request->data['rate']['list_1_desc_en']));
                $village_id = $level_1_id = $prop_level1_list_id = null;
                $checkvillagename = $this->VillageMapping->Find('all', array('conditions' => array('upper(village_name_en) like ' => $village_name, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id)));
                if ($checkvillagename != NULL) {
                    $village_id = $checkvillagename[0]['VillageMapping']['village_id'];
                } else {
                    $this->request->data['rate']['village_name_ll'] = $this->request->data['rate']['village_name_ll'];
//                    pr($this->request->data['rate']);exit;
                    $this->VillageMapping->save($this->request->data['rate']);
                    $lastinsertid1 = $this->VillageMapping->getLastInsertId();
                    $villagerecord = $this->VillageMapping->Find('all', array('conditions' => array('id' => $lastinsertid1)));
                    $village_id = $villagerecord[0]['VillageMapping']['village_id'];
                }

                $this->request->data['Levels_1_property']['state_id'] = $stateid;
                $this->request->data['Levels_1_property']['user_id'] = $this->Auth->User('user_id');
                $this->request->data['Levels_1_property']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['Levels_1_property']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->request->data['Levels_1_property']['district_id'] = $district_id;
                $this->request->data['Levels_1_property']['taluka_id'] = $taluka_id;
                $this->request->data['Levels_1_property']['village_id'] = $village_id;
                $this->request->data['Levels_1_property']['level_1_desc_en'] = $this->request->data['rate']['level_1_desc_en'];
                $this->request->data['Levels_1_property']['level_1_desc_ll'] = $this->request->data['rate']['level_1_desc_ll'];
                $this->request->data['Levels_1_property']['developed_land_types_id'] = $developed_land_types_id;
                if($stateid == 20){
                    $this->request->data['Levels_1_property']['word_no'] = $this->request->data['rate']['word_no'];
                    unset($fieldlist['lr_code']);
                    unset($fieldlist['segment_no']);
                    unset($fieldlist['hadbast_no']);
                }else {
                    unset($fieldlist['mauja_code']);
                    unset($fieldlist['word_no']);
                }

                $checklevel1name = $this->Levels_1_property->Find('all', array('conditions' => array('upper(level_1_desc_en) like ' => $level_1_desc_en, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'village_id' => $village_id)));
                if ($checklevel1name != NULL) {
                    $level_1_id = $checklevel1name[0]['Levels_1_property']['level_1_id'];
                } else {
                    $this->Levels_1_property->save($this->request->data['Levels_1_property']);
                    $lastinsertid2 = $this->Levels_1_property->getLastInsertId();
                    $level1record = $this->Levels_1_property->Find('all', array('conditions' => array('id' => $lastinsertid2)));
                    $level_1_id = $level1record[0]['Levels_1_property']['level_1_id'];
                }

                $this->request->data['Level1']['state_id'] = $stateid;
                $this->request->data['Level1']['user_id'] = $this->Auth->User('user_id');
                $this->request->data['Level1']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['Level1']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->request->data['Level1']['district_id'] = $district_id;
                $this->request->data['Level1']['taluka_id'] = $taluka_id;
                $this->request->data['Level1']['village_id'] = $village_id;
                $this->request->data['Level1']['level_1_id'] = $level_1_id;
                $this->request->data['Level1']['list_1_desc_en'] = $this->request->data['rate']['list_1_desc_en'];
                $this->request->data['Level1']['list_1_desc_ll'] = $this->request->data['rate']['list_1_desc_ll'];
                $this->request->data['Level1']['developed_land_types_id'] = $developed_land_types_id;
                $this->request->data['Level1']['corp_id'] = $corp_id;

                $checklevel1listname = $this->Level1->Find('all', array('conditions' => array('upper(list_1_desc_en) like ' => $list_1_desc_en, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'village_id' => $village_id, 'corp_id' => $corp_id, 'level_1_id' => $level_1_id)));
                if ($checklevel1listname != NULL) {
                    $prop_level1_list_id = $checklevel1listname[0]['Level1']['prop_level1_list_id'];
                } else {


                    $this->Level1->save($this->request->data['Level1']);
                    $lastinsertid3 = $this->Level1->getLastInsertId();
                    $level1listrecord = $this->Level1->Find('all', array('conditions' => array('id' => $lastinsertid3)));
                    $prop_level1_list_id = $level1listrecord[0]['Level1']['prop_level1_list_id'];
                }

                $this->request->data['levelmapping']['state_id'] = $stateid;
                $this->request->data['levelmapping']['user_id'] = $this->Auth->User('user_id');
                $this->request->data['levelmapping']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['levelmapping']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->request->data['levelmapping']['district_id'] = $district_id;
                $this->request->data['levelmapping']['taluka_id'] = $taluka_id;
                $this->request->data['levelmapping']['village_id'] = $village_id;
                $this->request->data['levelmapping']['level1_id'] = $level_1_id;
                $this->request->data['levelmapping']['prop_level1_list_id'] = $prop_level1_list_id;
                $this->request->data['levelmapping']['developed_land_types_id'] = $developed_land_types_id;
                $errarr = $this->validatedata($this->request->data['rate'], $fieldlist);

//                pr($errarr);exit;
                if ($this->ValidationError($errarr)) {
                    if ($this->levelmapping->save($this->request->data['levelmapping'])) {

                        $this->request->data['rate']['village_id'] = $village_id;
                        $this->request->data['rate']['level1_id'] = $level_1_id;
                        $this->request->data['rate']['level1_list_id'] = $prop_level1_list_id;
                        $this->request->data['rate']['finyear_id'] = 6;
                        $this->request->data['rate']['add_flag'] = 'Y';
                        if($stateid==20){
                          $checkrate = $this->rate->Find('all', array('conditions' => array('district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id, 'village_id' => $village_id, 'level1_id' => $level_1_id, 'level1_list_id' => $prop_level1_list_id,  'usage_main_catg_id' => $this->request->data['rate']['usage_main_catg_id'], 'usage_sub_catg_id' => $this->request->data['rate']['usage_sub_catg_id'], 'construction_type_id' => $this->request->data['rate']['construction_type_id']))); 
                        }else{
                        $checkrate = $this->rate->Find('all', array('conditions' => array('district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id, 'village_id' => $village_id, 'level1_id' => $level_1_id, 'level1_list_id' => $prop_level1_list_id, 'segment_no' => $this->request->data['rate']['segment_no'], 'usage_main_catg_id' => $this->request->data['rate']['usage_main_catg_id'], 'usage_sub_catg_id' => $this->request->data['rate']['usage_sub_catg_id'])));    
                        }
                        
                        if ($checkrate != NULL) {
                            $this->Session->setFlash(__("Rate is already exist for this Location...!!!!"));
                        } else {
                            if ($this->rate->save($this->request->data['rate'])) {
                                $this->Session->setFlash(__("Record $actionvalue Successfully"));
//                                    $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
                            } else {
                                $this->Session->setFlash(__("Record Not $actionvalue "));
                            }
                        }
                    }
                }
                //$this->Session->setFlash(__("Improper fields goes to server "));
            }
        } catch (Exception $exc) {
            pr($exc);
            exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function rategrid_save() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'Usagemainmain', 'Usagesub', 'UnitMapping']);
            $raterecord = NULL;
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");

            if (isset($_POST['district']) && is_numeric($_POST['district']) && isset($_POST['taluka']) && is_numeric($_POST['taluka'])) {
                $district_id = $_POST['district'];
                $taluka_id = $_POST['taluka'];
                $raterecord = $this->rate->query("select distinct a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                            a.developed_land_types_id,c.developed_land_types_desc_en,b.ulb_type_id,
                                            b.corp_id,g.governingbody_name_en,a.segment_no,e.class_description_en,
                                            a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                            a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                            u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en,
                                            b.mauja_code, i.word_no, a.construction_type_id, r.construction_type_desc_en
                                            from ngdrstab_mst_rate a
                                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                            inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                            inner join ngdrstab_conf_admblock_local_governingbody e on e.ulb_type_id = a.ulb_type_id
                                            inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                            inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                            inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                            inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                            inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                            inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                            left outer join ngdrstab_mst_construction_type r on r.construction_type_id = a.construction_type_id
                                                where finyear_id=6 and a.district_id = $district_id and a.taluka_id = $taluka_id order by a.district_id"); //finyear_id=6 and
            } else if (isset($_POST['district']) && is_numeric($_POST['district'])) {
                $district_id = $_POST['district'];
                $raterecord = $this->rate->query("select distinct a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                            a.developed_land_types_id,c.developed_land_types_desc_en,b.ulb_type_id,
                                            b.corp_id,g.governingbody_name_en,a.segment_no,e.class_description_en,
                                            a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                            a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                            u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en,
                                            b.mauja_code, i.word_no, a.construction_type_id, r.construction_type_desc_en
                                            from ngdrstab_mst_rate a
                                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                            inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                            inner join ngdrstab_conf_admblock_local_governingbody e on e.ulb_type_id = a.ulb_type_id
                                            inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                            inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                            inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                            inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                            inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                            inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                            left outer join ngdrstab_mst_construction_type r on r.construction_type_id = a.construction_type_id
                                                where finyear_id=6 and a.district_id = $district_id order by a.district_id"); //finyear_id=6 and
            }
//        pr($raterecord);exit;
            $this->set(compact('lang', 'raterecord', 'usage_main','stateid'));
        } catch (Exception $exc) {
//            pr($exc);
//            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //======================================== Rate Upadate ==============================================================


    public function rate_update() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain', 'mainlanguage']);
            $talukadata = $corplistdata = $hfid = $hfvillage = $hflevel1 = $hflevellist1 = NULL;
            $hfupdateflag = 'S';

            $user_id = $this->Auth->User("user_id");
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");

            $this->set('districtdata', Null);
            $distmapping = $this->rate->query("select district_id from ngdrstab_temp_user_dist_mapping where user_id= $user_id");
            if (!empty($distmapping)) {
                if ($distmapping[0][0]['district_id'] == 99999) {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
                } else {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid, 'district_id' => $distmapping[0][0]['district_id']), 'order' => array('district_name_en' => 'ASC'))));
                }
            } else {
                $this->Session->setFlash(__("User Not Valid To The Changes...!"));
                // $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
            }
//            $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
            $this->set('landtypedata', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('developed_land_types_desc_en' => 'ASC'))));
            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('class_description_en' => 'ASC'))));
//            $this->set('corpclasslistdata', ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('governingbody_name_en' => 'ASC'))));
            $this->set('usage_main', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));
            $raterecord = $this->rate->query("select a.id,a.village_id,b.village_name_en,a.level1_id , i.level_1_desc_en,a.developed_land_types_id,
                                                a.prop_level1_list_id,d.list_1_desc_en,a.developed_land_types_id,a.taluka_id,b.ulb_type_id,b.corp_id,
                                                a.district_id
                                                from ngdrstab_conf_lnk_village_location_mapping a
                                                inner  join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id                        
                                                inner  join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.prop_level1_list_id                        
                                                inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id limit 100");
//            pr($raterecord);exit;
            $this->set(compact('lang', 'raterecord', 'talukadata', 'corplistdata', 'hfid', 'hfupdateflag', 'hfvillage', 'hflevel1', 'hflevellist1'));


            if ($this->request->is('post') || $this->request->is('put')) {
//                pr($this->request->data);
//exit;

                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $this->set('talukadata', $json2array['taluka']);
                $this->set('corplistdata', $json2array['corplist']);
                $this->request->data['rate']['state_id'] = $stateid;
                $this->request->data['rate']['user_id'] = $this->Auth->User('user_id');
                $this->request->data['rate']['created_date'] = date('Y/m/d H:i:s');
                $this->request->data['rate']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->request->data['rate']['finyear_id'] = 6;
                
                $district_id = $this->request->data['rate']['district_id'];
                $taluka_id = $this->request->data['rate']['taluka_id'];
                $developed_land_types_id = $this->request->data['rate']['developed_land_types_id'];
                $ulb_type_id = $this->request->data['rate']['ulb_type_id'];
                $corp_id = $this->request->data['rate']['corp_id'];
                $village_name = trim(strtoupper($this->request->data['rate']['village_name_en']));
                $level_1_desc_en = trim(strtoupper($this->request->data['rate']['level_1_desc_en']));
                $list_1_desc_en = trim(strtoupper($this->request->data['rate']['list_1_desc_en']));

                $village_id = $_POST['hfvillage'];
                $level_1_id = $_POST['hflevel1'];
                $prop_level1_list_id = $_POST['hflevellist1'];

                $id_village = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('id'), 'conditions' => array('village_id' => array($village_id))));
                $id_village = $id_village[0]['VillageMapping']['id'];
                $checkvillagename = $this->VillageMapping->Find('all', array('conditions' => array('upper(village_name_en) like ' => $village_name, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id, 'id NOT IN (' . $id_village . ')')));

                if ($checkvillagename == NULL) {
                    $id_level1 = ClassRegistry::init('Levels_1_property')->find('all', array('fields' => array('id'), 'conditions' => array('level_1_id' => array($level_1_id))));
                    $id_level1 = $id_level1[0]['Levels_1_property']['id'];
                    $checklevel1name = $this->Levels_1_property->Find('all', array('conditions' => array('upper(level_1_desc_en) like ' => $level_1_desc_en, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'village_id' => $village_id, 'id NOT IN (' . $id_level1 . ')')));
                    if ($checklevel1name == NULL) {
                        $id_levellist1 = ClassRegistry::init('Level1')->find('all', array('fields' => array('id'), 'conditions' => array('prop_level1_list_id' => array($prop_level1_list_id))));
                        $id_levellist1 = $id_levellist1[0]['Level1']['id'];
                        $checklevel1listname = $this->Level1->Find('all', array('conditions' => array('upper(list_1_desc_en) like ' => $list_1_desc_en, 'district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id, 'village_id' => $village_id, 'corp_id' => $corp_id, 'level_1_id' => $level_1_id, 'id NOT IN (' . $id_levellist1 . ')')));
//                        pr($checklevel1listname);exit;
                        if ($checklevel1listname == NULL) {
                            $checkrate = $this->rate->Find('all', array('conditions' => array('district_id' => $district_id, 'taluka_id' => $taluka_id, 'developed_land_types_id' => $developed_land_types_id,
                                    'ulb_type_id' => $ulb_type_id, 'corp_id' => $corp_id, 'village_id' => $village_id, 'level1_id' => $level_1_id, 'level1_list_id' => $prop_level1_list_id,
                                    'segment_no' => $this->request->data['rate']['segment_no'], 'usage_main_catg_id' => $this->request->data['rate']['usage_main_catg_id'],
                                    'usage_sub_catg_id' => $this->request->data['rate']['usage_sub_catg_id'], 'finyear_id' => $this->request->data['rate']['finyear_id'], 'id NOT IN (' . $this->request->data['hfid'] . ')')));
//                                            pr($checkrate);exit;
                            if ($checkrate == NULL) {
                                $this->request->data['rate']['village_name_ll'] = $this->request->data['rate']['village_name_en'];
                                $this->request->data['rate']['id'] = $id_village;

                                if ($this->VillageMapping->save($this->request->data['rate'])) {
                                    $this->VillageMapping->query("update ngdrstab_conf_admblock_local_governingbody_list set ulb_type_id=? where corp_id=?", array($ulb_type_id, $corp_id));
                                   
                                    $this->request->data['Levels_1_property']['state_id'] = $stateid;
                                    $this->request->data['Levels_1_property']['user_id'] = $this->Auth->User('user_id');
                                    $this->request->data['Levels_1_property']['created_date'] = date('Y/m/d H:i:s');
                                    $this->request->data['Levels_1_property']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                                    $this->request->data['Levels_1_property']['district_id'] = $district_id;
                                    $this->request->data['Levels_1_property']['taluka_id'] = $taluka_id;
                                    $this->request->data['Levels_1_property']['village_id'] = $village_id;
                                    $this->request->data['Levels_1_property']['level_1_desc_en'] = $this->request->data['rate']['level_1_desc_en'];
                                    $this->request->data['Levels_1_property']['level_1_desc_ll'] = $this->request->data['rate']['level_1_desc_en'];
                                    $this->request->data['Levels_1_property']['developed_land_types_id'] = $developed_land_types_id;

                                    $this->request->data['Levels_1_property']['id'] = $id_level1;

                                    if ($this->Levels_1_property->save($this->request->data['Levels_1_property'])) {
                                        $this->request->data['Level1']['state_id'] = $stateid;
                                        $this->request->data['Level1']['user_id'] = $this->Auth->User('user_id');
                                        $this->request->data['Level1']['created_date'] = date('Y/m/d H:i:s');
                                        $this->request->data['Level1']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                                        $this->request->data['Level1']['district_id'] = $district_id;
                                        $this->request->data['Level1']['taluka_id'] = $taluka_id;
                                        $this->request->data['Level1']['village_id'] = $village_id;
                                        $this->request->data['Level1']['level_1_id'] = $level_1_id;
                                        $this->request->data['Level1']['list_1_desc_en'] = $this->request->data['rate']['list_1_desc_en'];
                                        $this->request->data['Level1']['list_1_desc_ll'] = $this->request->data['rate']['list_1_desc_en'];
                                        $this->request->data['Level1']['developed_land_types_id'] = $developed_land_types_id;
                                        $this->request->data['Level1']['corp_id'] = $corp_id;

                                        $this->request->data['Level1']['id'] = $id_levellist1;

                                        if ($this->Level1->save($this->request->data['Level1'])) {

                                            $this->request->data['rate']['village_id'] = $village_id;
                                            $this->request->data['rate']['level1_id'] = $level_1_id;
                                            $this->request->data['rate']['level1_list_id'] = $prop_level1_list_id;
                                            
                                            $this->request->data['rate']['add_flag'] = 'Y';
                                            $this->request->data['rate']['id'] = $this->request->data['hfid'];
//                                            pr($this->request->data);

                                            if ($this->rate->save($this->request->data['rate'])) {
                                                $this->Session->setFlash(__("Record Updated Successfully"));
//                                    $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
                                            } else {
                                                $this->Session->setFlash(__("Record Not Updated "));
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->Session->setFlash(__("Rate is already exist for this Location...!!!!"));
                            }
                        } else {
                            $this->Session->setFlash(__("Same Location Level 1 List name is already Exist for this location..!!!"));
                        }
                    } else {
                        $this->Session->setFlash(__("Same Location Level 1 name is already Exist for this location..!!!"));
                    }
                } else {
                    $this->Session->setFlash(__("Same Village Name is already Exist for this location..!!!"));
                }
            }
        } catch (Exception $exc) {
//            pr($exc);
//            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function rategrid_update() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'Usagemainmain', 'Usagesub', 'UnitMapping']);
            $raterecord = NULL;
            $lang = $this->Session->read("sess_langauge");

            if (isset($_POST['district']) && is_numeric($_POST['district']) && isset($_POST['taluka']) && is_numeric($_POST['taluka'])) {
                $district_id = $_POST['district'];
                $taluka_id = $_POST['taluka'];
                $raterecord = $this->rate->query("select distinct a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                            a.developed_land_types_id,c.developed_land_types_desc_en,a.ulb_type_id,
                                            a.corp_id,g.governingbody_name_en,a.segment_no,e.class_description_en,
                                            a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                            a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                            u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en                                              
                                            from ngdrstab_mst_rate a
                                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                            inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                            inner join ngdrstab_conf_admblock_local_governingbody e on e.ulb_type_id = a.ulb_type_id
                                            inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                            inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                            inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                            inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                            inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                            inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                                where finyear_id=6 and a.district_id = $district_id and a.taluka_id = $taluka_id order by a.district_id"); //finyear_id=6 and
            } else if (isset($_POST['district']) && is_numeric($_POST['district'])) {
                $district_id = $_POST['district'];
                $raterecord = $this->rate->query("select  a.id,a.district_id,a.taluka_id,a.village_id,b.village_name_en,
                                                a.developed_land_types_id,c.developed_land_types_desc_en,a.ulb_type_id,
                                                a.corp_id,g.governingbody_name_en,a.segment_no, e.class_description_en,
                                                a.level1_id, i.level_1_desc_en,a.level1_list_id,d.list_1_desc_en,
                                                a.usage_main_catg_id,l.usage_main_catg_desc_en, a.usage_sub_catg_id,
                                                u.usage_sub_catg_desc_en,b.lr_code, a.prop_rate, a.prop_unit,o.unit_desc_en                                              
                                                from ngdrstab_mst_rate a
                                                inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id = a.village_id
                                                inner join ngdrstab_mst_developed_land_types c on c.developed_land_types_id = a.developed_land_types_id
                                                 inner join ngdrstab_conf_admblock_local_governingbody e on e.ulb_type_id = a.ulb_type_id
                                                inner join ngdrstab_conf_admblock_local_governingbody_list g on g.corp_id = a.corp_id                          
                                                inner join ngdrstab_mst_loc_level_1_prop_list d on d.prop_level1_list_id = a.level1_list_id                        
                                                inner join ngdrstab_mst_location_levels_1_property i on i.level_1_id = a.level1_id 
                                                inner join ngdrstab_mst_usage_sub_category u on a.usage_sub_catg_id = u.usage_sub_catg_id						
                                                inner join ngdrstab_mst_usage_main_category l on l.usage_main_catg_id = a.usage_main_catg_id
                                                inner join ngdrstab_mst_unit o on o.unit_id = a.prop_unit
                                                where  finyear_id=6 and a.district_id = $district_id order by a.district_id"); //finyear_id=6 and
            }
//        pr($raterecord);
//        exit;
            $this->set(compact('lang', 'raterecord', 'usage_main'));
        } catch (Exception $exc) {
//            pr($exc);
//            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }
    
    public function getcorplist() {
        //echo 1;exit;
        try {
            $lang = $this->Session->read("sess_langauge");

            if (isset($_GET['district'])) {
                $district_id = $_GET['district'];
//                $corp_id = ClassRegistry::init('corporationclass')->find('list', array('fields' => array('corp_id'), 'conditions' => array('ulb_type_id' => array($ulb_type_id))));
                $corplistname = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => $district_id), 'order' => array('governingbody_name_en' => 'ASC')));

                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['corplist'] = $corplistname;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($corplistname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            pr($e);
        }
    }

    //////===========================================updation location=====================================================


    public function get_village() {
        try {

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['tal']) and is_numeric($_GET['tal'])) {
                $tal = $_GET['tal'];

                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));

                echo json_encode($villagelist);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function location_list() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain']);
            $talukadata = $corplistdata = $hfid = $hfupdateflag = NULL;

            $user_id = $this->Auth->User("user_id");
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
//           
            $this->set('districtdata', Null);
            $distmapping = $this->rate->query("select district_id from ngdrstab_temp_user_dist_mapping where user_id= $user_id");
            if (!empty($distmapping)) {
                if ($distmapping[0][0]['district_id'] == 99999) {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
                } else {
                    $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid, 'district_id' => $distmapping[0][0]['district_id']), 'order' => array('district_name_en' => 'ASC'))));
                }
            } else {
//                 echo 1;exit;
                $this->Session->setFlash(__("User Not Valid To The Changes...!"));
                // $this->redirect(array('controller' => 'Rate', 'action' => 'rate'));
            }

            // $this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('district_name_en' => 'ASC'))));
            $this->set('landtypedata', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('developed_land_types_desc_en' => 'ASC'))));
            $this->set('corpclassdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('usage_main', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));
            $this->set(compact('lang', 'raterecord', 'talukadata', 'corplistdata', 'hfid', 'hfupdateflag'));

            $fieldlist = array();
            $fieldlist['level_1_id']['select'] = 'is_select_req';
            $fieldlist['village_id']['select'] = 'is_select_req';

            $fieldlist['district_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_select_req';
            $fieldlist['level_1_desc_ll']['text'] = 'unicode_rule_ll';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_location() {
        try {

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['village_id']) and is_numeric($_GET['village_id'])) {
                $village = $_GET['village_id'];

                $location = ClassRegistry::init('Levels_1_property')->find('list', array('fields' => array('level_1_id', 'level_1_desc_' . $lang), 'conditions' => array('village_id' => $village)));

                echo json_encode($location);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function locationlistgrid() {
        array_map([$this, 'loadModel'], ['rate', 'Usagemainmain', 'Usagesub', 'UnitMapping']);
        $raterecord = NULL;
        $lang = $this->Session->read("sess_langauge");


        if (isset($_POST['district']) && is_numeric($_POST['district']) && isset($_POST['taluka']) && is_numeric($_POST['taluka'])) {
            $district_id = $_POST['district'];
            $taluka_id = $_POST['taluka'];
            $village_id = $_POST['village_id'];
            $level_1_id = $_POST['level_1_id'];
            $record = $this->rate->query("select list_1_desc_en,list_1_desc_ll ,prop_level1_list_id from ngdrstab_mst_loc_level_1_prop_list
                                                where district_id = $district_id and taluka_id = $taluka_id and village_id=$village_id and level_1_id=$level_1_id");


            $this->set(compact('lang', 'record'));
        }
    }

    function update_location_list() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['Level1']);
            if (isset($_POST['level_name']) and is_numeric($_POST['id'])) {
                $stateid = $this->Auth->User("state_id");


                $record['Level1']['state_id'] = $stateid;
                $record['Level1']['user_id'] = $this->Auth->User('user_id');
                $record['Level1']['created_date'] = date('Y/m/d H:i:s');
                $record['Level1']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $record['Level1']['list_1_desc_ll'] = $_POST['level_name'];

//18370
                $this->Level1->id = $this->Level1->field('id', array('prop_level1_list_id' => $_POST['id']));
                if ($this->Level1->save($record)) {
                    echo $_POST['level_name'];
                } else {
                    echo 'F';
                }
            }
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_ll_field() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['Levels_1_property']);
            if (isset($_POST['level_1_id']) and is_numeric($_POST['level_1_id'])) {
                echo $this->Levels_1_property->field('level_1_desc_ll', array('level_1_id' => $_POST['level_1_id']));
            }
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function update_location() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['Levels_1_property']);
            if (isset($_POST['level_name']) and is_numeric($_POST['id'])) {



                $stateid = $this->Auth->User("state_id");

                $this->Levels_1_property->id = $this->Levels_1_property->field('id', array('level_1_id' => $_POST['id']));


                $record['Levels_1_property']['state_id'] = $stateid;
                $record['Levels_1_property']['user_id'] = $this->Auth->User('user_id');
                $record['Levels_1_property']['created_date'] = date('Y/m/d H:i:s');
                $record['Levels_1_property']['req_ip'] = $_SERVER['REMOTE_ADDR'];
                $record['Levels_1_property']['level_1_desc_ll'] = $_POST['level_name'];

//18370
                if ($this->Levels_1_property->save($record)) {
                    echo $_POST['level_name'];
                } else {
                    echo 'F';
                }
            }
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
