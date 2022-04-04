<?php

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class AppController extends Controller {

// public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $persistModel = true;
//    if($citizenflag =='c'){
//         public $components = array(
//        'Security', 'RequestHandler', 'Cookie', 'Captcha',
//        'Session',
//        'Auth' => array(
//            'loginRedirect' => array('controller' => 'Users', 'action' => 'welcome'),
//            'logoutRedirect' => array('controller' => 'Users', 'action' => 'login'),
//            'authError' => 'You must be logged in to view this page.',
//            'loginError' => 'Invalid Username or Password entered, please try again.',
//            'authorize' => array('Controller')
//    ));
//    }else{
//comment on 1/6/2017 check
    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Users', 'action' => 'welcome'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'welcomenote'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.',
            'authorize' => array('Controller')
    ));
//    }


    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

//    $this->logoutdemo();
//    public function logoutdemo(){
//            $citizenflag = $this->Session->read("session_citizen");
//        
//        if($citizenflag=='c'){
//            return 'logout';
//        }
//        else{
//            return 'new_user';
//        }
//    }
    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();

        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
//        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0,$this->Auth->user('state_id')))));
//        $this->set('langaugelist', $langaugelist);
//        if ($this->Session->check('Auth.User')) {
//            $state_id = $this->Auth->user('state_id');
//            $langaugelist = $this->mainlanguage->query("select A.language_name,A.language_code from ngdrstab_mst_language A 
//                inner join ngdrstab_conf_language B on B.language_id=A.id where B.state_id= $state_id");
//            pr($langaugelist);
//            $this->set('langaugelist', $langaugelist);
//        }
        $this->response->disableCache();
        //$this->Security->unlockedActions = array('getsurveynumbers', 'exception_occurred', 'login', 'langaugechange', 'welcome', 'propertyscreen', 'empregistration', 'activate', 'checkcaptcha', 'checkemail', 'checkmobileno', 'checkuser', 'citizenregistration', 'emoplyeereportlist', 'district_new', 'divisionnew', 'taluka', 'village','csrftoken','document_disposal');
        //  $this->Auth->allow('scan', 'checkscan', 'loadfile', 'upload', 'welcomenote', 'exception_occurred', 'login', 'index', 'index1', 'index2', 'Disclaimer', 'registration', 'get_district_name', 'checkuser', 'aboutus', 'insertuser', 'checkorg', 'checkcaptcha', 'checkemail', 'send_sms', 'multiple_upload', 'biometricpopup', 'empregistration', 'activate', 'checkmobileno', 'get_taluka_name', 'get_division_name', 'citizenregistration', 'district_new', 'divisionnew', 'taluka','csrftoken');
        $this->Auth->allow('gras_payment_entry', 'gras_payment_response');
        $this->request->addDetector('ssl', array('callback' => function() {
                return CakeRequest::header('X-Forwarded-Proto') == 'https';
            }));

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }
	
	 //------------------- By Shridhar on 20-04-2021-------------------------------------------------------------
    function loadModels($arrModels = NULL) {
        $args = func_get_args();
        if (is_string($arrModels) && !is_array($args)) {
            $arrModels = explode(',', $arrModels);
        } else if (!is_array($arrModels)) {
            $arrModels = $args;
        }
        return array_map([$this, 'loadModel'], $arrModels);
    }

    /**
     * make just two dimentional array from multidimentional array for fetched data with query
     * @param array $data
     * @param type $moduleName
     * @return type
     */
    function formatData(array $data, $moduleName = '', $indexField) {
        $result = array();
        foreach ($data as $record) {
            $key = empty($record[$moduleName][$indexField]) ? $record[0][$indexField] : $record[$moduleName][$indexField];
            $result[$key] = array_reduce($record, 'array_merge', []);
        }
        return $result;
    }

    function createControllerObject($ControllerName = 'App') {
        $ControllerName .= 'Controller';
        $objCitizen = new $ControllerName();
        $objCitizen->constructClasses();
        return $objCitizen;
    }
    
    

    //----------------------------------Check if given input is date-------------------------------------------------------------
    public function is_Date($str) {
        $str = str_replace('/', '-', $str);
        $stamp = strtotime($str);
        if (is_numeric($stamp)) {

            $month = date('m', $stamp);
            $day = date('d', $stamp);
            $year = date('Y', $stamp);

            return checkdate($month, $day, $year);
        }
        return false;
    }

    function date_compaire($start_date, $end_date, $entrydate) {
        $returnflag = 0;
        if (!empty($start_date) && !empty($end_date)) {
            if (strpos($start_date, 'd') !== false) {
                $start_date = str_replace("d", " days", $start_date);
            } else if (strpos($start_date, 'm') !== false) {
                $start_date = str_replace("m", " months", $start_date);
            } else if (strpos($start_date, 'y') !== false) {
                $start_date = str_replace("y", " years", $start_date);
            }

            $cmpdt = date("Y-m-d", strtotime(date('Y-m-d') . $start_date));
            $datestartobj = new DateTime($cmpdt);

            if (strpos($end_date, 'd') !== false) {
                $end_date = str_replace("d", " days", $end_date);
            } else if (strpos($end_date, 'm') !== false) {
                $end_date = str_replace("m", " months", $end_date);
            } else if (strpos($end_date, 'y') !== false) {
                $end_date = str_replace("y", " years", $end_date);
            }

            $cmpdt = date("Y-m-d", strtotime(date('Y-m-d') . $end_date));
            $dateendobj = new DateTime($cmpdt);
            $entrydateobj = new DateTime($entrydate);

            if ($entrydateobj >= $datestartobj && $entrydateobj <= $dateendobj) {
                $returnflag = 1;
            }
        }
        return $returnflag;
    }

    // -------------------------------------------End ----------------------------------------------------------

    public function isAuthorized($user) {
        return true;
    }

  
    public function load_json_file() {
// Load Models
        array_map([$this, 'loadModel'], ['User', 'finyear', 'levelconfig', 'conf_reg_bool_info', 'genernalinfoentry']);        //3 18 12 12 
        array_map([$this, 'loadModel'], ['State', 'divisionnew', 'District', 'Subdivision', 'circle', 'VillageMapping', 'Developedlandtype', 'corporationclass', 'corporationclasslist', 'valuationsubzone', 'Level1', 'Level2', 'Level3', 'Level4', 'Levels_1_property', 'Level2_property', 'Level3_property', 'Level4_property']);
        array_map([$this, 'loadModel'], ['usage_main_category', 'usage_sub_category', 'usage_sub_sub_category', 'usage_category', 'damblkdpnd', 'usagelinkcategory', 'evalrule', 'subrule', 'evalcalculation', 'valuation', 'valuation_details', 'propertyarticledetails']);
        array_map([$this, 'loadModel'], ['rate', 'surveynorate', 'constructiontype', 'depreciation', 'roadvicinity', 'user_defined_dependancy1', 'user_defined_dependancy2', 'dependencyitems', 'areaconversion', 'rateconversion', 'ratefactor', 'tdrfactor']);
        array_map([$this, 'loadModel'], ['attribute_parameter', 'BehavioralPattens', 'ListItems', 'Developedlandtype']);
        // Set Multiple Variable to NULL/0/ empty
        $circle=$divisiondata = $districtdata = $subdivisiondata = $taluka = $blockdata = $corpclassdata = $corpclasslist = $villagenname = $Developedland = $level1propertylist = $level2propertylist = $level3propertylist = $level4propertylist = $level1propertydata = $level2propertydata = $level3propertydata = $level4propertydata = $design = $landtype = array();
        $valuation_id = NULL;
        $this->set(compact('divisiondata', 'districtdata', 'subdivisiondata', 'taluka', 'circle', 'corpclassdata', 'corpclasslist', 'villagenname', 'Developedland', 'level1propertylist', 'level2propertylist', 'level3propertylist', 'level4propertylist', 'level1propertydata', 'level2propertydata', 'level3propertydata', 'level4propertydata', 'valuation_id', 'design', 'landtype'));
        $actiontype = $hfusage_sub_sub_catg_id = $hfevalrule_id = $hflevel1list = $hflevel2list = $hflevel3list = $hflevel4list = $hflevel2 = $hflevel3 = $hflevel4 = 0;
        $usageitemlist = $subsubcat_id = $subcat_id = $rate = $outputfield = $subruleconditions = NULL;
        $hfconstructionflag = $hfdepreciationflag = $hfroadvicinityflag = $hfuserdependency1flag = $hfuserdependency2flag = 'N';
        $this->set(compact('actiontype', 'hfusage_sub_sub_catg_id', 'hfevalrule_id', 'usageitemlist', 'subsubcat_id', 'subcat_id', 'rate', 'outputfield', 'subruleconditions', 'hfconstructionflag', 'hfdepreciationflag', 'hfroadvicinityflag', 'hfuserdependency1flag', 'hfuserdependency2flag', 'hflevel1list', 'hflevel2list', 'hflevel3list', 'hflevel4list', 'hflevel2', 'hflevel3', 'hflevel4'));

      
// Declare variable

        $stateid = $this->Auth->User("state_id");
        $language = $this->Session->read("sess_langauge");

// One Time Load Basic Data When Action Call 
        if (!$this->request->is('post') && !$this->request->is('put')) {
            $token = $this->Session->read('Selectedtoken');
            $json2array = array();
            $json2array['usagecategory'] = $this->evalrule->find('list', array('conditions' => array('display_flag' => 'Y'), 'fields' => array('evalrule_id', 'evalrule_desc_' . $this->Session->read("sess_langauge")), 'order' => 'evalrule_id DESC'));
            $json2array['maincat_id'] = $this->usage_main_category->find('list', array('fields' => array('usage_main_category.usage_main_catg_id', 'usage_main_category.usage_main_catg_desc_' . $this->Session->read("sess_langauge")), 'order' => 'usage_main_category.usage_main_catg_id'));
            $json2array['constructiontype'] = $this->constructiontype->find('list', array('fields' => array('constructiontype.construction_type_id', 'constructiontype.construction_type_desc_' . $this->Session->read("sess_langauge")), 'order' => 'display_order'));
            $json2array['depreciation'] = $this->depreciation->find('list', array('fields' => array('depreciation.deprication_type_id', 'depreciation.deprication_type_desc_' . $this->Session->read("sess_langauge")), 'order' => 'display_order'));
            $json2array['roadvicinity'] = $this->roadvicinity->find('list', array('fields' => array('roadvicinity.road_vicinity_id', 'roadvicinity.road_vicinity_desc_' . $this->Session->read("sess_langauge")), 'order' => 'road_vicinity_id DESC'));
            $json2array['user_defined_dependancy1'] = $this->user_defined_dependancy1->find('list', array('fields' => array('user_defined_dependancy1.user_defined_dependency1_id', 'user_defined_dependancy1.user_defined_dependency1_desc_' . $this->Session->read("sess_langauge")), 'order' => 'user_defined_dependency1_id asc'));
            $json2array['user_defined_dependancy2'] = $this->user_defined_dependancy2->find('list', array('fields' => array('user_defined_dependancy2.user_defined_dependency2_id', 'user_defined_dependancy2.user_defined_dependency2_desc_' . $this->Session->read("sess_langauge")), 'order' => 'user_defined_dependency2_id asc'));
            $json2array['akar_ranges'] = array_unique($this->valuationsubzone->find('list', array('fields' => array('valuationsubzone.name'))));
            $json2array['configure'] = $this->damblkdpnd->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));


            if (is_numeric($token)) {
                $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($gentalukaconf)) {
                    $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $language),
                        'conditions' => array('division_id' => $this->District->find('list', array(
                                'fields' => array('division_id', 'division_id'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'District.district_id=link.district_id')),
                                ),
                                    )
                            )
                    )));
                } else {

                    $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $language),
                        'conditions' => array('division_id' => $this->District->find('list', array(
                                'fields' => array('division_id', 'division_id'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'District.district_id=link.district_id')),
                                ),
                                    )
                            )
                    )));
                }
            } else {
                $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $language)));
            }
           
            $json2array['divisiondata'] = $divisiondata;

            if (is_numeric($token)) {
                $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($gentalukaconf)) {
                    $json2array['districtnname'] = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'District.district_id=link.district_id')),
                        ),
                        'conditions' => array('District.state_id' => $stateid), 'order' => 'district_name_' . $language));
                } else {
                    $json2array['districtnname'] = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'District.district_id=link.district_id')),
                        ),
                        'conditions' => array('District.state_id' => $stateid), 'order' => 'district_name_' . $language));
                }
            } else if ($json2array['configure'][0][0]['is_div'] == 'Y') {
                $json2array['districtnname'] = NULL;
            } else {
                $json2array['districtnname'] = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $language), 'conditions' => array('state_id' => $stateid), 'order' => 'district_name_' . $language));
            }



            $json2array['configure1'] = $this->levelconfig->find('all', array('Conditions', array('state_id' => $stateid)));
            $json2array['landtypes'] = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $language)));
            $json2array['finyearList'] = $this->finyear->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'conditions' => array('display_flag' => 'Y'), 'order' => array('current_year DESC,finyear_id')));
            $json2array['usage_rule_link'] = $this->usagelinkcategory->query(" SELECT mc.usage_main_catg_desc_" . $language . ",mc.usage_main_catg_id,sc.usage_sub_catg_id, sc.usage_sub_catg_desc_" . $language . " FROM   ngdrstab_mst_usage_main_category mc,ngdrstab_mst_usage_sub_category sc,ngdrstab_mst_usage_lnk_category link  WHERE  link.usage_main_catg_id=mc.usage_main_catg_id AND link.usage_sub_catg_id =sc.usage_sub_catg_id group By mc.usage_main_catg_desc_" . $language . ",mc.usage_main_catg_id,sc.usage_sub_catg_id, sc.usage_sub_catg_desc_" . $language . "");
            $json2array['attributes'] = $this->attribute_parameter->find("list", array('fields' => array('attribute_id', 'eri_attribute_name_en'), 'conditions' => array('state_id' => $stateid)));
            $json2array['prop_attributes'] = array();
            $json2array['BehavioralPatterns'] = array();
            $json2array['listitemsoptions'] = $this->ListItems->find("all", array('conditions' => array('state_id' => $stateid)));
            $json2array['landtype'] = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $language), 'conditions' => array('state_id' => $stateid)));


// write to json file
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
        }

        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
        $json = $file->read(true, 'r');
        $json2array = json_decode($json, TRUE);

