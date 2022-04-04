<?php

class UsagecatgmullanController extends AppController {

    //put your code here

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('saveusagelinkitem', 'usagecategory', 'saveusagemaincategory', 'saveusagesubcategory', 'saveusagesubsubcategory');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
//        $this->Auth->allow();
    }

    public function usagecategory() {
        try {
            $this->loadModel('Usagemainmain');
            $this->loadModel('subsubcategory');
            $this->loadModel('Usagesub');
            $this->loadModel('usage_category');
            $this->loadModel('State');
            $this->loadModel('usagelinkcategory');
            $this->loadModel('mainlanguage');
            $this->loadModel('language');
            $this->set('usagemainrecord', NULL);
            $this->set('Usagesubrecord', NULL);
            $this->set('selectsubsubcategory', NULL);
            $this->set('actiontype', NULL);
//            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfsubid', NULL);
            $this->set('hfsubsubid', NULL);
            $this->set('hfcode', NULL);
            $this->set('hfsubcode', NULL);
            $this->set('hfsubsubcode', NULL);
            $this->set('hfupdateflag', 'S');
            $this->set('hfselectflag', NULL);
            $this->set('hfitemid', NULL);
//            $this->set('hfdeleteflag', NULL);
            $this->set('laug', NULL);
//             $this->set('r1', 'N');
//             $this->set('r2', 'N');
//             $this->set('r3', 'N');
//             $this->set('r4', 'N');
//             $this->set('r5', 'N');

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
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
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $stateid = $this->Auth->User("state_id");
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            if($state){
            $statedata = $state[0]['State']['state_name_' . $laug];
            $this->set('state_name', $statedata);
            }
            else{
               $this->set('state_name', NULL);  
            }

            $user_id = $this->Auth->User("user_id");

            $this->set('usagemainrecord', $this->Usagemainmain->find('all'));
            $this->set('usgitem', ClassRegistry::init('itemlist')->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_en'), 'conditions' => array('state_id' => $stateid, 'usage_param_type_id' => 1), 'order' => array('usage_param_desc_en' => 'ASC'))));

            $fieldlist1 = array();
            $fieldlist2 = array();
            $fieldlist3 = array();
            $fieldlist4 = array();
            $fieldlist5 = array();
            $fieldlist6 = array();
            $fieldlist7 = array();
            $fieldlist8 = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    //level 1
                    $fieldlist1['usage_main_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets,is_maxlength100';
                    //level 2
                    $fieldlist2['usage_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets,is_maxlength100';
                    //level 3
                    $fieldlist3['usage_sub_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets,is_maxlength100';
                    //level 4
//                    $fieldlist4['' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alpha,is_maxlength100';
                    $fieldlist5['usage_main_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets,is_maxlength100';
                    $fieldlist6['usage_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets,is_maxlength100';
                    $fieldlist7['usage_sub_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacedashdotslashroundbrackets,is_maxlength100';
                } else {
                    //list for all unicode fields
                    //level 1
                    $fieldlist1['usage_main_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                    //level 2
                    $fieldlist2['usage_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                    //level 3
                    $fieldlist3['usage_sub_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                    //level 4
//                    $fieldlist4['' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_'.$languagecode['mainlanguage']['language_code'];
                    $fieldlist5['usage_main_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                    $fieldlist6['usage_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                    $fieldlist7['usage_sub_sub_catg_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist1['dolr_usgaecode1']['text'] = 'is_alphanumeric,is_minmaxlength50';
            $this->set('fieldlist1', $fieldlist1);

            $fieldlist2['dolr_usage_code2']['text'] = 'is_alphanumeric,is_minmaxlength50';
            $this->set('fieldlist2', $fieldlist2);

            $fieldlist3['dolr_usage_code']['text'] = 'is_alphanumeric,is_minmaxlength50';
            $this->set('fieldlist3', $fieldlist3);

            $fieldlist4['usage_param_id']['select'] = 'is_select_req';
            $this->set('fieldlist4', $fieldlist4);
            
            $fieldlist5['dolr_usgaecode1']['text'] = 'is_alphanumeric,is_minmaxlength50';
            $fieldlist5['hfupdateflag']['text'] = 'is_alpha';
            $fieldlist5['hfcode']['text'] = 'is_emptyornumallow';
            $fieldlist5['hfid']['text'] = 'is_emptyornumallow';
            $this->set('fieldlist5', $fieldlist5);

            $fieldlist6['dolr_usage_code2']['text'] = 'is_alphanumeric,is_minmaxlength50';
            $fieldlist6['hfupdateflag']['text'] = 'is_alpha';
            $fieldlist6['hfsubcode']['text'] = 'is_emptyornumallow';
            $fieldlist6['hfsubid']['text'] = 'is_emptyornumallow';
            $this->set('fieldlist6', $fieldlist6);

            $fieldlist7['dolr_usage_code']['text'] = 'is_alphanumeric,is_minmaxlength50';
            $fieldlist7['hfupdateflag']['text'] = 'is_alpha';
            $fieldlist7['hfsubsubcode']['text'] = 'is_emptyornumallow';
            $fieldlist7['hfsubsubid']['text'] = 'is_emptyornumallow';
            $this->set('fieldlist7', $fieldlist7);

            $fieldlist8['usage_param_id']['select'] = 'is_select_req';
            $fieldlist8['hfupdateflag']['select'] = 'is_alpha';
            $fieldlist8['hfitemid']['select'] = 'is_emptyornumallow';
            $this->set('fieldlist8', $fieldlist8);

            $json2array['fieldlist1'] = $fieldlist1;
            $json2array['fieldlist2'] = $fieldlist2;
            $json2array['fieldlist3'] = $fieldlist3;
            $json2array['fieldlist4'] = $fieldlist4;
            $json2array['fieldlist5'] = $fieldlist5;
            $json2array['fieldlist6'] = $fieldlist6;
            $json2array['fieldlist7'] = $fieldlist7;
            $json2array['fieldlist8'] = $fieldlist8;
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
//for fieldlist1 main category
//            pr($fieldlist1);exit;
            foreach ($fieldlist1 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            // for fieldlist2 sub category
            foreach ($fieldlist2 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            // for fieldlist3 sub sub category
            foreach ($fieldlist3 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            // for fieldlist4 property item
            foreach ($fieldlist4 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            foreach ($fieldlist5 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            // for fieldlist2 sub category
            foreach ($fieldlist6 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            // for fieldlist3 sub sub category
            foreach ($fieldlist7 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            // for fieldlist4 property item
            foreach ($fieldlist8 as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);

            if ($this->request->is('post')) {
                
                //pr($this->request->data);exit;
              //  $this->check_csrf_token($this->request->data['usagecategory']['csrftoken']);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);
                     $flag = 0;
                    
                    if ($_POST['actiontype'] == '1' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {
                        
                    foreach ($json2array['fieldlist5'] as $key => $value) {
                        $data[$key] = @$this->request->data['usagecategory'][$key];
                    }
                    $data['hfupdateflag'] = $_POST['hfupdateflag'];
                    $data['hfcode'] = $_POST['hfcode'];
                    $data['hfid'] = $_POST['hfid'];
                    $data = $this->istrim($data);
                    $errarr = $this->validatedata($data, $json2array['fieldlist5']);
                   
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                   
                    } else if ($_POST['actiontype'] == '2' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {
                        
                        $json2array['fieldlist6']=  array_merge( $json2array['fieldlist5'],$json2array['fieldlist6']);
                        
                    foreach ($json2array['fieldlist6'] as $key => $value) {
                        $data[$key] = @$this->request->data['usagecategory'][$key];
                    }
                    $data['hfupdateflag'] = $_POST['hfupdateflag'];
                    $data['hfsubcode'] = $_POST['hfsubcode'];
                    $data['hfsubid'] = $_POST['hfsubid'];
                    $data = $this->istrim($data);
                   
                    $errarr = $this->validatedata($data, $json2array['fieldlist6']);
                   
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    }else if ($_POST['actiontype'] == '3' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {
                          $json2array['fieldlist6']=  array_merge( $json2array['fieldlist5'],$json2array['fieldlist6']);
                          $json2array['fieldlist7']=  array_merge($json2array['fieldlist6'],$json2array['fieldlist7']);
                    foreach ($json2array['fieldlist7'] as $key => $value) {
                        $data[$key] = @$this->request->data['usagecategory'][$key];
                    }
                    $data['hfupdateflag'] = $_POST['hfupdateflag'];
                    $data['hfsubsubcode'] = $_POST['hfsubsubcode'];
                    $data['hfsubsubid'] = $_POST['hfsubsubid'];
                    $data = $this->istrim($data);
                    $errarr = $this->validatedata($data, $json2array['fieldlist7']);
                   
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    }else if ($_POST['actiontype'] == '4' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {
                        $json2array['fieldlist6']=  array_merge( $json2array['fieldlist5'],$json2array['fieldlist6']);
                          $json2array['fieldlist7']=  array_merge($json2array['fieldlist6'],$json2array['fieldlist7']);
                           $json2array['fieldlist8']=  array_merge($json2array['fieldlist7'],$json2array['fieldlist8']);
                    foreach ($json2array['fieldlist8'] as $key => $value) {
                        $data[$key] = @$this->request->data['usagecategory'][$key];
                    }
                    
                        $data['usage_param_id'] = $this->request->data['usagecategory']['usage_param_id'];
                     $data['hfupdateflag'] = $_POST['hfupdateflag'];
                    $data['hfitemid'] = $_POST['hfitemid'];
                    $data = $this->istrim($data);
                    $errarr = $this->validatedata($data, $json2array['fieldlist8']);
                   
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    }
                    if ($flag == 1) {
                        $errarr1['errorcode'] = $errarr;
                        $this->set("errarr", $errarr);
                    } else {
                $this->request->data['usagecategory'] = $this->request->data['usagecategory'];
                $this->set('actiontype', $_POST['actiontype']);
//                $this->set('hfactionval', $_POST['hfaction']);
                
                $arry=array();
                $this->set($arry,'');
                $this->set('lngcd','');
                
                $arry_two=array();
                $this->set($arry_two,'');
                $this->set('lngcd_two','');
                
                foreach ($languagelist as $langcode)
                {
                    if($langcode['mainlanguage']['language_code']!='en'){
                        $langcd=$langcode['mainlanguage']['language_code'];
                       // $nn2='data[usagecategory][subcatg_'.$langcd.'_activation_flag]';
                      // $valnn2='val'.$langcd;
                       //pr($this->request->data['usagecategory']['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']);
                        if(isset($this->request->data['usagecategory']['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']))
                        {
                            $setvar=$this->request->data['usagecategory']['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag'];
                            $lngcd='subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag';
                            //pr($lngcd);
                            //pr($setvar);
                            $arr[$lngcd]=$setvar;
                           // pr($arr);
                            if($lngcd)
                                 $this->set('lngcd',$arr);
                            else
                                $this->set('lngcd','');
                        }
                        else{
                            $this->set('lngcd','');
                        }
                        //$this->set($valnn2,$this->request->data['usagecategory']['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']);
                       // $data['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']=$this->request->data['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag'];
                       // pr($nn2);
                       // pr($this->request->data['usagecategory']['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']);
                        
                        
                        if(isset($this->request->data['usagecategory']['subsubcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']))
                        {
                            $setvar_two=$this->request->data['usagecategory']['subsubcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag'];
                            $lngcd_two='subsubcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag';
                            //pr($lngcd);
                            //pr($setvar);
                            $arr_two[$lngcd_two]=$setvar_two;
                           // pr($arr);
                            if($lngcd_two)
                                 $this->set('lngcd_two',$arr_two);
                            else
                                $this->set('lngcd_two','');
                        }
                        else{
                            $this->set('lngcd_two','');
                        }
                        
                        
                        
                        
                        
                    }

                }
                        //exit;
                $this->set('hfcode', $_POST['hfcode']);
                $this->set('hfsubcode', $_POST['hfsubcode']);
                $this->set('hfsubsubcode', $_POST['hfsubsubcode']);
                $this->set('hfupdateflag', $_POST['hfupdateflag']);
                $this->set('hfid', $_POST['hfid']);
                $this->set('hfsubid', $_POST['hfsubid']);
                $this->set('hfsubsubid', $_POST['hfsubsubid']);
                $this->set('hfitemid', $_POST['hfitemid']);
                $hfitemid = $_POST['hfitemid'];
//                $this->set('hfdeleteflag', $_POST['hfdeleteflag']);
                $consflag = (isset($_POST['consflag'])) ? $_POST['consflag'] : 'N';
                $depflag = (isset($_POST['depflag'])) ? $_POST['depflag'] : 'N';
                $roadflag = (isset($_POST['roadflag'])) ? $_POST['roadflag'] : 'N';
                $ud1flag = (isset($_POST['ud1flag'])) ? $_POST['ud1flag'] : 'N';
                $ud2flag = (isset($_POST['ud2flag'])) ? $_POST['ud2flag'] : 'N';
                $this->set('r1', $consflag);
                $this->set('r2', $depflag);
                $this->set('r3', $roadflag);
                $this->set('r4', $ud1flag);
                $this->set('r5', $ud2flag);
                if ($_POST['actiontype'] == '1' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {
                    $gridsub = $this->usage_category->get_gridsub($_POST['hfcode']);
                    $this->set('Usagesubrecord', $gridsub);
                }

                //add subusege
                if ($_POST['actiontype'] == '2' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {
                    $gridsub = $this->usage_category->get_gridsub($_POST['hfcode']);
                    $this->set('Usagesubrecord', $gridsub);
                    
                    $gridsubsub = $this->usage_category->get_gridsubsub($_POST['hfcode'], $_POST['hfsubcode']);
                    $this->set('subsubcategoryrecord', $gridsubsub);
                }
                if ($_POST['actiontype'] == '3' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {

                   $gridsub = $this->usage_category->get_gridsub($_POST['hfcode']);
                    $this->set('Usagesubrecord', $gridsub);
                    
                    $gridsubsub = $this->usage_category->get_gridsubsub($_POST['hfcode'], $_POST['hfsubcode']);
                    $this->set('subsubcategoryrecord', $gridsubsub);
                    
                    $griditem = $this->usage_category->get_griditem($_POST['hfcode'], $_POST['hfsubcode'], $_POST['hfsubsubcode']);
                    $this->set('griditem', $griditem);
                }
                if ($_POST['actiontype'] == '4' && isset($_POST['actiontype']) && is_numeric($_POST['actiontype'])) {

                   $gridsub = $this->usage_category->get_gridsub($_POST['hfcode']);
                    $this->set('Usagesubrecord', $gridsub);
                    
                    $gridsubsub = $this->usage_category->get_gridsubsub($_POST['hfcode'], $_POST['hfsubcode']);
                    $this->set('subsubcategoryrecord', $gridsubsub);
                    
                    $griditem = $this->usage_category->get_griditem($_POST['hfcode'], $_POST['hfsubcode'], $_POST['hfsubsubcode']);
                    $this->set('griditem', $griditem);
                }
                    }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
         $this->set_csrf_token();
    }

    public function saveusagemaincategory() {
        try {
            $this->autoRender = false;
            $this->loadModel('usage_main_category');
            if (isset($_POST['usage_main_catg_desc_en'])) {
                $catmain = strtoupper($_POST['usage_main_catg_desc_en']);
                $checkname = $this->usage_main_category->Find('all', array('conditions' => array('upper(usage_main_catg_desc_en) like ' => $catmain)));
                $stateid = $this->Auth->User("state_id");
                $language = $this->language->find('all', array('conditions' => array('state_id' => $stateid), 'order' => array('id' => 'ASC')));
                if ($checkname != NULL && $_POST['actionval'] == 'S') {
                    echo json_encode('Record Already Exist');
                    exit;
                } else {
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);
                    foreach ($json2array['fieldlist1'] as $key => $value) {
                        //pr($key);exit;
                        $data[$key] = $_POST[$key];
                    }
                    $data['dolr_usgaecode'] = $_POST['dolr_usgaecode1'];
                    $data['state_id'] = $this->Auth->user('state_id');
                    $data['user_id'] = $this->Auth->User('user_id');
                    //$data['created_date'] = date('Y/m/d H:i:s');
                    $data['req_ip'] = $_SERVER['REMOTE_ADDR'];

                    $data = $this->istrim($data);
                    $errarr = $this->validatedata($data, $json2array['fieldlist1']);
                    $flag = 0;
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    if ($flag == 1) {
                        $errarr1['errorcode'] = $errarr;
                        echo json_encode($errarr1);
                        exit;
                    } else {

                        if ($_POST['actionval'] == 'S') {

                            if ($this->usage_main_category->save($data)) {
                                $lastinsertid = $this->usage_main_category->getLastInsertId();
                                $record = $this->usage_main_category->Find('all', array('conditions' => array('id' => $lastinsertid)));

                                $savequery = $this->usage_main_category->query("insert into ngdrstab_mst_usage_category (usage_main_catg_id,state_id) values(?,?)", array($record[0]['usage_main_category']['usage_main_catg_id'], $this->Auth->user('state_id')));

                                $resultarray = array('usage_main_catg_id' => $record[0]['usage_main_category']['usage_main_catg_id'],
                                    'id' => $lastinsertid);
                                echo json_encode($resultarray);
                                exit;
                            } else {
                                echo json_encode('Record Not Saved');
                                exit;
                            }
                        } else {
                            $this->usage_main_category->id = $_POST['id'];
//pr($data);exit;
                            if ($this->usage_main_category->save($data)) {
                                $record = $this->usage_main_category->Find('all', array('conditions' => array('id' => $_POST['id'])));
                                $resultarray = array('usage_main_catg_id' => $record[0]['usage_main_category']['usage_main_catg_id'],
                                    'id' => $_POST['id']);
                                echo json_encode($resultarray);
                                exit;
                            } else {
                                echo json_encode('Record Not Updated');
                                exit;
                            }
                        }
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

    public function saveusagesubcategory() {
        try {
            //pr($this->request->data);exit;
            $this->autoRender = false;
            $this->loadModel('usage_sub_category');
            $this->loadModel('usage_category');
            $this->loadModel('mainlanguage');
            
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            //pr($languagelist);exit;
            
                      
            if (isset($_POST['usage_sub_catg_desc_en'])) {
                $catsub = strtoupper($_POST['usage_sub_catg_desc_en']);

                $checkname = $this->usage_sub_category->Find('all', array('conditions' => array('upper(usage_sub_catg_desc_en) like ' => $catsub)));
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                foreach ($json2array['fieldlist2'] as $key => $value) {
                    //pr($key);exit;
                    $data[$key] = $_POST[$key];
                }
//                
                $data = $this->istrim($data);
                $errarr = $this->validatedata($data, $json2array['fieldlist2']);
//                  pr($errarr);exit;
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $errarr1['errorcode'] = $errarr;
                    echo json_encode($errarr1);
                    exit;
                } else {
                    if ($checkname != NULL && $_POST['actionval'] == 'S') {
                        $subid = $checkname[0]['usage_sub_category']['usage_sub_catg_id'];
                        $id = $checkname[0]['usage_sub_category']['id'];
                        $checkrec = $this->usage_category->query("select * from ngdrstab_mst_usage_category where usage_main_catg_id=? and usage_sub_catg_id=?", array($_POST['main_id'], $subid));
                        if ($checkrec != NULL) {
                            echo json_encode('Record Already Exist');
                            exit;
                        } else {
                            $check = $this->usage_category->Find('all', array('conditions' => array('usage_main_catg_id' => $_POST['main_id'], 'usage_sub_catg_id' => 0, 'usage_sub_sub_catg_id' => 0)));
                            if ($check == NULL) {
                                $savequery = $this->usage_category->query("insert into ngdrstab_mst_usage_category (usage_main_catg_id,usage_sub_catg_id,state_id) values(?,?,?)", array($_POST['main_id'], $subid, $this->Auth->user('state_id')));
                            } else {
                                $savequery = $this->usage_category->query("UPDATE ngdrstab_mst_usage_category SET usage_sub_catg_id = ? WHERE usage_main_catg_id=? and usage_sub_catg_id = ? and usage_sub_sub_catg_id = ?", array($subid, $_POST['main_id'], 0, 0));
                            }
                            $resultarray = array('usage_sub_catg_id' => $subid,
                                'id' => $id);
                            echo json_encode($resultarray);
                            exit;
                        }
                    } else {
                        $data['dolr_usage_code'] = $_POST['dolr_usage_code2'];
                        $data['state_id'] = $this->Auth->user('state_id');
                        $data['user_id'] = $this->Auth->User('user_id');
                        
                        foreach ($languagelist as $langcode)
                        {
                            if($langcode['mainlanguage']['language_code']!='en'){
                                $data['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']=$this->request->data['subcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag'];
                            }

                        }
                       
            
                        // $data['created_date'] = date('Y/m/d H:i:s');
                        $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
                        if ($_POST['actionval'] == 'S') {
                            if ($this->usage_sub_category->save($data)) {
                                $lastinsertid = $this->usage_sub_category->getLastInsertId();
                                $record = $this->usage_sub_category->Find('all', array('conditions' => array('id' => $lastinsertid)));
//                        pr($record);
                                $check = $this->usage_category->Find('all', array('conditions' => array('usage_main_catg_id' => $_POST['main_id'], 'usage_sub_catg_id' => 0, 'usage_sub_sub_catg_id' => 0)));
                                if ($check == NULL) {
                                    $savequery = $this->usage_category->query("insert into ngdrstab_mst_usage_category (usage_main_catg_id,usage_sub_catg_id,state_id) values(?,?,?)", array($_POST['main_id'], $record[0]['usage_sub_category']['usage_sub_catg_id'], $this->Auth->user('state_id')));
                                } else {
                                    $savequery = $this->usage_category->query("UPDATE ngdrstab_mst_usage_category SET usage_sub_catg_id = ? WHERE usage_main_catg_id=? and usage_sub_catg_id = ? and usage_sub_sub_catg_id = ?", array($record[0]['usage_sub_category']['usage_sub_catg_id'], $_POST['main_id'], 0, 0));
                                }
                                $resultarray = array('usage_sub_catg_id' => $record[0]['usage_sub_category']['usage_sub_catg_id'],
                                    'id' => $lastinsertid);
                                echo json_encode($resultarray);

                                exit;
                            } else {
                                echo json_encode('Record Not Saved');
                                exit;
                            }
                        } else {
                            $this->usage_sub_category->id = $_POST['id'];
                            if ($this->usage_sub_category->save($data)) {
                                $record = $this->usage_sub_category->Find('all', array('conditions' => array('id' => $_POST['id'])));
                                $resultarray = array('usage_sub_catg_id' => $record[0]['usage_sub_category']['usage_sub_catg_id'],
                                    'id' => $_POST['id']);
                                echo json_encode($resultarray);

                                exit;
                            } else {
                                echo json_encode('Record Not Updated');
                                exit;
                            }
                        }
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

    public function saveusagesubsubcategory() {
        try {
            $this->autoRender = false;
            $this->loadModel('Usagesubsub');
            $this->loadModel('usage_category');
            $this->loadModel('mainlanguage');
            
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            
            if (isset($_POST['usage_sub_sub_catg_desc_en'])) {
                $catsubsub = strtoupper($_POST['usage_sub_sub_catg_desc_en']);
                $checkname = $this->Usagesubsub->Find('all', array('conditions' => array('upper(usage_sub_sub_catg_desc_en) like ' => $catsubsub)));
                if ($checkname != NULL && $_POST['actionval'] == 'S') {
                    echo json_encode('Record Already Exist');
                    exit;
                } else {
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);
                    foreach ($json2array['fieldlist3'] as $key => $value) {
                        //pr($key);exit;
                        $data[$key] = $_POST[$key];
                    }
                    $data['dolr_usage_code'] = $_POST['dolr_usage_code'];
                    $data['contsruction_type_flag'] = $_POST['constuctionflag'];
                    $data['depreciation_flag'] = $_POST['depreciationflag'];
                    $data['road_vicinity_flag'] = $_POST['roadvicinityflag'];
                    $data['user_defined_dependency1_flag'] = $_POST['userdepflag1'];
                    $data['user_defined_dependency2_flag'] = $_POST['userdepflg2'];
                    $data['state_id'] = $this->Auth->user('state_id');
                    $data['user_id'] = $this->Auth->User('user_id');
                    //$data['created_date'] = date('Y/m/d H:i:s');
                    $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    
                    foreach ($languagelist as $langcode)
                    {
                        if($langcode['mainlanguage']['language_code']!='en'){
                            $data['subsubcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag']=$this->request->data['subsubcatg_'.$langcode['mainlanguage']['language_code'].'_activation_flag'];
                        }

                    }
                        
                    $data = $this->istrim($data);
                    $errarr = $this->validatedata($data, $json2array['fieldlist3']);
                    //  pr($errarr);exit;
                    $flag = 0;
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    if ($flag == 1) {
                        $errarr1['errorcode'] = $errarr;
                        echo json_encode($errarr1);
                        exit;
                    } else {
                        if ($_POST['actionval'] == 'S') {
                            if ($this->Usagesubsub->save($data)) {
                                $lastinsertid = $this->Usagesubsub->getLastInsertId();
                                $record = $this->Usagesubsub->Find('all', array('conditions' => array('id' => $lastinsertid)));

                                $check = $this->usage_category->Find('all', array('conditions' => array('usage_main_catg_id' => $_POST['main_id'], 'usage_sub_catg_id' => $_POST['sub_id'], 'usage_sub_sub_catg_id' => 0)));
                                if ($check == NULL) {
                                    $savequery = $this->usage_category->query("insert into ngdrstab_mst_usage_category (usage_main_catg_id,usage_sub_catg_id,usage_sub_sub_catg_id,contsruction_type_flag,depreciation_flag,road_vicinity_flag,user_defined_dependency1_flag,user_defined_dependency2_flag,state_id) values(? ,? ,?,?,?,? ,?,?,?)", array($_POST['main_id'], $_POST['sub_id'], $record[0]['Usagesubsub']['usage_sub_sub_catg_id'], $_POST['constuctionflag'], $_POST['depreciationflag'], $_POST['roadvicinityflag'], $_POST['userdepflag1'], $_POST['userdepflg2'], $this->Auth->user('state_id')));
                                } else {
                                    $savequery = $this->usage_category->query("UPDATE ngdrstab_mst_usage_category SET usage_sub_sub_catg_id = ? , contsruction_type_flag=?,depreciation_flag=?,road_vicinity_flag = ?,user_defined_dependency1_flag=?,user_defined_dependency2_flag=? WHERE usage_main_catg_id=? and usage_sub_catg_id=? and usage_sub_sub_catg_id = ?", array($record[0]['Usagesubsub']['usage_sub_sub_catg_id'], $_POST['constuctionflag'], $_POST['depreciationflag'], $_POST['roadvicinityflag'], $_POST['userdepflag1'], $_POST['userdepflg2'], $_POST['main_id'], $_POST['sub_id'], 0));
                                }
                                $resultarray = array('usage_sub_sub_catg_id' => $record[0]['Usagesubsub']['usage_sub_sub_catg_id'],
                                    'id' => $lastinsertid);
                                echo json_encode($resultarray);
                                exit;
                            } else {
                                echo json_encode('Record Not Saved');
                                exit;
                            }
                        } else {
                            $this->Usagesubsub->id = $_POST['id'];
                            if ($this->Usagesubsub->save($data)) {
                                $record = $this->Usagesubsub->Find('all', array('conditions' => array('id' => $_POST['id'])));
                                $savequery = $this->usage_category->query("UPDATE ngdrstab_mst_usage_category SET  contsruction_type_flag=?,depreciation_flag=?,road_vicinity_flag = ?,user_defined_dependency1_flag=?,user_defined_dependency2_flag=? WHERE usage_sub_sub_catg_id = ? ", array($_POST['constuctionflag'], $_POST['depreciationflag'], $_POST['roadvicinityflag'], $_POST['userdepflag1'], $_POST['userdepflg2'], $record[0]['Usagesubsub']['usage_sub_sub_catg_id']));
                                $resultarray = array('usage_sub_sub_catg_id' => $record[0]['Usagesubsub']['usage_sub_sub_catg_id'],
                                    'id' => $_POST['id']);
                                echo json_encode($resultarray);
                                exit;
                            } else {
                                echo json_encode('Record Not Updated');
                                exit;
                            }
                        }
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

    public function saveusagelinkitem() {
        try {
            $this->autoRender = false;
            $this->loadModel('usagelinkcategory');
            if (isset($_POST['usage_param_id'])) {
                $count = $this->usagelinkcategory->find('count', array('conditions' => array('usage_main_catg_id' => $_POST['main_id'], 'usage_sub_catg_id' => $_POST['sub_id'], 'usage_sub_sub_catg_id' => $_POST['subsub_id'], 'usage_param_id' => $_POST['usage_param_id'])));
                $ruleid = $this->usagelinkcategory->find('first', array('fields' => array('evalrule_id'), 'conditions' => array('usage_main_catg_id' => $_POST['main_id'], 'usage_sub_catg_id' => $_POST['sub_id'], 'usage_sub_sub_catg_id' => $_POST['subsub_id'], 'not' => array('evalrule_id' => null))));
                if ($ruleid) {
                    $data['evalrule_id'] = $ruleid['usagelinkcategory']['evalrule_id'];
                }
                if ($count > 0 && $_POST['actionval'] == 'S') {
                    echo json_encode('Record Already Exist');
                    exit;
                } else {

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);
                    foreach ($json2array['fieldlist4'] as $key => $value) {
                        //pr($key);exit;
                        $data[$key] = $_POST[$key];
                    }


                    $data['usage_main_catg_id'] = $_POST['main_id'];
                    $data['usage_sub_catg_id'] = $_POST['sub_id'];
                    $data['usage_sub_sub_catg_id'] = $_POST['subsub_id'];
                    $data['state_id'] = $this->Auth->user('state_id');
                    $data['user_id'] = $this->Auth->User('user_id');
                    ///  $data['created_date'] = date('Y/m/d H:i:s');
                    $data['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $itml = ClassRegistry::init('itemlist')->find('all', array('fields' => array('usage_param_code', 'range_field_flag'), 'conditions' => array('usage_param_id' => $_POST['usage_param_id'])));
                    $data['range_field_flag'] = $itml[0]['itemlist']['range_field_flag'];
                    $data['uasge_param_code'] = $itml[0]['itemlist']['usage_param_code'];
                    $data = $this->istrim($data);
                    $errarr = $this->validatedata($data, $json2array['fieldlist4']);
                    //  pr($errarr);exit;
                    $flag = 0;
                    foreach ($errarr as $dd) {
                        if ($dd != "") {
                            $flag = 1;
                        }
                    }
                    if ($flag == 1) {
                        $errarr1['errorcode'] = $errarr;
                        echo json_encode($errarr1);
                        exit;
                    } else {
                        if ($_POST['actionval'] == 'S') {
                            if ($this->usagelinkcategory->save($data)) {
                                $lastinsertid = $this->usagelinkcategory->getLastInsertId();
                                $record = $this->usagelinkcategory->Find('all', array('conditions' => array('id' => $lastinsertid)));
                                $resultarray = array('usage_param_id' => $record[0]['usagelinkcategory']['usage_param_id'],
                                    'id' => $lastinsertid);
                                echo json_encode($resultarray);
                                exit;
                            } else {
                                echo json_encode('Record Not Saved');
                                exit;
                            }
                        } else {
                            $this->usagelinkcategory->id = $_POST['id'];
                            if ($this->usagelinkcategory->save($data)) {
                                $record = $this->usagelinkcategory->Find('all', array('conditions' => array('id' => $_POST['id'])));
                                $resultarray = array('usage_param_id' => $record[0]['usagelinkcategory']['usage_param_id'],
                                    'id' => $_POST['id']);
                                echo json_encode($resultarray);
                                exit;
                            } else {
                                echo json_encode('Record Not Updated');
                                exit;
                            }
                        }
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

//===============================Usage Category End ============================
}
