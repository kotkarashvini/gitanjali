<?php

App::uses('Sanitize', 'Utility');

class MHRateController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);

//        $this->Security->unlockedActons = array();
        $this->Auth->allow('rate', 'rategrid_update');

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    function set_common_fields() {
        $data['state_id'] = $this->Auth->User("state_id");
        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['user_id'] = $this->Auth->User('user_id');
        return $data;
    }

    public function rate_delete($id = null) {
        $this->autoRender = false;
        $this->loadModel('rate');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->rate->id = $id;
                if ($this->rate->delete($id)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('controller' => 'MHRate', 'action' => 'rate'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function rate() {
        try {
            array_map([$this, 'loadModel'], ['rate', 'VillageMapping', 'Levels_1_property', 'Level1', 'levelmapping', 'Usagemain','mainlanguage','usage_category']);
            $hfactionflag = $hfid = $hfaction = $divdata = $distdata = $subdivdata = $talukadata = $ulbdata = $govbody = $zonedata = $subzonedata = $subcatdata = $subsubcatdata = $constdata = $roadvicdata = $userdep1data = $userdep2data = $villagedata = $locdata = $loclistdata = $config = NULL;
            $lang = $this->Session->read("sess_langauge");
            $this->set('Developedland', ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_' . $lang => 'ASC'))));
            $this->set('Usagemain', ClassRegistry::init('Usagemainmain')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'order' => array('usage_main_catg_desc_' . $lang => 'ASC'))));
            $this->set(compact('lang', 'hfactionflag', 'hfid', 'hfaction', 'divdata', 'distdata', 'subdivdata', 'talukadata', 'ulbdata', 'govbody', 'zonedata', 'subzonedata', 'subcatdata', 'subsubcatdata', 'constdata', 'roadvicdata', 'userdep1data', 'userdep2data', 'villagedata', 'locdata', 'loclistdata', 'config'));
         
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);
            $this->set("fieldlist", $fieldlist = $this->rate->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));



            if ($this->request->is('post')) {
                $data = $this->request->data['rate'];
                $commondata = $this->set_common_fields();
                $data = array_merge($data, $commondata);
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $this->set('hfaction', $_POST['hfaction']);
                $this->set('hfid', $_POST['hfid']);
                $this->set('hfactionflag', $_POST['hfactionflag']);
//                if ($_POST['hfaction'] == 1) {
                $config = $this->rate->query("select * from ngdrstab_conf_rate_search where developed_land_types_id=? and usage_main_cat_id=?
                                            and ready_reckoner_rate_flag=?", array($data['developed_land_types_id'], $data['usage_main_catg_id'], $data['readyrecflag']));
                if (!empty($config)) {
                    $this->set('finyear', ClassRegistry::init('finyear')->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'order' => array('finyear.current_year' => 'desc'))));
                    if ($config[0][0]['division_id'] == 'Y') {
                        $this->set('divdata', ClassRegistry::init('divisionnew')->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_name_' . $lang => 'ASC'))));
                    } else {
                        $this->set('distdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_name_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['ulb_type_id'] == 'Y') {
                        $this->set('ulbdata', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'order' => array('class_description_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['valutation_zone_id'] == 'Y') {
                        $this->set('zonedata', ClassRegistry::init('valuationzone')->find('list', array('fields' => array('valutation_zone_id', 'valuation_zone_desc_' . $lang), 'order' => array('valuation_zone_desc_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['valutation_subzone_id'] == 'Y') {
                        $this->set('subzonedata', ClassRegistry::init('valuationsubzone')->find('list', array('fields' => array('valutation_subzone_id', 'name'), 'order' => array('name' => 'ASC'))));
                    }
                    if ($config[0][0]['construction_type_id'] == 'Y') {
                        $this->set('constdata', ClassRegistry::init('constructiontype')->find('list', array('fields' => array('construction_type_id', 'construction_type_desc_' . $lang), 'order' => array('construction_type_desc_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['road_vicinity_id'] == 'Y') {
                        $this->set('roadvicinity', ClassRegistry::init('roadvicinity')->find('list', array('fields' => array('road_vicinity_id', 'road_vicinity_desc_' . $lang), 'order' => array('road_vicinity_desc_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['user_defined_dependency1_id'] == 'Y') {
                        $this->set('userdependency1', ClassRegistry::init('user_defined_dependancy1')->find('list', array('fields' => array('user_defined_dependency1_id', 'user_defined_dependency1_desc_' . $lang), 'order' => array('user_defined_dependency1_desc_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['user_defined_dependency2_id'] == 'Y') {
                        $this->set('userdependency2', ClassRegistry::init('user_defined_dependancy2')->find('list', array('fields' => array('user_defined_dependency2_id', 'user_defined_dependency2_desc_' . $lang), 'order' => array('user_defined_dependency2_desc_' . $lang => 'ASC'))));
                    }
                    if ($config[0][0]['usage_sub_catg_id'] == 'Y') {
                        $subid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_category.usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => array($data['usage_main_catg_id']))));
                        $this->set('subcatdata', ClassRegistry::init('Usagesub')->find('list', array('fields' => array('Usagesub.usage_sub_catg_id', 'Usagesub.usage_sub_catg_desc_en'), 'conditions' => array('usage_sub_catg_id' => $subid))));
                    }
                }

                $this->set('config', $config);
//                }
                if ($_POST['hfaction'] == 2) {
                    if ($_POST['hfactionflag'] == 'S' || $_POST['hfactionflag'] == 'U') {
                        if ($_POST['hfactionflag'] == 'U') {
                            $data['id'] = $_POST['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
//                        pr($data);exit;
                        $data['ready_reckoner_rate_flag']=$data['readyrecflag'];
                        if ($this->rate->save($data)) {
                            $this->Session->setFlash(__($actionvalue));
//                        $this->redirect(array('controller' => 'Masters', 'action' => 'regconfig'));
                        } else {
                            $this->Session->setFlash(__("lblnotsavemsg"));
                        }
                        $_POST['hfactionflag'] = 'D';
                    }
                    if (isset($json2array['distdata'])) {
                        $this->set('distdata', $json2array['distdata']);
                    }
                    if (isset($json2array['subdivdata'])) {
                        $this->set('subdivdata', $json2array['subdivdata']);
                    }
                    if (isset($json2array['talukadata'])) {
                        $this->set('talukadata', $json2array['talukadata']);
                    }
                    if (isset($json2array['villagedata'])) {
                        $this->set('villagedata', $json2array['villagedata']);
                    }
                    if (isset($json2array['locdata'])) {
                        $this->set('locdata', $json2array['locdata']);
                    }
                    if (isset($json2array['loclistdata'])) {
                        $this->set('loclistdata', $json2array['loclistdata']);
                    }
                    if (isset($json2array['subsubcatdata'])) {
                        $this->set('subsubcatdata', $json2array['subsubcatdata']);
                    }
                    $finyear = $data['finyear_id'];
                    $condition = " a.finyear_id = $finyear";
                    if (isset($data['division_id'])) {
                        $division_id = $data['division_id'];
                        $condition = $condition . " and a.division_id=$division_id";
                    }
                    if (isset($data['district_id'])) {
                        $district_id = $data['district_id'];
                        $condition = $condition . " and a.district_id=$district_id";
                    }
                    if (isset($data['subdivision_id'])) {
                        $subdivision_id = $data['subdivision_id'];
                        $condition = $condition . " and a.subdivision_id=$subdivision_id";
                    }
                    if (isset($data['taluka_id'])) {
                        $taluka_id = $data['taluka_id'];
                        $condition = $condition . " and a.taluka_id=$taluka_id";
                    }
                    if (isset($data['ulb_type_id'])) {
                        $ulb_type_id = $data['ulb_type_id'];
                        $condition = $condition . " and a.ulb_type_id=$ulb_type_id";
                    }
                    if (isset($data['valutation_zone_id'])) {
                        $valutation_zone_id = $data['valutation_zone_id'];
                        $condition = $condition . " and a.valutation_zone_id=$valutation_zone_id";
                    }
                    if (isset($data['developed_land_types_id'])) {
                        $developed_land_types_id = $data['developed_land_types_id'];
                        $condition = $condition . " and a.developed_land_types_id=$developed_land_types_id";
                    }
                    if (isset($data['valutation_zone_id'])) {
                        $valutation_zone_id = $data['valutation_zone_id'];
                        $condition = $condition . " and a.valutation_zone_id=$valutation_zone_id";
                    }
                    if (isset($data['village_id'])) {
                        $village_id = $data['village_id'];
                        $condition = $condition . " and a.village_id=$village_id";
                    }
                    if (isset($data['level1_id'])) {
                        $level1_id = $data['level1_id'];
                        $condition = $condition . " and a.level1_id=$level1_id";
                    }
                    if (isset($data['level1_list_id'])) {
                        $level1_list_id = $data['level1_list_id'];
                        $condition = $condition . " and a.level1_list_id=$level1_list_id";
                    }
                    if (isset($data['usage_sub_catg_id'])) {
                        $usage_sub_catg_id = $data['usage_sub_catg_id'];
                        $condition = $condition . " and a.usage_sub_catg_id=$usage_sub_catg_id";
                    }
                    if (isset($data['usage_sub_sub_catg_id'])) {
                        $usage_sub_sub_catg_id = $data['usage_sub_sub_catg_id'];
                        $condition = $condition . " and a.usage_sub_sub_catg_id=$usage_sub_sub_catg_id";
                    }
                    if (isset($data['valutation_subzone_id'])) {
                        $valutation_subzone_id = $data['valutation_subzone_id'];
                        $condition = $condition . " and a.valutation_subzone_id=$valutation_subzone_id";
                    }
                    if (isset($data['construction_type_id'])) {
                        $construction_type_id = $data['construction_type_id'];
                        $condition = $condition . " and a.construction_type_id=$construction_type_id";
                    }
                    if (isset($data['road_vicinity_id'])) {
                        $road_vicinity_id = $data['road_vicinity_id'];
                        $condition = $condition . " and a.road_vicinity_id=$road_vicinity_id";
                    }
                    if (isset($data['user_defined_dependency1_id'])) {
                        $user_defined_dependency1_id = $data['user_defined_dependency1_id'];
                        $condition = $condition . " and a.user_defined_dependency1_id=$user_defined_dependency1_id";
                    }
                    if (isset($data['user_defined_dependency2_id'])) {
                        $user_defined_dependency2_id = $data['user_defined_dependency2_id'];
                        $condition = $condition . " and a.user_defined_dependency2_id=$user_defined_dependency2_id";
                    }
                    if (isset($data['usage_main_cat_id'])) {
                        $usage_main_cat_id = $data['usage_main_cat_id'];
                        $condition = $condition . " and a.usage_main_cat_id=$usage_main_cat_id";
                    }
                    if (isset($data['readyrecflag'])) {
                        $ready_reckoner_rate_flag = $data['readyrecflag'];
                        $condition = $condition . " and a.ready_reckoner_rate_flag='$ready_reckoner_rate_flag'";
                    }

                    $raterecord = $this->rate->query("select distinct a.id, a.prop_rate, a.prop_unit                                              
                                            from ngdrstab_mst_rate a
                                            where $condition");
                    $this->set('raterecord', $raterecord);
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
                            'UnitMapping.usage_sub_catg_id' => $data['usage_sub_catg_id'],
                        ), 'order' => 'sr_no ASC'
                            )
                    );
                    $this->set('unit', $unit);
                }
            }
        } catch (Exception $exc) {
            pr($exc);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //---------------Division->District filteration
    public function getdist() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['division_id']) && is_numeric($data['division_id'])) {
                $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('division_id' => $data['division_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['distdata'] = $distdata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($distdata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gettaluka() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;


            if (isset($data['subdivision_id']) && is_numeric($data['subdivision_id'])) {
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $data['subdivision_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['talukadata'] = $talukadata1;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($talukadata1);
                exit;
            } else if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['talukadata'] = $talukadata1;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($talukadata1);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //---------------District->Subdivision filteration
    public function getsubdiv() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['subdivdata'] = $subdivisiondata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($subdivisiondata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gettalukadist() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $talukadata = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['talukadata'] = $talukadata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($talukadata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getvillagedist() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['villagedata'] = $villagedata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($villagedata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getvillage() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['taluka_id']) && is_numeric($data['taluka_id'])) {
                $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $data['taluka_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['villagedata'] = $villagedata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($villagedata);
                exit;
            } else {
                if (isset($data['circle_id']) && is_numeric($data['circle_id'])) {
                    $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('circle_id' => $data['circle_id'])));
                    $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $json2array['villagedata'] = $villagedata;
                    $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));
                    echo json_encode($villagedata);
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getlevel1() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['village_id']) && is_numeric($data['village_id'])) {
                $locdata = ClassRegistry::init('Levels_1_property')->find('list', array('fields' => array('level_1_id', 'level_1_desc_' . $lang), 'conditions' => array('village_id' => $data['village_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['locdata'] = $locdata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($locdata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getlevel1list() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            $this->loadModel('loc_level_1_prop_list');
            
            if (isset($data['level1_id']) && is_numeric($data['level1_id'])) {
                $loclistdata = ClassRegistry::init('loc_level_1_prop_list')->find('list', array('fields' => array('prop_level1_list_id', 'list_1_desc_' . $lang), 'conditions' => array('level_1_id' => $data['level1_id'])));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['loclistdata'] = $loclistdata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($loclistdata);
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getusagesubsub() {
        try {
            $this->autoRender = FALSE;
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['usage_sub_catg_id']) && is_numeric($data['usage_sub_catg_id'])) {
                $subsubid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_sub_catg_id'), 'conditions' => array('usage_sub_catg_id' => array($data['usage_sub_catg_id']))));
                $subsubcatdata = ClassRegistry::init('Usagesubsub')->find('list', array('fields' => array('Usagesubsub.usage_sub_sub_catg_id', 'Usagesubsub.usage_sub_sub_catg_desc_en'), 'conditions' => array('usage_sub_sub_catg_id' => $subsubid)));
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['subsubcatdata'] = $subsubcatdata;
                $file = new File(WWW_ROOT . 'files/ratejsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($subsubcatdata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