//Set Variable for Every Page Rander 
        
        
        $this->set('divisiondata', $json2array['divisiondata']);
        $this->set('districtdata', $json2array['districtnname']);
        $this->set('configure', $json2array['configure']);
        $this->set('configure1', $json2array['configure1']);
        $this->set('landtypes', $json2array['landtypes']);
        $this->set('finyearList', $json2array['finyearList']);
        $this->set('usagecategory', $json2array['usagecategory']);
        $this->set('maincat_id', $json2array['maincat_id']);
        $this->set('usage_rule_link', $json2array['usage_rule_link']);
        $this->set('construction_type_id', $json2array['constructiontype']);
        $this->set('depreciation_id', $json2array['depreciation']);
        $this->set('road_vicinity_id', $json2array['roadvicinity']);
        $this->set('user_defined_dependancy1', $json2array['user_defined_dependancy1']);
        $this->set('user_defined_dependancy2', $json2array['user_defined_dependancy2']);

        $this->set('attributes', $json2array['attributes']);
        $this->set('prop_attributes', $json2array['prop_attributes']);
        $this->set('BehavioralPatterns', $json2array['BehavioralPatterns']);
        
        $this->set('locationsearchconf', $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 69, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y'))));


        $token = $this->Session->read('Selectedtoken');
        $propgroup = 0;
        $propgroupconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 160, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
        if (!empty($propgroupconf)) {
            if (is_numeric($token)) {
                $info = $this->genernalinfoentry->query("select info.token_no from ngdrstab_trn_generalinformation as info 
JOIN ngdrstab_mst_article as article ON  article.article_id=info.article_id and article.exchange_property_flag='Y'
where info.token_no=?", array($token));

                if (!empty($info)) {
                    $propgroup = 1;
                }
            }
        }
        $this->set('propgroup', $propgroup);


        return $json2array;
    }

    // set back posted data    
    public function post_back_valuation_data($json2array, $frmdata = NULL) {
        // pr($json2array);exit;

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->set('actiontype', $_POST['actiontype']);
            $this->set('hfusage_sub_sub_catg_id', $_POST['hfusage_sub_sub_catg_id']);
            $this->set('hfevalrule_id', $_POST['hfevalrule_id']);

            $this->set('hfconstructionflag', $_POST['hfconstructionflag']);
            $this->set('hfdepreciationflag', $_POST['hfdepreciationflag']);
            $this->set('hfroadvicinityflag', $_POST['hfroadvicinityflag']);
            $this->set('hfuserdependency1flag', $_POST['hfuserdependency1flag']);
            $this->set('hfuserdependency2flag', $_POST['hfuserdependency2flag']);

            $this->set('hflevel1list', $_POST['hflevel1list']);
            $this->set('hflevel2list', $_POST['hflevel2list']);
            $this->set('hflevel3list', $_POST['hflevel3list']);
            $this->set('hflevel4list', $_POST['hflevel4list']);
            $this->set('hflevel2', $_POST['hflevel2']);
            $this->set('hflevel3', $_POST['hflevel3']);
            $this->set('hflevel4', $_POST['hflevel4']);
        } else {
            if (!is_null($frmdata)) {
                if (is_numeric($frmdata['construction_type_id'])) {
                    $this->set('hfconstructionflag', 'Y');
                }
                if (is_numeric($frmdata['depreciation_id'])) {
                    $this->set('hfdepreciationflag', 'Y');
                }
                if (is_numeric($frmdata['road_vicinity_id'])) {
                    $this->set('hfroadvicinityflag', 'Y');
                }
                if (is_numeric($frmdata['user_defined_dependency1_id'])) {
                    $this->set('hfuserdependency1flag', 'Y');
                }
                if (is_numeric($frmdata['user_defined_dependency2_id'])) {
                    $this->set('hfuserdependency2flag', 'Y');
                }
                if (isset($json2array['totaldependency']['hfboundaryflag']) && $json2array['totaldependency']['hfboundaryflag'] == 'Y') {
                    $this->set('hfboundaryflag', 'Y');
                }

                if (is_numeric($frmdata['level1_id'])) {
                    $this->set('hflevel1', 1);
                }
                if (is_numeric($frmdata['level2_id'])) {
                    $this->set('hflevel2', 1);
                }if (is_numeric($frmdata['level3_id'])) {
                    $this->set('hflevel3', 1);
                }if (is_numeric($frmdata['level4_id'])) {
                    $this->set('hflevel4', 1);
                }

                if (is_numeric($frmdata['level1_list_id'])) {
                    $this->set('hflevel1list', 1);
                }
                if (is_numeric($frmdata['level2_list_id'])) {
                    $this->set('hflevel2list', 1);
                }
                if (is_numeric($frmdata['level3_list_id'])) {
                    $this->set('hflevel3list', 1);
                }if (is_numeric($frmdata['level4_list_id'])) {
                    $this->set('hflevel4list', 1);
                }
            }
        }


        if (isset($json2array['usageitemlist'])) {
            $this->set('usageitemlist', $json2array['usageitemlist']);
        }
        if (isset($json2array['evalconditions'])) {
            $this->set('outputfield', $json2array['evalconditions']);
        }
        if (isset($json2array['akar_ranges'])) {
            $this->set('akar_ranges', $json2array['akar_ranges']);
        }
        if (isset($json2array['listitemsoptions'])) {
            $this->set('listitemsoptions', $json2array['listitemsoptions']);
        }

         
        if (isset($json2array['divisiondata'])) {
            $this->set('divisiondata', $json2array['divisiondata']);
        }
        if (isset($json2array['districtdata'])) {
            $this->set('districtdata', $json2array['districtdata']);
        }
        if (isset($json2array['subdiv'])) {
            $this->set('subdivisiondata', $json2array['subdiv']);
        } 
                
                
        if (isset($json2array['taluka'])) {
            $this->set('taluka', $json2array['taluka']);
        }
         if (isset($json2array['circle'])) {
            $this->set('circle', $json2array['circle']);
        }

        if (isset($json2array['corp'])) {
            $this->set('corpclasslist', $json2array['corp']);
        }
        if (isset($json2array['subdiv'])) {
            $this->set('subdivisiondata', $json2array['subdiv']);
        }
        if (isset($json2array['village'])) {
            $this->set('villagenname', $json2array['village']);
        }
        if (isset($json2array['landtype'])) {
            $this->set('Developedland', $json2array['landtype']);
        }


        if (isset($json2array['level1']) && !empty($json2array['level1'])) {
            $this->set('level1propertydata', $json2array['level1']);
        } else {
            $this->set('level1propertydata', array());
        }
        if (isset($json2array['level1list']) && !empty($json2array['level1list']) && !empty($json2array['level1'])) {
            $this->set('level1propertylist', $json2array['level1list']);
        } else {
            $this->set('level1propertylist', array());
        }

        if (isset($json2array['level2'])) {
            $this->set('level2propertydata', $json2array['level2']);
        }
        if (isset($json2array['level2list'])) {
            $this->set('level2propertylist', $json2array['level2list']);
        }
        if (isset($json2array['level3'])) {
            $this->set('level3propertydata', $json2array['level3']);
        }
        if (isset($json2array['level3list'])) {
            $this->set('level3propertylist', $json2array['level3list']);
        }
        if (isset($json2array['level4'])) {
            $this->set('level4propertydata', $json2array['level4']);
        }
        if (isset($json2array['level4list'])) {
            $this->set('level4propertylist', $json2array['level4list']);
        }
        if (isset($json2array['BehavioralPatterns'])) {
            $this->set('BehavioralPatterns', $json2array['BehavioralPatterns']);
        }
    }

    public function manage_usage_item_inputfield($json2array, $singlerule) {
        foreach ($json2array['usageitemlist'] as $usageitem) {
            if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) {
                // convert input fields Name  eg. AAH <-- AAH_130
                if ($usageitem['itemlist']['is_input_hidden'] == 'N') {
                    $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "_" . $singlerule['evalrule']['evalrule_id']];
                }
                if ($usageitem['itemlist']['area_field_flag'] == 'Y') {
                    // convert input fields Name  eg. AAHunit <-- AAHunit_130 
                    $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "unit"] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "unit_" . $singlerule['evalrule']['evalrule_id']];
                    // convert input fields Name  eg. AAHareatype <-- AAHareatype_130 
                    if (isset($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "areatype_" . $singlerule['evalrule']['evalrule_id']])) {
                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "areatype"] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "areatype_" . $singlerule['evalrule']['evalrule_id']];
                    }
                }
            }
        }
        // Marge Dependencis 
        // ACD - contruction Type 
        $this->request->data['propertyscreennew']['DA1'] = $this->request->data['propertyscreennew']['construction_type_id'] ? $this->request->data['propertyscreennew']['construction_type_id'] : 0;
    }

    public function rate_selection_range_field($json2array, $singlerule) {


        foreach ($json2array['usageitemlist'] as $usageitem) {
            if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) {
                if ($usageitem['usagelinkcategory']['item_rate_flag'] == 'Y') {
                    $itemratevalue = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] * $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . $usageitem['usagelinkcategory']['usage_param_id']];
                    $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'hf'] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']];
                    $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] = $itemratevalue;
                }
                if ($usageitem['itemlist']['range_field_flag'] == 'Y') {
                    $rangeflag = 'Y';
                    if ($singlerule['evalrule']['dependency_flag'] == 'N') {

                        $dependencyitems = $this->dependencyitems->find('list', array('fields' => array('item_desc')));


                        foreach ($dependencyitems as $dependencyitem) {
                            if ($dependencyitem == 'state_id') {
                                $option['conditions'][$dependencyitem] = $this->Auth->user('state_id');
                                $option7['conditions'][$dependencyitem] = $this->Auth->user('state_id');
                                $additionalrateoptions['conditions'][$dependencyitem] = $this->Auth->user('state_id');
                            } else if ($dependencyitem == 'usage_main_catg_id' || $dependencyitem == 'usage_sub_catg_id' || $dependencyitem == 'usage_sub_sub_catg_id') {
                                $option['conditions'][$dependencyitem] = $singlerule['linkcat'][$dependencyitem];
                                $option7['conditions'][$dependencyitem] = $singlerule['linkcat'][$dependencyitem];
//                                                $additionalrateoptions['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                            } else if ($dependencyitem == 'contsruction_type_flag' || $dependencyitem == 'depreciation_flag' || $dependencyitem == 'road_vicinity_flag' || $dependencyitem == 'user_defined_dependency1_flag' || $dependencyitem == 'user_defined_dependency2_flag') {
//                                                pr($singlerule['usagecat'][$dependencyitem]);
                                if ($singlerule['usagecat'][$dependencyitem] == 'Y' && isset($this->request->data['propertyscreennew'][$dependencyitem]) && is_numeric($this->request->data['propertyscreennew'][$dependencyitem])) {
                                    $option['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                                    $option7['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                                    $additionalrateoptions['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                                }
                            } else if (isset($this->request->data['propertyscreennew'][$dependencyitem]) && is_numeric($this->request->data['propertyscreennew'][$dependencyitem])) {
                                $option['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                                $option7['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                                $additionalrateoptions['conditions'][$dependencyitem] = $this->request->data['propertyscreennew'][$dependencyitem];
                            }
                        }

                        $option['fields'] = array('rate_id', 'prop_rate', 'prop_unit', 'slab_rate_flag', 'range_from', 'range_to', 'rate.land_rate', 'rate.construction_rate');
                        $option7['fields'] = array('rate_id', 'prop_rate', 'prop_unit', 'slab_rate_flag', 'range_from', 'range_to', 'rate.land_rate', 'rate.construction_rate');
                        $option['order'] = 'rate_id';
                        $option7['order'] = 'rate_id';

                        if ($usageitem['itemlist']['rate_table_flag'] == '2') {

                            if (is_numeric(trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]))) {
                                array_push($option['conditions'], trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]) . ' >= cast(range_from as integer)');
                                array_push($option['conditions'], trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]) . ' <= cast(range_to as integer)');
                                array_push($option['conditions'], 'range_to is not null');
                            } else {
                                array_push($option['conditions'], "'" . trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]) . "'" . ' = TRIM(range_from)');
                                array_push($option7['conditions'], "'" . trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]) . "'" . ' = TRIM(range_from)');
                                array_push($option['conditions'], 'range_to is null');
                                array_push($option7['conditions'], 'range_to is null');
                            }

                            $this->rate->useTable = 'ngdrstab_mst_surveyno_rate';
                            $json2array['rate'] = $this->rate->find('all', $option);
                            if ($json2array['rate'] == NULL) {
                                $this->rate->useTable = 'ngdrstab_mst_surveyno_rate';
                                $json2array['rate'] = $this->rate->find('all', $option7);
                            }
                        } else {
                            array_push($option['conditions'], trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]) . ' >= range_from');
                            array_push($option['conditions'], trim($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']]) . ' <= range_to');
                            $json2array['rate'] = $this->rate->find('all', $option);
                        }

                        $json2array['option'] = $option;
                        $json2array['additionalrateoptions'] = $additionalrateoptions;

                        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                        $file->write(json_encode($json2array));
                    } else {


                        $option['conditions'] = array();
                        if ($singlerule['evalrule']['division_id'] != NULL && $singlerule['evalrule']['division_id'] != '0') {
                            $option['conditions']['division_id'] = $singlerule['evalrule']['division_id'];
                        } else {
                            array_push($option['conditions'], 'division_id is null');
                        }
                        if ($singlerule['evalrule']['dist_id'] != NULL && $singlerule['evalrule']['dist_id'] != '0') {
                            $option['conditions']['district_id'] = $singlerule['evalrule']['dist_id'];
                        } else {
                            array_push($option['conditions'], 'district_id is null');
                        }
                        if ($singlerule['evalrule']['subdiv_id'] != NULL && $singlerule['evalrule']['subdiv_id'] != '0') {
                            $option['conditions']['subdivision_id'] = $singlerule['evalrule']['subdiv_id'];
                        } else {
                            array_push($option['conditions'], 'subdivision_id is null');
                        }
                        if ($singlerule['evalrule']['taluka_id'] != NULL && $singlerule['evalrule']['taluka_id'] != '0') {
                            $option['conditions']['taluka_id'] = $singlerule['evalrule']['taluka_id'];
                        } else {
                            array_push($option['conditions'], 'taluka_id is null');
                        }
                        if ($singlerule['evalrule']['circle_id'] != NULL && $singlerule['evalrule']['circle_id'] != '0') {
                            $option['conditions']['circle_id'] = $singlerule['evalrule']['circle_id'];
                        } else {
                            array_push($option['conditions'], 'circle_id is null');
                        }
                        if ($singlerule['evalrule']['land_type_id'] != NULL && $singlerule['evalrule']['land_type_id'] != '0') {
                            $option['conditions']['developed_land_types_id'] = $singlerule['evalrule']['land_type_id'];
                        } else {
                            array_push($option['conditions'], 'developed_land_types_id is null');
                        }
                        if ($singlerule['evalrule']['ulbtype_id'] != NULL && $singlerule['evalrule']['ulbtype_id'] != '0') {
                            $option['conditions']['ulb_type_id'] = $singlerule['evalrule']['ulbtype_id'];
                        } else {
                            array_push($option['conditions'], 'ulb_type_id is null');
                        }
                        if ($singlerule['evalrule']['ulb_id'] != NULL && $singlerule['evalrule']['ulb_id'] != '0') {
                            $option['conditions']['corp_id'] = $singlerule['evalrule']['ulb_id'];
                        } else {
                            array_push($option['conditions'], 'corp_id is null');
                        }
                        if ($singlerule['evalrule']['village_id'] != NULL && $singlerule['evalrule']['village_id'] != '0') {
                            $option['conditions']['village_id'] = $singlerule['evalrule']['village_id'];
                        } else {
                            array_push($option['conditions'], 'village_id is null');
                        }
                        $option['conditions']['usage_main_catg_id'] = $singlerule['linkcat']['usage_main_catg_id'];
                        $option['conditions']['usage_sub_catg_id'] = $singlerule['linkcat']['usage_sub_catg_id'];
                        $option['fields'] = array('rate_id', 'prop_rate', 'prop_unit', 'slab_rate_flag', 'range_from', 'range_to', 'rate.land_rate', 'rate.construction_rate');
                        $option['order'] = 'rate_id';


                        if ($usageitem['itemlist']['rate_table_flag'] == '2') {

                            $this->rate->useTable = 'ngdrstab_mst_surveyno_rate';
                            $json2array['rate'] = $this->rate->find('all', $option);
                        } else {
                            $json2array['rate'] = $this->rate->find('all', $option);
                        }

                        $json2array['option'] = $option;
                        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                        $file->write(json_encode($json2array));
                    }
                }
            }
        }

        return $json2array;
    }

    /* ------- Rate Selection Funtion ------------

     * Query Depends Rate Selection Rule 
     * Rate Not Depends Location - dependency_flag is N
      --------------------------------------------
     */

    public function rate_slection_rule($json2array, $singlerule) {
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');
        $this->loadModel('usagelinkcategory');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];
        $json2array['rate'] = NULL;
        $json2array['rate_search_rule_id'] = NULL;

        $usage = $this->usagelinkcategory->find('list', array('fields' => array('evalrule_id', 'usage_main_catg_id'), 'conditions' => array('evalrule_id' => $singlerule['evalrule']['evalrule_id'])));

        $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'], 'usage_main_cat_id' => $usage[$singlerule['evalrule']['evalrule_id']], 'ready_reckoner_rate_flag' => 'Y')));
//pr($rate_selection_rule);exit;
        $json2array['RateSearchRule'] = $rate_selection_rule;
        $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
        $json2array['dependencyitems'] = $dependencyitems;


        // for each search Rule
        $i = 0;
        $rate_conditions = array();
        foreach ($rate_selection_rule as $searchrule) {
            // for each row in search rule 

            $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);
            // for usage
            foreach ($json2array['usageitemlist'] as $usageitem) { // loop for each user selected usage rule 
                if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) { //if match usage from  list and user selected usage
                    // to add usage conditions
                    foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                        if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                            $fieldname = $field['dependencyitems']['item_desc'];
                            if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                                $rate_conditions['conditions'][$fieldname] = $singlerule['linkcat'][$fieldname];  // add usage to conditios
                            }
                        }
                    }
                }
            }
//pr($rate_conditions);
//exit;
            $json2array['rate'] = $this->rate->find('all', $rate_conditions);
            if (!empty($json2array['rate']) and $json2array['rate'][0]['rate']['prop_rate'] != 0) {

                $json2array['rate_search_rule_id'] = $i;

                $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                $json2array['rate'] = $this->rate->find('all', $rate_conditions);
                $json2array['option'] = $rate_conditions;
                $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate');
                break;
            }

            $i++;
        }
//pr($json2array['rate']);
        //    exit;
        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));
        return $json2array;
    }

    /* ------- Rate Selection Funtion ------------

     * Query Depends Rate Selection Rule 
     * Rate   Depends Location - dependency_flag is Y
      --------------------------------------------
     */

    public function location_depend_rate($json2array, $singlerule) {

        $rangeflag = 'N';
        $option['conditions'] = array();

        if ($singlerule['evalrule']['division_id'] != NULL && $singlerule['evalrule']['division_id'] != '0') {
            $option['conditions']['division_id'] = $singlerule['evalrule']['division_id'];
        } else {
            array_push($option['conditions'], 'division_id is null');
        }
        if ($singlerule['evalrule']['dist_id'] != NULL && $singlerule['evalrule']['dist_id'] != '0') {
            $option['conditions']['district_id'] = $singlerule['evalrule']['dist_id'];
        } else {
            array_push($option['conditions'], 'district_id is null');
        }
        if ($singlerule['evalrule']['subdiv_id'] != NULL && $singlerule['evalrule']['subdiv_id'] != '0') {
            $option['conditions']['subdivision_id'] = $singlerule['evalrule']['subdiv_id'];
        } else {
            array_push($option['conditions'], 'subdivision_id is null');
        }
        if ($singlerule['evalrule']['taluka_id'] != NULL && $singlerule['evalrule']['taluka_id'] != '0') {
            $option['conditions']['taluka_id'] = $singlerule['evalrule']['taluka_id'];
        } else {
            array_push($option['conditions'], 'taluka_id is null');
        }
        if ($singlerule['evalrule']['circle_id'] != NULL && $singlerule['evalrule']['circle_id'] != '0') {
            $option['conditions']['circle_id'] = $singlerule['evalrule']['circle_id'];
        } else {
            array_push($option['conditions'], 'circle_id is null');
        }
        if ($singlerule['evalrule']['land_type_id'] != NULL && $singlerule['evalrule']['land_type_id'] != '0') {
            $option['conditions']['developed_land_types_id'] = $singlerule['evalrule']['land_type_id'];
        } else {
            array_push($option['conditions'], 'developed_land_types_id is null'
                    . '');
        }
        if ($singlerule['evalrule']['ulbtype_id'] != NULL && $singlerule['evalrule']['ulbtype_id'] != '0') {
            $option['conditions']['ulb_type_id'] = $singlerule['evalrule']['ulbtype_id'];
        } else {
            array_push($option['conditions'], 'ulb_type_id is null');
        }
        if ($singlerule['evalrule']['ulb_id'] != NULL && $singlerule['evalrule']['ulb_id'] != '0') {
            $option['conditions']['corp_id'] = $singlerule['evalrule']['ulb_id'];
        } else {
            array_push($option['conditions'], 'corp_id is null');
        }
        if ($singlerule['evalrule']['village_id'] != NULL && $singlerule['evalrule']['village_id'] != '0') {
            $option['conditions']['village_id'] = $singlerule['evalrule']['village_id'];
        } else {
            array_push($option['conditions'], 'village_id is null');
        }
        if ($singlerule['evalrule']['construction_type_id'] != NULL && $singlerule['evalrule']['construction_type_id'] != '0') {
            $option['conditions']['construction_type_id'] = $singlerule['evalrule']['construction_type_id'];
        } else {
            array_push($option['conditions'], 'construction_type_id is null');
        }

        $option['conditions']['usage_main_catg_id'] = $singlerule['linkcat']['usage_main_catg_id'];
        $option['conditions']['usage_sub_catg_id'] = $singlerule['linkcat']['usage_sub_catg_id'];
        $option['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
        $option1['fields'] = array('rate.rate_id', 'rate.prop_rate');
        $option['order'] = 'rate.rate_id';

        $json2array['rate'] = $this->rate->find('all', $option);
        $json2array['ratelist'] = $this->rate->find('list', $option1);


        $json2array['option'] = $option;
        //pr($option);exit;

        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));

        return $json2array;
    }

    /* ------- Rate Selection Funtion ------------

     * Query Depends additional rate and rate comparison 

      --------------------------------------------
     */

    public function additional_rate_selection($json2array, $singlerule) {
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];
        $json2array['additionalrate'] = NULL;
        $json2array['add_rate_search_rule_id'] = NULL;
        // Build Array of Location  Options
        //$rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'])));
        $rate_selection_rule = $json2array['RateSearchRule'];

//          pr($rate_selection_rule);
        //$dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
        $dependencyitems = $json2array['dependencyitems'];

        if ($singlerule['evalrule']['additional_rate_flag'] == 'Y') {

            // for each search Rule
            $i = 0;
            $rate_conditions = array();
            foreach ($rate_selection_rule as $searchrule) {          // pr($searchrule);
                // for each row in search rule
                $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);

                foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                    if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                        $fieldname = $field['dependencyitems']['item_desc'];
                        if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage 
                            $rate_conditions['conditions'][$fieldname] = $singlerule['evalrule']['add_' . $fieldname];  // add usage to conditios
                        }
                    }
                }
//pr($rate_conditions);exit;

                $json2array['additionalrate'] = $this->rate->find('all', $rate_conditions);
//pr($rate_conditions);
//pr( $json2array['additionalrate']);
//exit;

                if (!empty($json2array['additionalrate']) and $json2array['additionalrate'][0]['rate']['prop_rate'] != 0) {
                    $json2array['add_rate_search_rule_id'] = $i;
                    $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                    $json2array['additionalrate'] = $this->rate->find('all', $rate_conditions);
                    // $json2array['option'] = $rate_conditions;
                    $json2array['additionalrateoptions'] = $rate_conditions;

                    //$rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate');
                    //pr($rate_conditions);
                    //  exit;            
                    break;
                }
                $i++;
            } // loop
        } // if add flag
//pr($json2array['additionalrate']);exit;
        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));

        return $json2array;
    }

    public function additional2_rate_selection($json2array, $singlerule) {
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];
        $json2array['additionalrate2'] = NULL;
        $json2array['add_rate_search_rule_id2'] = NULL;
        // Build Array of Location  Options
        //$rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'])));
        $rate_selection_rule = $json2array['RateSearchRule'];

//          pr($rate_selection_rule);
        //$dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
        $dependencyitems = $json2array['dependencyitems'];

        if ($singlerule['evalrule']['additional1_rate_flag'] == 'Y') {

            // for each search Rule
            $i = 0;
            $rate_conditions = array();
            foreach ($rate_selection_rule as $searchrule) {          // pr($searchrule);
                // for each row in search rule
                $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);

                foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                    if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                        $fieldname = $field['dependencyitems']['item_desc'];
                        if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage 
                            $rate_conditions['conditions'][$fieldname] = $singlerule['evalrule']['add1_' . $fieldname];  // add usage to conditios
                        }
                    }
                }
//pr($rate_conditions);
                $json2array['additionalrate2'] = $this->rate->find('all', $rate_conditions);
//pr($rate_conditions);
//pr( $json2array['additionalrate2']);
//exit;

                if (!empty($json2array['additionalrate2']) and $json2array['additionalrate2'][0]['rate']['prop_rate'] != 0) {
                    $json2array['add1_rate_search_rule_id'] = $i;
                    $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                    $json2array['additionalrate2'] = $this->rate->find('all', $rate_conditions);
                    // $json2array['option'] = $rate_conditions;
                    $json2array['additionalrateoptions2'] = $rate_conditions;

                    //$rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate');
//                   pr($json2array['additionalrate2']);
//                     exit;            
                    break;
                }
                $i++;
            } // loop
        } // if add flag

        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));
//pr($json2array['additionalrate2']);exit;
        return $json2array;
    }

    public function comparison_rate_selection($json2array, $singlerule) {

        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];
        $json2array['comparisonrate'] = NULL;
        $json2array['cmp_rate_search_rule_id'] = NULL;
        // Build Array of Location  Options
        // $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'])));
        //$json2array['RateSearchRule'] = $rate_selection_rule;
        // $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
        // $json2array['dependencyitems'] = $dependencyitems;
        $rate_selection_rule = $json2array['RateSearchRule'];
        $dependencyitems = $json2array['dependencyitems'];

        if ($singlerule['evalrule']['rate_compare_flag'] == 'Y') {


            // for each search Rule
            $i = 0;
            $rate_conditions = array();
            foreach ($rate_selection_rule as $searchrule) {          // pr($searchrule);
                // for each row in search rule
                $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);


                foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                    if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                        $fieldname = $field['dependencyitems']['item_desc'];
                        if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                            $rate_conditions['conditions'][$fieldname] = $singlerule['evalrule']['cmp_' . $fieldname];  // add usage to conditios
                        }
                    }
                }


                $json2array['comparisonrate'] = $this->rate->find('all', $rate_conditions);



                if (!empty($json2array['comparisonrate']) and $json2array['comparisonrate'][0]['rate']['prop_rate'] != 0) {
                    $json2array['cmp_rate_search_rule_id'] = $i;
                    $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                    $json2array['comparisonrate'] = $this->rate->find('all', $rate_conditions);
                    // $json2array['option'] = $rate_conditions;
                    $json2array['comparisonrateoptions'] = $rate_conditions;

                    //$rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate');
                    //pr($rate_conditions);
                    //  exit;            
                    break;
                }
                $i++;
            } // loop   
        }

        // pr($json2array['option']);
        // pr($json2array['rate']);
        //pr( $json2array['comparisonrateoptions']);
        // pr($json2array['comparisonrate']); exit;

        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));
        return $json2array;
    }

    public function build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails) {

        $this->loadModel('taluka');
        $taluka = $this->taluka->find('first', array('conditions' => array('taluka_id' => $data['taluka_id'])));
        $subdivision_id = $taluka['taluka']['subdivision_id'];
        $this->request->data['propertyscreennew']['subdivision_id'] = $subdivision_id;
        $this->request->data['propertyscreennew']['ulb_type_id'] = @$villagedetails[0]['VillageMapping']['ulb_type_id'];
        $rate_conditions = array();
        $rate_conditions['conditions']['ready_reckoner_rate_flag'] = 'Y';
        foreach ($searchrule['RateSearch'] as $keyfield => $status) {

            if ($keyfield == 'finyear_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } else if ($keyfield == 'district_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } else if ($keyfield == 'division_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } else if ($keyfield == 'taluka_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } else if ($keyfield == 'village_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];

                foreach ($dependencyitems as $key => $field) { // read dependency fields
                    if ($field['dependencyitems']['item_type_flag'] == 'L') {

                        if (isset($data[$field['dependencyitems']['item_desc']]) and is_numeric($data[$field['dependencyitems']['item_desc']])) { // field should selected
                            $rate_conditions['conditions'][$field['dependencyitems']['item_desc']] = $data[$field['dependencyitems']['item_desc']];
                        }
                    }
                }
            } elseif ($keyfield == 'construction_type_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } elseif ($keyfield == 'road_vicinity_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } elseif ($keyfield == 'user_defined_dependency1_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } elseif ($keyfield == 'user_defined_dependency2_id' and $status == 'Y' and isset($data[$keyfield]) and is_numeric($data[$keyfield])) {
                $rate_conditions['conditions'][$keyfield] = $data[$keyfield];
            } elseif ($keyfield == 'subdivision_id' and $status == 'Y' and isset($subdivision_id) and is_numeric($subdivision_id)) {
                $rate_conditions['conditions'][$keyfield] = $subdivision_id;
            } elseif ($keyfield == 'ulb_type_id' and $status == 'Y' and isset($villagedetails[0]['VillageMapping']['ulb_type_id']) and is_numeric($villagedetails[0]['VillageMapping']['ulb_type_id'])) {
                $rate_conditions['conditions']['ulb_type_id'] = $villagedetails[0]['VillageMapping']['ulb_type_id'];
            } elseif ($keyfield == 'valutation_zone_id' and $status == 'Y' and isset($villagedetails[0]['VillageMapping']['valutation_zone_id']) and is_numeric($villagedetails[0]['VillageMapping']['valutation_zone_id'])) {
                $rate_conditions['conditions']['valutation_zone_id'] = $villagedetails[0]['VillageMapping']['valutation_zone_id'];
                $this->request->data['propertyscreennew']['valutation_subzone_id'] = $villagedetails[0]['VillageMapping']['valutation_zone_id'];
            } elseif ($keyfield == 'valutation_subzone_id' and $status == 'Y') {

                if (
                        isset($this->request->data['propertyscreennew']['ABO']) and is_numeric($this->request->data['propertyscreennew']['ABO']) and $this->request->data['propertyscreennew']['ABO'] != 0 and

                        isset($this->request->data['propertyscreennew']['ABE']) and is_numeric($this->request->data['propertyscreennew']['ABE']) and $this->request->data['propertyscreennew']['ABE'] != 0
                ) {
                    $aakar = eval("return (" . $this->request->data['propertyscreennew']['ABE'] . '/' . $this->request->data['propertyscreennew']['ABO'] . ");");
                } else if (isset($this->request->data['propertyscreennew']['akar_range'])) {

                    $aakar = $this->request->data['propertyscreennew']['akar_range'];
                } else {
                    $aakar = 0;
                }
                if (isset($this->request->data['propertyscreennew']['akar_range'])) {
                    array_push($rate_conditions['conditions'], 'valutation_subzone_id=(select valutation_subzone_id from ngdrstab_mst_valuation_subzone where ' . $aakar . ' >= cast(from_desc_en as numeric) '
                            . ' and usage_main_catg_id=' . $singlerule['linkcat']['usage_main_catg_id'] . ''
                            . ' and usage_sub_catg_id=' . $singlerule['linkcat']['usage_sub_catg_id'] . ''
                            . ' order by cast(from_desc_en as numeric) desc limit 1)');
                    $subzone_result = $this->rate->query('select valutation_subzone_id from ngdrstab_mst_valuation_subzone where ' . $aakar . ' >= cast(from_desc_en as numeric) '
                            . ' and usage_main_catg_id=' . $singlerule['linkcat']['usage_main_catg_id'] . ''
                            . ' and usage_sub_catg_id=' . $singlerule['linkcat']['usage_sub_catg_id'] . ''
                            . ' order by cast(from_desc_en as numeric) desc limit 1');
                    if (!empty($subzone_result)) {
                        $this->request->data['propertyscreennew']['valutation_subzone_id'] = $subzone_result[0][0]['valutation_subzone_id'];
                    }
                } else {
                    array_push($rate_conditions['conditions'], 'valutation_subzone_id=0');
                    $this->request->data['propertyscreennew']['valutation_subzone_id'] = 0;
                }
            }
        }
        if ($searchrule['RateSearch']['ready_reckoner_rate_flag'] == 'N') {

//       pr($searchrule);
//         pr($data);
//         
//          pr($rate_conditions);exit;
//          
//         exit;     
        }

        return $rate_conditions;
    }

    public function alternative_rate($json2array, $singlerule) {
        $this->loadModel('AlternativeRate');
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];

        $json2array['rate'] = NULL;
        $json2array['rate_search_rule_id'] = NULL;
        // Build Array of Location  Options
//        $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'])));
//        $json2array['RateSearchRule'] = $rate_selection_rule;
//        $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
//        $json2array['dependencyitems'] = $dependencyitems;
        $rate_selection_rule = $json2array['RateSearchRule'];
        $dependencyitems = $json2array['dependencyitems'];
        // for each search Rule
        $i = 0;
        $rate_conditions = array();
        foreach ($rate_selection_rule as $searchrule) {          // pr($searchrule);
            // for each row in search rule
            $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);
            foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                    $fieldname = $field['dependencyitems']['item_desc'];
                    if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                        $newoptions['conditions'][$fieldname] = $singlerule['linkcat'][$fieldname];  // add usage to conditios
                    }
                }
            }
            $newoptions['conditions']['developed_land_types_id'] = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
            //pr($newoptions);exit;   
            $alternative_rate = $this->AlternativeRate->find("all", $newoptions);
            $loop_count = 0;
            while (!empty($alternative_rate)) {
                $loop_count++;
                if ($loop_count >= 10) {
                    break; // To exit in creatical Condition( if user entered data is in loop)
                }

                foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                    if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                        $fieldname = $field['dependencyitems']['item_desc'];
                        if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                            $rate_conditions['conditions'][$fieldname] = $alternative_rate['0']['AlternativeRate']['alt_' . $fieldname];  // overide usage to conditios
                        }
                    }
                }

                $json2array['rate'] = $this->rate->find('all', $rate_conditions);
                //pr($rate_conditions);
                //pr($json2array['rate']);exit;

                if (!empty($json2array['rate']) and $json2array['rate'][0]['rate']['prop_rate'] != 0) {
                    // pr($json2array['rate']);exit;
                    $json2array['rate_search_rule_id'] = "1" . $i; // alt - search rule number 
                    $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                    $json2array['rate'] = $this->rate->find('all', $rate_conditions);
                    $json2array['option'] = $rate_conditions;
                    break;
                } else {

                    foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                        if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                            $fieldname = $field['dependencyitems']['item_desc'];
                            if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                                $newoptions['conditions'][$fieldname] = $alternative_rate['0']['AlternativeRate']['alt_' . $fieldname];  // overide usage to conditios
                            }
                        }
                    }
                    $newoptions['conditions']['developed_land_types_id'] = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
                    $alternative_rate = $this->AlternativeRate->find("all", $newoptions);
                }
            } // while -loop of Alternative Rate record

            if (!empty($json2array['rate']) and $json2array['rate'][0]['rate']['prop_rate'] != 0) {
                break;  // if rate found in alternative break(exit) loop of search rule
            }

            $i++;
        } //loop of search rule
        // pr($json2array['rate_search_rule_id']);
        //pr($json2array['option']);exit;
        //exit;
        return $json2array;
    }

    public function alternative_additional_rate($json2array, $singlerule) {
        $this->loadModel('AlternativeRate');
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');

        $villagedetails = $json2array['villagedetails'];
        $data = $this->request->data['propertyscreennew'];

        $json2array['additionalrate'] = NULL;
        $json2array['add_rate_search_rule_id'] = NULL;
        // Build Array of Location  Options
//        $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'])));
//        $json2array['RateSearchRule'] = $rate_selection_rule;
//        $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
//        $json2array['dependencyitems'] = $dependencyitems;
        $rate_selection_rule = $json2array['RateSearchRule'];
        $dependencyitems = $json2array['dependencyitems'];
        // for each search Rule
        $i = 0;
        $rate_conditions = array();
        foreach ($rate_selection_rule as $searchrule) {          // pr($searchrule);
            // for each row in search rule
            $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);
            foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                    $fieldname = $field['dependencyitems']['item_desc'];
                    if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage 
                        $newoptions['conditions'][$fieldname] = $singlerule['evalrule']['add_' . $fieldname];  // add usage to conditios
                    }
                }
            }
            $newoptions['conditions']['developed_land_types_id'] = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
            $alternative_rate = $this->AlternativeRate->find("all", $newoptions);

            $loop_count = 0;
            while (!empty($alternative_rate)) {
                $loop_count++;
                if ($loop_count >= 10) {
                    break; // To exit in creatical Condition( if user entered data is in loop)
                }
                foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                    if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                        $fieldname = $field['dependencyitems']['item_desc'];
                        if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                            $rate_conditions['conditions'][$fieldname] = $alternative_rate['0']['AlternativeRate']['alt_' . $fieldname];  // overide usage to conditios
                        }
                    }
                }

                $json2array['additionalrate'] = $this->rate->find('all', $rate_conditions);


                if (!empty($json2array['additionalrate']) and $json2array['additionalrate'][0]['rate']['prop_rate'] != 0) {
                    // pr($json2array['rate']);exit;
                    $json2array['add_rate_search_rule_id'] = "1" . $i; // alt - search rule number 
                    $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                    $json2array['additionalrate'] = $this->rate->find('all', $rate_conditions);
                    $json2array['additionalrateoptions'] = $rate_conditions;
                    break;
                } else {

                    foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                        if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                            $fieldname = $field['dependencyitems']['item_desc'];
                            if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                                $newoptions['conditions'][$fieldname] = $alternative_rate['0']['AlternativeRate']['alt_' . $fieldname];  // overide usage to conditios
                            }
                        }
                    }
                    $newoptions['conditions']['developed_land_types_id'] = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
                    $alternative_rate = $this->AlternativeRate->find("all", $newoptions);
                }
            } // while -loop of Alternative Rate record

            if (!empty($json2array['additionalrate']) and $json2array['additionalrate'][0]['rate']['prop_rate'] != 0) {
                break;  // if rate found in alternative break(exit) loop of search rule
            }

            $i++;
        } //loop of search rule
        // pr($json2array['rate_search_rule_id']);
        //pr($json2array['option']);exit;
        //exit;
        return $json2array;
    }

    public function alternative_comparison_rate($json2array, $singlerule) {
        $this->loadModel('AlternativeRate');
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');

        $villagedetails = $json2array['villagedetails'];
        $data = $this->request->data['propertyscreennew'];

        $json2array['comparisonrate'] = NULL;
        $json2array['cmp_rate_search_rule_id'] = NULL;
        // Build Array of Location  Options
//        $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'])));
//        $json2array['RateSearchRule'] = $rate_selection_rule;
//        $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
//        $json2array['dependencyitems'] = $dependencyitems;
        $rate_selection_rule = $json2array['RateSearchRule'];
        $dependencyitems = $json2array['dependencyitems'];
        // for each search Rule
        $i = 0;
        $rate_conditions = array();
        foreach ($rate_selection_rule as $searchrule) {          // pr($searchrule);
            // for each row in search rule
            $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);
            foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                    $fieldname = $field['dependencyitems']['item_desc'];
                    if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                        $newoptions['conditions'][$fieldname] = $singlerule['evalrule']['cmp_' . $fieldname];  // add usage to conditios
                    }
                }
            }
            $newoptions['conditions']['developed_land_types_id'] = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
            $alternative_rate = $this->AlternativeRate->find("all", $newoptions);

            $loop_count = 0;
            while (!empty($alternative_rate)) {
                $loop_count++;
                if ($loop_count >= 10) {
                    break; // To exit in creatical Condition( if user entered data is in loop)
                }
                foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                    if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                        $fieldname = $field['dependencyitems']['item_desc'];
                        if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                            $rate_conditions['conditions'][$fieldname] = $alternative_rate['0']['AlternativeRate']['alt_' . $fieldname];  // overide usage to conditios
                        }
                    }
                }

                $json2array['comparisonrate'] = $this->rate->find('all', $rate_conditions);


                if (!empty($json2array['comparisonrate']) and $json2array['comparisonrate'][0]['rate']['prop_rate'] != 0) {
                    // pr($json2array['rate']);exit;
                    $json2array['cmp_rate_search_rule_id'] = "1" . $i; // alt - search rule number 
                    $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                    $json2array['comparisonrate'] = $this->rate->find('all', $rate_conditions);
                    $json2array['comparisonrateoptions'] = $rate_conditions;
                    break;
                } else {

                    foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                        if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                            $fieldname = $field['dependencyitems']['item_desc'];
                            if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                                $newoptions['conditions'][$fieldname] = $alternative_rate['0']['AlternativeRate']['alt_' . $fieldname];  // overide usage to conditios
                            }
                        }
                    }
                    $newoptions['conditions']['developed_land_types_id'] = $villagedetails[0]['VillageMapping']['developed_land_types_id'];
                    $alternative_rate = $this->AlternativeRate->find("all", $newoptions);
                }
            } // while -loop of Alternative Rate record

            if (!empty($json2array['comparisonrate']) and $json2array['comparisonrate'][0]['rate']['prop_rate'] != 0) {
                break;  // if rate found in alternative break(exit) loop of search rule
            }

            $i++;
        } //loop of search rule
        // pr($json2array['rate_search_rule_id']);
        //pr($json2array['option']);exit;
        //exit;
        return $json2array;
    }

    public function fixed_rate_selection($json2array, $singlerule) {
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');
        $this->loadModel('usagelinkcategory');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];

        $usage = $this->usagelinkcategory->find('list', array('fields' => array('evalrule_id', 'usage_main_catg_id'), 'conditions' => array('evalrule_id' => $singlerule['evalrule']['evalrule_id'])));

        $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'], 'usage_main_cat_id' => $usage[$singlerule['evalrule']['evalrule_id']], 'ready_reckoner_rate_flag' => 'N')));
//        pr($rate_selection_rule);
        $json2array['RateSearchRule1'] = $rate_selection_rule;
        $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
        $json2array['dependencyitems'] = $dependencyitems;


        // for each search Rule
        $i = 0;
        $rate_conditions = array();
        foreach ($rate_selection_rule as $searchrule) {
            // for each row in search rule 

            $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);
            //overide ready_reckoner rate FLAG
            $rate_conditions['conditions']['ready_reckoner_rate_flag'] = 'N';
            // for usage
            foreach ($json2array['usageitemlist'] as $usageitem) { // loop for each user selected usage rule 
                if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) { //if match usage from  list and user selected usage
                    // to add usage conditions
                    foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                        if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                            $fieldname = $field['dependencyitems']['item_desc'];
                            if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                                $rate_conditions['conditions'][$fieldname] = $singlerule['linkcat'][$fieldname];  // add usage to conditios
                            }
                        }
                    }
                }
            }
//            pr($rate_conditions);
            $json2array['fixedrate'] = $this->rate->find('all', $rate_conditions);
            if (!empty($json2array['fixedrate']) and $json2array['fixedrate'][0]['rate']['prop_rate'] != 0) {

                $json2array['rate_search_rule_id'] = "3" . $i;

                $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                $json2array['fixedrate'] = $this->rate->find('all', $rate_conditions);
                $json2array['fixedrateoption'] = $rate_conditions;
                $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate');
                break;
            }

            $i++;
        }
//       pr($json2array['RateSearchRule']);
//        pr($json2array['fixedrate']);
//        exit;
        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));
        return $json2array;
    }

    /* ------- Rate Selection Funtion ------------
     * Extra Construction Rate Selection  
      -------------------------------------------- */

    public function fixed_rate_selection_extra($json2array, $singlerule) {
        $this->loadModel('RateSearch');
        $this->loadModel('dependencyitems');
        $this->loadModel('usagelinkcategory');

        $villagedetails = $json2array['villagedetails'];

        $data = $this->request->data['propertyscreennew'];

        $usage = $this->usagelinkcategory->find('list', array('fields' => array('evalrule_id', 'usage_main_catg_id'), 'conditions' => array('evalrule_id' => $singlerule['evalrule']['evalrule_id'])));
//pr($usage);
        $rate_selection_rule = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $villagedetails[0]['VillageMapping']['developed_land_types_id'], 'usage_main_cat_id' => $usage[$singlerule['evalrule']['evalrule_id']], 'ready_reckoner_rate_flag' => 'N')));
        // pr($rate_selection_rule);exit;
        $json2array['RateSearchRule1'] = $rate_selection_rule;
        $dependencyitems = $this->dependencyitems->find('all', array('fields' => array('item_type_flag', 'item_desc')));
        $json2array['dependencyitems'] = $dependencyitems;


        // for each search Rule
        $i = 0;
        $rate_conditions = array();
        foreach ($rate_selection_rule as $searchrule) {
            // for each row in search rule 

            $rate_conditions = $this->build_rate_search_condition($singlerule, $searchrule, $dependencyitems, $data, $villagedetails);
            //overide ready_reckoner rate FLAG
            $rate_conditions['conditions']['ready_reckoner_rate_flag'] = 'N';
            // for usage
            // echo 'hi';exit;
            foreach ($json2array['usageitemlist'] as $usageitem) { // loop for each user selected usage rule 
                if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) { //if match usage from  list and user selected usage
                    // to add usage conditions
                    foreach ($dependencyitems as $key => $field) { // Read Dependency Items  
                        if ($field['dependencyitems']['item_type_flag'] == 'U') {   // match item type Usage
                            $fieldname = $field['dependencyitems']['item_desc'];
                            if ($searchrule['RateSearch'][$fieldname] == 'Y') { // If rate depends Usage
                                $rate_conditions['conditions'][$fieldname] = $singlerule['linkcat'][$fieldname];  // add usage to conditios
                            }
                        }
                    }
                }
            }
            // Overiding contruction type
            $rate_conditions['conditions']['construction_type_id'] = 1;
            // pr($rate_conditions);exit;
            $json2array['fixedrate_extra'] = $this->rate->find('all', $rate_conditions);
            if (!empty($json2array['fixedrate_extra']) and $json2array['fixedrate_extra'][0]['rate']['prop_rate'] != 0) {

                $json2array['rate_search_rule_id'] = "3" . $i;

                $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate', 'prop_unit', 'rate.slab_rate_flag', 'rate.range_from', 'rate.range_to', 'rate.land_rate', 'rate.construction_rate');
                $json2array['fixedrate_extra'] = $this->rate->find('all', $rate_conditions);
                $json2array['fixedrate_extraoption'] = $rate_conditions;
                $rate_conditions['fields'] = array('rate.rate_id', 'rate.prop_rate');
                break;
            }

            $i++;
        }
//       pr($json2array['RateSearchRule']);
        //pr($json2array['fixedrate_extra']);
        // exit;
        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
        $file->write(json_encode($json2array));
        return $json2array;
    }

    public function item_rate_selection($json2array, $singlerule) {
        $this->loadModel('itemrate');
        if (isset($json2array['villagedetails'])) {
            $villagedetails = $json2array['villagedetails'][0]['VillageMapping'];
            $this->request->data['propertyscreennew']['developed_land_types_id'] = $villagedetails['developed_land_types_id'];
            $this->request->data['propertyscreennew']['ulb_type_id'] = $villagedetails['ulb_type_id'];
            $this->request->data['propertyscreennew']['valutation_zone_id'] = $villagedetails['valutation_zone_id'];
        }
        $formdata = $this->request->data['propertyscreennew'];


        foreach ($json2array['usageitemlist'] as $singleitem) {

            if ($singlerule['evalrule']['evalrule_id'] == $singleitem['usagelinkcategory']['evalrule_id']) {
                $locationoption = array();
                $itemrate = NULL;
                if ($singleitem['itemlist']['item_rate_flag'] == 'Y') {
                    $locationoption['usage_param_id'] = $singleitem['usagelinkcategory']['usage_param_id'];
                    $search_rule = $this->itemrate->find("first", array('conditions' => $locationoption));
                    if (!empty($search_rule)) {
                        $checklist = array('finyear_id' => 'finyear_flag', 'district_id' => 'district_flag', 'division_id' => 'division_flag', 'taluka_id' => 'taluka_flag', 'valutation_zone_id' => 'valutation_zone_flag', 'village_id' => 'village_flag', 'developed_land_types_id' => 'developed_land_types_flag');
                        foreach ($checklist as $fieldkey => $fieldflag) {
                            if ($search_rule['itemrate'][$fieldflag] == 'Y') {
                                if (isset($formdata[$fieldkey])) {
                                    $locationoption[$fieldkey] = $formdata[$fieldkey];
                                }
                            }
                        }
                    }

                    $rateresult = $this->itemrate->find("first", array('conditions' => $locationoption));
                    if (!empty($rateresult)) {
                        $itemrate[$singleitem['usagelinkcategory']['evalrule_id']][$singleitem['usagelinkcategory']['usage_param_id']]['items'][$singleitem['usagelinkcategory']['uasge_param_code']] = $formdata[$singleitem['usagelinkcategory']['uasge_param_code']];
                        $itemrate[$singleitem['usagelinkcategory']['evalrule_id']][$singleitem['usagelinkcategory']['usage_param_id']]['items']['RRR'] = $rateresult['itemrate']['item_rate'];

                        // $itemrate['rates'][$singleitem['usagelinkcategory']['evalrule_id']."_".$singleitem['usagelinkcategory']['uasge_param_code']] = $rateresult['itemrate']['item_rate'];
                    } else {
                        $itemrate[$singleitem['usagelinkcategory']['evalrule_id']][$singleitem['usagelinkcategory']['usage_param_id']]['items'][$singleitem['usagelinkcategory']['uasge_param_code']] = $formdata[$singleitem['usagelinkcategory']['uasge_param_code']];
                        $itemrate[$singleitem['usagelinkcategory']['evalrule_id']][$singleitem['usagelinkcategory']['usage_param_id']]['items']['RRR'] = 0;
                    }

//               $itemrate['conditions'][$singleitem['usagelinkcategory']['evalrule_id']."_".$singleitem['usagelinkcategory']['uasge_param_code']] =$locationoption;
                    $itemrate[$singleitem['usagelinkcategory']['evalrule_id']][$singleitem['usagelinkcategory']['usage_param_id']] ['conditions'] = $locationoption;
                    $itemrate[$singleitem['usagelinkcategory']['evalrule_id']][$singleitem['usagelinkcategory']['usage_param_id']] ['outputitem'] = $singleitem['itemlist']['output_item_id'];
                }
            }
        }

        $json2array['usageitemrate'] = $itemrate;
        return $json2array;
    }

    public function property_valuation() {
        $this->loadModel("conf_reg_bool_info");

        try {

            $this->set('pdfflag', 0);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y/m/d H:i:s');
            $user_id = $this->Session->read("session_user_id");
            $stateid = $this->Auth->User("state_id");
            $language = $this->Session->read("sess_langauge");


            if ($this->request->is('post')) {

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $villagedetails = @$json2array['villagedetails'];
                $actiontype = $_POST['actiontype'];

                // valuation Calculation
               // pr($actiontype);exit;
                if ($actiontype == '3') {
                    $this->request->data['rate_revision_flag'] = 'N';
                    if (isset($json2array['evalconditions']) && is_array($json2array['evalconditions'])) {
                        foreach (@$json2array['evalconditions'] as $singlerule) { // loop for each usage rule
                            $this->request->data['propertyscreennew']['usage_main_catg_id'] = $singlerule['linkcat']['usage_main_catg_id'];
                            $this->request->data['propertyscreennew']['usage_sub_catg_id'] = $singlerule['linkcat']['usage_sub_catg_id'];
                            $this->request->data['propertyscreennew']['usage_sub_sub_catg_id'] = $singlerule['linkcat']['usage_sub_sub_catg_id'];

                            $json2array['rate'] = NULL;
                            $json2array['rate_search_rule_id'] = NULL;
                            $json2array['fixedrate'] = NULL;
                            $json2array['fixedrate_extra'] = NULL;
                            $json2array['option'] = NULL;
                            $json2array['additionalrate'] = NULL;
                            $json2array['add_rate_search_rule_id'] = NULL;
                            $json2array['comparisonrate'] = NULL;
                            $json2array['cmp_rate_search_rule_id'] = NULL;

                            $json2array['additionalrate1'] = NULL; // not Used
                            $json2array['ratelist'] = NULL;  // Not Used


                            $this->manage_usage_item_inputfield($json2array, $singlerule);

                            foreach ($json2array['usageitemlist'] as $usageitem) {
                                if ($usageitem['itemlist']['area_field_flag'] == 'Y') {
                                    if (isset($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'areatype']) && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'areatype'] != '0' && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'areatype'] != 'empty' && $villagedetails[0]['VillageMapping']['developed_land_types_id'] != '2' && isset($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit']) && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'] != '0' && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'] != 'empty') {
                                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'converted'] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']];
                                        $convertedarea1 = $this->areaconversion->areatypeconversion($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'areatype']);
                                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] = $convertedarea1;
                                        $convertedarea = $this->areaconversion->standardareaconversion($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit']);
                                        $actarea = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']];

                                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] = $convertedarea;
                                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . "act"] = $actarea;
                                    } else if (isset($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit']) && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'] != '0' && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'] != 'empty') {
                                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'converted'] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']];
                                        $convertedarea = $this->areaconversion->standardareaconversion($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']], $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit']);
                                        $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] = $convertedarea;
                                    }
                                }
                            }
                            if ($singlerule['evalrule']['dependency_flag'] == 'N') {
                                //exit;
                                /* ------ Rate Selection START --------  */
                                $json2array = $this->rate_slection_rule($json2array, $singlerule);   // call to rate selection  
                                if (!isset($json2array['rate']) || $json2array['rate'] == NULL) {
                                    $json2array = $this->alternative_rate($json2array, $singlerule);
                                } else if ($json2array['rate'][0]['rate']['prop_rate'] == 0) {
                                    $json2array = $this->alternative_rate($json2array, $singlerule);
                                }
                                $json2array = $this->fixed_rate_selection($json2array, $singlerule);
                                $json2array = $this->fixed_rate_selection_extra($json2array, $singlerule);
                                /* ------ Rate Selection END --------  */

                                /* ------ Comparison Rate Selection START --------  */
                                if ($singlerule['evalrule']['rate_compare_flag'] == 'Y') {
                                    $json2array = $this->comparison_rate_selection($json2array, $singlerule);        // call to compare rate selection

                                    if (!isset($json2array['comparisonrate']) || $json2array['comparisonrate'] == NULL) {
                                        $json2array = $this->alternative_comparison_rate($json2array, $singlerule);
                                    } else if ($json2array['comparisonrate'][0]['rate']['prop_rate'] == 0) {
                                        $json2array = $this->alternative_comparison_rate($json2array, $singlerule);
                                    }
                                }
                                /* ------ Comparison Rate Selection END --------  */

                                /* ------ Additional Rate Selection START --------  */
                                if ($singlerule['evalrule']['additional_rate_flag'] == 'Y') {
                                    $json2array = $this->additional_rate_selection($json2array, $singlerule);   // call to additional rate selection 
                                    if (!isset($json2array['additionalrate']) || $json2array['additionalrate'] == NULL) {
                                        $json2array = $this->alternative_additional_rate($json2array, $singlerule);
                                    } else if ($json2array['additionalrate'][0]['rate']['prop_rate'] == 0) {
                                        $json2array = $this->alternative_additional_rate($json2array, $singlerule);
                                    }
                                }
                                /* ------ Additional Rate Selection END --------  */
                            }

                            $json2array = $this->item_rate_selection($json2array, $singlerule);
                            $json2array = $this->additional2_rate_selection($json2array, $singlerule);   // call to additional rate selection 



                            if ($json2array['rate'] != NULL || $json2array['fixedrate'] != NULL || $json2array['additionalrate'] != NULL || $singlerule['evalrule']['skip_val_flag'] == 'Y') {


                                //   pr("Rate : ".$json2array['rate_search_rule_id']);
                                //    pr($json2array['rate']);
                                //    pr("ADD Rate : ".$json2array['add_rate_search_rule_id']);
                                // pr($json2array['additionalrate']);
                                // exit;
                                $rate = 0;
                                $additionalrate = 0;
                                $additionalrate2 = 0;
                                $comparisonrate = 0;
                                $rate_land = 0;
                                $rate_construction = 0;
                                $rate_factor = 'RRR';
                                $ratefactorarray = array();
                                $tdrfactorarray = array();
                                $search_rule_flag = NULL;
                                $fixedrate = 0;
                                $fixedrate_extra = 0;
                                //pr($json2array['rate']);exit;
                                // pr($singlerule['evalrule']['evalrule_id']);exit;
                                $act_rate = 0;
                                $act_rate_addtional = 0;
                                $act_cmp_rate_addtional = 0;
                                $act_fixed_rate_addtional = 0;
                                // echo "---";
                                // pr($json2array['additionalrate']);exit;
                                // pr($json2array['additionalrate']);exit;
                                if ($json2array['rate'] != NULL) {
                                    $convertedrate = $this->rateconversion->standardrateconversion($json2array['rate'][0]['rate']);
                                    $rate = $convertedrate['prop_rate'];

                                    $rate_land = $convertedrate['land_rate']; //-- no need
                                    $rate_construction = $convertedrate['construction_rate']; //--no need
                                    $this->request->data['propertyscreennew'][$singlerule['evalrule']['evalrule_id']]['rate'] = $json2array['rate'][0]['rate'];
                                    $act_rate = $json2array['rate'][0]['rate']['prop_rate'];
                                }

                                if ($json2array['additionalrate'] != NULL) {
                                    $convertedadditionalrate = $this->rateconversion->standardrateconversion($json2array['additionalrate'][0]['rate']);
                                    $additionalrate = $convertedadditionalrate['prop_rate'];
                                    $this->request->data['propertyscreennew'][$singlerule['evalrule']['evalrule_id']]['additionalrate'] = $json2array['additionalrate'][0]['rate'];
                                    $act_rate_addtional = $json2array['additionalrate'][0]['rate']['prop_rate'];
                                }
                                if ($json2array['additionalrate2'] != NULL) {
                                    $convertedadditionalrate = $this->rateconversion->standardrateconversion($json2array['additionalrate2'][0]['rate']);
                                    $additionalrate2 = $convertedadditionalrate['prop_rate'];
                                    $this->request->data['propertyscreennew'][$singlerule['evalrule']['evalrule_id']]['additionalrate2'] = $json2array['additionalrate2'][0]['rate'];
                                    $act_rate_addtional2 = $json2array['additionalrate2'][0]['rate']['prop_rate'];
                                }

                                if ($json2array['comparisonrate'] != NULL) {
                                    $convertedcomparisonrate = $this->rateconversion->standardrateconversion($json2array['comparisonrate'][0]['rate']);
                                    $comparisonrate = $convertedcomparisonrate['prop_rate'];
                                    $this->request->data['propertyscreennew'][$singlerule['evalrule']['evalrule_id']]['comparisonrate'] = $json2array['comparisonrate'][0]['rate'];
                                    $act_cmp_rate_addtional = $json2array['comparisonrate'][0]['rate']['prop_rate'];
                                }

                                if ($json2array['fixedrate'] != NULL) {
                                    $convertedfixedrate = $this->rateconversion->standardrateconversion($json2array['fixedrate'][0]['rate']);
                                    $fixedrate = $convertedfixedrate['prop_rate'];
                                    $this->request->data['propertyscreennew'][$singlerule['evalrule']['evalrule_id']]['fixedrate'] = $json2array['fixedrate'][0]['rate'];

                                    $act_fixed_rate_addtional = $json2array['fixedrate'][0]['rate']['prop_rate'];
                                }

                                if ($json2array['fixedrate_extra'] != NULL) {
                                    $convertedfixedrate = $this->rateconversion->standardrateconversion($json2array['fixedrate_extra'][0]['rate']);
                                    $fixedrate_extra = $convertedfixedrate['prop_rate'];
                                    $this->request->data['propertyscreennew'][$singlerule['evalrule']['evalrule_id']]['fixedrate_extra'] = $json2array['fixedrate_extra'][0]['rate'];
                                    $act_fixed_rate_extra = $json2array['fixedrate_extra'][0]['rate']['prop_rate'];
                                }


//pr($this->request->data['propertyscreennew']);exit;
                                if (isset($this->request->data['propertyscreennew']['is_tdr_applicable'])) {
                                    if ($this->request->data['propertyscreennew']['is_tdr_applicable'] == 'Y') {
                                        $tdrfactorarray = $this->tdrfactor->find('all', array('conditions' => array('state_id' => $this->Auth->user('state_id'))));
                                        if ($tdrfactorarray != NULL) {
                                            $rate = eval("return (" . $rate . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                            $rate_land = eval("return (" . $rate_land . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                            $rate_construction = eval("return (" . $rate_construction . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                        }
                                    }
                                }

                                if ($singlerule['usagecat']['contsruction_type_flag'] == 'Y' || $singlerule['usagecat']['depreciation_flag'] == 'Y') {
                                    $ratefactoroption['conditions'] = array();
                                    if ($singlerule['usagecat']['contsruction_type_flag'] == 'Y') {
                                        $ratefactoroption['conditions']['constructiontype_id'] = $this->request->data['propertyscreennew']['construction_type_id'];
                                    } else {
                                        $ratefactoroption['conditions']['constructiontype_id'] = 0;
                                    }
                                    if ($singlerule['usagecat']['depreciation_flag'] == 'Y') {
                                        $ratefactoroption['conditions']['depreciation_id'] = $this->request->data['propertyscreennew']['depreciation_id'];
                                    } else {
                                        $ratefactoroption['conditions']['depreciation_id'] = 0;
                                    }

                                    $ratefactorarray = $this->ratefactor->find('all', $ratefactoroption);
                                    //rate_revision_flag
                                    if ($singlerule['evalrule']['rate_revision_flag'] == 'Y') {
                                        $this->request->data['rate_revision_flag'] = 'Y';
                                    }
                                }


                                $insertdata = array();
                                if ($singlerule['evalrule']['subrule_flag'] == 'Y') {

                                    $this->set('subruleconditions', $json2array['subruleconditions']);


                                    // pr($singlerule['usagecat']);
                                    // road_vicinity_flag,user_defined_dependency1_flag,user_defined_dependency2_flag
                                    if ($singlerule['usagecat']['road_vicinity_flag'] == 'Y') {
                                        $dependency_option['road_vicinity_id'] = $this->request->data['propertyscreennew']['road_vicinity_id'];
                                    } else {
                                        $dependency_option['road_vicinity_id'] = 0;
                                    }
                                    if ($singlerule['usagecat']['user_defined_dependency1_flag'] == 'Y') {
                                        $dependency_option['user_defined_dependency1_id'] = $this->request->data['propertyscreennew']['user_defined_dependency1_id'];
                                    } else {
                                        $dependency_option['user_defined_dependency1_id'] = 0;
                                    }
                                    if ($singlerule['usagecat']['user_defined_dependency2_flag'] == 'Y') {
                                        $dependency_option['user_defined_dependency2_id'] = $this->request->data['propertyscreennew']['user_defined_dependency2_id'];
                                    } else {
                                        $dependency_option['user_defined_dependency2_id'] = 0;
                                    }

                                    foreach ($json2array['subruleconditions'] as $key => $subrulecondition) {
                                        //   pr($subrulecondition);
                                        if ($singlerule['evalrule']['evalrule_id'] == $subrulecondition['subrule']['evalrule_id']) {

                                            if (isset($dependency_option) and ! empty($dependency_option)) {

                                                $loopflag = 0;
                                                $ifflag = 0;
                                                foreach ($dependency_option as $keyfield => $value) {
                                                    $loopflag++;
                                                    if ($value == $subrulecondition['subrule'][$keyfield]) {
                                                        $ifflag++;
                                                    }
                                                }
                                                // echo $rate;exit;
//                                             pr($rate);
//                                            pr($act_rate);
//                                             pr($act_rate_addtional);
//                                              pr($act_cmp_rate_addtional);
//                                               pr($act_fixed_rate_addtional);


                                                if ($loopflag == $ifflag) {
                                                    $resultarray = $this->evalcalculation->multiplecalculation($this->request->data, $json2array, $subrulecondition['subrule'], $rate, $fixedrate, $fixedrate_extra, $comparisonrate, $additionalrate, $additionalrate2, $ratefactorarray);
                                                    $this->request->data['propertyscreennew']['derivedresult' . $subrulecondition['subrule']['subrule_id']] = $resultarray['derivedresult'];
                                                    $this->request->data['propertyscreennew']['maxvalresult' . $subrulecondition['subrule']['subrule_id']] = $resultarray['maxvalresult'];
                                                    $this->request->data['propertyscreennew']['finalresult' . $subrulecondition['subrule']['subrule_id']] = $resultarray['finalresult'];
                                                    $this->request->data['propertyscreennew']['usedFormula' . $subrulecondition['subrule']['subrule_id']] = $resultarray['usedFormula'];
                                                    $this->request->data['propertyscreennew']['rate_rivision_formula' . $subrulecondition['subrule']['subrule_id']] = $resultarray['rate_rivision_formula'];
                                                    $this->request->data['propertyscreennew']['eff_rrr' . $subrulecondition['subrule']['subrule_id']] = $resultarray['eff_rrr'];
                                                    $this->request->data['propertyscreennew']['eff_rr2' . $subrulecondition['subrule']['subrule_id']] = $resultarray['eff_rr2'];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $slabflag = 0;
                                    $slabfield = "";
                                    $slabfieldvalue = 0;
                                    if ($json2array['rate'] != NULL) {
                                        foreach ($json2array['rate'] as $rate1) {
                                            if ($rate1['rate']['slab_rate_flag'] == 'Y') {
                                                $slabflag = 1;
                                            }
                                        }
                                    }
                                    if ($slabflag == 1) {
                                        foreach ($json2array['usageitemlist'] as $usageitem) {
                                            if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) {
                                                if ($usageitem['itemlist']['slab_rate_flag'] == 'Y') {
                                                    $slabfield = $usageitem['usagelinkcategory']['uasge_param_code'];
                                                }
                                            }
                                        }
                                        foreach ($json2array['rate'] as $rate1) {

                                            $convertedrate = $this->rateconversion->standardrateconversion($rate1['rate']);

                                            $rate = $convertedrate['prop_rate'];
                                            $rate_land = $convertedrate['land_rate'];
                                            $rate_construction = $convertedrate['construction_rate'];

                                            if ($this->request->data['propertyscreennew']['is_tdr_applicable'] == 'Y') {
                                                if ($tdrfactorarray != NULL) {
                                                    $rate = eval("return (" . $rate . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                                    $rate_land = eval("return (" . $rate_land . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                                    $rate_construction = eval("return (" . $rate_construction . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                                }
                                            }

                                            if ($ratefactorarray != NULL) {
                                                $rate = eval("return (" . $rate . '*' . $ratefactorarray[0]['ratefactor']['rate_factor'] . ");");
                                                $rate_land = eval("return (" . $rate_land . '*' . $ratefactorarray[0]['ratefactor']['rate_factor'] . ");");
                                                $rate_construction = eval("return (" . $rate_construction . '*' . $ratefactorarray[0]['ratefactor']['rate_factor'] . ");");
                                                $rate_factor = 'RRR*' . $ratefactorarray[0]['ratefactor']['rate_factor'];
                                            }

                                            if ($rate1['rate']['range_from'] < $this->request->data['propertyscreennew'][$slabfield] && ($rate1['rate']['range_to'] > $this->request->data['propertyscreennew'][$slabfield] || $rate1['rate']['range_to'] == NULL)) {
                                                $slabfieldvalue = $this->request->data['propertyscreennew'][$slabfield] - $rate1['rate']['range_from'];
                                                $resultarray = $this->evalcalculation->singlecalculationslab($this->request->data, $singlerule, $rate, $slabfield, $slabfieldvalue, $fixedrate, $json2array, $comparisonrate, $additionalrate, $ratefactorarray);
                                            } else if ($rate1['rate']['range_from'] < $this->request->data['propertyscreennew'][$slabfield] && $rate1['rate']['range_to'] < $this->request->data['propertyscreennew'][$slabfield]) {
                                                $slabfieldvalue = $rate1['rate']['range_to'] - $rate1['rate']['range_from'];
                                                $resultarray = $this->evalcalculation->singlecalculationslab($this->request->data, $singlerule, $rate, $slabfield, $slabfieldvalue, $fixedrate, $json2array, $comparisonrate, $additionalrate, $ratefactorarray);
                                            } else {
                                                $resultarray['derivedresult'] = 0;
                                                $resultarray['maxvalresult'] = 0;
                                                $resultarray['finalresult'] = 0;
                                            }
                                            $this->request->data['propertyscreennew']['derivedresult' . $rate1['rate']['range_from']] = $resultarray['derivedresult'];
                                            $this->request->data['propertyscreennew']['maxvalresult' . $rate1['rate']['range_from']] = $resultarray['maxvalresult'];
                                            $this->request->data['propertyscreennew']['finalresult' . $rate1['rate']['range_from']] = $resultarray['finalresult'];
                                            $this->request->data['propertyscreennew']['usedFormula' . $rate1['rate']['range_from']] = $resultarray['usedFormula'];
                                            $this->request->data['propertyscreennew']['rate_rivision_formula' . $rate1['rate']['range_from']] = $resultarray['rate_rivision_formula'];
                                            $this->request->data['propertyscreennew']['eff_rrr' . $rate1['rate']['range_from']] = $resultarray['eff_rrr'];
                                            $this->request->data['propertyscreennew']['eff_rr2' . $rate1['rate']['range_from']] = $resultarray['eff_rr2'];
                                        }
                                    } else {

                                        $resultarray = $this->evalcalculation->singlecalculation($this->request->data, $singlerule, $rate, $fixedrate, $json2array, $comparisonrate, $additionalrate, $additionalrate2, $ratefactorarray);
                                        $this->request->data['propertyscreennew']['derivedresult'] = $resultarray['derivedresult'];
                                        $this->request->data['propertyscreennew']['maxvalresult'] = $resultarray['maxvalresult'];
                                        $this->request->data['propertyscreennew']['finalresult'] = $resultarray['finalresult'];
                                        $this->request->data['propertyscreennew']['usedFormula'] = $resultarray['usedFormula'];
                                        $this->request->data['propertyscreennew']['rate_rivision_formula'] = $resultarray['rate_rivision_formula'];
                                        $this->request->data['propertyscreennew']['eff_rrr'] = $resultarray['eff_rrr'];
                                        $this->request->data['propertyscreennew']['eff_rr2'] = $resultarray['eff_rr2'];
                                    }
                                }

                                $this->request->data['propertyscreennew']['state_id'] = $this->Auth->user('state_id');
                                $this->request->data['propertyscreennew']['ip_address'] = $ip_address;
                                $this->request->data['propertyscreennew']['user_id'] = $this->Auth->user('user_id');
                                // $this->request->data['propertyscreennew']['created_date'] = $created_date;
                                // for rajestan
                                $this->request->data['propertyscreennew']['rate'] = $rate;

                                if (!is_numeric($this->request->data['propertyscreennew']['corp_id'])) {
                                    $this->request->data['propertyscreennew']['corp_id'] = '';
                                }
                                if (!is_numeric($this->request->data['propertyscreennew']['taluka_id'])) {
                                    $this->request->data['propertyscreennew']['taluka_id'] = '';
                                }
                                if (isset($this->request->data['propertyscreennew']['level1_id']) && !is_numeric($this->request->data['propertyscreennew']['level1_id'])) {
                                    $this->request->data['propertyscreennew']['level1_id'] = NULL;
                                }

                                //-------------- get usage category ids [ shrishail ]--------------------------------------------------
                                $usagecategory = array();
                                // RRR
                                if (isset($json2array['option']['conditions'])) {
                                    $condition = $json2array['option']['conditions'];
                                    if (isset($condition['usage_main_catg_id'])) {
                                        $usagecategory['RRR']['rrr_main_catg_id'] = $condition['usage_main_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_catg_id'])) {
                                        $usagecategory['RRR']['rrr_sub_catg_id'] = $condition['usage_sub_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_sub_catg_id'])) {
                                        $usagecategory['RRR']['rrr_sub_sub_catg_id'] = $condition['usage_sub_sub_catg_id'];
                                    }
                                    $this->request->data['propertyscreennew'] = array_merge($this->request->data['propertyscreennew'], $usagecategory['RRR']);
                                }
                                //RR1
                                if (isset($json2array['additionalrateoptions']['conditions'])) {
                                    $condition = $json2array['additionalrateoptions']['conditions'];
                                    if (isset($condition['usage_main_catg_id'])) {
                                        $usagecategory['RR1']['rr1_main_catg_id'] = $condition['usage_main_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_catg_id'])) {
                                        $usagecategory['RR1']['rr1_sub_catg_id'] = $condition['usage_sub_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_sub_catg_id'])) {
                                        $usagecategory['RR1']['rr1_sub_sub_catg_id'] = $condition['usage_sub_sub_catg_id'];
                                    }
                                    $this->request->data['propertyscreennew'] = array_merge($this->request->data['propertyscreennew'], $usagecategory['RR1']);
                                }
                                //RR2
                                if (isset($json2array['fixedrateoption']['conditions'])) {
                                    $condition = $json2array['fixedrateoption']['conditions'];
                                    if (isset($condition['usage_main_catg_id'])) {
                                        $usagecategory['RR2']['rr2_main_catg_id'] = $condition['usage_main_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_catg_id'])) {
                                        $usagecategory['RR2']['rr2_sub_catg_id'] = $condition['usage_sub_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_sub_catg_id'])) {
                                        $usagecategory['RR2']['rr2_sub_sub_catg_id'] = $condition['usage_sub_sub_catg_id'];
                                    }
                                    $this->request->data['propertyscreennew'] = array_merge($this->request->data['propertyscreennew'], $usagecategory['RR2']);
                                }
                                //ABE
                                if (isset($json2array['comparisonrateoptions']['conditions'])) {
                                    $condition = $json2array['comparisonrateoptions']['conditions'];
                                    if (isset($condition['usage_main_catg_id'])) {
                                        $usagecategory['ABE']['abe_main_catg_id'] = $condition['usage_main_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_catg_id'])) {
                                        $usagecategory['ABE']['abe_sub_catg_id'] = $condition['usage_sub_catg_id'];
                                    }
                                    if (isset($condition['usage_sub_sub_catg_id'])) {
                                        $usagecategory['ABE']['abe_sub_sub_catg_id'] = $condition['usage_sub_sub_catg_id'];
                                    }
                                    $this->request->data['propertyscreennew'] = array_merge($this->request->data['propertyscreennew'], $usagecategory['ABE']);
                                }

                                $valuation_id = NULL;
                                $this->request->data['propertyscreennew']['created'] = date('Y-m-d H:i:s');
                                if ($this->valuation->save($this->request->data['propertyscreennew'])) {
                                    $insertrate = 0;
                                    if (empty($ratefactorarray)) {
                                        $ratefactorarray[0]['ratefactor']['rate_factor'] = NULL;
                                    }
                                    $lastinsertedid = $this->valuation->getLastInsertId();
                                    $valuation_id = $lastinsertedid;
                                    if (!isset($this->request->data['propertyscreennew']['val_id'])) {
                                        $this->request->data['propertyscreennew']['val_id'] = $lastinsertedid;
                                    }
                                    $lastinsertedid = $this->request->data['propertyscreennew']['val_id'];
                                    $this->set('valuation_id', $lastinsertedid);

                                    if ($singlerule['evalrule']['subrule_flag'] == 'Y') {
                                        foreach ($json2array['subruleconditions'] as $subrulecondition) {

                                            //   pr("Rate : ".$json2array['rate_search_rule_id']);
                                            //    pr($json2array['rate']);
                                            //    pr("ADD Rate : ".$json2array['add_rate_search_rule_id']);
                                            // pr($json2array['additionalrate']);
                                            // $this->request->data['propertyscreennew']['usedFormula' . $subrulecondition['subrule']['subrule_id']];    

                                            if (strpos($subrulecondition['subrule']['evalsubrule_formula1'], 'RRL') || strpos($subrulecondition['subrule']['evalsubrule_formula2'], 'RRL')) {
                                                $insertrate = $rate_land;
                                            } else if (strpos($subrulecondition['subrule']['evalsubrule_formula1'], 'RRC') || strpos($subrulecondition['subrule']['evalsubrule_formula2'], 'RRC')) {
                                                $insertrate = $rate_construction;
                                            } else if (strpos($subrulecondition['subrule']['evalsubrule_formula1'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula2'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula3'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula4'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula5'], 'RRR')) {

                                                $insertrate = $rate;
                                                $search_rule_flag = $json2array['rate_search_rule_id'];
                                            } else if (strpos($subrulecondition['subrule']['evalsubrule_formula1'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula2'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula3'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula4'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula5'], 'RR1')) {

                                                $insertrate = $additionalrate;
                                                $search_rule_flag = $json2array['add_rate_search_rule_id'];
                                            }



                                            if ($singlerule['evalrule']['evalrule_id'] == $subrulecondition['subrule']['evalrule_id']) {

                                                if (isset($this->request->data['propertyscreennew']['derivedresult' . $subrulecondition['subrule']['subrule_id']])) {

                                                    $formulaused = $this->request->data['propertyscreennew']['usedFormula' . $subrulecondition['subrule']['subrule_id']];
                                                    $rate_revised = $this->request->data['propertyscreennew']['rate_rivision_formula' . $subrulecondition['subrule']['subrule_id']];
                                                    if (strpos($formulaused, 'ABE')) {

                                                        $insertrate = $comparisonrate;
                                                        $search_rule_flag = $json2array['cmp_rate_search_rule_id'];
                                                    }

                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'subrule_id' => $subrulecondition['subrule']['subrule_id'],
                                                        'item_id' => $subrulecondition['subrule']['output_item_id'],
                                                        'item_type_id' => 2,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                        // 'created_date' => $created_date,
                                                        'arrived_value' => $this->request->data['propertyscreennew']['derivedresult' . $subrulecondition['subrule']['subrule_id']],
                                                        'maxformula_value' => $this->request->data['propertyscreennew']['maxvalresult' . $subrulecondition['subrule']['subrule_id']],
                                                        'final_value' => $this->request->data['propertyscreennew']['finalresult' . $subrulecondition['subrule']['subrule_id']],
                                                        'dep_detail' => $this->request->data['propertyscreennew']['usedFormula' . $subrulecondition['subrule']['subrule_id']],
                                                        //  'dep_detail1' => $this->request->data['propertyscreennew']['usedFormula1' . $subrulecondition['subrule']['subrule_id']],
                                                        'rate' => $insertrate,
                                                        'search_rule_id' => $search_rule_flag,
                                                        'org_rrr' => $insertrate,
                                                        'org_rr1' => $additionalrate,
                                                        'org_rr2' => $fixedrate,
                                                        'org_abe' => $comparisonrate,
                                                        //'act_rrr' => $act_rate,
//                                                    'act_rr1' => $act_rate_addtional,
//                                                    'act_rr2' => $act_fixed_rate_addtional,
//                                                    'act_abe' => $act_cmp_rate_addtional,
                                                        'rate_revision_formula' => $this->request->data['propertyscreennew']['rate_rivision_formula' . $subrulecondition['subrule']['subrule_id']],
                                                        'depreciation_factor' => $ratefactorarray[0]['ratefactor']['rate_factor'],
                                                        'effect_rrr' => $this->request->data['propertyscreennew']['eff_rrr' . $subrulecondition['subrule']['subrule_id']],
                                                        'effect_rr2' => $this->request->data['propertyscreennew']['eff_rr2' . $subrulecondition['subrule']['subrule_id']],
                                                        'effect_rr1' => $additionalrate,
                                                        'usage_main_catg_id' => $this->request->data['propertyscreennew']['usage_main_catg_id'],
                                                        'usage_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_catg_id'],
                                                        'usage_sub_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_sub_catg_id'],
                                                    );
                                                    array_push($insertdata, $arr);
                                                }
                                            }
                                        }
                                    } else {
                                        $slabflag = 0;
                                        $slabfield = "";
                                        $slabfieldid = 0;
                                        $slabfieldvalue = 0;
                                        if ($json2array['rate'] != NULL) {
                                            foreach ($json2array['rate'] as $rate1) {
                                                if ($rate1['rate']['slab_rate_flag'] == 'Y') {
                                                    $slabflag = 1;
                                                }
                                            }
                                        }
                                        if ($slabflag == 1) {
                                            foreach ($json2array['usageitemlist'] as $usageitem) {
                                                if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) {
                                                    if ($usageitem['itemlist']['slab_rate_flag'] == 'Y') {
                                                        $slabfield = $usageitem['usagelinkcategory']['uasge_param_code'];
                                                        $slabfieldid = $usageitem['usagelinkcategory']['usage_param_id'];
                                                    }
                                                }
                                            }
                                            foreach ($json2array['rate'] as $rate1) {

                                                $convertedrate = $this->rateconversion->standardrateconversion($rate1['rate']);

                                                $rate = $convertedrate['prop_rate'];
                                                $rate_land = $convertedrate['land_rate'];
                                                $rate_construction = $convertedrate['construction_rate'];

                                                if ($this->request->data['propertyscreennew']['is_tdr_applicable'] == 'Y') {
                                                    if ($tdrfactorarray != NULL) {
                                                        $rate = eval("return (" . $rate . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                                        $rate_land = eval("return (" . $rate_land . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                                        $rate_construction = eval("return (" . $rate_construction . '*' . $tdrfactorarray[0]['tdrfactor']['tdr_factor'] . ");");
                                                    }
                                                }

                                                if ($ratefactorarray != NULL) {
                                                    $rate = eval("return (" . $rate . '*' . $ratefactorarray[0]['ratefactor']['rate_factor'] . ");");
                                                    $rate_land = eval("return (" . $rate_land . '*' . $ratefactorarray[0]['ratefactor']['rate_factor'] . ");");
                                                    $rate_construction = eval("return (" . $rate_construction . '*' . $ratefactorarray[0]['ratefactor']['rate_factor'] . ");");
                                                    $rate_factor = 'RRR*' . $ratefactorarray[0]['ratefactor']['rate_factor'];
                                                }

                                                if (strpos($singlerule['evalrule']['evalrule_formula1'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula2'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula3'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula4'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula5'], 'RRL')) {
                                                    $insertrate = $rate_land;
                                                } else if (strpos($singlerule['evalrule']['evalrule_formula1'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula2'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula3'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula4'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula5'], 'RRC')) {
                                                    $insertrate = $rate_construction;
                                                } else if (strpos($subrulecondition['subrule']['evalsubrule_formula1'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula2'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula3'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula4'], 'RRR') || strpos($subrulecondition['subrule']['evalsubrule_formula5'], 'RRR')) {

                                                    $insertrate = $rate;
                                                    $search_rule_flag = $json2array['rate_search_rule_id'];
                                                } else if (strpos($subrulecondition['subrule']['evalsubrule_formula1'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula2'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula3'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula4'], 'RR1') || strpos($subrulecondition['subrule']['evalsubrule_formula5'], 'RR1')) {
                                                    $insertrate = $additionalrate;
                                                    $search_rule_flag = $json2array['add_rate_search_rule_id'];
                                                }

                                                if ($rate1['rate']['range_from'] < $this->request->data['propertyscreennew'][$slabfield] && ($rate1['rate']['range_to'] > $this->request->data['propertyscreennew'][$slabfield] || $rate1['rate']['range_to'] == NULL)) {
                                                    $slabfieldvalue = $this->request->data['propertyscreennew'][$slabfield] - $rate1['rate']['range_from'];

                                                    // To Add Cmp Rate
                                                    $formulaused = $this->request->data['propertyscreennew']['usedFormula' . $rate1['rate']['range_from']];
                                                    $rate_revised = $this->request->data['propertyscreennew']['rate_rivision_formula' . $rate1['rate']['range_from']];
                                                    if (strpos($formulaused, 'ABE')) {
                                                        $insertrate = $comparisonrate;
                                                        $search_rule_flag = $json2array['cmp_rate_search_rule_id'];
                                                    }

                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'item_id' => $singlerule['evalrule']['output_item_id'],
                                                        'item_type_id' => 2,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                        //'created_date' => $created_date,
                                                        'slab_field_flag' => 'Y',
                                                        'slab_field_value' => $slabfieldvalue,
                                                        'arrived_value' => $this->request->data['propertyscreennew']['derivedresult' . $rate1['rate']['range_from']],
                                                        'maxformula_value' => $this->request->data['propertyscreennew']['maxvalresult' . $rate1['rate']['range_from']],
                                                        'final_value' => $this->request->data['propertyscreennew']['finalresult' . $rate1['rate']['range_from']],
                                                        'dep_detail' => $this->request->data['propertyscreennew']['usedFormula' . $rate1['rate']['range_from']],
                                                        'rate' => $insertrate,
                                                        'search_rule_id' => $search_rule_flag,
                                                        'org_rrr' => $insertrate,
                                                        'org_rr1' => $additionalrate,
                                                        'org_rr2' => $fixedrate,
                                                        'org_abe' => $comparisonrate,
                                                        'rate_revision_formula' => $this->request->data['propertyscreennew']['rate_rivision_formula' . $rate1['rate']['range_from']],
                                                        'depreciation_factor' => $ratefactorarray[0]['ratefactor']['rate_factor'],
                                                        'effect_rrr' => $this->request->data['propertyscreennew']['eff_rrr' . $rate1['rate']['range_from']],
                                                        'effect_rr2' => $this->request->data['propertyscreennew']['eff_rr2' . $rate1['rate']['range_from']],
                                                        'effect_rr1' => $additionalrate,
                                                        'usage_main_catg_id' => $this->request->data['propertyscreennew']['usage_main_catg_id'],
                                                        'usage_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_catg_id'],
                                                        'usage_sub_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_sub_catg_id']
                                                    );
                                                    array_push($insertdata, $arr);
                                                } else if ($rate1['rate']['range_from'] < $this->request->data['propertyscreennew'][$slabfield] && $rate1['rate']['range_to'] < $this->request->data['propertyscreennew'][$slabfield]) {
                                                    $slabfieldvalue = $rate1['rate']['range_to'] - $rate1['rate']['range_from'];
                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'item_id' => $singlerule['evalrule']['output_item_id'],
                                                        'item_type_id' => 2,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                        //  'created_date' => $created_date,
                                                        'slab_field_flag' => 'Y',
                                                        'slab_field_value' => $slabfieldvalue,
                                                        'arrived_value' => $this->request->data['propertyscreennew']['derivedresult' . $rate1['rate']['range_from']],
                                                        'maxformula_value' => $this->request->data['propertyscreennew']['maxvalresult' . $rate1['rate']['range_from']],
                                                        'final_value' => $this->request->data['propertyscreennew']['finalresult' . $rate1['rate']['range_from']],
                                                        'dep_detail' => $this->request->data['propertyscreennew']['usedFormula' . $rate1['rate']['range_from']],
                                                        'rate' => $insertrate,
                                                        'search_rule_id' => $search_rule_flag,
                                                        'org_rrr' => $insertrate,
                                                        'org_rr1' => $additionalrate,
                                                        'org_rr2' => $fixedrate,
                                                        'org_abe' => $comparisonrate,
                                                        'rate_revision_formula' => $this->request->data['propertyscreennew']['rate_rivision_formula' . $rate1['rate']['range_from']],
                                                        'depreciation_factor' => $ratefactorarray[0]['ratefactor']['rate_factor'],
                                                        'effect_rrr' => $this->request->data['propertyscreennew']['rate_rivision_formula' . $rate1['rate']['eff_rrr']],
                                                        'effect_rr2' => $this->request->data['propertyscreennew']['rate_rivision_formula' . $rate1['rate']['eff_rr2']],
                                                        'effect_rr1' => $additionalrate,
                                                        'usage_main_catg_id' => $this->request->data['propertyscreennew']['usage_main_catg_id'],
                                                        'usage_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_catg_id'],
                                                        'usage_sub_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_sub_catg_id']
                                                    );
                                                    array_push($insertdata, $arr);
                                                }
                                            }
                                        } else {
                                            $resultarray = $this->evalcalculation->singlecalculation($this->request->data, $singlerule, $rate, $fixedrate, $json2array, $comparisonrate, $additionalrate, $additionalrate2, $ratefactorarray);
                                            $this->request->data['propertyscreennew']['derivedresult'] = $resultarray['derivedresult'];
                                            $this->request->data['propertyscreennew']['maxvalresult'] = $resultarray['maxvalresult'];
                                            $this->request->data['propertyscreennew']['finalresult'] = $resultarray['finalresult'];
                                            $this->request->data['propertyscreennew']['usedFormula'] = $resultarray['usedFormula'];
                                            $this->request->data['propertyscreennew']['rate_rivision_formula'] = $resultarray['rate_rivision_formula'];
                                            $this->request->data['propertyscreennew']['eff_rrr'] = $resultarray['eff_rrr'];
                                            $this->request->data['propertyscreennew']['eff_rr2'] = $resultarray['eff_rr2'];

                                            if (strpos($singlerule['evalrule']['evalrule_formula1'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula2'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula3'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula4'], 'RRL') || strpos($singlerule['evalrule']['evalrule_formula5'], 'RRL')) {
                                                $insertrate = $rate_land;
                                            } else if (strpos($singlerule['evalrule']['evalrule_formula1'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula2'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula3'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula4'], 'RRC') || strpos($singlerule['evalrule']['evalrule_formula5'], 'RRC')) {
                                                $insertrate = $rate_construction;
                                            } else if (strpos($singlerule['evalrule']['evalrule_formula1'], 'RRR') || strpos($singlerule['evalrule']['evalrule_formula2'], 'RRR') || strpos($singlerule['evalrule']['evalrule_formula3'], 'RRR') || strpos($singlerule['evalrule']['evalrule_formula4'], 'RRR') || strpos($singlerule['evalrule']['evalrule_formula5'], 'RRR')) {

                                                $insertrate = $rate;
                                                $search_rule_flag = $json2array['rate_search_rule_id'];
                                            } else if (strpos($singlerule['evalrule']['evalsubrule_formula1'], 'RR1') || strpos($singlerule['evalrule']['evalsubrule_formula2'], 'RR1') || strpos($singlerule['evalrule']['evalsubrule_formula3'], 'RR1') || strpos($singlerule['evalrule']['evalsubrule_formula4'], 'RR1') || strpos($singlerule['evalrule']['evalsubrule_formula5'], 'RR1')) {

                                                $insertrate = $additionalrate;
                                                $search_rule_flag = $json2array['add_rate_search_rule_id'];
                                            }

                                            // To Add Cmp Rate
                                            $formulaused = $resultarray['usedFormula'];
                                            $rate_revised = $resultarray['rate_rivision_formula'];
                                            if (strpos($formulaused, 'ABE')) {
                                                $insertrate = $comparisonrate;
                                                $search_rule_flag = $json2array['cmp_rate_search_rule_id'];
                                            }

                                            $arr = array('val_id' => $lastinsertedid,
                                                'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                'item_id' => $singlerule['evalrule']['output_item_id'],
                                                'item_type_id' => 2,
                                                'ip_address' => $ip_address,
                                                'user_id' => $this->Auth->user('user_id'),
                                                //'created_date' => $created_date,
                                                'arrived_value' => $resultarray['derivedresult'],
                                                'maxformula_value' => $resultarray['maxvalresult'],
                                                'final_value' => $resultarray['finalresult'],
                                                'dep_detail' => $resultarray['usedFormula'],
                                                'rate' => $insertrate,
                                                'search_rule_id' => $search_rule_flag,
                                                'org_rrr' => $insertrate,
                                                'org_rr1' => $additionalrate,
                                                'org_rr2' => $fixedrate,
                                                'org_abe' => $comparisonrate,
                                                'rate_revision_formula' => $resultarray['rate_rivision_formula'],
                                                'depreciation_factor' => $ratefactorarray[0]['ratefactor']['rate_factor'],
                                                'effect_rrr' => $resultarray['eff_rrr'],
                                                'effect_rr2' => $resultarray['eff_rr2'],
                                                'effect_rr1' => $additionalrate,
                                                'usage_main_catg_id' => $this->request->data['propertyscreennew']['usage_main_catg_id'],
                                                'usage_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_catg_id'],
                                                'usage_sub_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_sub_catg_id']
                                            );
                                            array_push($insertdata, $arr);
                                        }
                                    }
//------------ Item Rate --------------------------
//pr();                                
//pr($json2array['usageitemrate']);exit;
                                    if (isset($json2array['usageitemrate']) && $json2array['usageitemrate'] != NULL) {
                                        foreach ($json2array['usageitemrate'] as $keyrule => $usagerule) {
                                            if ($singlerule['evalrule']['evalrule_id'] == $keyrule) {
                                                foreach ($usagerule as $keyitem => $usageitem) {
                                                    $formula = '';

                                                    foreach ($usageitem['items'] as $usageitemparam) {
                                                        $formula .= "*" . $usageitemparam;
                                                    }
                                                    $formula = substr($formula, 1);
                                                    // pr($keyrule);exit;

                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $keyrule,
                                                        'item_id' => $usageitem['outputitem'],
                                                        'item_type_id' => 2,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                        //'created_date' => $created_date,
                                                        'arrived_value' => eval("return ($formula);"),
                                                        'maxformula_value' => '',
                                                        'final_value' => eval("return ($formula);"),
                                                        'dep_detail' => $formula,
                                                        'rate' => $usageitem['items']['RRR'],
                                                        'search_rule_id' => 555, //for item rate
                                                        'org_rrr' => $insertrate,
                                                        'org_rr1' => $additionalrate,
                                                        'org_rr2' => $fixedrate,
                                                        'org_abe' => $comparisonrate,
                                                        'rate_revision_formula' => $resultarray['rate_rivision_formula'],
                                                        'depreciation_factor' => $ratefactorarray[0]['ratefactor']['rate_factor'],
                                                        'effect_rrr' => $resultarray['eff_rrr'],
                                                        'effect_rr2' => $resultarray['eff_rr2'],
                                                        'effect_rr1' => $additionalrate,
                                                        'usage_main_catg_id' => $this->request->data['propertyscreennew']['usage_main_catg_id'],
                                                        'usage_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_catg_id'],
                                                        'usage_sub_sub_catg_id' => $this->request->data['propertyscreennew']['usage_sub_sub_catg_id']
                                                    );
                                                    array_push($insertdata, $arr);
                                                }
                                            } // rule match
                                        }
                                    }
//-------------------Item Rate End --------------------------   
                                    foreach ($json2array['usageitemlist'] as $usageitem) {
                                        //pr($usageitem['itemlist']['is_string']); exit;
                                        if ($usageitem['itemlist']['area_field_flag'] == 'Y') {
                                            if (isset($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit']) && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'] != '0' && $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'] != 'empty') {
                                                $convertedarea = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']];
                                                $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'converted'];
                                                $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'converted'] = $convertedarea;
                                            }
                                        }
                                        if ($singlerule['evalrule']['evalrule_id'] == $usageitem['usagelinkcategory']['evalrule_id']) {
                                            if ($usageitem['usagelinkcategory']['item_rate_flag'] == 'Y') {
                                                if ($usageitem['itemlist']['is_string'] == 'Y') {
                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'item_id' => $usageitem['usagelinkcategory']['usage_param_id'],
                                                        'item_value_string' => $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'hf'],
                                                        'item_type_id' => 1,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                            //'created_date' => $created_date
                                                    );
                                                } else {
                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'item_id' => $usageitem['usagelinkcategory']['usage_param_id'],
                                                        'item_value' => $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'hf'],
                                                        'item_type_id' => 1,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                            //'created_date' => $created_date
                                                    );
                                                }
                                            } else {
                                                if ($usageitem['itemlist']['is_string'] == 'Y') {
                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'item_id' => $usageitem['usagelinkcategory']['usage_param_id'],
                                                        'item_value_string' => $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']],
                                                        'item_type_id' => 1,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                            // 'created_date' => $created_date
                                                    );
                                                } else {
                                                    $arr = array('val_id' => $lastinsertedid,
                                                        'rule_id' => $singlerule['evalrule']['evalrule_id'],
                                                        'item_id' => $usageitem['usagelinkcategory']['usage_param_id'],
                                                        'item_value' => $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code']],
                                                        'item_type_id' => 1,
                                                        'ip_address' => $ip_address,
                                                        'user_id' => $this->Auth->user('user_id'),
                                                            // 'created_date' => $created_date
                                                    );
                                                }
                                                if ($usageitem['itemlist']['area_field_flag'] == 'Y') {
                                                    $arr['area_unit'] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'unit'];
                                                    if ($this->Session->read("land_type") == 'U') {
                                                        if (isset($this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'areatype'])) {
                                                            $arr['area_type'] = $this->request->data['propertyscreennew'][$usageitem['usagelinkcategory']['uasge_param_code'] . 'areatype'];
                                                        }
                                                    }
                                                }
                                            }
                                            array_push($insertdata, $arr);
                                        }
                                    }
                                    $this->valuation_details->saveAll($insertdata);

                                    $this->loadModel('valuation_details');
                                    $total = $this->valuation_details->query("SELECT SUM(final_value) as total from  ngdrstab_trn_valuation_details where val_id=?", array($lastinsertedid));
                                    $final_amt = $total[0][0]['total'];
                                    $rounded_amt = $final_amt;
                                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                                    if (!empty($regconf)) {
                                        if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                            if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                                $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                            $rounded_amt = $this->round_tonext($final_amt, $roundto);
                                        }
                                    }
                                    $this->valuation_details->updateAll(array("rounded_val_amt" => $rounded_amt), array("val_id" => $lastinsertedid));
                                    $this->valuation->updateAll(array("rounded_val_amt" => $rounded_amt), array("val_id" => $lastinsertedid));

                                    //return $lastinsertedid;
                                }
                            } else {
                                
                            }
                        }
                    }
                }
            }


            if (!isset($this->request->data['propertyscreennew']['val_id'])) {
                return 'RNA';
            } else {
                // pr($this->request->data['propertyscreennew']);
                return $this->request->data['propertyscreennew']['val_id'];
            }
        } catch (Exception $ex) {
           // pr($ex);
          // exit;
        }
    }

    function get_valuation_amt($val_id = NULL) { //updated on 06-April-2016
        try {
//            $this->autoRender = FALSE;
            $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
            if ($valuation_id) {

                $this->loadModel('valuation_details');
                $total = $this->valuation_details->query("SELECT SUM(final_value) as total from  ngdrstab_trn_valuation_details where val_id=?", array($val_id));
                if (!empty($total)) {
                    return $total[0][0]['total'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $e) {
            echo 0;
        }
    }

    function round_tonext500($number) {
        $x = floor($number / 500);
        $rem = fmod($number, 500);
        if ($rem > 1) {
            $num2 = 1;
            $num3 = $x + $num2;
            $final_amt = $num3 * 500;
        } else {
            $final_amt = $number;
        }
        return $final_amt;
    }

    function round_tonext($number, $roundto) {
        @$x = floor($number / $roundto);
        $rem = fmod($number, $roundto);
        if ($rem > 1) {
            $num2 = 1;
            $num3 = $x + $num2;
            $final_amt = $num3 * $roundto;
        } else {
            $final_amt = $number;
        }
        return $final_amt;
    }

    //-----------------------------------Validation dynamic control for server side(KALYANI)----------------------------------------------
    public function validatedata($data, $fieldlist) {
        $this->loadModel('divisionnew');
        $this->loadModel('employee');
        $this->loadModel("NGDRSErrorCode");

        $laug = $this->Session->read("sess_langauge");
//          pr($laug);exit;
        $result_codes = $this->NGDRSErrorCode->find("all");
        $errorarray = array();
        foreach ($fieldlist as $frmkey => $valrule) {
            $errorarray[$frmkey . '_error'] = "";
        }
        //in these function data firstly passed from check function of cakephp which does not allow to save  all statements like drop,delete,truncate,select,insert,update,table, from,where.
        if (!Sanitize::check($data)) {
            $errorarray['error'] = "proper data";
            $this->Session->setFlash(__('Do not enter Invalid Data'));
            return $errorarray;
        }
        // read    fields   from  $fieldlist
        foreach ($fieldlist as $listkey => $listcontrol) {
            // read form  fields       
            $fieldexist = 0;
            foreach ($data as $frmkey => $frmval) {

                if ($frmkey == $listkey) { // emp_name == emp_name
                    $fieldexist = 1;
                    // pr($listcontrol); exit;
                    foreach ($listcontrol as $controltype => $valrule) {//  $controltype-> text/select  $valrule -> is_alpha/is_numeric
                        // get error_code  from errorcode result                
                        $valrule_arr = explode(",", $valrule);
                        // pr($valrule_arr);exit;
                        $messflag = 0;
                        foreach ($valrule_arr as $singlerule) {
                            foreach ($result_codes as $errorkey => $error_record) { /// is_alpha  
                                if ($error_record['NGDRSErrorCode']['error_code'] == $singlerule) {// ERROR CODE MATCHING
                                    //get pattern from errorkey
                                    $pattern = $error_record['NGDRSErrorCode']['pattern_rule'];
                                    if (!empty($pattern)) {
                                        if (is_array($frmval)) {
                                            foreach ($frmval as $singleval) {
                                                if (!preg_match($pattern, $singleval)) {//PATTERN MATCHING
                                                    $errorarray[$frmkey . "_error"] = $error_record['NGDRSErrorCode']['error_messages_' . $laug];
                                                    $messflag = 1;
                                                }
                                            }
                                        } else {

                                            if (!preg_match($pattern, $frmval)) {//PATTERN MATCHING
                                                $errorarray[$frmkey . "_error"] = $error_record['NGDRSErrorCode']['error_messages_' . $laug];
                                                $messflag = 1;
                                            }
                                        }
                                    }
                                }
                            }
                            if ($messflag == 1) {
                                break;
                            }
                        }
                    }
                }
            }

            if ($fieldexist == 0) {
                $errorarray[$listkey . "_error"] = 'Error in some form fields';
            }
        }


        return $errorarray;
    }

    //-----------------------------------Validation RULE SETTING for server AND client side(KALYANI)----------------------------------------------
    public function getvalidationruleset($fieldlist, $multiform = False) {
        $this->loadModel('NGDRSErrorCode');
        $errarr = array();
        $fielderrorarray = array();
        if ($multiform) {
            foreach ($fieldlist as $fieldlist1) {
                foreach ($fieldlist1 as $key => $valrule) {
                    $errarr[$key . '_error'] = "";
                }
            }
            foreach ($fieldlist as $fieldlist1) {
                foreach ($fieldlist1 as $fielderrarr) {
                    foreach ($fielderrarr as $field) {
                        $rulesset = explode(",", $field);
                        foreach ($rulesset as $rules) {

                            $fielderrorarray[$rules] = $rules;
                        }
                    }
                }
            }
            $this->set("errarr", $errarr);
        } else {
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            foreach ($fieldlist as $fielderrarr) {
                foreach ($fielderrarr as $field) {
                    $rulesset = explode(",", $field);
                    foreach ($rulesset as $rules) {

                        $fielderrorarray[$rules] = $rules;
                    }
                }
            }
        }


        $result_codes = $this->NGDRSErrorCode->find("all", array('conditions' => array('error_code' => $fielderrorarray)));
        return $result_codes;
    }

//-----------------------------------Validation Errors(KALYANI)----------------------------------------------
    function ValidationError($errors) {
        $errorflag = 1;
        foreach ($errors as $message) {
            if (!empty($message)) {
                $errorflag = 0;
            }
        }
        if ($errorflag == 0) {
            $this->set("errarr", $errors);
        }
        return $errorflag;
    }

//    --------------------------------------------------istrim validate function(KALYANI)----------------------------------------
    public function istrim($data) {
        foreach ($data as $formkey => $formval) {
            ////pr($formval);
            if (is_array($formval)) {
                foreach ($formval as $formkey1 => $formval1) {
                    $trimmed = trim($formval1);
                    $data[$formkey][$formkey1] = $trimmed;
                }
            } else {
                $trimmed = trim($formval);
                $data[$formkey] = $trimmed;
            }
        }
        return $data;
    }

    //    ----------------------------------------------------set csrf token(KALYANI)-----------------------------------------------
    public function set_csrf_token() {
        $csrftoken = rand(14552, 124521142563352);
        $this->Session->write('csrftoken', $csrftoken);
    }

//-------------------------------------------------------check csrf token(KALYANI)----------------------------------------------
    public function check_csrf_token($token, $flag = NULL) {

        $temp = $this->Session->read('csrftoken');
        if (is_null($temp)) {
            $temp = $this->Session->read('csrftoken');
        }
        if (strcmp($token, $temp) !== 0) {

            //  return $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
        }
        $this->set_csrf_token();
    }

    public function check_csrf_token_withoutset($token) {

        $temp = $this->Session->read('csrftoken');
        if (is_null($temp)) {
            $temp = $this->Session->read('csrftoken');
        }
        if (strcmp($token, $temp) !== 0) {
//        
            return $this->redirect(array('controller' => 'Error', 'action' => 'csrftoken'));
        }
        // exit;
        // $this->set_csrf_token();
    }

//-------------------------------------------------------check role wise all forms(KALYANI)----------------------------------------------
    public function check_role_escalation() {
        try {
            $this->loadModel('role');
            $this->loadModel('userpermissions');
            $this->loadModel('Menu');
            $this->loadModel('SubMenu');
            $this->loadModel('Subsubmenu');
            $this->loadModel('currentstate');
            $this->loadModel('adminLevelConfig');
            

            $currentstate = $this->currentstate->find("all");
            if (empty($currentstate)) {
                $this->Session->setFlash(__('Please Initiate/Configure the state first'));
                return $this->redirect(array('controller' => 'Users', 'action' => 'welcome'));
            }else{ 
              $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $currentstate[0]['currentstate']['state_id'])));  
              if(empty($is_div_flag)){     
                $curraction = $this->request->params['action'];
                $functionlist=array('admin_block_level_config');
                if(!in_array($curraction,$functionlist)){
                $this->Session->setFlash(__('Please initiate administrative Blocks'));
                return $this->redirect(array('controller' => 'Users', 'action' => 'welcome')); 
                }  
              }
            }

            $user = $this->Auth->user('user_id');
            $role_id = $this->Auth->user('role_id');
            $usertype = $this->Session->read("session_usertype");

            $currcontroller = $this->request->params['controller'];
            $curraction = $this->request->params['action'];

            $data = array();
            if ($usertype == 'O') {
                $data = ClassRegistry::init('getUserRole')->find('list', array('fields' => array('getUserRole.role_id'), 'conditions' => array('user_id' => array($user))));
            } else {
                $data = ClassRegistry::init('getUserRolecitizen')->find('list', array('fields' => array('getUserRolecitizen.role_id'), 'conditions' => array('user_id' => array($user))));
            }


            $fields = '';
            $organizationrole = '';
            foreach ($data as $key => $value) {
                $fields .= $value . ',';
            }
            if (!empty($fields)) {
                $organizationrole = substr($fields, 0, -1);
            } else {
                $organizationrole = "'0'";
            }
//            pr($organizationrole);
//            pr($currcontroller);
//            pr($curraction);
            // exit;

            $checkmainmenu = $this->userpermissions->query("select drp.* ,mm.controller,mm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm   where    drp.role_id IN( $organizationrole )   AND  drp.menu_id =  mm .id    AND mm.controller='$currcontroller'  AND mm.action='$curraction' ");
            if (empty($checkmainmenu)) {
                //pr("select drp.* ,sm.controller,sm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm  where    drp.role_id IN( $organizationrole )    AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id    AND sm.controller='$currcontroller'  AND sm.action='$curraction' ");
                $checksubmenu = $this->userpermissions->query("select drp.* ,sm.controller,sm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm  where    drp.role_id IN( $organizationrole )    AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id    AND sm.controller='$currcontroller'  AND sm.action='$curraction' ");
                // pr($checksubmenu);
                if (empty($checksubmenu)) {
                    $checksubsubmenu = $this->userpermissions->query("select drp.* ,ssm.controller,ssm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm, ngdrstab_mst_subsubmenu ssm  where    drp.role_id IN( $organizationrole )    AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id   AND  drp.subsubmenu_id =  ssm .id AND ssm.controller='$currcontroller'  AND ssm.action='$curraction' ");
                    if (empty($checksubsubmenu)) {
                        // exit;
                        $this->Session->setFlash(__('Not In Your Role'));
                        return $this->redirect(array('controller' => 'Error', 'action' => 'notfound'));
                    }
                }
            }
        } catch (Exception $e) {
            // pr($e);
            //exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_role_escalation_old() {
        try {
            $this->loadModel('role');
            $this->loadModel('userpermissions');
            $this->loadModel('Menu');
            $this->loadModel('SubMenu');
            $this->loadModel('Subsubmenu');

            $user = $this->Auth->user('user_id');
            $role_id = $this->Auth->user('role_id');
            //   pr($role_id);


            $currcontroller = $this->request->params['controller'];
            $curraction = $this->request->params['action'];
// pr($currcontroller);
            ///  pr($curraction);
            //   exit;
            $checkmainmenu = $this->userpermissions->query("select drp.* ,mm.controller,mm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm   where    drp.role_id= $role_id   AND  drp.menu_id =  mm .id    AND mm.controller='$currcontroller'  AND mm.action='$curraction' ");

            if (empty($checkmainmenu)) {
                $checksubmenu = $this->userpermissions->query("select drp.* ,sm.controller,sm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm  where    drp.role_id= $role_id   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id    AND sm.controller='$currcontroller'  AND sm.action='$curraction' ");
//pr("select drp.* ,sm.controller,sm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm  where    drp.role_id= $role_id   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id    AND sm.controller='$currcontroller'  AND sm.action='$curraction' ");
//pr($checksubmenu);exit;
                if (empty($checksubmenu)) {
                    $checksubsubmenu = $this->userpermissions->query("select drp.* ,ssm.controller,ssm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm, ngdrstab_mst_subsubmenu ssm  where    drp.role_id= $role_id   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id   AND  drp.subsubmenu_id =  ssm .id AND ssm.controller='$currcontroller'  AND ssm.action='$curraction' ");

                    if (empty($checksubsubmenu)) {
                        $this->Session->setFlash(__('Not In Your Role'));
                        //return $this->redirect(array('controller' => 'Error', 'action' => 'notfound'));
                    }
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function check_role_escalation_tab() {
        try {
            $this->loadModel('role');
            $this->loadModel('userpermissions');
            $this->loadModel('Menu');
            $this->loadModel('SubMenu');
            $this->loadModel('Subsubmenu');
            $this->loadModel('currentstate');    
            $this->loadModel('adminLevelConfig'); 
            $usertype = $this->Session->read("session_usertype");
            
            $currentstate = $this->currentstate->find("all");
            if (empty($currentstate)) {
                $this->Session->setFlash(__('Please Initiate/Configure the state first'));
                return $this->redirect(array('controller' => 'Users', 'action' => 'welcome'));
            }else{ 
              $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $currentstate[0]['currentstate']['state_id'])));  
              if(empty($is_div_flag)){     
                $curraction = $this->request->params['action'];
                $functionlist=array('admin_block_level_config');
                if(!in_array($curraction,$functionlist)){
                $this->Session->setFlash(__('Please initiate administrative Blocks'));
                return $this->redirect(array('controller' => 'Users', 'action' => 'welcome')); 
                }  
              }
            }
            
            
            
            $TabMenu = $this->Session->read('TabMenu');
            if (empty($TabMenu)) {
                $TabMenu = $this->Session->read('TabMenu');
            }
            $user = $this->Auth->user('user_id');
            $role_id = $this->Auth->user('role_id');

            $currcontroller = ucfirst($this->request->params['controller']);
            $curraction = $this->request->params['action'];

            $data = array();
            if ($usertype == 'O') {
                $data = ClassRegistry::init('getUserRole')->find('list', array('fields' => array('getUserRole.role_id'), 'conditions' => array('user_id' => array($user))));
            } else {
                $data = ClassRegistry::init('getUserRolecitizen')->find('list', array('fields' => array('getUserRolecitizen.role_id'), 'conditions' => array('user_id' => array($user))));
            }


            $fields = '';
            $organizationrole = '';
            foreach ($data as $key => $value) {
                $fields .= $value . ',';
            }
            if (!empty($fields)) {
                $organizationrole = substr($fields, 0, -1);
            } else {
                $organizationrole = "'0'";
            }

            if (is_null($TabMenu)) {
                $checkmainmenu = $this->userpermissions->query("select drp.* ,mm.controller,mm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm   where    drp.role_id IN($organizationrole)   AND  drp.menu_id =  mm .id    AND mm.controller='$currcontroller'  AND mm.action='$curraction' ");
                if (empty($checkmainmenu)) {
                    $checksubmenu = $this->userpermissions->query("select drp.* ,sm.controller,sm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm  where    drp.role_id IN($organizationrole)   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id    AND sm.controller='$currcontroller'  AND sm.action='$curraction' ");
                    if (empty($checksubmenu)) {
                        $checksubsubmenu = $this->userpermissions->query("select drp.* ,ssm.controller,ssm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm, ngdrstab_mst_subsubmenu ssm  where    drp.role_id  IN ($organizationrole)   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id   AND  drp.subsubmenu_id =  ssm .id AND ssm.controller='$currcontroller'  AND ssm.action='$curraction' ");
                        if (empty($checksubsubmenu)) {
                            $this->Session->setFlash(__('Not In Your Role'));
                            return $this->redirect(array('controller' => 'Error', 'action' => 'notfound'));
                        }
                    }
                }
                $this->Session->write('TabMenu', 'Yes');
            } else {
                $checkmainmenu = $this->userpermissions->query("select drp.* ,mm.controller,mm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm   where    drp.role_id IN($organizationrole)  AND  drp.menu_id =  mm .id    AND mm.controller='$currcontroller' ");
                if (empty($checkmainmenu)) {
                    $checksubmenu = $this->userpermissions->query("select drp.* ,sm.controller,sm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm  where    drp.role_id IN($organizationrole)   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id    AND sm.controller='$currcontroller'  ");
                    if (empty($checksubmenu)) {
                        $checksubsubmenu = $this->userpermissions->query("select drp.* ,ssm.controller,ssm.action from ngdrstab_mst_userpermissions drp , ngdrstab_mst_menu mm , ngdrstab_mst_submenu sm, ngdrstab_mst_subsubmenu ssm  where    drp.role_id IN ($organizationrole)   AND  drp.menu_id =  mm .id     AND  drp.submenu_id =  sm .id   AND  drp.subsubmenu_id =  ssm .id AND ssm.controller='$currcontroller'  ");
                        if (empty($checksubsubmenu)) {
                            $this->Session->setFlash(__('Not In Your Role'));
                            return $this->redirect(array('controller' => 'Error', 'action' => 'notfound'));
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // pr($e);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//-------------------------------------------------------encode_special_char(KALYANI)----------------------------------------------
    public function encode_special_char($data) {
        // add slashes to to special charectors
        $flag = 0;
        foreach ($data as $key => $val) {
            $flag = 0;

            //   foreach ($val as $key1 => $val1) {
            //  $flag = 0;
            // pg_escape function will escape all malicious chars by prefixing them with ' . the character is stored as it is to database e.g. < > 
            // htmlentities function will conver < > etc to  &lt & gt etc. so that they appear as it is on client side.
            // < > etc chars are shown as it is without being interpreted as html tag characters.
            if (is_array($val) || is_object($val)) {
                //  pr($val);exit;
                foreach ($val as $key1 => $val1) {
                    $flag = 1;
                    $data[$key][$key1] = htmlentities(pg_escape_string($val1));
                }
            }
            if ($flag == 0) {
                $data[$key] = htmlentities(pg_escape_string($val));
            }
            //}
        }
        return $data;
    }

//-------------------------------------------------------decode_special_char(KALYANI)----------------------------------------------
    public function decode_special_char($data) {
        // add strip quote    to special charectors
        $flag = 0;
        foreach ($data as $key => $val) {
            $flag = 0;
            foreach ($val as $key1 => $val1) {
                $flag = 0;
                if (is_array($val1) || is_object($val1)) {
                    foreach ($val1 as $key2 => $val2) {
                        $flag = 1;
                        $data[$key][$key1][$key2] = str_replace("''", "'", $val2);
                    }
                }
                if ($flag == 0) {
                    $data[$key][$key1] = str_replace("''", "'", $val1);
                }
            }
        }
        return $data;
    }

    //-------------------------------------------------------Setflash messages from db----------------------------------------------
    public function load_alert_msgs() {
        $this->loadModel('ErrorMessages');
        $alerts = $this->ErrorMessages->find('all');
        foreach ($alerts as $key => $alert) {
            $alert = $alert['ErrorMessages'];
            $jsonarr[$alert['function_name_en']][$alert['btn_name']]['en'] = $alert['msg_alert_desc_en'];
            $jsonarr[$alert['function_name_en']][$alert['btn_name']]['ll'] = $alert['msg_alert_desc_ll'];
            $jsonarr[$alert['function_name_en']][$alert['btn_name']]['ll1'] = $alert['msg_alert_desc_ll1'];
            $jsonarr[$alert['function_name_en']][$alert['btn_name']]['ll2'] = $alert['msg_alert_desc_ll2'];
            $jsonarr[$alert['function_name_en']][$alert['btn_name']]['ll3'] = $alert['msg_alert_desc_ll3'];
            $jsonarr[$alert['function_name_en']][$alert['btn_name']]['ll4'] = $alert['msg_alert_desc_ll4'];
        }
        $file = new File(WWW_ROOT . 'files/jsonfile_alerts.json', true);
        $file->write(json_encode($jsonarr));
    }

// for encription 

    function encrypt($sData, $sKey) {
        $sResult = '';
        for ($i = 0; $i < strlen($sData); $i++) {
            $sChar = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar = chr(ord($sChar) + ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $this->encode_base64($sResult);
    }

    function decrypt($sData, $sKey) {
        $sResult = '';
        $sData = $this->decode_base64($sData);
        for ($i = 0; $i < strlen($sData); $i++) {
            $sChar = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar = chr(ord($sChar) - ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $sResult;
    }

    function encode_base64($sData) {
        $sBase64 = base64_encode($sData);
        return strtr($sBase64, '+/', '-_');
    }

    function decode_base64($sData) {
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64);
    }

    public function create_pdf($html_design = NULL, $file_name = NULL, $page_size = 'A4', $display_flag = 'D') {
        try {
            $this->autoRender = FALSE;
            Configure::write('debug', 0);
            App::import('Vendor', 'MPDF/mpdf');
            $mpdf = new mPDF('utf-8', $page_size);
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            if ($waterMark) {
                $mpdf->SetWatermarkText($waterMark);
                $mpdf->watermarkTextAlpha = 0.2;
                $mpdf->showWatermarkText = true;
            }

            $mpdf->WriteHTML($html_design);
            $mpdf->Output($file_name . ".pdf", $display_flag); // 'I' for Display PDF in Next Tab
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! error in creating PDF');
        }
    }

    function replace_string_to_oprator($originalString) {
        try {
            $find_what = array('[DOT]', '[PLUS]', '[MINUS]', '[MULTIPLY]', '[DIVIDE]', '[AND]', '[OR]', '[EQUAL_TO]', '[NOT_EQUAL_TO]', '[LESS_THAN]', '[LESS_THAN_EQUAL]', '[GREATER_THAN]', '[GREATER_THAN_EQUAL]', '[EQUAL]');
            $replace_with = array('.', '+', '-', '*', '/', '&&', '||', '==', '!=', '<', '<=', '>', '>=', '=');
            return str_replace($find_what, $replace_with, $originalString);
        } catch (Exception $ex) {
            
        }
    }

    //check duplicate party witness
    public function check_duplicate_piw($token_no = NULL, $mob_no = NULL, $pan = NULL, $uid = NULL, $email_id = NULL, $action = NULL) {
        try {
            array_map([$this, 'loadModel'], ['identification', 'party_entry', 'witness']);
            $orConditions = array();

            $condition = array('AND' => array(array('token_no' => $token_no)),
                array('OR' => array((($mob_no) ? (array('mobile_no' => trim($mob_no))) : ('')),
                        (($pan) ? ( array('pan_no' => trim($pan))) : ('')),
                        (($uid) ? ( array('uid' => trim($uid))) : ('')),
                        (($email_id) ? ( array('email_id' => trim($email_id))) : (''))
                    )
                )
            );

            $party_count = $this->party_entry->find('count', array('conditions' => $condition));

            isset($orConditions['uid']) ? ($orConditions['uid_no'] = $orConditions['uid']) : ('');
            unset($orConditions['uid']);

            $condition = array('AND' => array(array('token_no' => $token_no)),
                array('OR' => array((($mob_no) ? (array('mobile_no' => trim($mob_no))) : ('')),
                        (($pan) ? ( array('pan_no' => trim($pan))) : ('')),
                        (($uid) ? ( array('uid_no' => trim($uid))) : ('')),
                        (($email_id) ? ( array('email_id' => trim($email_id))) : (''))
                    )
                )
            );



            $identifier_count = $this->identification->find('count', array('conditions' => $condition));
            $withness_count = $this->witness->find('count', array('conditions' => $condition));

            if ($action == 'S') {

                if ($party_count > 0 || $identifier_count > 0 || $withness_count > 0) {
                    return false;
                } else {
                    return true;
                }
            } else if ($action == 'U') {
                if ($party_count > 1 || $identifier_count > 1 || $withness_count > 1) {
                    return false;
                } else {
                    return true;
                }
            }

            //return $party_count;
        } catch (Exception $ex) {
            pr($ex);
            exit;
            return 'error in Query';
        }
    }

    //file validation
    function validfile($file) {
        try {
            $this->loadModel('upload_file_format');

            $allowMime = array('application/pdf', 'application/octet-stream');
            $mime = mime_content_type($file['tmp_name']);
            $fname = pathinfo($file['name'], PATHINFO_FILENAME);
            if (!in_array($mime, $allowMime) || !preg_match("/^[0-9a-zA-Z_\-]*$/", $fname)) {
                return 0;
            }
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $record = $this->upload_file_format->find('first', array('conditions' => array(
                    'upload_file_format.field_type ' => $file_ext, 'type' => 'F')));
            if (empty($record)) {
                return 0;
            }

            if (!empty($record)) {

                $size = ($file['size'] / 1000000);
                if ($size > $record['upload_file_format']['upload_size']) {
                    return 0;
                }

                if ($mime == "application/pdf" || $mime == "application/octet-stream") {
                    App::import('Vendor', 'PDF2Text');
                    $a = new PDF2Text();
                    $a->setFilename($file['tmp_name']);
                    $validpdf = $a->decodePDF();
//                            pr($validpdf);
//                            exit;
                    if (empty($validpdf)) {
                        return 0;
                    }
                }
            }
            return 1;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //file validation
    function image_validation($file) {
        try {
            $this->loadModel('upload_file_format');
            $getimagesize = getimagesize($file['tmp_name']);
            if (empty($getimagesize)) {
                return 0;
            }

            $allowMime = array('image/jpeg', 'image/png', 'image/jpg');
            $mime = mime_content_type($file['tmp_name']);
            $fname = pathinfo($file['name'], PATHINFO_FILENAME);
            if (!in_array($mime, $allowMime) || !preg_match("/^[0-9a-zA-Z_\-]*$/", $fname)) {
                $this->Session->setFlash(__('Invalid MIME Type'));  
                return 0;
            }
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $record = $this->upload_file_format->find('first', array('conditions' => array(
                    'upload_file_format.field_type ' => $file_ext, 'type' => 'I')));
            if (empty($record)) {
                $this->Session->setFlash(__('File size not defined for this extention')); 
                return 0;
            }

            if (!empty($record)) {
                $size = ($file['size'] / 1000000);
                if ($size > $record['upload_file_format']['upload_size']) {
                    $this->Session->setFlash(__('Invalid Image Size'));  
                    return 0;
                }
            }
            return 1;
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function create_and_save_pdf($path = NULL, $html = NULL, $waterMark = "NGDRS Report") {
        Configure::write('debug', 0);
        App::import('Vendor', 'MPDF/mpdf');
        $mpdf = new mPDF('utf-8', 'A4');
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;

        if ($waterMark) {
            $mpdf->SetWatermarkText($waterMark);
            $mpdf->watermarkTextAlpha = 0.2;
            $mpdf->showWatermarkText = true;
        }

        $mpdf->WriteHTML($html);
        $mpdf->Output($path, 'F'); // 'I' for Display PDF in Next Tab
    }

    function validatepdffile($file) {
        try {
            $this->loadModel('upload_file_format');
            if ($file['error'] != 0) {
                $responce['ERROR'] = 'File Not Uploaded';
                return $responce;
            }
            $allowMime = array('application/pdf', 'application/octet-stream');
            $mime = mime_content_type($file['tmp_name']);
            $fname = pathinfo($file['name'], PATHINFO_FILENAME);
            $responce['ERROR'] = '';

            if (!in_array($mime, $allowMime) || !preg_match("/^[0-9a-zA-Z_()\-]*$/", $fname)) {
                $responce['ERROR'] = 'Invalid PDF. or Invalid File Name :' . $fname;
                return $responce;
            }
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $record = $this->upload_file_format->find('first', array('conditions' => array(
                    'upload_file_format.field_type ' => $file_ext)));
            if (empty($record)) {
                $responce['ERROR'] = 'File Extention Not Allowed';
                return $responce;
            }

            if (!empty($record)) {
                if (is_numeric($record['upload_file_format']['upload_size'])) {
                    $size = ($file['size'] / 1048576);
                    if ($size > $record['upload_file_format']['upload_size']) {
                        $responce['ERROR'] = 'Invalid File Size. Max Upload Size is ' . $record['upload_file_format']['upload_size'] . " MB";
                        return $responce;
                    }
                }

                if ($mime == "application/pdf" || $mime == "application/octet-stream") {
                    App::import('Vendor', 'PDF2Text');
                    $a = new PDF2Text();
                    $a->setFilename($file['tmp_name']);
                    $validpdf = $a->decodePDF();
                    // echo $validpdf;exit;
                    if (empty($validpdf)) {
                        $responce['ERROR'] = 'Invalid PDF File1.';
                        return $responce;
                    } else {
                        $responce['SUCCESS'] = 'Valid File';
                        return $responce;
                    }
                } else {
                    $responce['ERROR'] = 'Invalid PDF File2.';
                    return $responce;
                }
            }
            return $responce;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function uploadvalidfile($file, $doc_id) {
        try {
            //return TRUE;exit;
            $this->loadModel('upload_file_format');
            $this->loadModel('upload_document');

            $allowMime = array('application/pdf', 'application/octet-stream');
            $mime = mime_content_type($file['tmp_name']);
            $fname = pathinfo($file['name'], PATHINFO_FILENAME);
            if (!in_array($mime, $allowMime) || !preg_match("/^[0-9a-zA-Z_\-]*$/", $fname)) {
                return 0;
            }
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $record = $this->upload_file_format->find('first', array('conditions' => array(
                    'upload_file_format.field_type ' => $file_ext, 'type' => 'F')));
            if (empty($record)) {
                return 0;
            }

            if (!empty($record)) {
                $filesize = $this->upload_document->field('file_size', array('document_id' => $doc_id));

                $size = ($file['size'] / 1000000);
                if ($size > $filesize) {
                    return 0;
                }

                if ($mime == "application/pdf" || $mime == "application/octet-stream") {
                    App::import('Vendor', 'PDF2Text');
                    $a = new PDF2Text();
                    $a->setFilename($file['tmp_name']);
                    $validpdf = $a->decodePDF();
//                            pr($validpdf);
//                            exit;
                    if (empty($validpdf)) {
                        return 0;
                    }
                }
            }
            return 1;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function smssend($smsid, $mobile_no, $extramsg, $userid, $event_id) {
        try {

            array_map(array($this, 'loadModel'), array('external_interface', 'smstext', 'smslog'));
            $interface = $this->external_interface->find('all', array('conditions' => array(
                    'external_interface.interface_id ' => 7)));
            $smsdata = $this->smstext->find("all", array('conditions' => array('textid' => $smsid)));

            $sms = $smsdata[0]['smstext']['smstext'];
            if (!empty($interface)) {
                $content = "hello";
                $body = $content;
                $subject = "Test";
                //   $attachment = "../Transaction/uploads/uc20160429103829Scan0005.pdf";
                // $to = 'pankajmanchanda99@gmail.com';
                //  $send1= sendmail($to,$subject,$body);
                $username = $interface[0]['external_interface']['interface_user_id']; //"dogrpunjab-plrs"; //username of the department
                $password = $interface[0]['external_interface']['interface_password']; //"plrs@pb2"; //password of the department
                $senderid = $interface[0]['external_interface']['remark']; //"PBGOVT"; //senderid of the deparment
                $message = $sms . $extramsg; //"Test SMS"; //message content
                //$messageUnicode = "यहा?¤? यूननकोड संदेश जो़"; //message content in unicode
                $mobileno = $mobile_no; //"9096648524"; //if single sms need to be send use mobileno keyword
                //$mobileNos = "9503042223"; //if bulk sms  need to send use mobileNos as keyword and mobile number seperated by
                $deptSecureKey = $interface[0]['external_interface']['secure_key']; //"c9f1a61f-f16d-42a5-9118-c7880d0a7696"; //departsecure key for encryption of message...
                $url = $interface[0]['external_interface']['interface_url'];
                $encryp_password = sha1($password);

                $key = hash('sha512', $username . $senderid . $message . $deptSecureKey);
                $data = array(
                    "username" => $username,
                    "password" => $encryp_password,
                    "senderid" => $senderid,
                    "content" => $message,
                    "smsservicetype" => "singlemsg",
                    "mobileno" => $mobileno,
                    "key" => $key
                );
                $send = $this->post_to_url($url, $data); //calling post_to_url to send sms
                $sdata = explode("MsgID = ", $send);
                // pr($sdata);exit;
                if (!empty($sdata)) {
                    if (isset($sdata[1])) {
                        $data1 = array(
                            "user_id" => $userid,
                            "mobno" => $mobileno,
                            "messageid" => $sdata[1],
                            "message_time" => date('Y/m/d H:i:s'),
                            "event_id" => $event_id
                        );
                        $this->smslog->save($data1);
                    }
                    return true;
                }
            }
            return true;
//        exit;
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    function post_to_url($url, $data) {
        try {
            $fields = '';
            foreach ($data as $key => $value) {
                $fields .= $key . '=' . $value . '&';
            }
            rtrim($fields, '&');
            $post = curl_init();

            curl_setopt($post, CURLOPT_URL, $url);
            curl_setopt($post, CURLOPT_POST, count($data));
            curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($post, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($post); //result from mobile seva server
//            pr($result); //output from server displayed

            curl_close($post);
            return $result;
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    function save_documentstatus($status_id, $token_no, $office_id) {
        array_map(array($this, 'loadModel'), array('trndocumentstatus'));
        $date = date('Y/m/d H:i:s');
        $stateid = $this->Auth->User("state_id");
        $usertype = $this->Session->read("session_usertype");
        if ($usertype == 'C') {
            $data = array('token_no' => $token_no,
                'status_id' => $status_id,
                'status_date' => $date,
                'state_id' => $stateid,
                'req_ip' => $_SERVER['REMOTE_ADDR'],
                'user_id' => $this->Auth->user('user_id'),
                'user_type' => $usertype,
                'office_id' => $office_id
            );
        } else {
            $data = array('token_no' => $token_no,
                'status_id' => $status_id,
                'status_date' => $date,
                'state_id' => $stateid,
                'req_ip' => $_SERVER['REMOTE_ADDR'],
                'org_user_id' => $this->Auth->user('user_id'),
                'user_type' => $usertype,
                'office_id' => $office_id,
                'org_created' => date('Y-m-d H:i:s')
            );
        }

        $this->trndocumentstatus->save($data);
        return true;
    }

    function enc($str) {
        $key = "";
        $enc = openssl_encrypt($str, 'bf-ecb', $key, true);
        $final_str = (bin2hex($enc));
        return ($final_str);
    }

//    function dec($str) {
//        $key = "";
//        $final_strv = (hex2bin(trim($str)));
//        $dec = openssl_decrypt($final_strv, 'bf-ecb', $key, true);
//        return $dec;
//    }

    function dec($str = NULL) {
        // pr($str);
        if (!empty($str) || $str != NULL || $str != '') {
            $key = "";
            $final_strv = (hex2bin(trim($str)));
            $dec = openssl_decrypt($final_strv, 'bf-ecb', $key, true);
            return $dec;
        } else {
            $str;
        }
    }

    function create_folder($basepath = NULL, $name = NULL) {
        $mode = 0777;
        if (!is_null($basepath)) {
            if (!file_exists($basepath)) {
                return 0;
            }
            if (!file_exists($basepath . $name)) {
                mkdir($basepath . $name, $mode);
            }
            return $basepath . $name;
        }
    }

    public function number_of_pages($token = NULL) {

        $this->loadModel('party_entry');
        $this->loadModel('witness');
        $this->loadModel('identification');
        $this->loadModel('conf_reg_bool_info');

        $party = count($this->party_entry->find("all", array('conditions' => array('token_no' => $token))));
        $witness = count($this->witness->find("all", array('conditions' => array('token_no' => $token))));
        $identifire = count($this->identification->find("all", array('conditions' => array('token_no' => $token))));
        $total_count = $party + $witness + $identifire;

        $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 110)));
        if (!empty($regconf)) {
            $value = $regconf[0]['conf_reg_bool_info']['info_value'];
        }

        $pages = 0;
        if ($total_count < $value) {
            $pages = 0;
        } else {
            if ($total_count % $value == 0) {
                $pages = ((int) ($total_count / $value));
            } else {
                $pages = ((int) ($total_count / $value));
                $pages++;
            }
        }
        return $pages;
    }

    public function get_prop_same_usage_flag_old($token = NULL) {
        //Sonam code for add valuation amount of same rule_id-------------------------------
        $this->loadModel('property_details_entry');
        $this->loadModel('valuation_details');
        $this->loadModel('conf_reg_bool_info');

        $regconf = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 142)));
        if (!empty($regconf)) {
            if ($regconf['conf_reg_bool_info']['info_value'] == 'Y') {
                $flag = 0;
            } else {
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
                $data['val'] = 0;
                $data['cons'] = 0;
                $rule_new = array();
                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];
                    $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                    foreach ($rule_id as $rule) {
                        $ruleid = $rule['rule_id'];
                        array_push($rule_new, $ruleid);
                    }
                }
                $flag = 0;
                $count = count($rule_new);
                if ($count == 1 || $this->Session->read("article_id") == 32) {
                    $flag = 1;
                } else {
                    foreach ($rule_new as $ruleid_new) {
                        if ($ruleid_new != $rule_new[0]) {
                            $flag = 1;
                        }
                    }
                }
            }
        } else {
            $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
            $data['val'] = 0;
            $data['cons'] = 0;
            $rule_new = array();
            foreach ($tokenno as $tokenid) {
                $valuation_id = $tokenid['property_details_entry']['val_id'];
                $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                foreach ($rule_id as $rule) {
                    $ruleid = $rule['rule_id'];
                    array_push($rule_new, $ruleid);
                }
            }
            $flag = 0;
            $count = count($rule_new);
            if ($count == 1 || $this->Session->read("article_id") == 32) {
                $flag = 1;
            } else {
                foreach ($rule_new as $ruleid_new) {
                    if ($ruleid_new != $rule_new[0]) {
                        $flag = 1;
                    }
                }
            }
        }

        $regconf = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 138)));
        if (!empty($regconf)) {
            if ($regconf['conf_reg_bool_info']['info_value'] == 'Y') {
                if ($this->Session->read("article_id") == 91 || $this->Session->read("article_id") == 160) { //91=Conveyance and 160=Exchange Deed (New article added for Goa)
                    if ($count == 1) {
                        $flag = 1;
                    } else {
                        $flag = 0;
                    }
                }
            }
        }
        return $flag;
    }

    public function get_prop_same_usage_flag($token = NULL) {
        //Sonam code for add valuation amount of same rule_id-------------------------------
        $this->loadModel('property_details_entry');
        $this->loadModel('valuation_details');
        $this->loadModel('conf_reg_bool_info');

        $regconf = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 142)));
        if (!empty($regconf)) {
            if ($regconf['conf_reg_bool_info']['info_value'] == 'Y') {
                $flag = 0;
            } else {
                $allresult = $this->property_details_entry->query("select a.val_id,b.rule_id
                            from ngdrstab_trn_property_details_entry a
                            JOIN ngdrstab_trn_valuation_details b ON b.val_id=a.val_id and b.item_type_id=2
                            where a.token_no=?
                            group by a.val_id,b.rule_id", array($token));

                $flag = 0;
                $val_id_arr = array();
                $rule_id_arr = array();
                $val_flag = 0;
                $rule_flag = 0;
                if (!empty($allresult)) {
                    foreach ($allresult as $result) {
                        $val_id = $result[0]['val_id'];
                        array_push($val_id_arr, $val_id);
                        $rule_id = $result[0]['rule_id'];
                        array_push($rule_id_arr, $rule_id);
                    }

                    $count = count($rule_id_arr);
                    if ($count == 1 || $this->Session->read("article_id") == 32) {
                        $flag = 1;
                    } else {
                        foreach ($val_id_arr as $val_id_arr_res) {
                            if ($val_id_arr_res != $val_id_arr[0]) {
                                $val_flag = 1;
                            }
                        }
                        foreach ($rule_id_arr as $rule_id_arr_res) {
                            if ($rule_id_arr_res != $rule_id_arr[0]) {
                                $rule_flag = 1;
                            }
                        }
                        if ($val_flag == 1 && $rule_flag == 1) {
                            $flag = 1;
                        }
                    }
                }
            }
        } else {
            $allresult = $this->property_details_entry->query("select a.val_id,b.rule_id
                            from ngdrstab_trn_property_details_entry a
                            JOIN ngdrstab_trn_valuation_details b ON b.val_id=a.val_id and b.item_type_id=2
                            where a.token_no=?
                            group by a.val_id,b.rule_id", array($token));

            $flag = 0;
            $val_id_arr = array();
            $rule_id_arr = array();
            $val_flag = 0;
            $rule_flag = 0;
            if (!empty($allresult)) {
                foreach ($allresult as $result) {
                    $val_id = $result[0]['val_id'];
                    array_push($val_id_arr, $val_id);
                    $rule_id = $result[0]['rule_id'];
                    array_push($rule_id_arr, $rule_id);
                }

                $count = count($rule_id_arr);
                if ($count == 1 || $this->Session->read("article_id") == 32) {
                    $flag = 1;
                } else {
                    foreach ($val_id_arr as $val_id_arr_res) {
                        if ($val_id_arr_res != $val_id_arr[0]) {
                            $val_flag = 1;
                        }
                    }
                    foreach ($rule_id_arr as $rule_id_arr_res) {
                        if ($rule_id_arr_res != $rule_id_arr[0]) {
                            $rule_flag = 1;
                        }
                    }
                    if ($val_flag == 1 && $rule_flag == 1) {
                        $flag = 1;
                    }
                }
            }
        }

        $regconf = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 138)));
        if (!empty($regconf)) {
            if ($regconf['conf_reg_bool_info']['info_value'] == 'Y') {
                if ($this->Session->read("article_id") == 91 || $this->Session->read("article_id") == 160) { //91=Conveyance and 160=Exchange Deed (New article added for Goa)
                    if ($count == 1) {
                        $flag = 1;
                    } else {
                        $flag = 0;
                    }
                }
            }
        }
        return $flag;
    }

    public function restrict_edit_after_submit($token = NULL) {
        $this->loadModel('genernalinfoentry');
        $role_id = $this->Auth->user('role_id');
        if (!empty($role_id)) {
            if ($role_id == 1) {
                $status_result = $this->genernalinfoentry->find('all', array('fields' => array('last_status_id'), 'conditions' => array('token_no' => $token)));
                if (!empty($status_result)) {
                    $last_status_id = $status_result[0]['genernalinfoentry']['last_status_id'];
                    if ($last_status_id != 1) {
                        $this->Session->setFlash("You cannot Edit Document After Submission, Please take Appointment...!!!");
                        return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'appointment', $this->Session->read('csrftoken')));
                    }
                }
            }
        }
    }
      public function check_duplicate($duplicate, $data) {
        //pr($data);exit;
        $this->loadModel('ApplicationSubmitted');
        if (isset($duplicate['PrimaryKey']) && isset($data[$duplicate['PrimaryKey']]) && is_numeric($data[$duplicate['PrimaryKey']])) {
            $duplicate['Action'] = 'U';
        } else {
            $duplicate['Action'] = 'S';
        }

        if ($duplicate['Action'] == 'S') {
            $sql = "select * from " . $duplicate['Table'] . " Where ";
            $arrg = array();
            $i = 0;
            foreach ($duplicate['Fields'] as $field) {

                $field_arr = explode(',', $field);
                if ($i) {
                    $sql.=" or ( ";
                } else {
                    $sql.=" ( ";
                }
                $i++;

                $k = 0;
                foreach ($field_arr as $fieldsingle) {
                    if (isset($data[$fieldsingle]) && !empty($data[$fieldsingle])) {
                        if ($k) {
                            $sql.=" and   " . $fieldsingle . "  =?    ";
                        } else {
                            $sql.="   " . $fieldsingle . "  =?   ";
                        }
                        $k++;
                        array_push($arrg, $data[$fieldsingle]);
                    }
                }
                $sql.=" ) ";
            }

//pr($sql); exit;
            $result = $this->ApplicationSubmitted->query($sql, $arrg);

            if (empty($result)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            $arrg = array();
            $i = 0;

            $sql = "select * from " . $duplicate['Table'] . " Where ";


            foreach ($duplicate['Fields'] as $field) {

                $field_arr = explode(',', $field);
                if ($i) {
                    $sql.=" or ( ";
                } else {
                    $sql.=" ( ";
                }
                if (isset($duplicate['PrimaryKey']) && isset($data[$duplicate['PrimaryKey']])) {
                    $sql .= " " . $duplicate['PrimaryKey'] . "!= ? and  ";
                    array_push($arrg, $data[$duplicate['PrimaryKey']]);
                }
                $i++;

                $k = 0;
                foreach ($field_arr as $fieldsingle) {
                    if (isset($data[$fieldsingle]) && !is_null($data[$fieldsingle])) {
                        if ($k) {
                            $sql.=" and   " . $fieldsingle . "  =?    ";
                        } else {
                            $sql.="   " . $fieldsingle . "  =?   ";
                        }
                        $k++;
                        array_push($arrg, $data[$fieldsingle]);
                    }
                }
                $sql.=" ) ";
            }

//           pr($sql);
//           pr($arrg);
//             exit;
            $result = $this->ApplicationSubmitted->query($sql, $arrg);
            //pr($result);exit;


            if (empty($result)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function check_duplicate_new($duplicate, $data) {
        //pr($data);exit;
        $this->loadModel('ApplicationSubmitted');
        if (isset($duplicate['PrimaryKey']) && isset($data[$duplicate['PrimaryKey']]) && is_numeric($data[$duplicate['PrimaryKey']])) {
            $duplicate['Action'] = 'U';
        } else {
            $duplicate['Action'] = 'S';
        }

        if ($duplicate['Action'] == 'S') {
            $sql = "select * from " . $duplicate['Table'] . " Where ";
            $arrg = array();
            $i = 0;
            foreach ($duplicate['Fields'] as $field) {

                $field_arr = explode(',', $field);
                if ($i) {
                    $sql.=" or ( ";
                } else {
                    $sql.=" ( ";
                }
                $i++;

                $k = 0;
                foreach ($field_arr as $fieldsingle) {
                    if (isset($data[$fieldsingle]) && !empty($data[$fieldsingle])) {
                        if ($k) {
                            $sql.=" and  LOWER ( " . $fieldsingle . " ) =?    ";
                        } else {
                            $sql.="  LOWER ( " . $fieldsingle . " ) =?   ";
                        }
                        $k++;
                        array_push($arrg, strtolower($data[$fieldsingle]));
                    }
                }
                $sql.=" ) ";
            }


            $result = $this->ApplicationSubmitted->query($sql, $arrg);

            if (empty($result)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            $arrg = array();
            $i = 0;

            $sql = "select * from " . $duplicate['Table'] . " Where ";


            foreach ($duplicate['Fields'] as $field) {

                $field_arr = explode(',', $field);
                if ($i) {
                    $sql.=" or ( ";
                } else {
                    $sql.=" ( ";
                }
                if (isset($duplicate['PrimaryKey']) && isset($data[$duplicate['PrimaryKey']])) {
                    $sql .= " " . $duplicate['PrimaryKey'] . "!= ? and  ";
                    array_push($arrg, $data[$duplicate['PrimaryKey']]);
                }
                $i++;

                $k = 0;
                foreach ($field_arr as $fieldsingle) {
                    if (isset($data[$fieldsingle]) && !is_null($data[$fieldsingle])) {
                        if ($k) {
                            $sql.=" and  LOWER ( " . $fieldsingle . " ) =?    ";
                        } else {
                            $sql.="  LOWER ( " . $fieldsingle . " ) =?   ";
                        }
                        $k++;
                        array_push($arrg, strtolower($data[$fieldsingle]));
                    }
                }
                $sql.=" ) ";
            }

//           pr($sql);
//           pr($arrg);
//             exit;
            $result = $this->ApplicationSubmitted->query($sql, $arrg);
            //pr($result);exit;


            if (empty($result)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    
 public function set_unicode_regex() {
        $this->loadModel('NGDRSErrorCode');
        $results = $this->NGDRSErrorCode->query(" select  language_name,language_code,unicode_regex_optional_client,unicode_regex_optional_server,unicode_regex_required_client,unicode_regex_required_server from  ngdrstab_conf_language  as conflan
JOIN  ngdrstab_mst_language as mstlang ON mstlang.id=conflan.language_id
where language_code!='en' ");
        foreach ($results as $result) {
            $message = 'Do not enter any character rather than ' . $result[0]['language_name'];
            $message_req = 'Required Field. Enter character in  ' . $result[0]['language_name'];
            if (empty($result[0]['unicode_regex_optional_client']) || empty($result[0]['unicode_regex_optional_server']) || empty($result[0]['unicode_regex_required_client']) || empty($result[0]['unicode_regex_required_server'])) {

                $this->Session->setFlash(__('Unicode regex not Found for language : ' . $result[0]['language_name']));
                return $this->redirect(array('controller' => 'Users', 'action' => 'welcome'));
            }
//pr($results);exit;
            $results = $this->NGDRSErrorCode->query("update ngdrstab_mst_errorcodes set pattern_rule_client=? , pattern_rule=? ,error_messages_en=? where error_code=? ", array($result[0]['unicode_regex_optional_client'], $result[0]['unicode_regex_optional_server'], $message, 'unicode_rule_' . $result[0]['language_code']));
            $results = $this->NGDRSErrorCode->query("update ngdrstab_mst_errorcodes set pattern_rule_client=? , pattern_rule=? ,error_messages_en=? where error_code=? ", array($result[0]['unicode_regex_required_client'], $result[0]['unicode_regex_required_server'], $message_req, 'unicoderequired_rule_' . $result[0]['language_code']));
        }
    }

}
