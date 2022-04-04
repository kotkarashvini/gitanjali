<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class PropertyController extends AppController {

    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        //$this->Security->unlockedActions = array('propertyscreen', 'frmpresentation', 'frmparty', 'frmproprty', 'propertyscreennew', 'rulechangeevent', 'getsurveynumbers', 'usage_filter', 'getallrates', 'round_tonext500','get_corp_list','district_change_event','taluka_change_event','corp_change_event','village_change_event','Level1_change_event','usagecategory_change_event','fetchsubrule','get_zone','get_location');
        $this->Auth->allow('district_change_event_new', 'corp_change_event_new', 'get_corp_list_new', 'taluka_change_event_new', 'welcomenote', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'rulechangeevent', 'usage_filter', 'get_location', 'get_zone', 'getallrates', 'round_tonext500');

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function propertyscreen() {

        try {
            $this->set('actiontype', 0);
            $this->set('hfarticle_id', 0);
            $this->set('articleparameters', NULL);

            $this->loadModel('article');
            $this->loadModel('articleformula');
            $this->loadModel('articleparameters');
            $this->loadModel('propertyarticledetails');
            if (!$this->request->is('post')) {
//write json
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $article['article'] = $this->article->find('list', array('fields' => array('article.id', 'article.article_desc_en')));
                $article['articleformula'] = $this->articleformula->find('all');
                $article['articleparameters'] = $this->articleparameters->find('all');

                $file->write(json_encode($article));
            }

//read json
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
//pr($json2array['article']);exit;
            $this->set('article_id', $json2array['article']);

            if ($this->request->is('post')) {
                $actiontype = $_POST['actiontype'];
                $hfarticle_id = $_POST['hfarticle_id'];

                if ($actiontype == '1') {
                    $this->set('actiontype', $actiontype);
                    $this->set('hfarticle_id', $hfarticle_id);
                    $articleformula = array();
                    foreach ($json2array['articleformula'] as $formuladata) {
                        if ($formuladata['articleformula']['article_id'] == $hfarticle_id) {
                            $articleformula = $formuladata;
                        }
                    }
                    $data = explode(',', $articleformula['articleformula']['parameters']);

                    $articleparameters = array();
                    foreach ($json2array['articleparameters'] as $parameterdata) {
                        if (in_array($parameterdata['articleparameters']['parameter_id'], $data)) {
                            $articleparameters[$parameterdata['articleparameters']['parameter_id']] = $parameterdata;
                        }
                    }
                    $this->set('articleparameters', $articleparameters);
                }
                if ($actiontype == '2') {
                    $this->set('actiontype', $actiontype);
                    $this->set('hfarticle_id', $hfarticle_id);

                    $articleformula = array();
                    foreach ($json2array['articleformula'] as $formuladata) {
                        if ($formuladata['articleformula']['article_id'] == $hfarticle_id) {
                            $articleformula = $formuladata;
                        }
                    }
                    $data = explode(',', $articleformula['articleformula']['parameters']);

                    $formula = $articleformula['articleformula']['formula'];
                    foreach ($data as $data1) {
                        $formula = str_replace($data1, $this->request->data['propertyscreen'][$data1], $formula);
                    }
                    $result = eval("return ($formula);");
                    $insertdata = array();
                    foreach ($data as $data1) {
                        $arr = array('property_id' => 1,
                            'article_id' => $hfarticle_id,
                            'parameter_id' => $data1,
                            'parameter_value' => $this->request->data['propertyscreen'][$data1],
                            'arrived_value' => $result
                        );
                        array_push($insertdata, $arr);
                    }
                    $this->propertyarticledetails->saveAll($insertdata);

                    $articleparameters = array();
                    foreach ($json2array['articleparameters'] as $parameterdata) {
                        if (in_array($parameterdata['articleparameters']['parameter_id'], $data)) {
                            $articleparameters[$parameterdata['articleparameters']['parameter_id']] = $parameterdata;
                        }
                    }
                    $this->set('articleparameters', $articleparameters);
                }
            }
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function dropdowndependency() {
        try {
            if (isset($_GET['ddlid']) && isset($_GET['ddlval'])) {
                $ddlid = $_GET['ddlid'];
                $ddlval = $_GET['ddlval'];
                if ($ddlid === 'fldarticle') {
                    $result = ClassRegistry::init('ngdrstab_mst_document_title')->find('list', array('fields' => array('id', 'title_name'), 'conditions' => array('article_id' => array($ddlval))));
                    echo json_encode($result);
                    exit;
                }
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function propertymaster() {
        try {
            $this->loadModel('majorfunction');
            $this->loadModel('minorfunction');
            $this->loadModel('formbehaviour');
            $this->loadModel('fieldformlinkage');
            $this->loadModel('presentation');



            if (!$this->request->is('post')) {
//write json
                /*
                  $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);

                  $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
                  $options1['order'] = array('minorfunction.mf_serial');
                  $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')));
                  $options1['fields'] = array('minorfunction.mf_serial', 'minorfunction.function_desc', 'mf_forms.form_name');
                  $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);
                  $file->write(json_encode($minorfunction));
                 */
//
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $options1['conditions'] = array('majorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('majorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('majorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('majorfunction.mf_serial', 'majorfunction.function_desc', 'majorfunction.major_id', 'formlabels.labelname');
                $minorfunction['majorfunction'] = $this->majorfunction->find('all', $options1);



//pr($majorfunction['majorfunction']);exit;
////                $options2['conditions'] = array('fieldformlinkage.state_id' => $this->Auth->user('state_id'));
////                $options2['order'] = array('fieldformlinkage.field_id ASC');
////                $options2['joins'] = array(array('table' => 'ngdrstab_mst_fieldlist', 'alias' => 'fieldlist', 'type' => 'INNER', 'conditions' => array('fieldformlinkage.field_id=fieldlist.id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('fieldlist.label_id=formlabels.id')));
////                //$options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname', 'formlabels.label_desc_en', 'formlabels.label_desc_' . $this->Session->read("local_langauge"));
////                $options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname');
////                $majorfunction['formfields'] = $this->fieldformlinkage->find('all', $options2);
//
////                $options3['conditions'] = array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => $this->Auth->user('state_id'));
////                $options3['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('formbehaviour.label_id=formlabels.id')));
////                $options3['fields'] = array('formbehaviour.id', 'formbehaviour.behaviour_desc', 'formlabels.labelname');
////                $majorfunction['formbehaviour'] = $this->formbehaviour->find('all', $options3);
//
//                //$majorfunction['formbehaviour'] = $this->formbehaviour->find('all', array('fields' => array('formbehaviour.id', 'formbehaviour.behaviour_desc'), 'conditions' => array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => 'MH')));
//                $file->write(json_encode($majorfunction));
//                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('minorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_majorfunctions', 'alias' => 'major', 'type' => 'INNER', 'conditions' => array('minorfunction.major_id = major.major_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('minorfunction.mf_serial', 'major.major_id', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);

                $options2['conditions'] = array('fieldformlinkage.state_id' => $this->Auth->user('state_id'));
                $options2['order'] = array('fieldformlinkage.field_id ASC');
                $options2['joins'] = array(array('table' => 'ngdrstab_mst_fieldlist', 'alias' => 'fieldlist', 'type' => 'INNER', 'conditions' => array('fieldformlinkage.field_id=fieldlist.id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('fieldlist.label_id=formlabels.id')));
//$options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname', 'formlabels.label_desc_en', 'formlabels.label_desc_' . $this->Session->read("local_langauge"));
                $options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname');
                $minorfunction['formfields'] = $this->fieldformlinkage->find('all', $options2);

                $options3['conditions'] = array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => $this->Auth->user('state_id'));
                $options3['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('formbehaviour.label_id=formlabels.id')));
                $options3['fields'] = array('formbehaviour.id', 'formbehaviour.behaviour_desc', 'formlabels.labelname');
                $minorfunction['formbehaviour'] = $this->formbehaviour->find('all', $options3);


//              $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
//                $options1['order'] = array('minorfunction.mf_serial');
////                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
//                $options1['fields'] = array('minorfunction.mf_serial', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
//                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);
//$minorfunction['formbehaviour'] = $this->formbehaviour->find('all', array('fields' => array('formbehaviour.id', 'formbehaviour.behaviour_desc'), 'conditions' => array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => 'MH')));
                $file->write(json_encode($minorfunction));
            }

//read json
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            $this->set('minorfunction', $json2array['minorfunction']);
            $this->set('majorfunction', $json2array['majorfunction']);
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function frmpresentation() {
        try {

            $this->loadModel('minorfunction');
            $this->loadModel('majorfunction');
            $this->loadModel('formbehaviour');
            $this->loadModel('fieldformlinkage');
            $this->loadModel('presentation');

            if (!$this->request->is('post')) {
//write json
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);

                $options1['conditions'] = array('majorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('majorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('majorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('majorfunction.mf_serial', 'majorfunction.function_desc', 'majorfunction.major_id', 'formlabels.labelname');
                $minorfunction['majorfunction'] = $this->majorfunction->find('all', $options1);

//                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
//                $options1['order'] = array('minorfunction.mf_serial');
//                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
//                $options1['fields'] = array('minorfunction.mf_serial', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
//                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);

                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('minorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_majorfunctions', 'alias' => 'major', 'type' => 'INNER', 'conditions' => array('minorfunction.major_id = major.major_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('minorfunction.mf_serial', 'major.major_id', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);

                $options2['conditions'] = array('fieldformlinkage.state_id' => $this->Auth->user('state_id'));
                $options2['joins'] = array(array('table' => 'ngdrstab_mst_fieldlist', 'alias' => 'fieldlist', 'type' => 'INNER', 'conditions' => array('fieldformlinkage.field_id=fieldlist.id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('fieldlist.label_id=formlabels.id')));
                $options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname', 'formlabels.label_desc_en', 'formlabels.label_desc_' . $this->Session->read("sess_langauge"), 'formlabels.labelname');
                $minorfunction['formfields'] = $this->fieldformlinkage->find('all', $options2);
//pr($options2);exit;
                $options3['conditions'] = array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => $this->Auth->user('state_id'));
                $options3['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('formbehaviour.label_id=formlabels.id')));
                $options3['fields'] = array('formbehaviour.id', 'formbehaviour.behaviour_desc', 'formlabels.labelname');
                $minorfunction['formbehaviour'] = $this->formbehaviour->find('all', $options3);

                $minorfunction['formbehaviour'] = $this->formbehaviour->find('all', array('fields' => array('formbehaviour.id', 'formbehaviour.behaviour_desc'), 'conditions' => array('formbehaviour.form_id' => '1', 'formbehaviour.state_id' => $this->Auth->user('state_id'))));
                $file->write(json_encode($minorfunction));
            }

//read json
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            $this->set('minorfunction', $json2array['minorfunction']);
            $this->set('majorfunction', $json2array['majorfunction']);
            $this->set('formbehaviour', $json2array['formbehaviour']);
            $this->set('formfields', $json2array['formfields']);

            if ($this->request->is('post')) {
                if ($this->presentation->save($this->request->data['frmpresentation'])) {
                    $this->Session->setFlash(__('lblsavemsg'));
                }
            }
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function frmproperty() {
        try {

            $this->loadModel('minorfunction');
            $this->loadModel('majorfunction');
            $this->loadModel('formbehaviour');
            $this->loadModel('fieldformlinkage');
            $this->loadModel('property');

            if (!$this->request->is('post')) {
//write json
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);

                $options1['conditions'] = array('majorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('majorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('majorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('majorfunction.mf_serial', 'majorfunction.function_desc', 'majorfunction.major_id', 'formlabels.labelname');
                $minorfunction['majorfunction'] = $this->majorfunction->find('all', $options1);

//                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
//                $options1['order'] = array('minorfunction.mf_serial');
//                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
//                $options1['fields'] = array('minorfunction.mf_serial', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
//                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);

                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('minorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_majorfunctions', 'alias' => 'major', 'type' => 'INNER', 'conditions' => array('minorfunction.major_id = major.major_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('minorfunction.mf_serial', 'major.major_id', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);

                $options2['conditions'] = array('fieldformlinkage.state_id' => $this->Auth->user('state_id'));
                $options2['order'] = array('fieldformlinkage.id ASC');
                $options2['joins'] = array(array('table' => 'ngdrstab_mst_fieldlist', 'alias' => 'fieldlist', 'type' => 'INNER', 'conditions' => array('fieldformlinkage.field_id=fieldlist.id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('fieldlist.label_id=formlabels.id')));
//$options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname', 'formlabels.label_desc_en', 'formlabels.label_desc_' . $this->Session->read("local_langauge"));
                $options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname');
                $minorfunction['formfields'] = $this->fieldformlinkage->find('all', $options2);

                $options3['conditions'] = array('formbehaviour.form_id' => '2', 'formbehaviour.state_id' => $this->Auth->user('state_id'));
                $options3['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('formbehaviour.label_id=formlabels.id')));
                $options3['fields'] = array('formbehaviour.id', 'formbehaviour.behaviour_desc', 'formlabels.labelname');
                $minorfunction['formbehaviour'] = $this->formbehaviour->find('all', $options3);

//$minorfunction['formbehaviour'] = $this->formbehaviour->find('all', array('fields' => array('formbehaviour.id', 'formbehaviour.behaviour_desc'), 'conditions' => array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => 'MH')));
                $file->write(json_encode($minorfunction));
            }

//read json
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            $this->set('minorfunction', $json2array['minorfunction']);
            $this->set('majorfunction', $json2array['majorfunction']);
            $this->set('formbehaviour', $json2array['formbehaviour']);
            $this->set('formfields', $json2array['formfields']);

            if ($this->request->is('post')) {
//pr($this->request->data);
                if ($this->property->save($this->request->data['frmproperty'])) {
                    $this->Session->setFlash(__('lblsavemsg'));
                }
            }
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function frmparty() {
        try {
            $this->loadModel('minorfunction');
            $this->loadModel('majorfunction');
            $this->loadModel('formbehaviour');
            $this->loadModel('fieldformlinkage');
            $this->loadModel('party');

            if (!$this->request->is('post')) {
//write json
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);

//                  $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $options1['conditions'] = array('majorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('majorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('majorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('majorfunction.mf_serial', 'majorfunction.function_desc', 'majorfunction.major_id', 'formlabels.labelname');
                $minorfunction['majorfunction'] = $this->majorfunction->find('all', $options1);

//                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
//                $options1['order'] = array('minorfunction.mf_serial');
//                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
//                $options1['fields'] = array('minorfunction.mf_serial', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
//                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);


                $options1['conditions'] = array('minorfunction.state_id' => $this->Auth->user('state_id'));
                $options1['order'] = array('minorfunction.mf_serial');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_mf_forms', 'alias' => 'mf_forms', 'type' => 'INNER', 'conditions' => array('minorfunction.id = mf_forms.mf_id')), array('table' => 'ngdrstab_mst_majorfunctions', 'alias' => 'major', 'type' => 'INNER', 'conditions' => array('minorfunction.major_id = major.major_id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('minorfunction.label_id=formlabels.id')));
                $options1['fields'] = array('minorfunction.mf_serial', 'major.major_id', 'minorfunction.function_desc', 'mf_forms.form_name', 'formlabels.labelname');
                $minorfunction['minorfunction'] = $this->minorfunction->find('all', $options1);

                $options2['conditions'] = array('fieldformlinkage.state_id' => $this->Auth->user('state_id'));
                $options2['order'] = array('fieldformlinkage.field_id ASC');
                $options2['joins'] = array(array('table' => 'ngdrstab_mst_fieldlist', 'alias' => 'fieldlist', 'type' => 'INNER', 'conditions' => array('fieldformlinkage.field_id=fieldlist.id')), array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('fieldlist.label_id=formlabels.id')));
//$options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname', 'formlabels.label_desc_en', 'formlabels.label_desc_' . $this->Session->read("local_langauge"));
                $options2['fields'] = array('fieldformlinkage.form_id', 'fieldformlinkage.behaviour_id', 'fieldlist.fieldname', 'fieldlist.fieldtype', 'fieldlist.ddltablename', 'fieldlist.ddlfieldvalue', 'fieldlist.ddlfielddesc', 'formlabels.labelname');
                $minorfunction['formfields'] = $this->fieldformlinkage->find('all', $options2);

                $options3['conditions'] = array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => $this->Auth->user('state_id'));
                $options3['joins'] = array(array('table' => 'ngdrstab_mst_formlabels', 'alias' => 'formlabels', 'type' => 'INNER', 'conditions' => array('formbehaviour.label_id=formlabels.id')));
                $options3['fields'] = array('formbehaviour.id', 'formbehaviour.behaviour_desc', 'formlabels.labelname');
                $minorfunction['formbehaviour'] = $this->formbehaviour->find('all', $options3);

//$minorfunction['formbehaviour'] = $this->formbehaviour->find('all', array('fields' => array('formbehaviour.id', 'formbehaviour.behaviour_desc'), 'conditions' => array('formbehaviour.form_id' => '3', 'formbehaviour.state_id' => 'MH')));
                $file->write(json_encode($minorfunction));
            }

//read json
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            $this->set('minorfunction', $json2array['minorfunction']);
            $this->set('formbehaviour', $json2array['formbehaviour']);
            $this->set('formfields', $json2array['formfields']);
            $this->set('majorfunction', $json2array['majorfunction']);

            if ($this->request->is('post')) {
//pr($this->request->data);
                if ($this->party->save($this->request->data['frmparty'])) {
                    $this->Session->setFlash(__('lblsavemsg'));
                }
            }
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function propertyscreennew() {
        try {

            $this->set('pdfflag', 0);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $created_date = date('Y/m/d H:i:s');
            $user_id = $this->Session->read("session_user_id");
            $stateid = $this->Auth->User("state_id");
            $language = $this->Session->read("sess_langauge");
            $this->set('lang', $language);
            $this->Session->write('Selectedtoken', NULL);
            // Load data to json file and set variable for ctp
            $json2array = $this->load_json_file();
            $fieldlist = $this->valuation->fieldlist();
            $fieldlist['captcha']['text'] = 'is_required,is_captcha';
            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->post_back_valuation_data($json2array);
                $captcha = $this->Session->read('captcha_code');
                if ($this->request->data['propertyscreennew']['captcha'] == $captcha) {
                    $rulelist = NULL;              //  pr($this->request->data['propertyscreennew']);
                    if (isset($this->request->data['propertyscreennew']['usage_cat_id'])) {
                        $rulelist = $this->request->data['propertyscreennew']['usage_cat_id'];
                    }
                    $fieldlist = $this->valuation->fieldlist($rulelist);
                    //pr($fieldlist);
                    // exit;
                    $fieldlist = $this->modifyfieldlist($fieldlist, $this->request->data['propertyscreennew']);

                    $errors = $this->validatedata($this->request->data['propertyscreennew'], $fieldlist);
                    // pr($errors);exit;
                    if ($this->ValidationError($errors)) {
                        $result = $this->property_valuation();
                        if (is_numeric($result)) {
                            $this->set('pdfflag', 1);
                            $this->Session->setFlash(__('lblsavemsg'));
                        } else {
                            $this->Session->setFlash('Rate not available.');
                        }
                    } else {
                        $this->Session->setFlash('Please check validations!');
                    }
                } else {
                    $this->request->data['propertyscreennew']['captcha'] = '';
                    $this->Session->setFlash('Captcha Mismatch Error!');
                }
                $this->request->data['propertyscreennew']['captcha'] = '';
            }
        } catch (Exception $ex) {
            // pr($ex);exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function modifyfieldlist($fieldlist, $data) {
        try {

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $errors = array();
            unset($fieldlist['corp_id']);

            if (!isset($json2array['level1']) || empty($json2array['level1']) || $json2array['level1'] == NULL) {
                unset($fieldlist['level1_id']);
                if (isset($this->request->data['propertyscreennew']['level1_id'])) {
                    unset($this->request->data['propertyscreennew']['level1_id']);
                }
            }
            if (!isset($json2array['level1list']) || empty($json2array['level1list']) || $json2array['level1list'] == NULL || !isset($fieldlist['level1_id'])) {

                if (!is_numeric($this->request->data['propertyscreennew']['level1_id'])) {
                    unset($fieldlist['level1_list_id']);
                    if (isset($this->request->data['propertyscreennew']['level1_list_id'])) {
                        unset($this->request->data['propertyscreennew']['level1_list_id']);
                    }
                }
            }

            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfconstructionflag'] == 'N') {
                unset($fieldlist['construction_type_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfdepreciationflag'] == 'N') {
                unset($fieldlist['depreciation_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfroadvicinityflag'] == 'N') {
                unset($fieldlist['road_vicinity_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfuserdependency1flag'] == 'N') {
                unset($fieldlist['user_defined_dependency1_id']);
            }
            if (!isset($json2array['totaldependency']) || empty($json2array['totaldependency']) || $json2array['totaldependency']['hfuserdependency2flag'] == 'N') {
                unset($fieldlist['user_defined_dependency2_id']);
            }

            return $fieldlist;
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

   public function editvaluation($val_id = NULL) {
        $this->autoRender = False;
        $lang = $this->Session->read("sess_langauge");
        $token = $this->Session->read('Selectedtoken');
        array_map([$this, 'loadModel'], ['valuation', 'valuation_details', 'usage_category', 'usagelinkcategory', 'evalrule', 'subrule', 'usagelnk', 'BehavioralPattens', 'PropertyFields', 'conf_reg_bool_info']);
        $response['Error'] = '';
        $response['Datalist'] = '';
        $valuation = $this->valuation->find('first', array('conditions' => array('val_id' => $val_id)));
        //pr($valuation);
        if (empty($valuation)) {
            $response['Error'] = 'Valuation Not Found';
            return $response;
        } else {
            $valuation = $valuation['valuation'];
        }
        $landtype = $valuation['developed_land_types_id'];


        if (is_numeric($token)) {
            if (is_numeric(@$valuation['subdivision_id'])) {
                $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($gentalukaconf)) {
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                        ),
                        'conditions' => array('taluka.subdivision_id' => $valuation['subdivision_id'])
                            )
                    );
                } else {
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'taluka.taluka_id=link.taluka_id')),
                        ),
                        'conditions' => array('taluka.subdivision_id' => $valuation['subdivision_id'])
                            )
                    );
                }
            } else {
                $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                if (!empty($gentalukaconf)) {
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                        ),
                            // 'conditions' => array('taluka.subdivision_id' => $valuation['subdivision_id'])
                            )
                    );
                } else {
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'taluka.taluka_id=link.taluka_id')),
                        ),
                            // 'conditions' => array('taluka.subdivision_id' => $valuation['subdivision_id'])
                            )
                    );
                }
            }
//            $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
//                'joins' => array(
//                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
//                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'taluka.taluka_id=link.taluka_id')),
//                ),
//                    //  'conditions' => array('taluka.taluka_id' => 'link.taluka_id')
//                    )
//            );
        } else {
            $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $valuation['district_id'])));
        }

        $response['Datalist']['taluka'] = $talukalist;

        if (!empty($valuation['taluka_id'])) {
            $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'damblkdpnd.taluka_id=link.taluka_id')),
                        ),
                        'conditions' => array('damblkdpnd.taluka_id' => $valuation['taluka_id']))))));
            $response['Datalist']['corp'] = $corplist;
        } elseif (!empty($valuation['district_id'])) {
            $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'damblkdpnd.taluka_id=link.taluka_id')),
                        ),
                        'conditions' => array('district_id' => $valuation['district_id']))))));
            $response['Datalist']['corp'] = $corplist;
        }
        if (!empty($valuation['district_id'])) {
            if (is_numeric($token)) {
                //
                //+pr($valuation);exit;
                if (isset($valuation['subdivision_id']) && is_numeric($valuation['subdivision_id'])) {
                    $taluka = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.subdivision_id', 'taluka.subdivision_id'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                    )));
//pr($taluka);
                    $subdivdata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $valuation['district_id'], 'subdivision_id' => $taluka)));
                    // pr($distdata);
                } else {
                    $subdivdata = array();
                }
            } else {
                $subdivdata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $valuation['district_id'])));
            }
            // exit;
            $response['Datalist']['subdiv'] = $subdivdata;
        }


        if (is_numeric($token)) {
            if (!empty($valuation['corp_id'])) {
                $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                        array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'damblkdpnd.village_id=link.village_id')),
                    ),
                    'conditions' => array('damblkdpnd.district_id' => $valuation['district_id'], 'damblkdpnd.corp_id' => $valuation['corp_id']), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                $response['Datalist']['village'] = $villagelist;
            } else {
                $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang),
                    'joins' => array(
                        array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                        array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'damblkdpnd.village_id=link.village_id')),
                    ),
                    'conditions' => array('damblkdpnd.district_id' => $valuation['district_id'], 'damblkdpnd.taluka_id' => $valuation['taluka_id']), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                $response['Datalist']['village'] = $villagelist;
            }
        } else {
            if (!empty($valuation['corp_id'])) {
                $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang), 'conditions' => array('district_id' => $valuation['district_id'], 'corp_id' => $valuation['corp_id']), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                $response['Datalist']['village'] = $villagelist;
            } else {
                $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang), 'conditions' => array('district_id' => $valuation['district_id'], 'taluka_id' => $valuation['taluka_id']), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                $response['Datalist']['village'] = $villagelist;
            }
        }





       //$level1id = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level1_id'), 'conditions' => array('village_id' => $valuation['village_id'])));
        $level1 = ClassRegistry::init('Levels_1_property')->find('list', array('fields' => array('Levels_1_property.level_1_id', 'Levels_1_property.level_1_desc_' . $lang), 'conditions' => array('village_id' => $valuation['village_id'])));
        $response['Datalist']['level1'] = $level1;

        if (!empty($valuation['level1_id'])) {
            $level1list = ClassRegistry::init('loc_level_1_prop_list')->find('list', array('fields' => array('prop_level1_list_id', 'list_1_desc_' . $lang), 'conditions' => array('level_1_id' =>  $valuation['level1_id'])));
            $response['Datalist']['level1list'] = $level1list;
        }

        $valuation_details = $this->valuation_details->find('all', array('fields' => array('DISTINCT rule_id'), 'conditions' => array('val_id' => $val_id, 'item_type_id' => 1)));
        foreach ($valuation_details as $vdetails) {
            $rulearr[$vdetails['valuation_details']['rule_id']] = $vdetails['valuation_details']['rule_id'];
        }
        $json2array['ruleid'] = $rulearr;

        $options2['conditions'] = array('evalrule.evalrule_id' => $json2array['ruleid'], 'evalrule.evalrule_id is not null');
        $options2['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'left', 'conditions' => array('evalrule.output_item_id = itemlist.usage_param_id')), array('table' => 'ngdrstab_mst_usage_lnk_category', 'alias' => 'linkcat', 'type' => 'INNER', 'conditions' => array('evalrule.evalrule_id = linkcat.evalrule_id')), array('table' => 'ngdrstab_mst_usage_category', 'alias' => 'usagecat', 'type' => 'INNER', 'conditions' => array('linkcat.usage_main_catg_id = usagecat.usage_main_catg_id', 'linkcat.usage_sub_catg_id = usagecat.usage_sub_catg_id', 'linkcat.usage_sub_sub_catg_id = usagecat.usage_sub_sub_catg_id')));
        $options2['fields'] = array('DISTINCT itemlist.usage_param_desc_' . $this->Session->read("sess_langauge"), 'evalrule.*', 'linkcat.usage_main_catg_id', 'linkcat.usage_sub_catg_id', 'linkcat.usage_sub_sub_catg_id', 'usagecat.contsruction_type_flag', 'usagecat.depreciation_flag', 'usagecat.road_vicinity_flag', 'usagecat.user_defined_dependency1_flag', 'usagecat.user_defined_dependency2_flag', 'usagecat.is_boundary_applicable');
        $json2array['evalconditions'] = $this->evalrule->find('all', $options2);
        $json2array['evalconditions'] = $this->replace_operater_mainrule($json2array['evalconditions']);

        $options1['order'] = array('usagelinkcategory.evalrule_id' => 'ASC', 'usagelinkcategory.display_order' => 'ASC');
        $options1['conditions'] = array('usagelinkcategory.evalrule_id' => $json2array['ruleid'], 'usagelinkcategory.evalrule_id is not null');
        $options1['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_param_id = itemlist.usage_param_id')), array('table' => 'ngdrstab_mst_item_rate', 'alias' => 'itemrate', 'type' => 'left outer', 'conditions' => array('usagelinkcategory.usage_param_id=itemrate.usage_param_id')), array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'usage_main', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_main_catg_id = usage_main.usage_main_catg_id')), array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'usage_sub', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_sub_catg_id = usage_sub.usage_sub_catg_id')), array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'subsub', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_sub_sub_catg_id = subsub.usage_sub_sub_catg_id')));
        $options1['fields'] = array('DISTINCT usagelinkcategory.usage_param_id', 'usagelinkcategory.uasge_param_code', 'usagelinkcategory.evalrule_id', 'usagelinkcategory.display_order', 'usagelinkcategory.main_cat_id', 'usagelinkcategory.sub_cat_id', 'usagelinkcategory.sub_sub_cat_id', 'itemlist.usage_param_desc_en', 'itemlist.usage_param_desc_ll', 'itemlist.range_field_flag', 'itemlist.slab_rate_flag', 'itemlist.rate_table_flag', 'itemlist.area_field_flag', 'itemlist.is_list_field_flag', 'itemlist.unit_cat_id', 'itemlist.usage_param_type_id', 'usagelinkcategory.item_rate_flag', 'itemlist.item_rate_flag', 'itemlist.single_unit_flag', 'itemlist.area_type_flag', 'itemlist.districtwise_unit_change_flag', 'itemlist.unit_id', 'itemlist.is_input_hidden', 'itemlist.is_string', 'itemlist.output_item_id', 'CASE WHEN itemrate.item_rate IS NULL then 0 else itemrate.item_rate END AS item_rate', 'usage_main.usage_main_catg_desc_' . $lang, 'usage_sub.usage_sub_catg_desc_' . $lang, 'subsub.usage_sub_sub_catg_desc_' . $lang);

        $json2array['usageitemlist'] = $this->usagelinkcategory->find('all', $options1);

        $hfconstructionflag = 'N';
        $hfdepreciationflag = 'N';
        $hfroadvicinityflag = 'N';
        $hfuserdependency1flag = 'N';
        $hfuserdependency2flag = 'N';
        $hfboundaryflag = 'N';
        //$rateflag='Y';
        foreach ($json2array['evalconditions'] as $frm) {
            // $this->
            // pr($frm);
            if ($frm['usagecat']['contsruction_type_flag'] === 'Y') {
                $hfconstructionflag = 'Y';
            }
            if ($frm['usagecat']['depreciation_flag'] === 'Y') {
                $hfdepreciationflag = 'Y';
            }
            if ($frm['usagecat']['road_vicinity_flag'] === 'Y') {
                $hfroadvicinityflag = 'Y';
            }
            if ($frm['usagecat']['user_defined_dependency1_flag'] === 'Y') {
                $hfuserdependency1flag = 'Y';
            }
            if ($frm['usagecat']['user_defined_dependency2_flag'] === 'Y') {
                $hfuserdependency2flag = 'Y';
            }
            if ($frm['usagecat']['is_boundary_applicable'] === 'Y') {
                $hfboundaryflag = 'Y';
            }
        }

        /* ------------------------------------ */
        $subruleid = array();
        foreach ($json2array['evalconditions'] as $evalconditions) {
            if ($evalconditions['evalrule']['subrule_flag'] == 'Y') {

                $conditions1['evalrule_id'] = $evalconditions['evalrule']['evalrule_id'];
                $subrl1 = $this->subrule->find('all', array('conditions' => $conditions1));
                if ($subrl1) {
                    foreach ($subrl1 as $subrl11) {
                        array_push($subruleid, $subrl11['subrule']['subrule_id']);
                    }
                }
            }
        }
//PR($subruleid);EXIT;
//Sub Rule Flag Checking
        if ($subruleid != NULL) {
            $options3['order'] = array('subrule.subrule_id');
            $options3['conditions'] = array('subrule_id' => $subruleid, 'evalrule_id is not null');
            $options3['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'INNER', 'conditions' => array('subrule.output_item_id = itemlist.usage_param_id')));
            $options3['fields'] = array('subrule.*', 'itemlist.usage_param_desc_' . $this->Session->read("sess_langauge"));
            $json2array['subruleconditions'] = $this->subrule->find('all', $options3);

            $json2array['subruleconditions'] = $this->replace_operater_subrule($json2array['subruleconditions']);

            $this->set('subruleconditions', $json2array['subruleconditions']);
        }
//pr($json2array);

        /* -------------------------------- */

        $result_array = array('hfconstructionflag' => $hfconstructionflag,
            'hfdepreciationflag' => $hfdepreciationflag,
            'hfroadvicinityflag' => $hfroadvicinityflag,
            'hfuserdependency1flag' => $hfuserdependency1flag,
            'hfuserdependency2flag' => $hfuserdependency2flag,
            'hfboundaryflag' => $hfboundaryflag);

        $json2array['totaldependency'] = $result_array;


        // evalrulechange event
        //$village_id = $data['village_id'];
        $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_en', 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id', 'VillageMapping.ulb_type_id', 'VillageMapping.district_id'), 'conditions' => array('village_id' => $valuation['village_id'])));

        $LanTypeId = 1;
        foreach ($villagedetails as $village) {
            $LanTypeId = $village['VillageMapping']['developed_land_types_id'];
        }
        if ($LanTypeId == '2') {
            $this->Session->write("land_type", 'R');
        } else {
            $this->Session->write("land_type", 'U');
        }

        $this->set('areatype', $LanTypeId);
        $response['Datalist']['villagedetails'] = $villagedetails;

        $language = $this->Session->read("sess_langauge");
        $this->set('language', $language);

//                $this->set('usageitemlist', @$json2array['usageitemlist']);
//                $this->set('outputfield', @$json2array['evalconditions']);
//                $this->set('akar_ranges', @$json2array['akar_ranges']);
//                $this->set('listitemsoptions', @$json2array['listitemsoptions']);
//               
        //behavioral_patterns ***
        // dependency change event

        $subruleid = array();
        foreach ($json2array['evalconditions'] as $evalconditions) {
            if ($evalconditions['evalrule']['subrule_flag'] == 'Y') {
                $conditions['evalrule_id'] = $evalconditions['evalrule']['evalrule_id'];
                if ($evalconditions['evalrule']['construction_type_id']) {
                    $conditions['construction_type_id'] = $valuation['construction_type_id'];
                }
                if ($evalconditions['evalrule']['depreciation_id']) {
                    $conditions['depreciation_id'] = $valuation['depreciation_id'];
                }
                if ($evalconditions['evalrule']['road_vicinity_id']) {
                    $conditions['road_vicinity_id'] = $valuation['road_vicinity_id'];
                    ;
                }
                if ($evalconditions['evalrule']['user_defined_dependency1_id']) {
                    $conditions['user_defined_dependency1_id'] = '';
                }
                if ($evalconditions['evalrule']['user_defined_dependency2_id']) {
                    $conditions['user_defined_dependency2_id'] = '';
                }
                $subrl = $this->subrule->find('all', array('conditions' => $conditions));
                if ($subrl) {
                    foreach ($subrl as $subr2) {
                        array_push($subruleid, $subr2['subrule']['subrule_id']);
                    }
                }

                $conditions1['evalrule_id'] = $evalconditions['evalrule']['evalrule_id'];
                $conditions1['construction_type_id'] = 0;
                $conditions1['depreciation_id'] = 0;
                $conditions1['road_vicinity_id'] = 0;
                $conditions1['user_defined_dependency1_id'] = 0;
                $conditions1['user_defined_dependency2_id'] = 0;
                $subrl1 = $this->subrule->find('all', array('conditions' => $conditions1));
                if ($subrl1) {
                    foreach ($subrl1 as $subrl11) {
                        array_push($subruleid, $subrl11['subrule']['subrule_id']);
                    }
                }
            }
        }

//Sub Rule Flag Checking
        if ($subruleid != NULL) {
            $options3['order'] = array('subrule.subrule_id');
            $options3['conditions'] = array('subrule_id' => $subruleid, 'evalrule_id is not null');
            $options3['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'INNER', 'conditions' => array('subrule.output_item_id = itemlist.usage_param_id')));
            $options3['fields'] = array('subrule.*', 'itemlist.usage_param_desc_' . $this->Session->read("sess_langauge"));
            $json2array['subruleconditions'] = $this->subrule->find('all', $options3);
            $json2array['subruleconditions'] = $this->replace_operater_subrule($json2array['subruleconditions']);
        }
        $response['Datalist'] = array_merge($response['Datalist'], $json2array);


        // pr($valuation['construction_type_id']);exit;
        $response['VData']['construction_type_id'] = $valuation['construction_type_id'];
        $response['VData']['depreciation_id'] = $valuation['depreciation_id'];
        $response['VData']['road_vicinity_id'] = $valuation['road_vicinity_id'];
        $response['VData']['usage_cat_id'] = $rulearr;


        foreach ($valuation_details as $vdetails) {
            $inputdetails = $this->valuation_details->getValuationDetail_all($val_id, 1, $vdetails['valuation_details']['rule_id'], $lang);
            foreach ($inputdetails as $input) {
                if ($input[0]['is_string'] == 'Y') {
                    $response['VData'][$input[0]['usage_param_code'] . "_" . $input[0]['rule_id']] = $input[0]['item_value_string'];
                } else {
                    $response['VData'][$input[0]['usage_param_code'] . "_" . $input[0]['rule_id']] = $input[0]['item_value'];
                }
                $response['VData'][$input[0]['usage_param_code'] . "unit_" . $input[0]['rule_id']] = $input[0]['area_unit'];
                $response['VData'][$input[0]['usage_param_code'] . "areatype_" . $input[0]['rule_id']] = $input[0]['area_type'];
            }
        }
        // behaviour

        $BehavioralPatterns = array();
        // pr($rulearr);
        foreach ($rulearr as $rule) {
            $main_cat_id = ClassRegistry::init('usage_category')->field('usage_main_catg_id', array('evalrule_id' => $rule));
            if ($landtype == 1) {
                $landtypeflag = 'U';
            } else if ($landtype == 2) {
                $landtypeflag = 'R';
            } else if ($landtype == 3) {
                $landtypeflag = 'I';
            } else {
                $landtypeflag = 'U';
            }

            $BehavioralPatterns = $this->BehavioralPattens->query("select * from   ngdrstab_conf_behavioral behavioral,   ngdrstab_conf_behavioral_details details, ngdrstab_conf_behavioral_patterns patterns where behavioral.behavioral_id=details.behavioral_id AND details.behavioral_details_id=patterns.behavioral_details_id AND patterns.behavioral_id=1 AND details.developed_land_types_flag=?  AND details.main_usage_id=?", array($landtypeflag, $main_cat_id));
            $response['Datalist']['BehavioralPatterns'] = $BehavioralPatterns;
        }

        $usage_category = $this->usage_category->find("all", array('fields' => array('usage_main_catg_id', 'usage_sub_catg_id'),
            'conditions' => array('evalrule_id' => $rulearr)
        ));
        $sql = "select propfield.field_id,propfield.field_desc_en,propfield.field_desc_en ,is_required,is_date

from ngdrstab_conf_property_dependent_fields_mapping  as  propfieldmap
JOIN ngdrstab_conf_property_dependent_fields  as propfield ON propfield.field_id=propfieldmap.field_id

where  1=0 ";
        $flag = 0;
        if (!empty($usage_category)) {
            foreach ($usage_category as $usage) {
                $usage = $usage['usage_category'];
                if (is_numeric($LanTypeId) && is_numeric($usage['usage_main_catg_id']) && is_numeric($usage['usage_sub_catg_id'])) {
                    $flag = 1;
                    $sql = $sql . " OR  (developed_land_types_id=" . $LanTypeId . " and usage_main_catg_id=" . $usage['usage_main_catg_id'] . " and usage_sub_catg_id=" . $usage['usage_sub_catg_id'] . ")";
                }
            }
        }
        if ($flag) {
            $PropertyFields = $this->PropertyFields->query($sql);
            $response['Datalist']['PropertyFields'] = $PropertyFields;
        }

        return $response;
    }

    public function valuation_calculater_validation($data) {

        try {

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $errors = array();

//        pr($data);
//        pr($json2array['taluka']);
//        pr($json2array['corp']);
//        exit;
            if (isset($json2array['districtnname']) && !empty($json2array['districtnname']) && !is_numeric($data['district_id'])) {
                $errors['type1']['district_id'] = "Please Select option";
            }
            if (isset($json2array['taluka']) && !empty($json2array['taluka']) && !is_numeric($data['taluka_id']) && isset($json2array['corp']) && !empty($json2array['corp']) && !is_numeric($data['corp_id'])) {
                $errors['type1']['taluka_id'] = "Please Select option";
                $errors['type1']['corp_id'] = "Please Select option";
            }
            if (isset($json2array['village']) && !empty($json2array['village']) && !is_numeric($data['village_id'])) {
                $errors['type1']['village_id'] = "Please Select option";
            }
            if (isset($json2array['level1']) && !empty($json2array['level1']) && !is_numeric($data['level1_id'])) {
                $errors['type1']['level1_id'] = "Please Select option";
            }
            if (isset($json2array['level1list']) && !empty($json2array['level1list']) && !is_numeric($data['level1_list_id'])) {
                $errors['type1']['level1_list_id'] = "Please Select option";
            }
            if (empty($data['usage_cat_id'])) {
                $errors['type2']['usage-list'] = "Please Select Usage Rule";
            }
            // pr($json2array);
            if (isset($json2array['totaldependency']) && !empty($json2array['totaldependency']) && $json2array['totaldependency']['hfconstructionflag'] == 'Y' && !is_numeric($data['construction_type_id'])) {

                $errors['type1']['construction_type_id'] = "Please Select option";
            }
            if (isset($json2array['totaldependency']) && !empty($json2array['totaldependency']) && $json2array['totaldependency']['hfdepreciationflag'] == 'Y' && !is_numeric($data['depreciation_id'])) {

                $errors['type1']['depreciation_id'] = "Please Select option";
            }

            if (isset($json2array['usageitemlist']) && !empty($json2array['usageitemlist'])) {
                foreach ($json2array['usageitemlist'] as $singleitem) {

                    if (empty($data[$singleitem['usagelinkcategory']['uasge_param_code'] . "_" . $singleitem['usagelinkcategory']['evalrule_id']]) && $data[$singleitem['usagelinkcategory']['uasge_param_code'] . "_" . $singleitem['usagelinkcategory']['evalrule_id']] != 0) {

                        if ($singleitem['itemlist']['is_list_field_flag'] == 'N') {
                            $errors['type1'][$singleitem['usagelinkcategory']['uasge_param_code'] . "_" . $singleitem['usagelinkcategory']['evalrule_id']] = "This Field Is Required";
                        } else {
                            $errors['type1'][$singleitem['usagelinkcategory']['uasge_param_code'] . "_" . $singleitem['usagelinkcategory']['evalrule_id']] = "Please Select  Option";
                        }
                    } elseif (!is_numeric($data[$singleitem['usagelinkcategory']['uasge_param_code'] . "_" . $singleitem['usagelinkcategory']['evalrule_id']])) {
                        $errors['type1'][$singleitem['usagelinkcategory']['uasge_param_code'] . "_" . $singleitem['usagelinkcategory']['evalrule_id']] = "Enter Numeric Value";
                    }
                }
            }

            if (!isset($json2array['villagedetails']) && empty($json2array['usageitemlist'])) {
                $errors['type3']['villagedetails'] = " Village Details Missing . Please try Again ";
            }
            //   exit;
            return $errors;
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function rulechangeevent() {
        try {
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);
            $this->loadModel('VillageMapping');
            $this->layout = 'ajax';

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            if (isset($data['village_id']) && is_numeric($data['village_id'])) {
                $village_id = $data['village_id'];
                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_en', 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id', 'VillageMapping.ulb_type_id', 'VillageMapping.district_id'), 'conditions' => array('village_id' => $village_id)));

                $LanTypeId = 1;
                foreach ($villagedetails as $village) {
                    $LanTypeId = $village['VillageMapping']['developed_land_types_id'];
                }
                if ($LanTypeId == '2') {
                    $this->Session->write("land_type", 'R');
                } else {
                    $this->Session->write("land_type", 'U');
                }

                $this->set('areatype', $LanTypeId);
                $json2array['villagedetails'] = $villagedetails;

                $language = $this->Session->read("sess_langauge");
                $this->set('language', $language);
            }
            $this->set('district_id', @$data['district_id']);
            $this->set('usageitemlist', @$json2array['usageitemlist']);
            $this->set('outputfield', @$json2array['evalconditions']);
            $this->set('akar_ranges', @$json2array['akar_ranges']);
            $this->set('listitemsoptions', @$json2array['listitemsoptions']);

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function fetchsubrule() {

        try {
            $this->autoRender - FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $landType = @$data['landType'];
            $constructionType = @$data['cType'];
            $depreciationType = @$data['dType'];
            $roadVicinity = @$data['rVicinity'];
            $UDD1 = @$data['UDD1'];
            $UDD2 = @$data['UDD2'];

            if ($landType == '2') {
                $this->Session->write("land_type", 'R');
            } else {
                $this->Session->write("land_type", 'U');
            }
            $this->loadModel('damblkdpnd');
            $this->loadModel('subrule');

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $subruleid = array();
            foreach ($json2array['evalconditions'] as $evalconditions) {
                if ($evalconditions['evalrule']['subrule_flag'] == 'Y') {
                    $conditions['evalrule_id'] = $evalconditions['evalrule']['evalrule_id'];
                    if ($evalconditions['evalrule']['construction_type_id']) {
                        $conditions['construction_type_id'] = $constructionType;
                    }
                    if ($evalconditions['evalrule']['depreciation_id']) {
                        $conditions['depreciation_id'] = $depreciationType;
                    }
                    if ($evalconditions['evalrule']['road_vicinity_id']) {
                        $conditions['road_vicinity_id'] = $roadVicinity;
                    }
                    if ($evalconditions['evalrule']['user_defined_dependency1_id']) {
                        $conditions['user_defined_dependency1_id'] = $UDD1;
                    }
                    if ($evalconditions['evalrule']['user_defined_dependency2_id']) {
                        $conditions['user_defined_dependency2_id'] = $UDD2;
                    }
                    $subrl = $this->subrule->find('all', array('conditions' => $conditions));
                    if ($subrl) {
                        foreach ($subrl as $subr2) {
                            array_push($subruleid, $subr2['subrule']['subrule_id']);
                        }
                    }

                    $conditions1['evalrule_id'] = $evalconditions['evalrule']['evalrule_id'];
                    $conditions1['construction_type_id'] = 0;
                    $conditions1['depreciation_id'] = 0;
                    $conditions1['road_vicinity_id'] = 0;
                    $conditions1['user_defined_dependency1_id'] = 0;
                    $conditions1['user_defined_dependency2_id'] = 0;
                    $subrl1 = $this->subrule->find('all', array('conditions' => $conditions1));
                    if ($subrl1) {
                        foreach ($subrl1 as $subrl11) {
                            array_push($subruleid, $subrl11['subrule']['subrule_id']);
                        }
                    }
                }
            }

//Sub Rule Flag Checking
            if ($subruleid != NULL) {
                $options3['order'] = array('subrule.subrule_id');
                $options3['conditions'] = array('subrule_id' => $subruleid, 'evalrule_id is not null');
                $options3['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'INNER', 'conditions' => array('subrule.output_item_id = itemlist.usage_param_id')));
                $options3['fields'] = array('subrule.*', 'itemlist.usage_param_desc_' . $this->Session->read("sess_langauge"));
                $json2array['subruleconditions'] = $this->subrule->find('all', $options3);
                $json2array['subruleconditions'] = $this->replace_operater_subrule($json2array['subruleconditions']);
                $this->set('subruleconditions', $json2array['subruleconditions']);
            }
//pr($json2array);
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
            exit;
//        $this->set('usageitemlist', $json2array['usageitemlist']);
//        $this->set('outputfield', $json2array['evalconditions']);
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getsubcategory() {
        try {
            if (isset($_GET['usage_main_catg_id'])) {
                $this->loadModel('usage_sub_category');

                $usage_main_catg_id = $_GET['usage_main_catg_id'];

                $options1['conditions'] = array('usagelink.usage_main_catg_id' => $usage_main_catg_id);
                $options1['order'] = array('usage_sub_category.usage_sub_catg_id');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_usage_category', 'alias' => 'usagelink', 'type' => 'INNER', 'conditions' => array('usage_sub_category.usage_sub_catg_id = usagelink.usage_sub_catg_id')));
                $options1['fields'] = array('usage_sub_category.usage_sub_catg_id', 'usage_sub_category.usage_sub_catg_desc_' . $this->Session->read("sess_langauge"));
                $result = $this->usage_sub_category->find('list', $options1);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['subcat_id'] = $result;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
//$result = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc'), 'conditions' => array('article_id' => array($ddlval))));
                echo json_encode($result);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getsubsubcategory() {
        try {
            if (isset($_GET['usage_main_catg_id']) && isset($_GET['usage_sub_catg_id'])) {
                $this->loadModel('usage_sub_sub_category');

                $usage_main_catg_id = $_GET['usage_main_catg_id'];
                $usage_sub_catg_id = $_GET['usage_sub_catg_id'];

                $options1['order'] = array('usage_sub_sub_category.usage_sub_sub_catg_id');
                $options1['conditions'] = array('usagelink.usage_main_catg_id' => $usage_main_catg_id, 'usagelink.usage_sub_catg_id' => $usage_sub_catg_id);
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_usage_category', 'alias' => 'usagelink', 'type' => 'INNER', 'conditions' => array('usage_sub_sub_category.usage_sub_sub_catg_id = usagelink.usage_sub_sub_catg_id')));
                $options1['fields'] = array('usage_sub_sub_category.usage_sub_sub_catg_id', 'usage_sub_sub_category.usage_sub_sub_catg_desc_' . $this->Session->read("sess_langauge"));
                $result = $this->usage_sub_sub_category->find('list', $options1);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['subsubcat_id'] = $result;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
//$result = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc'), 'conditions' => array('article_id' => array($ddlval))));
                echo json_encode($result);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getusageitemlist() {
        try {
            if (isset($_GET['usage_main_catg_id']) && isset($_GET['usage_sub_catg_id'])) {
                $this->loadModel('itemlist');
                $usage_main_catg_id = $_GET['usage_main_catg_id'];
                $usage_sub_catg_id = $_GET['usage_sub_catg_id'];
                $usage_sub_sub_catg_id = $_GET['usage_sub_sub_catg_id'];
                $lang = $this->Session->read('sess_langauge'); //new
                $paramids = ClassRegistry::init('usagelinkcategory')->find('all', array('fields' => array('DISTINCT usage_param_id'), 'conditions' => array('usage_main_catg_id' => $usage_main_catg_id, 'usage_sub_catg_id' => $usage_sub_catg_id, 'usage_sub_sub_catg_id' => $usage_sub_sub_catg_id), 'order' => 'usage_param_id'));
                $ids = "";
                foreach ($paramids as $pid) {
                    $ids .= $pid['usagelinkcategory']['usage_param_id'] . ',';
                }
                $ids = substr($ids, 0, -1);
                $ids = explode(',', $ids);
//$paramlist = ClassRegistry::init('itemlist')->find('list', array('fields' => array('usage_param_code', 'usage_param_desc_' . $lang), 'conditions' => array('usage_param_id' => $ids), 'order' => 'usage_param_id'));
                $paramlist = ClassRegistry::init('itemlist')->find('list', array('fields' => array('usage_param_code', 'usage_param_desc_' . $lang), 'conditions' => array('OR' => array(array('usage_param_id' => $ids), 'usage_param_type_id' => 99)), 'order' => 'usage_param_id'));
                echo json_encode($paramlist);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//dropdown
    public function getdist() {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($_GET['div'])) {
                $div = $_GET['div'];
                $configure = $this->damblkdpnd->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));
                if ($configure[0][0]['is_dist'] == 1) {
                    $distid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.state_id'), 'conditions' => array('division_id' => array($div))));
                    $divdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.id', 'District.district_name_en'), 'conditions' => array('id' => $distid)));
                } else if ($configure[0][0]['is_dist'] == 0 && $configure[0][0]['is_zp'] == 1) {
                    $subdivid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.subdivision_id'), 'conditions' => array('division_id' => array($div))));
                    $divdata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.id', 'Subdivision.subdivision_name_en'), 'conditions' => array('id' => $subdivid)));
                } else if ($configure[0][0]['is_dist'] == 0 && $configure[0][0]['is_zp'] == 0 && $configure[0][0]['is_taluka'] == 1) {
                    $talukaid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.taluka_id'), 'conditions' => array('division_id' => array($div))));
                    $divdata = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.id', 'taluka.taluka_name_en'), 'conditions' => array('id' => $talukaid)));
                } else if ($configure[0][0]['is_dist'] == 0 && $configure[0][0]['is_zp'] == 0 && $configure[0][0]['is_taluka'] == 0 && $configure[0][0]['is_block'] == 1) {
                    $circleid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.circle_id'), 'conditions' => array('division_id' => array($div))));
                    $divdata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.id', 'circle.circle_name_en'), 'conditions' => array('id' => $circleid)));
                } else {
                    $ulbid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.ulb_type_id'), 'conditions' => array('division_id' => array($div))));
                    $divdata = ClassRegistry::init('corporationclass')->find('list', array('fields' => array('corporationclass.ulb_type_id', 'corporationclass.class_description'), 'conditions' => array('ulb_type_id' => $ulbid)));
                }
//                pr($divdata);
                echo json_encode($divdata);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getsubdiv() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            $token = $this->Session->read('Selectedtoken');
            //  pr($data);
            // exit;
            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                if (is_numeric($token)) {
                    $taluka = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.subdivision_id', 'taluka.subdivision_id'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                    )));

                    $distdata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'], 'subdivision_id' => $taluka)));
                } else {
                    $distdata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                    //   pr($distdata);    exit;
                }
//                

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['subdiv'] = $distdata;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($distdata);
                exit;
            }
            //pr($data);exit;
        } catch (Exception $e) {
            //pr($e->getMessage());exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function division_change_event() {
        try {
            $this->loadModel('conf_reg_bool_info');
            $this->loadModel('District');


            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $lang = $this->Session->read("sess_langauge");
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            if (isset($data['division_id'])) {
                $division_id = $data['division_id'];
                if (is_numeric($token)) {
                    $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                    if (!empty($gentalukaconf)) {
                        $json2array['districtnname'] = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang),
                            'joins' => array(
                                array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'District.district_id=link.district_id')),
                            ),
                            'conditions' => array('District.division_id' => $division_id), 'order' => 'district_name_' . $lang));
                    } else {
                        $json2array['districtnname'] = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang),
                            'joins' => array(
                                array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'District.district_id=link.district_id')),
                            ),
                            'conditions' => array('District.division_id' => $division_id), 'order' => 'district_name_' . $lang));
                    }
                } else {
                    $json2array['districtnname'] = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('District.division_id' => $division_id), 'order' => 'district_name_' . $lang));
                }


                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($json2array['districtnname']);
                exit;
            }
        } catch (Exception $ex) {
            
        }
    }

    public function subdivision_change_event() {

        try {
            $token = $this->Session->read('Selectedtoken');
            $data = $this->request->data;
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['subdivision_id'])) {
                $subdivision_id = $data['subdivision_id'];
                if (is_numeric($token)) {
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                        'joins' => array(
                            array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'type' => 'INNER', 'conditions' => array('gen.token_no' => $token)),
                            array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'type' => 'INNER', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                        ),
                        'conditions' => array('taluka.subdivision_id' => $subdivision_id)
                            )
                    );
                } else {
                    $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $subdivision_id)));
                }

                $result_array = array('subdiv' => NULL, 'taluka' => $talukalist, 'circle' => NULL, 'corp' => NULL, 'village' => NULL);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['taluka'] = $talukalist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $ex) {
            
        }
    }

    public function district_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
//            $this->check_csrf_token_withoutset($data['csrftoken']); 
            $token = $this->Session->read('Selectedtoken');
            $this->loadModel('damblkdpnd');
            $this->loadModel('conf_reg_bool_info');
            $stateid = $this->Auth->User("state_id");
            if (isset($data['dist'])) {
                $dist = $data['dist'];
                if (is_numeric($token)) {
                    if (is_numeric(@$data['subdivision_id'])) {
                        $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                        if (!empty($gentalukaconf)) {
                            $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                                ),
                                'conditions' => array('taluka.subdivision_id' => @$data['subdivision_id'])
                                    )
                            );
                        } else {
                            $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'taluka.taluka_id=link.taluka_id')),
                                ),
                                'conditions' => array('taluka.subdivision_id' => @$data['subdivision_id'])
                                    )
                            );
                        }
                    } else {
                        $gentalukaconf = $this->conf_reg_bool_info->find('all', array('conditions' => array('reginfo_id' => 102, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
                        if (!empty($gentalukaconf)) {
                            $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'taluka.taluka_id=link.taluka_id')),
                                ),
                                    // 'conditions' => array('taluka.subdivision_id' => @$data['subdivision_id'])
                                    )
                            );
                        } else {
                            $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'taluka.taluka_id=link.taluka_id')),
                                ),
                                    // 'conditions' => array('taluka.subdivision_id' => @$data['subdivision_id'])
                                    )
                            );
                        }
                    }
                } else {
                    if (is_numeric(@$data['subdivision_id'])) {
                        $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'), 'conditions' => array('district_id' => $dist, 'subdivision_id' => @$data['subdivision_id'])));
                    } else {
                        $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'), 'conditions' => array('district_id' => $dist)));
                    }
                }

                $result_array = array('subdiv' => NULL, 'taluka' => $talukalist, 'circle' => NULL, 'corp' => NULL, 'village' => NULL);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['taluka'] = $talukalist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
//            pr($e);
//            exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//-----------------by Shrishail 29-June-2017-----------------------------------------
    function taluka_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
//            $this->check_csrf_token_withoutset($data['csrftoken']);  
            $this->loadModel('damblkdpnd');
            $this->loadModel('valuationzone');
            $this->loadModel('VillageMapping');
            $token = $this->Session->read('Selectedtoken');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['tal']) and is_numeric($data['tal'])) {
                $tal = @$data['tal'];
                $dist = @$data['dist'];
                $landtype = @$data['landtype'];
                $corp = @$data['corp'];
                $finyear_id = @$data['finyear'];

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $cond = array();
                if (is_numeric($corp)) {
                    array_push($cond, array('damblkdpnd.corp_id' => $corp));
                }
                if (is_numeric($tal)) {
                    array_push($cond, array('damblkdpnd.taluka_id' => $tal));
                }
                if (is_numeric($landtype)) {
                    array_push($cond, array('damblkdpnd.developed_land_types_id' => $landtype));
                }

                if (isset($finyear_id) && is_numeric($finyear_id)) {
                    array_push($cond, array('from_finyear_id<=' . $finyear_id, 'OR' => array('to_finyear_id>=' . $finyear_id, 'to_finyear_id is NULL')));
//pr($cond);        
                    if (is_numeric($token)) {
                        $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang),
                            'joins' => array(
                                array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'damblkdpnd.village_id=link.village_id')),
                            ),
                            'conditions' => $cond,
                            'order' => array('damblkdpnd.village_name_' . $lang . ' ASC  ')));

                        $circledata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                    } else {
                        $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang), 'conditions' => $cond, 'order' => array('damblkdpnd.village_name_' . $lang . ' ASC  ')));
                        $circledata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                    }

                    $result_array = array('village' => $villagelist, 'circle' => $circledata);
                    $json2array['circle'] = $circledata;
                    $json2array['village'] = $villagelist;

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));
                    echo json_encode($result_array);
                    exit;
                }
            }
        } catch (Exception $e) {
//            pr($e);
//            exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function circle_change_event() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('valuationzone');
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");

            if (isset($this->request->data['circle_id']) and is_numeric($this->request->data['circle_id'])) {
                $circle_id = $this->request->data['circle_id'];
                $cond = array('circle_id' => $circle_id);
                if (isset($this->request->data['developed_land_types_id']) and is_numeric($this->request->data['developed_land_types_id'])) {
                    //developed_land_types_id
                    $cond['developed_land_types_id'] = $this->request->data['developed_land_types_id'];
                }




                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_' . $lang), 'conditions' => $cond));

                $result_array = array('village' => $villagelist);
                $json2array['village'] = $villagelist;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
            exit;
            //$this->redirect(array('action' => 'error404'));
        }
    }

    public function get_corp_list() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;

//            $this->check_csrf_token_withoutset($data['csrftoken']); 

            $this->loadModel('damblkdpnd');
            $this->loadModel('valuationzone');
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $token = $this->Session->read('Selectedtoken');
            if (is_numeric($token)) {

                if (isset($data['taluka']) and is_numeric($data['taluka'])) {
                    $taluka = $data['taluka'];
                    $landtype = @$data['landtype'];
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")),
                        'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'damblkdpnd.taluka_id=link.taluka_id')),
                                ),
                                'conditions' => array('damblkdpnd.taluka_id' => array($taluka)))))
                    ));
                    $result_array = array('corp' => $corplist);
                    $json2array['corp'] = $corplist;

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));

                    echo json_encode($result_array);
                    exit;
                } else if (isset($data['district']) and is_numeric($data['district'])) {
                    $district = $data['district'];

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")),
                        'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'link.taluka_id=gen.taluka_id', 'damblkdpnd.taluka_id=link.taluka_id')),
                                ),
                                'conditions' => array('damblkdpnd.district_id' => array($district)))))));
                    $result_array = array('corp' => $corplist);
                    $json2array['corp'] = $corplist;

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));

                    echo json_encode($result_array);
                    exit;
                }
            } else {

                if (isset($data['taluka']) and is_numeric($data['taluka'])) {
                    $taluka = $data['taluka'];
                    $landtype = @$data['landtype'];
                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('taluka_id' => array($taluka)))))));
                    $result_array = array('corp' => $corplist);
                    $json2array['corp'] = $corplist;

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));

                    echo json_encode($result_array);
                    exit;
                } else if (isset($data['district']) and is_numeric($data['district'])) {
                    $district = $data['district'];

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('district_id' => array($district)))))));
                    $result_array = array('corp' => $corplist);
                    $json2array['corp'] = $corplist;

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));

                    echo json_encode($result_array);
                    exit;
                }
            }
        } catch (Exception $e) {
            pr($e->getMessage());
            // $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//------------------------by Shrishail -29-June-2017-----------------------------------------
    public function corp_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
//            $this->check_csrf_token_withoutset($data['csrftoken']);
            $lang = $this->Session->read("sess_langauge");
            $this->loadModel('damblkdpnd');
            $this->loadModel('VillageMapping');
            $token = $this->Session->read('Selectedtoken');
            $stateid = $this->Auth->User("state_id");
            if (isset($data['corp'])) {
                $corp = @$data['corp'];
                $dist = @$data['dist'];
                $tal = @$data['tal'];
                $finyear_id = @$data['finyear'];
                $landtype = @$data['landtype'];

                $villagelist = array();
                if (is_numeric($finyear_id)) {
                    if (is_numeric($token)) {
                        if (is_numeric($tal) && !is_numeric($corp)) {
                            $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'damblkdpnd.village_id=link.village_id')),
                                ),
                                'conditions' => array('damblkdpnd.district_id' => $dist, 'damblkdpnd.taluka_id' => $tal, 'developed_land_types_id' => $landtype, 'from_finyear_id<=' . $finyear_id, 'OR' => array('to_finyear_id>=' . $finyear_id, 'to_finyear_id is NULL')), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                        } else if (is_numeric($corp)) {
                            $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang),
                                'joins' => array(
                                    array('table' => 'ngdrstab_trn_generalinformation', 'alias' => 'gen', 'conditions' => array('gen.token_no' => $token)),
                                    array('table' => 'ngdrstab_trn_office_village_linking', 'alias' => 'link', 'conditions' => array('link.office_id=gen.office_id', 'damblkdpnd.village_id=link.village_id')),
                                ),
                                'conditions' => array('damblkdpnd.district_id' => $dist, 'damblkdpnd.corp_id' => $corp, 'developed_land_types_id' => $landtype, 'from_finyear_id<=' . $finyear_id, 'OR' => array('to_finyear_id>=' . $finyear_id, 'to_finyear_id is NULL')), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                        }
                    } else {
                        if (is_numeric($tal) && !is_numeric($corp)) {
                            $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang), 'conditions' => array('district_id' => $dist, 'taluka_id' => $tal, 'developed_land_types_id' => $landtype, 'from_finyear_id<=' . $finyear_id, 'OR' => array('to_finyear_id>=' . $finyear_id, 'to_finyear_id is NULL')), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                        } else if (is_numeric($corp)) {
                            $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang), 'conditions' => array('district_id' => $dist, 'corp_id' => $corp, 'developed_land_types_id' => $landtype, 'from_finyear_id<=' . $finyear_id, 'OR' => array('to_finyear_id>=' . $finyear_id, 'to_finyear_id is NULL')), 'order' => array('damblkdpnd.village_name_' . $lang => 'ASC')));
                        }
                    }
                }
                $result_array = array('village' => $villagelist);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['village'] = $villagelist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function village_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $emptyarr = array();
            if (isset($data['village_id'])) {
                $villageid = $data['village_id'];
                $level1id = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level1_id'), 'conditions' => array('village_id' => $villageid)));
                $level1 = ClassRegistry::init('Levels_1_property')->find('list', array('fields' => array('Levels_1_property.level_1_id', 'Levels_1_property.level_1_desc_' . $lang), 'conditions' => array('village_id' => $villageid)));

                $result_array = array('data1' => NULL,
                    'data2' => $level1);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['level1'] = $level1;
                $json2array['level1list'] = $emptyarr;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function village_change_event_old() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $emptyarr = array();
            if (isset($data['village_id'])) {
                $villageid = $data['village_id'];
                $level1id = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level1_id'), 'conditions' => array('village_id' => $villageid)));
                $level1 = ClassRegistry::init('Levels_1_property')->find('list', array('fields' => array('Levels_1_property.level_1_id', 'Levels_1_property.level_1_desc_' . $lang), 'conditions' => array('level_1_id' => $level1id)));

                $result_array = array('data1' => NULL,
                    'data2' => $level1);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['level1'] = $level1;
                $json2array['level1list'] = $emptyarr;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    /*    public function village_change_event() {
      try {
      $this->loadModel('damblkdpnd');
      $stateid = $this->Auth->User("state_id");
      $lang = $this->Session->read("sess_langauge");
      if (isset($_GET['landtype'])) {
      $villageid = $_GET['landtype'];
      $landtypelist = ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('Developedlandtype.id', 'Developedlandtype.developed_land_types_desc_' . $lang), 'conditions' => array('id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.developed_land_types_id'), 'conditions' => array('village_id' => $villageid))))));

      $result_array = array('landtype' => $landtypelist);

      $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
      $json = $file->read(true, 'r');
      $json2array = json_decode($json, TRUE);
      $json2array['landtype'] = $landtypelist;
      $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
      $file->write(json_encode($json2array));

      echo json_encode($result_array);
      exit;
      } else {
      header('Location:../cterror.html');
      exit;
      }
      } catch (Exception $e) {
      //pr($e);
      exit;
      $this->redirect(array('action' => 'error404'));
      }
      }
     */

    public function land_change_event() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('valuationzone');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($_POST['landtype']) && isset($_POST['tal'])) {
                $landtype = $_POST['landtype'];
                $tal = $_POST['tal'];

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                if ($landtype === '1') {
                    $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('taluka_id' => array($tal)))))));
                    $result_array = array('corp' => $corplist);
                    $json2array['corp'] = $corplist;
                } else if ($landtype === '2') {
                    //  $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('taluka_id' => array($tal)))))));

                    $corplist = ClassRegistry::init('valuationzone')->find('list', array('fields' => array('valutation_zone_id', 'valuation_zone_desc_' . $this->Session->read("sess_langauge")), 'conditions' => array('developed_land_types_id' => $landtype)));

                    $result_array = array('corp' => $corplist);
                    $json2array['corp'] = $corplist;
                }

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gettalukaname() {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($_POST['subdiv'])) {
                $subdivid = $_POST['subdiv'];
                $configure = $this->damblkdpnd->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", atray($stateid));
                if ($configure[0][0]['is_taluka'] == 1) {
                    $talukaid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.taluka_id'), 'conditions' => array('subdivision_id' => array($subdivid))));
                    $subdivdata = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.id', 'taluka.taluka_name_en'), 'conditions' => array('id' => $talukaid)));
                } else if ($configure[0][0]['is_taluka'] == 0 && $configure[0][0]['is_block'] == 1) {
                    $circleid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.circle_id'), 'conditions' => array('subdivision_id' => array($subdivid))));
                    $subdivdata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.id', 'circle.circle_name_en'), 'conditions' => array('id' => $circleid)));
                } else {
                    $ulbid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.ulb_type_id'), 'conditions' => array('subdivision_id' => array($subdivid))));
                    $subdivdata = ClassRegistry::init('corporationclass')->find('list', array('fields' => array('corporationclass.ulb_type_id', 'corporationclass.class_description'), 'conditions' => array('ulb_type_id' => $ulbid)));
                }
//                pr($talukaname);exit; 
                echo json_encode($subdivdata);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getcircle() {
        try {

            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($_GET['tal'])) {
                $taluka = $_GET['tal'];
                $configure = $this->damblkdpnd->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));
                if ($configure[0][0]['is_block'] == 1) {
                    $circleid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.circle_id'), 'conditions' => array('taluka_id' => array($taluka))));
                    $talukadata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.id', 'circle.circle_name_en'), 'conditions' => array('id' => $circleid)));
                } else {
                    $ulbid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.ulb_type_id'), 'conditions' => array('taluka_id' => array($taluka))));
                    $talukadata = ClassRegistry::init('corporationclass')->find('list', array('fields' => array('corporationclass.ulb_type_id', 'corporationclass.class_description'), 'conditions' => array('ulb_type_id' => $ulbid)));
                }
                echo json_encode($talukadata);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getulb() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($_GET['ulb'])) {
                $circle = $_GET['ulb'];
                $ulbid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.ulb_type_id'), 'conditions' => array('circle_id' => array($circle))));
                $ulbname = ClassRegistry::init('corporationclass')->find('list', array('fields' => array('corporationclass.ulb_type_id', 'corporationclass.class_description'), 'conditions' => array('ulb_type_id' => $ulbid)));
                echo json_encode($ulbname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getcorp() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($_GET['ulb'])) {
                $ulb = $_GET['ulb'];
                $ulbid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('ulb_type_id' => array($ulb))));
                $corpname = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_en'), 'conditions' => array('id' => $ulbid)));
                echo json_encode($corpname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getvillage() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($_GET['corp'])) {
                $ulb = $_GET['corp'];
//$ulbid = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id'), 'conditions' => array('ulb_type_id' => array($ulb))));
//$corp_id=
                $villagename = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.id', 'damblkdpnd.village_name_en'), 'conditions' => array('corp_id' => $ulb)));
                echo json_encode($villagename);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getlandtype() {
        try {

            $lang = 'en';
            if ($this->Session->read("sess_langauge") != 'en') {
                $lang = 'll';
            }
            $this->loadModel('Developedlandtype');
            if (isset($_GET['landtype'])) {
                $villageid = $_GET['landtype'];
                $landid = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.developed_land_types_id'), 'conditions' => array('village_id' => $villageid)));
                $landtypename = ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('Developedlandtype.id', 'Developedlandtype.developed_land_types_desc_' . $lang), 'conditions' => array('id' => $landid)));

                echo json_encode($landtypename);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getvillagename() {
        try {
//            echo 'hi';
            $lang = 'en';
            if ($this->Session->read("sess_langauge") != 'en') {
                $lang = 'll';
            }
            $this->loadModel('District');
            $this->loadModel('VillageMapping');

            if (isset($_GET['villagename'])) {
                $villagename = $_GET['villagename'];
//                pr($villagename);exit;
//$villageid = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id'), 'conditions' => array('state_id' => $villagename)));
//pr($lang);exit; 
                $vname = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('state_id' => $villagename)));
//pr($vname );exit; 
                echo json_encode($vname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function Level1_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('Levels_1_property');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['level1list']) && isset($data['village_id'])) {
                $level1id = $data['level1list'];
//$level1list = ClassRegistry::init('Level1')->find('list', array('fields' => array('Level1.id', 'Level1.list_1_desc_' . $lang), 'conditions' => array('id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id))))));
                $level1list = ClassRegistry::init('loc_level_1_prop_list')->find('list', array('fields' => array('prop_level1_list_id', 'list_1_desc_' . $lang), 'conditions' => array('level_1_id' => $level1id)));
                $level1listflag = 0;
                if ($level1list != NULL) {
                    $level1listflag = 1;
                }
//pr($level1list);
//$level2descid = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id)));
                $level2 = ClassRegistry::init('Level2_property')->find('list', array('fields' => array('Level2_property.level_2_id', 'Level2_property.level_2_desc_' . $lang), 'conditions' => array('level_2_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level2_id'), 'conditions' => array('level1_id' => $level1id, 'village_id' => $data['village_id']))))));
                $level2flag = 0;
                if ($level2 != NULL) {
                    $level2flag = 1;
                }
                $result_array = array('data1' => $level1list,
                    'data2' => $level2,
                    'level1listflag' => $level1listflag,
                    'level2flag' => $level2flag);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['level1list'] = $level1list;
                $json2array['level2'] = $level2;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function Level1_change_event_OLD() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('Levels_1_property');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['level1list']) && isset($data['village_id'])) {
                $level1id = $data['level1list'];
//$level1list = ClassRegistry::init('Level1')->find('list', array('fields' => array('Level1.id', 'Level1.list_1_desc_' . $lang), 'conditions' => array('id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id))))));
                $level1list = ClassRegistry::init('Level1')->find('list', array('fields' => array('Level1.prop_level1_list_id', 'Level1.list_1_desc_' . $lang), 'conditions' => array('prop_level1_list_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('level1_id' => $level1id, 'village_id' => $data['village_id']))))));
                $level1listflag = 0;
                if ($level1list != NULL) {
                    $level1listflag = 1;
                }
//pr($level1list);
//$level2descid = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id)));
                $level2 = ClassRegistry::init('Level2_property')->find('list', array('fields' => array('Level2_property.level_2_id', 'Level2_property.level_2_desc_' . $lang), 'conditions' => array('level_2_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level2_id'), 'conditions' => array('level1_id' => $level1id, 'village_id' => $data['village_id']))))));
                $level2flag = 0;
                if ($level2 != NULL) {
                    $level2flag = 1;
                }
                $result_array = array('data1' => $level1list,
                    'data2' => $level2,
                    'level1listflag' => $level1listflag,
                    'level2flag' => $level2flag);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['level1list'] = $level1list;
                $json2array['level2'] = $level2;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function Level2_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('Level2_property');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['level2list']) && isset($data['village_id'])) {
                $level2id = $data['level2list'];
//$level1list = ClassRegistry::init('Level1')->find('list', array('fields' => array('Level1.id', 'Level1.list_1_desc_' . $lang), 'conditions' => array('id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id))))));
                $level2list = ClassRegistry::init('Level2')->find('list', array('fields' => array('Level2.prop_level2_list_id', 'Level2.list_2_desc_' . $lang), 'conditions' => array('prop_level2_list_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level2_list_id'), 'conditions' => array('level2_id' => $level2id, 'village_id' => $data['village_id']))))));
                $level2listflag = 0;
                if ($level2list != NULL) {
                    $level2listflag = 1;
                }
//pr($level1list);
//$level2descid = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id)));
                $level3 = ClassRegistry::init('Level3_property')->find('list', array('fields' => array('Level3_property.level_3_id', 'Level3_property.level_3_desc_' . $lang), 'conditions' => array('level_3_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level3_id'), 'conditions' => array('level2_id' => $level2id, 'village_id' => $data['village_id']))))));
                $level3flag = 0;
                if ($level3 != NULL) {
                    $level3flag = 1;
                }
                $result_array = array('data1' => $level2list,
                    'data2' => $level3,
                    'level2listflag' => $level2listflag,
                    'level3flag' => $level3flag);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['level2list'] = $level2list;
                $json2array['level3'] = $level3;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function Level3_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('Level3_property');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['level3list']) && isset($data['village_id'])) {
                $level3id = $data['level3list'];
//$level1list = ClassRegistry::init('Level1')->find('list', array('fields' => array('Level1.id', 'Level1.list_1_desc_' . $lang), 'conditions' => array('id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id))))));
                $level3list = ClassRegistry::init('Level3')->find('list', array('fields' => array('Level3.prop_leve3_list_id', 'Level3.list_3_desc_' . $lang), 'conditions' => array('prop_leve3_list_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level3_list_id'), 'conditions' => array('level3_id' => $level3id, 'village_id' => $data['village_id']))))));
                $level3listflag = 0;
                if ($level3list != NULL) {
                    $level3listflag = 1;
                }
//pr($level1list);
//$level2descid = ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id)));
                $level4 = ClassRegistry::init('Level4_property')->find('list', array('fields' => array('Level4_property.level_4_id', 'Level4_property.level_4_desc_' . $lang), 'conditions' => array('level_4_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.level4_id'), 'conditions' => array('level3_id' => $level3id, 'village_id' => $data['village_id']))))));
                $level4flag = 0;
                if ($level4 != NULL) {
                    $level4flag = 1;
                }
                $result_array = array('data1' => $level3list,
                    'data2' => $level4,
                    'level3listflag' => $level3listflag,
                    'level4flag' => $level4flag);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['level3list'] = $level3list;
                $json2array['level4'] = $level4;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function Level4_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);


            $this->loadModel('Level4_property');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['level4list']) && isset($data['village_id'])) {
                $level4id = $data['level4list'];
//$level1list = ClassRegistry::init('Level1')->find('list', array('fields' => array('Level1.id', 'Level1.list_1_desc_' . $lang), 'conditions' => array('id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level1_list_id'), 'conditions' => array('village_id' => $level1id))))));
                $level4list = ClassRegistry::init('Level4')->find('list', array('fields' => array('Level4.prop_level4_list_id', 'Level4.list_4_desc_' . $lang), 'conditions' => array('prop_level4_list_id' => ClassRegistry::init('villagelevelmapping')->find('list', array('fields' => array('villagelevelmapping.prop_level4_list_id'), 'conditions' => array('level4_id' => $level4id, 'village_id' => $data['village_id']))))));


                $result_array = array('data1' => $level4list);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['level4list'] = $level4list;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function usagecategory_change_event() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('usage_category');
            $this->loadModel('usagelinkcategory');
            $this->loadModel('evalrule');
            $this->loadModel('subrule');
            $this->loadModel('usagelnk');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($data['usagecatid'])) { //$_GET['usagecatid'] is rule ID passed from View(propertyscreennew)
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $json2array['ruleid'] = explode(',', $data['usagecatid']);

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

//to Get Eval Condition and UsageItemList
                $options2['conditions'] = array('evalrule.evalrule_id' => $json2array['ruleid'], 'evalrule.evalrule_id is not null');
                $options2['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'left', 'conditions' => array('evalrule.output_item_id = itemlist.usage_param_id')), array('table' => 'ngdrstab_mst_usage_lnk_category', 'alias' => 'linkcat', 'type' => 'INNER', 'conditions' => array('evalrule.evalrule_id = linkcat.evalrule_id')), array('table' => 'ngdrstab_mst_usage_category', 'alias' => 'usagecat', 'type' => 'INNER', 'conditions' => array('linkcat.usage_main_catg_id = usagecat.usage_main_catg_id', 'linkcat.usage_sub_catg_id = usagecat.usage_sub_catg_id', 'linkcat.usage_sub_sub_catg_id = usagecat.usage_sub_sub_catg_id')));
                $options2['fields'] = array('DISTINCT itemlist.usage_param_desc_' . $this->Session->read("sess_langauge"), 'evalrule.*', 'linkcat.usage_main_catg_id', 'linkcat.usage_sub_catg_id', 'linkcat.usage_sub_sub_catg_id', 'usagecat.contsruction_type_flag', 'usagecat.depreciation_flag', 'usagecat.road_vicinity_flag', 'usagecat.user_defined_dependency1_flag', 'usagecat.user_defined_dependency2_flag', 'usagecat.is_boundary_applicable');
                $json2array['evalconditions'] = $this->evalrule->find('all', $options2);
                $json2array['evalconditions'] = $this->replace_operater_mainrule($json2array['evalconditions']);

                $options1['order'] = array('usagelinkcategory.evalrule_id' => 'ASC', 'usagelinkcategory.display_order' => 'ASC');
                $options1['conditions'] = array('usagelinkcategory.evalrule_id' => $json2array['ruleid'], 'usagelinkcategory.evalrule_id is not null');
                $options1['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_param_id = itemlist.usage_param_id')), array('table' => 'ngdrstab_mst_item_rate', 'alias' => 'itemrate', 'type' => 'left outer', 'conditions' => array('usagelinkcategory.usage_param_id=itemrate.usage_param_id')), array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'usage_main', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_main_catg_id = usage_main.usage_main_catg_id')), array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'usage_sub', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_sub_catg_id = usage_sub.usage_sub_catg_id')), array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'subsub', 'type' => 'INNER', 'conditions' => array('usagelinkcategory.usage_sub_sub_catg_id = subsub.usage_sub_sub_catg_id')));
                $options1['fields'] = array('DISTINCT usagelinkcategory.usage_param_id', 'usagelinkcategory.uasge_param_code', 'usagelinkcategory.evalrule_id', 'usagelinkcategory.display_order', 'usagelinkcategory.main_cat_id', 'usagelinkcategory.sub_cat_id', 'usagelinkcategory.sub_sub_cat_id', 'itemlist.usage_param_desc_en', 'itemlist.usage_param_desc_ll', 'itemlist.range_field_flag', 'itemlist.slab_rate_flag', 'itemlist.rate_table_flag', 'itemlist.area_field_flag', 'itemlist.is_list_field_flag', 'itemlist.unit_cat_id', 'itemlist.usage_param_type_id', 'usagelinkcategory.item_rate_flag', 'itemlist.item_rate_flag', 'itemlist.single_unit_flag', 'itemlist.area_type_flag', 'itemlist.districtwise_unit_change_flag', 'itemlist.unit_id', 'itemlist.is_input_hidden', 'itemlist.is_string', 'itemlist.output_item_id', 'CASE WHEN itemrate.item_rate IS NULL then 0 else itemrate.item_rate END AS item_rate', 'usage_main.usage_main_catg_desc_' . $lang, 'usage_sub.usage_sub_catg_desc_' . $lang, 'subsub.usage_sub_sub_catg_desc_' . $lang);

                $json2array['usageitemlist'] = $this->usagelinkcategory->find('all', $options1);

//pr($json2array['usageitemlist']);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                $hfconstructionflag = 'N';
                $hfdepreciationflag = 'N';
                $hfroadvicinityflag = 'N';
                $hfuserdependency1flag = 'N';
                $hfuserdependency2flag = 'N';
                $hfboundaryflag = 'N';
                //$rateflag='Y';
                foreach ($json2array['evalconditions'] as $frm) {
                    // $this->
                    // pr($frm);
                    if ($frm['usagecat']['contsruction_type_flag'] === 'Y') {
                        $hfconstructionflag = 'Y';
                    }
                    if ($frm['usagecat']['depreciation_flag'] === 'Y') {
                        $hfdepreciationflag = 'Y';
                    }
                    if ($frm['usagecat']['road_vicinity_flag'] === 'Y') {
                        $hfroadvicinityflag = 'Y';
                    }
                    if ($frm['usagecat']['user_defined_dependency1_flag'] === 'Y') {
                        $hfuserdependency1flag = 'Y';
                    }
                    if ($frm['usagecat']['user_defined_dependency2_flag'] === 'Y') {
                        $hfuserdependency2flag = 'Y';
                    }
                    if ($frm['usagecat']['is_boundary_applicable'] === 'Y') {
                        $hfboundaryflag = 'Y';
                    }
                }

                /* ------------------------------------ */
                $subruleid = array();
                foreach ($json2array['evalconditions'] as $evalconditions) {
                    if ($evalconditions['evalrule']['subrule_flag'] == 'Y') {

                        $conditions1['evalrule_id'] = $evalconditions['evalrule']['evalrule_id'];
                        $subrl1 = $this->subrule->find('all', array('conditions' => $conditions1));
                        if ($subrl1) {
                            foreach ($subrl1 as $subrl11) {
                                array_push($subruleid, $subrl11['subrule']['subrule_id']);
                            }
                        }
                    }
                }
//PR($subruleid);EXIT;
//Sub Rule Flag Checking
                if ($subruleid != NULL) {
                    $options3['order'] = array('subrule.subrule_id');
                    $options3['conditions'] = array('subrule_id' => $subruleid, 'evalrule_id is not null');
                    $options3['joins'] = array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'itemlist', 'type' => 'INNER', 'conditions' => array('subrule.output_item_id = itemlist.usage_param_id')));
                    $options3['fields'] = array('subrule.*', 'itemlist.usage_param_desc_' . $this->Session->read("sess_langauge"));
                    $json2array['subruleconditions'] = $this->subrule->find('all', $options3);

                    $json2array['subruleconditions'] = $this->replace_operater_subrule($json2array['subruleconditions']);

                    $this->set('subruleconditions', $json2array['subruleconditions']);
                }
//pr($json2array);

                /* -------------------------------- */

                $result_array = array('hfconstructionflag' => $hfconstructionflag,
                    'hfdepreciationflag' => $hfdepreciationflag,
                    'hfroadvicinityflag' => $hfroadvicinityflag,
                    'hfuserdependency1flag' => $hfuserdependency1flag,
                    'hfuserdependency2flag' => $hfuserdependency2flag,
                    'hfboundaryflag' => $hfboundaryflag);

                $json2array['totaldependency'] = $result_array;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));


                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
//            pr($e);
            // $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function checkrate_exist() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('User');
            $this->loadModel('VillageMapping');
            $this->loadModel('RateSearch');
            $this->loadModel('rate');
            $this->loadModel('usage_category');

            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);
            $lang = $this->Session->read("sess_langauge");

            if (is_numeric($data['village']) && is_numeric($data['village'])) {
                $talid = @$data['taluka'];
                $district = @$data['district'];
                $villageid = @$data['village'];
                $survey_no = @$data['survey_no'];
                $usagecatlist = @$data['usagecatlist'];
                $language = $this->Session->read("sess_langauge");
                $this->set('lang', $language);

                $allrates = array();
                $village = $this->VillageMapping->find("all", array('conditions' => array('village_id' => $villageid)));

                if (!empty($village)) {
                    $landtype_id = $village[0]['VillageMapping']['developed_land_types_id'];
                    $valutation_zone_id = $village[0]['VillageMapping']['valutation_zone_id'];
                    $ulb_type_id = $village[0]['VillageMapping']['ulb_type_id'];
                    $rate_selection_rule1 = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $landtype_id, 'ready_reckoner_rate_flag' => 'Y')));
                    $options['rate.ready_reckoner_rate_flag'] = 'Y';
                    //$options['rate.developed_land_types_id'] = $landtype_id;

                    $allrates = array();
                    foreach ($rate_selection_rule1 as $searchrule) {
                        if ($searchrule['RateSearch']['village_id'] == 'Y' && isset($data['village']) && is_numeric($data['village'])) {
                            $options['rate.village_id'] = $data['village'];
                            if (isset($data['lavel1']) && is_numeric($data['lavel1'])) {
                                $options['rate.level1_id'] = $data['lavel1'];
                            }
                            if (isset($data['lavel1_list']) && is_numeric($data['lavel1_list'])) {
                                $options['rate.level1_list_id'] = $data['lavel1_list'];
                            }
                        }
                        if ($searchrule['RateSearch']['finyear_id'] == 'Y' && isset($data['finyear_id']) && is_numeric($data['finyear_id'])) {
                            $options['rate.finyear_id'] = $data['finyear_id'];
                        }
                        if ($searchrule['RateSearch']['taluka_id'] == 'Y' && isset($data['taluka']) && is_numeric($data['taluka'])) {
                            $options['rate.taluka_id'] = $data['taluka'];
                        }
                        if ($searchrule['RateSearch']['district_id'] == 'Y' && isset($data['district']) && is_numeric($data['district'])) {
                            $options['rate.district_id'] = $data['district'];
                        }
                        if ($searchrule['RateSearch']['valutation_zone_id'] == 'Y' && isset($valutation_zone_id) && is_numeric($valutation_zone_id)) {
                            $options['rate.valutation_zone_id'] = $valutation_zone_id;
                        }
                        if ($searchrule['RateSearch']['ulb_type_id'] == 'Y' && isset($ulb_type_id) && is_numeric($ulb_type_id)) {
                            $options['rate.ulb_type_id'] = $ulb_type_id;
                        }

                        $options1['conditions'] = $options;
                        $options1['joins'] = array(
                            array('table' => 'ngdrstab_mst_unit', 'alias' => 'unit', 'type' => 'LEFT', 'conditions' => array('unit.unit_id = rate.prop_unit')),
                            array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'alias' => 'location1', 'type' => 'LEFT', 'conditions' => array('location1.prop_level1_list_id=rate.level1_list_id')),
                            array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'usage_main', 'type' => 'LEFT', 'conditions' => array('rate.usage_main_catg_id = usage_main.usage_main_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'usage_sub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_catg_id = usage_sub.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'subsub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_sub_catg_id = subsub.usage_sub_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_valuation_zone', 'alias' => 'zone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_zone_id = zone.valutation_zone_id')),
                            array('table' => 'ngdrstab_mst_valuation_subzone', 'alias' => 'subzone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_subzone_id = subzone.valutation_subzone_id', 'subzone.usage_main_catg_id=rate.usage_main_catg_id', 'subzone.usage_sub_catg_id=rate.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_construction_type', 'alias' => 'ctype', 'type' => 'LEFT', 'conditions' => array('ctype.construction_type_id = rate.construction_type_id')),
                            array('table' => 'ngdrstab_conf_admblock_local_governingbody', 'alias' => 'ulbclass', 'type' => 'LEFT', 'conditions' => array('ulbclass.ulb_type_id = rate.ulb_type_id')));


                        $options1['fields'] = array('rate.prop_rate', 'unit.unit_desc_' . $lang, 'usage_main.usage_main_catg_desc_' . $lang, 'usage_sub.usage_sub_catg_desc_' . $lang, 'subsub.usage_sub_sub_catg_desc_' . $lang, 'location1.list_1_desc_' . $lang, 'zone.valuation_zone_desc_' . $lang, 'subzone.from_desc_' . $lang, 'subzone.to_desc_' . $lang, 'ulbclass.class_description_' . $lang, 'ctype.construction_type_desc_' . $lang, 'rate.usage_main_catg_id', 'rate.usage_sub_catg_id');

                        $rate = $this->rate->find('all', $options1);
                        $allrates[$searchrule['RateSearch']['search_id']] = $rate;
                        break;
                    }
                    $options = array();
                    $options1 = array();

                    $options['rate.ready_reckoner_rate_flag'] = 'N';
                    //$options['rate.developed_land_types_id'] = $landtype_id;
                    $rate_selection_rule2 = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $landtype_id, 'ready_reckoner_rate_flag' => 'N')));

                    foreach ($rate_selection_rule2 as $searchrule) {
                        if ($searchrule['RateSearch']['village_id'] == 'Y' && isset($data['village']) && is_numeric($data['village'])) {
                            $options['rate.village_id'] = $data['village'];
                            if (isset($data['lavel1']) && is_numeric($data['lavel1'])) {
                                $options['rate.level1_id'] = $data['lavel1'];
                            }
                            if (isset($data['lavel1_list']) && is_numeric($data['lavel1_list'])) {
                                $options['rate.level1_list_id'] = $data['lavel1_list'];
                            }
                        }
                        if ($searchrule['RateSearch']['finyear_id'] == 'Y' && isset($data['finyear_id']) && is_numeric($data['finyear_id'])) {
                            $options['rate.finyear_id'] = $data['finyear_id'];
                        }
                        if ($searchrule['RateSearch']['taluka_id'] == 'Y' && isset($data['taluka']) && is_numeric($data['taluka'])) {
                            $options['rate.taluka_id'] = $data['taluka'];
                        }
                        if ($searchrule['RateSearch']['district_id'] == 'Y' && isset($data['district']) && is_numeric($data['district'])) {
                            $options['rate.district_id'] = $data['district'];
                        }
                        if ($searchrule['RateSearch']['valutation_zone_id'] == 'Y' && isset($valutation_zone_id) && is_numeric($valutation_zone_id)) {
                            $options['rate.valutation_zone_id'] = $valutation_zone_id;
                        }
                        if ($searchrule['RateSearch']['ulb_type_id'] == 'Y' && isset($ulb_type_id) && is_numeric($ulb_type_id)) {
                            $options['rate.ulb_type_id'] = $ulb_type_id;
                        }

                        $options1['conditions'] = $options;
                        $options1['joins'] = array(
                            array('table' => 'ngdrstab_mst_unit', 'alias' => 'unit', 'type' => 'LEFT', 'conditions' => array('unit.unit_id = rate.prop_unit')),
                            array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'alias' => 'location1', 'type' => 'LEFT', 'conditions' => array('location1.prop_level1_list_id=rate.level1_list_id')),
                            array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'usage_main', 'type' => 'LEFT', 'conditions' => array('rate.usage_main_catg_id = usage_main.usage_main_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'usage_sub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_catg_id = usage_sub.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'subsub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_sub_catg_id = subsub.usage_sub_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_valuation_zone', 'alias' => 'zone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_zone_id = zone.valutation_zone_id')),
                            array('table' => 'ngdrstab_mst_valuation_subzone', 'alias' => 'subzone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_subzone_id = subzone.valutation_subzone_id', 'subzone.usage_main_catg_id=rate.usage_main_catg_id', 'subzone.usage_sub_catg_id=rate.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_construction_type', 'alias' => 'ctype', 'type' => 'LEFT', 'conditions' => array('ctype.construction_type_id = rate.construction_type_id')),
                            array('table' => 'ngdrstab_conf_admblock_local_governingbody', 'alias' => 'ulbclass', 'type' => 'LEFT', 'conditions' => array('ulbclass.ulb_type_id = rate.ulb_type_id')));
                        $options1['fields'] = array('rate.prop_rate', 'unit. unit_desc_' . $lang, 'usage_main.usage_main_catg_desc_' . $lang, 'usage_sub.usage_sub_catg_desc_' . $lang, 'subsub.usage_sub_sub_catg_desc_' . $lang, 'location1.list_1_desc_' . $lang, 'zone.valuation_zone_desc_' . $lang, 'subzone.from_desc_' . $lang, 'subzone.to_desc_' . $lang, 'ulbclass.class_description_' . $lang, 'ctype.construction_type_desc_' . $lang, 'rate.usage_main_catg_id', 'rate.usage_sub_catg_id');

                        $rate = $this->rate->find('all', $options1);

                        $allrates[$searchrule['RateSearch']['search_id']] = $rate;
                        break;
                    }
                }


                $usagearr = explode(",", $usagecatlist);
                $usagecat = $this->usage_category->find("all", array(
                    'fields' => array('usage_main_catg_id', 'usage_sub_catg_id', 'rule.skip_val_flag'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_evalrule_new', 'alias' => 'rule', 'conditions' => array('rule.evalrule_id=usage_category.evalrule_id')),
                    ),
                    'conditions' => array('usage_category.evalrule_id' => $usagearr)));
                $ratecheckflag = 'success';
                foreach ($usagecat as $singlerule) {

                    if ($singlerule['rule']['skip_val_flag'] == 'N') {
                        $ratematch = 0;
                    } else {
                        $ratematch = 1;
                    }
                    foreach ($allrates as $singlerate) {
                        foreach ($singlerate as $singlerecord) {
                            if ($singlerecord['rate']['usage_main_catg_id'] == $singlerule['usage_category']['usage_main_catg_id'] && $singlerecord['rate']['usage_sub_catg_id'] == $singlerule['usage_category']['usage_sub_catg_id']) {
                                $ratematch = 1;
                            }
                        }
                    }
                    if ($ratematch == 0) {
                        $ratecheckflag = 'fail';
                    }
                }
                echo $ratecheckflag;
            }
        } catch (Exception $ex) {
            
        }
    }

    public function replace_operater_subrule($subruleconditions) {
        $subruleconditions_new = NULL;
        foreach ($subruleconditions as $key => $subrule) {
            $subruleconditions_new[$key]['itemlist'] = $subrule['itemlist'];
            foreach ($subrule['subrule'] as $key1 => $subrulefield) {
                $subrulefield = $this->replace_string_to_oprator($subrulefield);
                $subruleconditions_new[$key]['subrule'][$key1] = $subrulefield;
            }
        }
        return $subruleconditions_new;
    }

    public function replace_operater_mainrule($evalconditions) {
        $evalconditions_new = NULL;
        foreach ($evalconditions as $key => $rule) {
            $evalconditions_new[$key]['itemlist'] = $rule['itemlist'];
            $evalconditions_new[$key]['linkcat'] = $rule['linkcat'];
            $evalconditions_new[$key]['usagecat'] = $rule['usagecat'];
            foreach ($rule['evalrule'] as $key1 => $rulefield) {
                $rulefield = $this->replace_string_to_oprator($rulefield);
                $evalconditions_new[$key]['evalrule'][$key1] = $rulefield;
            }
        }
        return $evalconditions_new;
    }

    public function usage_filter() {

        $data = $this->request->data;
        $this->check_csrf_token_withoutset($data['csrftoken']);

        $this->loadModel("usagelinkcategory");
        $this->loadModel("evalrule");
        $this->loadModel("VillageMapping");
        $this->loadModel("EvalruleMapping");
        try {

            $options['display_flag'] = 'Y';
            if (isset($data['sub_cat_id']) and is_numeric($data['sub_cat_id'])) {
                $subcatid = $_POST['sub_cat_id'];
                $options['usage_sub_catg_id'] = $subcatid;
            }
            $mappingrules = array();
            if (isset($data['village_id']) and is_numeric($data['village_id'])) {

                $village_id = $this->request->data['village_id'];

                $villagedetails = ClassRegistry::init('VillageMapping')->find('all', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_en', 'VillageMapping.developed_land_types_id', 'VillageMapping.valutation_zone_id', 'VillageMapping.ulb_type_id', 'VillageMapping.district_id'), 'conditions' => array('village_id' => $village_id)));
                foreach ($villagedetails as $village) {
                    $LanTypeId = $village['VillageMapping']['developed_land_types_id'];

                    if ($LanTypeId == 1) {
                        $options['is_urban'] = 'Y';
                    } else
                    if ($LanTypeId == 2) {
                        $options['is_rural'] = 'Y';
                    } else
                    if ($LanTypeId == 3) {
                        $options['is_influence'] = 'Y';
                    }
                }
                $mappingrules = $this->EvalruleMapping->find('list', array('fields' => array('evalrule_id', 'evalrule_id'), 'conditions' => array('district_id' => $village['VillageMapping']['district_id'])));
            }

            $commonrules = $this->evalrule->find('list', array('fields' => array('evalrule_id', 'evalrule_id'), 'conditions' => array('location_dependency_flag' => 'N')));
            $ruleids = array_merge($commonrules, $mappingrules);
            $options['evalrule.evalrule_id IN'] = $ruleids;

            $this->set("usagecategory", $usagecategory = $this->evalrule->find('list', array('fields' => array('evalrule.evalrule_id', 'evalrule_desc_' . $this->Session->read("sess_langauge")), 'order' => 'evalrule.evalrule_id DESC', "joins" => array(
                    array(
                        "table" => "ngdrstab_mst_usage_lnk_category",
                        "alias" => "usage_lnk",
                        "type" => "LEFT",
                        "conditions" => array(
                            "evalrule.evalrule_id = usage_lnk.evalrule_id"
                        )
                    )), 'conditions' => $options)));


            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);

            $json2array['usagecategory'] = $usagecategory;

            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
        } catch (Exception $ex) {
            //$json2array=array('Out');
            // json_encode($json2array);
            //  exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getsurveynumbers() {
        try {

            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);

            $this->loadModel('Surveyno');
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $district = $data['district'];
            $taluka = $data['taluka'];
            $council = $data['council'];
            $village = $data['village'];
            $lavel1 = $data['lavel1'];
            $lavel1_list = $data['lavel1_list'];
            $lavel2 = $data['lavel2'];
            $lavel2_list = $data['lavel2_list'];
            $lavel3 = $data['lavel3'];
            $lavel3_list = $data['lavel3_list'];
            $lavel4 = $data['lavel4'];
            $lavel4_list = $data['lavel4_list'];

            $conditions['Surveyno.state_id'] = $stateid;
            if (is_numeric($district)) {
                $conditions['district_id'] = $district;
            }
            if (is_numeric($taluka)) {
                $conditions['taluka_id'] = $taluka;
            }
            if (is_numeric($council)) {
                $conditions['corp_id'] = $council;
            }
            if (is_numeric($village)) {
                $conditions['village_id'] = $village;
            }
            if (is_numeric($lavel1)) {
                $conditions['level1_id'] = $lavel1;
            }
            if (is_numeric($lavel1_list)) {
                $conditions['level1_list_id'] = $lavel1_list;
            }
            if (is_numeric($lavel2)) {
                $conditions['level2_id'] = $lavel2;
            }
            if (is_numeric($lavel2_list)) {
                $conditions['level2_list_id'] = $lavel2_list;
            }
            if (is_numeric($lavel3)) {
                $conditions['level3_id'] = $lavel3;
            }
            if (is_numeric($lavel3_list)) {
                $conditions['level3_list_id'] = $lavel3_list;
            }
            if (is_numeric($lavel4)) {
                $conditions['level4_id'] = $lavel4;
            }
            if (is_numeric($lavel4_list)) {
                $conditions['level4_list_id'] = $lavel4_list;
            } // pr($conditions);
            $this->set("results", $result = $this->Surveyno->find('all', array('fields' => array('Surveyno.survey_no', 'attribute.eri_attribute_name_en'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_attribute_parameter', 'alias' => 'attribute', 'conditions' => array('attribute.attribute_id=Surveyno.ri_attribute'))
                ),
                'conditions' => $conditions)));
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    public function vibhag_change_event() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            if (isset($_GET['zone_id']) && isset($_GET['tal']) && isset($_GET['land_type'])) {
                $zone_id = $_GET['zone_id'];
                $tal = $_GET['tal'];
                $land_type = $_GET['land_type'];

                if ($land_type == 2) {
                    $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_en'), 'conditions' => array('valutation_zone_id' => $zone_id, 'taluka_id' => $tal)));
                    $result_array = array('village' => $villagelist);
                }
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['village'] = $villagelist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
//            exit;
//            $this->redirect(array('action' => 'error404'));
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//madhuri code start
    function get_location() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);
            if (isset($data['survey_no']) && is_numeric($data['survey_no']) && isset($data['village_id']) && is_numeric($data['village_id'])) {

                $this->loadModel('Surveyno');
                $language = $this->Session->read("sess_langauge");

                $location = $this->Surveyno->get_location($data['survey_no'], $language, $data['village_id']);
                $a = array();

                for ($i = 0; $i < count($location); $i++) {
                    $a[trim($location[$i][0]['level1_list_id'])] = trim($location[$i][0]['list_1_desc_' . $language]);
                }

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['level1list'] = $a;


                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($a);
                exit;


                echo json_encode($a);

                exit;
            }
            exit;
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_zone() {
        try {
            $this->autoRender = FALSE;
            $data = $this->request->data;
            if (isset($data['csrftoken'])) {
                $this->check_csrf_token_withoutset($data['csrftoken']);
                if (isset($data['survey_no']) && is_numeric($data['survey_no']) && isset($data['village_id']) && is_numeric($data['village_id'])) {

                    $this->loadModel('Surveyno');
                    $language = $this->Session->read("sess_langauge");

                    $location = $this->Surveyno->get_zone($data['survey_no'], $language, $data['village_id']);
                    $a = array();

                    for ($i = 0; $i < count($location); $i++) {
                        $a[trim($location[$i][0]['level1_id'])] = trim($location[$i][0]['level_1_desc_' . $language]);
                    }



                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                    $json = $file->read(true, 'r');
                    $json2array = json_decode($json, TRUE);

                    $json2array['level1'] = $a;

                    $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                    $file->write(json_encode($json2array));

                    echo json_encode($a);

                    exit;
                }
            } else {
                exit;
            }
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function getallrates() {
        try {
            $this->loadModel('User');
            $this->loadModel('VillageMapping');
            $this->loadModel('RateSearch');
            $this->loadModel('rate');

            $data = $this->request->data;
            $this->check_csrf_token_withoutset($data['csrftoken']);
            $lang = $this->Session->read("sess_langauge");
            if (is_numeric($data['village']) && is_numeric($data['village'])) {
                $talid = $data['taluka'];
                $district = $data['district'];
                $villageid = $data['village'];
                $survey_no = @$data['survey_no'];
                $language = $this->Session->read("sess_langauge");
                $this->set('lang', $language);



                $village = $this->VillageMapping->find("all", array('conditions' => array('village_id' => $villageid)));

                if (!empty($village)) {
                    $landtype_id = $village[0]['VillageMapping']['developed_land_types_id'];
                    $valutation_zone_id = $village[0]['VillageMapping']['valutation_zone_id'];
                    $ulb_type_id = $village[0]['VillageMapping']['ulb_type_id'];
                    $rate_selection_rule1 = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $landtype_id, 'ready_reckoner_rate_flag' => 'Y')));
                    $options['rate.ready_reckoner_rate_flag'] = 'Y';
//                    $options['rate.developed_land_types_id'] = $landtype_id;

                    $allrates = array();
                    foreach ($rate_selection_rule1 as $searchrule) {
                        if ($searchrule['RateSearch']['village_id'] == 'Y' && isset($data['village']) && is_numeric($data['village'])) {
                            $options['rate.village_id'] = $data['village'];
                            if (isset($data['lavel1']) && is_numeric($data['lavel1'])) {
                                $options['rate.level1_id'] = $data['lavel1'];
                            }
                            if (isset($data['lavel1_list']) && is_numeric($data['lavel1_list'])) {
                                $options['rate.level1_list_id'] = $data['lavel1_list'];
                            }
                        }
                        if ($searchrule['RateSearch']['finyear_id'] == 'Y' && isset($data['finyear_id']) && is_numeric($data['finyear_id'])) {
                            $options['rate.finyear_id'] = $data['finyear_id'];
                        }
                        if ($searchrule['RateSearch']['taluka_id'] == 'Y' && isset($data['taluka']) && is_numeric($data['taluka'])) {
                            $options['rate.taluka_id'] = $data['taluka'];
                        }
                        if ($searchrule['RateSearch']['district_id'] == 'Y' && isset($data['district']) && is_numeric($data['district'])) {
                            $options['rate.district_id'] = $data['district'];
                        }
                        if ($searchrule['RateSearch']['valutation_zone_id'] == 'Y' && isset($valutation_zone_id) && is_numeric($valutation_zone_id)) {
                            $options['rate.valutation_zone_id'] = $valutation_zone_id;
                        }
                        if ($searchrule['RateSearch']['ulb_type_id'] == 'Y' && isset($ulb_type_id) && is_numeric($ulb_type_id)) {
                            $options['rate.ulb_type_id'] = $ulb_type_id;
                        }

                        $options1['conditions'] = $options;
                        $options1['joins'] = array(
                            array('table' => 'ngdrstab_mst_unit', 'alias' => 'unit', 'type' => 'LEFT', 'conditions' => array('unit.unit_id = rate.prop_unit')),
                            array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'alias' => 'location1', 'type' => 'LEFT', 'conditions' => array('location1.prop_level1_list_id=rate.level1_list_id')),
                            array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'usage_main', 'type' => 'LEFT', 'conditions' => array('rate.usage_main_catg_id = usage_main.usage_main_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'usage_sub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_catg_id = usage_sub.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'subsub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_sub_catg_id = subsub.usage_sub_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_valuation_zone', 'alias' => 'zone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_zone_id = zone.valutation_zone_id')),
                            array('table' => 'ngdrstab_mst_valuation_subzone', 'alias' => 'subzone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_subzone_id = subzone.valutation_subzone_id', 'subzone.usage_main_catg_id=rate.usage_main_catg_id', 'subzone.usage_sub_catg_id=rate.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_construction_type', 'alias' => 'ctype', 'type' => 'LEFT', 'conditions' => array('ctype.construction_type_id = rate.construction_type_id')),
                            array('table' => 'ngdrstab_conf_admblock_local_governingbody', 'alias' => 'ulbclass', 'type' => 'LEFT', 'conditions' => array('ulbclass.ulb_type_id = rate.ulb_type_id')),
                            array('table' => 'ngdrstab_mst_user_def_depe1', 'alias' => 'udep1', 'type' => 'LEFT', 'conditions' => array('udep1.user_defined_dependency1_id = rate.user_defined_dependency1_id')),
                            array('table' => 'ngdrstab_mst_user_def_depe2', 'alias' => 'udep2', 'type' => 'LEFT', 'conditions' => array('udep2.user_defined_dependency2_id = rate.user_defined_dependency2_id')));


                        $options1['fields'] = array('rate.prop_rate', 'unit.unit_desc_' . $lang, 'usage_main.usage_main_catg_desc_' . $lang, 'usage_sub.usage_sub_catg_desc_' . $lang, 'subsub.usage_sub_sub_catg_desc_' . $lang, 'location1.list_1_desc_' . $lang, 'zone.valuation_zone_desc_' . $lang, 'subzone.from_desc_' . $lang, 'subzone.to_desc_' . $lang, 'ulbclass.class_description_' . $lang, 'ctype.construction_type_desc_' . $lang, 'udep1.user_defined_dependency1_desc_' . $lang, 'udep2.user_defined_dependency2_desc_' . $lang);

                        $rate = $this->rate->find('all', $options1);

                        $allrates[$searchrule['RateSearch']['search_id']] = $rate;
                        break;
                    }
                    $options = array();
                    $options1 = array();

                    $options['rate.ready_reckoner_rate_flag'] = 'N';
//                    $options['rate.developed_land_types_id'] = $landtype_id;
                    $rate_selection_rule2 = $this->RateSearch->find("all", array('order' => array('search_id ASC'), 'conditions' => array('developed_land_types_id' => $landtype_id, 'ready_reckoner_rate_flag' => 'N')));

                    foreach ($rate_selection_rule2 as $searchrule) {
                        if ($searchrule['RateSearch']['village_id'] == 'Y' && isset($data['village']) && is_numeric($data['village'])) {
                            $options['rate.village_id'] = $data['village'];
                            if (isset($data['lavel1']) && is_numeric($data['lavel1'])) {
                                $options['rate.level1_id'] = $data['lavel1'];
                            }
                            if (isset($data['lavel1_list']) && is_numeric($data['lavel1_list'])) {
                                $options['rate.level1_list_id'] = $data['lavel1_list'];
                            }
                        }
                        if ($searchrule['RateSearch']['finyear_id'] == 'Y' && isset($data['finyear_id']) && is_numeric($data['finyear_id'])) {
                            $options['rate.finyear_id'] = $data['finyear_id'];
                        }
                        if ($searchrule['RateSearch']['taluka_id'] == 'Y' && isset($data['taluka']) && is_numeric($data['taluka'])) {
                            $options['rate.taluka_id'] = $data['taluka'];
                        }
                        if ($searchrule['RateSearch']['district_id'] == 'Y' && isset($data['district']) && is_numeric($data['district'])) {
                            $options['rate.district_id'] = $data['district'];
                        }
                        if ($searchrule['RateSearch']['valutation_zone_id'] == 'Y' && isset($valutation_zone_id) && is_numeric($valutation_zone_id)) {
                            $options['rate.valutation_zone_id'] = $valutation_zone_id;
                        }
                        if ($searchrule['RateSearch']['ulb_type_id'] == 'Y' && isset($ulb_type_id) && is_numeric($ulb_type_id)) {
                            $options['rate.ulb_type_id'] = $ulb_type_id;
                        }

                        $options1['conditions'] = $options;
                        $options1['joins'] = array(
                            array('table' => 'ngdrstab_mst_unit', 'alias' => 'unit', 'type' => 'LEFT', 'conditions' => array('unit.unit_id = rate.prop_unit')),
                            array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'alias' => 'location1', 'type' => 'LEFT', 'conditions' => array('location1.prop_level1_list_id=rate.level1_list_id')),
                            array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'usage_main', 'type' => 'LEFT', 'conditions' => array('rate.usage_main_catg_id = usage_main.usage_main_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'usage_sub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_catg_id = usage_sub.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_usage_sub_sub_category', 'alias' => 'subsub', 'type' => 'LEFT', 'conditions' => array('rate.usage_sub_sub_catg_id = subsub.usage_sub_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_valuation_zone', 'alias' => 'zone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_zone_id = zone.valutation_zone_id')),
                            array('table' => 'ngdrstab_mst_valuation_subzone', 'alias' => 'subzone', 'type' => 'LEFT', 'conditions' => array('rate.valutation_subzone_id = subzone.valutation_subzone_id', 'subzone.usage_main_catg_id=rate.usage_main_catg_id', 'subzone.usage_sub_catg_id=rate.usage_sub_catg_id')),
                            array('table' => 'ngdrstab_mst_construction_type', 'alias' => 'ctype', 'type' => 'LEFT', 'conditions' => array('ctype.construction_type_id = rate.construction_type_id')),
                            array('table' => 'ngdrstab_conf_admblock_local_governingbody', 'alias' => 'ulbclass', 'type' => 'LEFT', 'conditions' => array('ulbclass.ulb_type_id = rate.ulb_type_id')),
                            array('table' => 'ngdrstab_mst_user_def_depe1', 'alias' => 'udep1', 'type' => 'LEFT', 'conditions' => array('udep1.user_defined_dependency1_id = rate.user_defined_dependency1_id')),
                            array('table' => 'ngdrstab_mst_user_def_depe2', 'alias' => 'udep2', 'type' => 'LEFT', 'conditions' => array('udep2.user_defined_dependency2_id = rate.user_defined_dependency2_id')));

                        $options1['fields'] = array('rate.prop_rate', 'unit.unit_desc_' . $lang, 'usage_main.usage_main_catg_desc_' . $lang, 'usage_sub.usage_sub_catg_desc_' . $lang, 'subsub.usage_sub_sub_catg_desc_' . $lang, 'location1.list_1_desc_' . $lang, 'zone.valuation_zone_desc_' . $lang, 'subzone.from_desc_' . $lang, 'subzone.to_desc_' . $lang, 'ulbclass.class_description_' . $lang, 'ctype.construction_type_desc_' . $lang, 'udep1.user_defined_dependency1_desc_' . $lang, 'udep2.user_defined_dependency2_desc_' . $lang);

                        $rate = $this->rate->find('all', $options1);

                        $allrates[$searchrule['RateSearch']['search_id']] = $rate;
                        break;
                    }
                }

                $this->set("allrates", $allrates);
            }
        } catch (Exception $ex) {
            //    pr($ex);
            //    exit;
        }
    }

    function round_tonext500($number) {
        try {
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
        } catch (Exception $ex) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //madhuri code end


    public function taluka_change_event_new() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('valuationzone');
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['tal']) and is_numeric($_GET['tal'])) {
                $tal = $_GET['tal'];

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));
                $circledata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('taluka_id' => $tal)));

                $result_array = array('village' => $villagelist, 'circle' => $circledata);
                $json2array['village'] = $villagelist;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
            exit;
            //$this->redirect(array('action' => 'error404'));
        }
    }

    public function get_corp_list_new() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('valuationzone');
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['district']) and is_numeric($_GET['district'])) {
                $district = $_GET['district'];

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('district_id' => array($district)))))));
                $result_array = array('corp' => $corplist);
                $json2array['corp'] = $corplist;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else if (isset($_GET['taluka']) and is_numeric($_GET['taluka'])) {
                $taluka = $_GET['taluka'];

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);

                $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('taluka_id' => array($taluka)))))));
                $result_array = array('corp' => $corplist);
                $json2array['corp'] = $corplist;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
            exit;
            //$this->redirect(array('action' => 'error404'));
        }
    }

    public function corp_change_event_new() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");

            if (isset($this->request->data['corp']) && is_numeric($this->request->data['corp'])) {
                $corp = $this->request->data['corp'];
                $cond = array('corp_id' => $corp);
                if (isset($this->request->data['taluka_id']) && is_numeric($this->request->data['taluka_id'])) {
                    $cond['taluka_id'] = $this->request->data['taluka_id'];
                }
                if (isset($this->request->data['circle_id']) && is_numeric($this->request->data['circle_id'])) {
                    $cond['circle_id'] = $this->request->data['circle_id'];
                }

                $villagelist = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.village_id', 'damblkdpnd.village_name_' . $lang), 'conditions' => $cond));

                $result_array = array('village' => $villagelist);


                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['village'] = $villagelist;
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            //pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function district_change_event_new() {
        try {
            $this->loadModel('damblkdpnd');
            $this->loadModel('corporationclasslist');
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            if (isset($_GET['dist'])) {
                $dist = $_GET['dist'];
                $talukalist = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_en'), 'conditions' => array('district_id' => $dist)));
                $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $dist)));


                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $json2array['taluka'] = $talukalist;

                $corplist = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corporationclasslist.corp_id', 'corporationclasslist.governingbody_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('corp_id' => ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('damblkdpnd.corp_id'), 'conditions' => array('district_id' => array($dist)))))));
                $result_array = array('subdiv' => $subdivisiondata, 'taluka' => $talukalist, 'circle' => NULL, 'corp' => $corplist, 'village' => NULL);

                $json2array['corp'] = $corplist;

                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));

                echo json_encode($result_array);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            // //pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    //for land record

    function getlandrecord() {
        try {
            // $this->autoRender=FALSE;
            $this->loadModel('User');
            $this->loadModel('VillageMapping');
            $this->loadModel('RateSearch');
            $this->loadModel('external_interface');

            $data = $this->request->data;
            //$this->check_csrf_token_withoutset($data['csrftoken']);
            $lang = $this->Session->read("sess_langauge");
            if (is_numeric($data['village']) && is_numeric($data['village'])) {
                $talid = $data['taluka'];
                $district = $data['district'];
                $villageid = $data['village'];
                $survey_no = @$data['survey_no'];
                $language = $this->Session->read("sess_langauge");
                $this->set('lang', $language);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');

                $json2array = json_decode($json, TRUE);
                if (isset($json2array['prop_attributes_seller'])) {
                    $prop_attributes = $json2array['prop_attributes_seller'];



                    $interface = $this->external_interface->find('all', array('conditions' => array(
                            'external_interface.interface_id ' => 1)));

                    $village = $this->VillageMapping->find("all", array('conditions' => array('village_id' => $villageid)));

//                $districtname =$this->District->->find("", array('conditions' => array('village_id' => $villageid)));
                    //pr($village);exit;

                    $district_code = trim($village[0]['VillageMapping']['district_code']);
                    $taluka_code = trim($village[0]['VillageMapping']['jh_taluka_code']);
                    $halka_no = trim($village[0]['VillageMapping']['halka_no']);
                    $census_code = $village[0]['VillageMapping']['state_spacific_code'];
//                       $census_code=$village[0]['VillageMapping']['census_code'] ;
//                       pr($district_code);exit;

                    if (!empty($village)) {
                        $landtype_id = $village[0]['VillageMapping']['developed_land_types_id'];
                        $valutation_zone_id = $village[0]['VillageMapping']['valutation_zone_id'];
                        $ulb_type_id = $village[0]['VillageMapping']['ulb_type_id'];
//pr($village);exit;


                        $url = $interface[0]['external_interface']['interface_url'];
//                     $taluka_code='02';
//                     $halka_no='01';
//                     $census_code='1163';
//                     $prop_attributes[207]["attribute_value"]=01;
//                     
//                     $prop_attributes[209]["attribute_value"]=10;



                        $fields = array('DistCode' => $district_code,
                            'CircleCode' => $taluka_code,
                            'HalkaCode' => $halka_no,
                            'MaujaCode' => $census_code,
                            'VolCurValue' => $prop_attributes[207]["attribute_value"],
                            'CurPageNo' => $prop_attributes[209]["attribute_value"],
                            'User' => $interface[0]['external_interface']['interface_user_id'],
                            'Password' => $interface[0]['external_interface']['interface_password']
                        );
//                     pr($fields);
//                     exit;

                        $client = new SoapClient($url);
                        $result = $client->RegisterIIdata($fields
                        );
                        $servicedata = (array) $result->RegisterIIdataResult;
                        $arr = $servicedata['any'];
                        $arr1 = simplexml_load_string($arr);
                        //$xml=(xml_parse($parser, $arr));
                        $json = json_encode($arr1);
                        $decode = json_decode($json, true);
                        $arr_new = (array) $decode;
//                    pr($arr_new);
//                    exit;
                        $this->set("arr_new", $arr_new);
                    }
                } else {
                    echo 0;
                }

                //$this->set("allrecord", $allrecord);
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }
    
     function getlandrecordtest() {
        try {
            // $this->autoRender=FALSE;
            $this->loadModel('User');
            $this->loadModel('VillageMapping');
            $this->loadModel('RateSearch');
            $this->loadModel('external_interface');

$igd_village_code=272654;
$khatian_no=261;
$application_id='ngdrs_trp_appid';
$app_key='6c439a7b4bf5ea5adca5b2a6d2817b41cd34101d48d8bace5a203de7ddc6242d';
$hmac=$igd_village_code.$khatian_no.$application_id.$app_key;

$url='http://10.183.15.121:8080/lrservice_tripura/webresources/khatians/khatian';
$fields = array('igd_village_code' => $igd_village_code,
                            'khatian_no' => $khatian_no,
                            'application_id' => $application_id,
                            'hmac' => $hmac
                        );
$client = new SoapClient($url);
$result = $client($fields);
echo($result);exit;
$servicedata = (array) $result->RegisterIIdataResult;

            $data = $this->request->data;
            //$this->check_csrf_token_withoutset($data['csrftoken']);
            $lang = $this->Session->read("sess_langauge");
            if (is_numeric($data['village']) && is_numeric($data['village'])) {
                $talid = $data['taluka'];
                $district = $data['district'];
                $villageid = $data['village'];
                $survey_no = @$data['survey_no'];
                $language = $this->Session->read("sess_langauge");
                $this->set('lang', $language);
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');

                $json2array = json_decode($json, TRUE);
                if (isset($json2array['prop_attributes_seller'])) {
                    $prop_attributes = $json2array['prop_attributes_seller'];



                    $interface = $this->external_interface->find('all', array('conditions' => array(
                            'external_interface.interface_id ' => 1)));

                    $village = $this->VillageMapping->find("all", array('conditions' => array('village_id' => $villageid)));

//                $districtname =$this->District->->find("", array('conditions' => array('village_id' => $villageid)));
                    //pr($village);exit;

                    $district_code = trim($village[0]['VillageMapping']['district_code']);
                    $taluka_code = trim($village[0]['VillageMapping']['jh_taluka_code']);
                    $halka_no = trim($village[0]['VillageMapping']['halka_no']);
                    $census_code = $village[0]['VillageMapping']['state_spacific_code'];
//                       $census_code=$village[0]['VillageMapping']['census_code'] ;
//                       pr($district_code);exit;

                    if (!empty($village)) {
                        $landtype_id = $village[0]['VillageMapping']['developed_land_types_id'];
                        $valutation_zone_id = $village[0]['VillageMapping']['valutation_zone_id'];
                        $ulb_type_id = $village[0]['VillageMapping']['ulb_type_id'];
//pr($village);exit;


                        $url = $interface[0]['external_interface']['interface_url'];
//                     $taluka_code='02';
//                     $halka_no='01';
//                     $census_code='1163';
//                     $prop_attributes[207]["attribute_value"]=01;
//                     
//                     $prop_attributes[209]["attribute_value"]=10;



                        $fields = array('DistCode' => $district_code,
                            'CircleCode' => $taluka_code,
                            'HalkaCode' => $halka_no,
                            'MaujaCode' => $census_code,
                            'VolCurValue' => $prop_attributes[207]["attribute_value"],
                            'CurPageNo' => $prop_attributes[209]["attribute_value"],
                            'User' => $interface[0]['external_interface']['interface_user_id'],
                            'Password' => $interface[0]['external_interface']['interface_password']
                        );
//                     pr($fields);
//                     exit;

                        $client = new SoapClient($url);
                        $result = $client->RegisterIIdata($fields
                        );
                        $servicedata = (array) $result->RegisterIIdataResult;
                        $arr = $servicedata['any'];
                        $arr1 = simplexml_load_string($arr);
                        //$xml=(xml_parse($parser, $arr));
                        $json = json_encode($arr1);
                        $decode = json_decode($json, true);
                        $arr_new = (array) $decode;
//                    pr($arr_new);
//                    exit;
                        $this->set("arr_new", $arr_new);
                    }
                } else {
                    echo 0;
                }

                //$this->set("allrecord", $allrecord);
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    //for holding service

    function getholdingrecord() {
        try {

            $this->loadModel('external_interface');
            $this->loadModel('VillageMapping');

            $interface = $this->external_interface->find('all', array('conditions' => array(
                    'external_interface.interface_id ' => 11)));

            $data = $this->request->data;

            if (is_numeric($data['corp_id']) && is_numeric($data['corp_id'])) {
                $ulb_name = $this->VillageMapping->query('select governingbody_name_en from ngdrstab_conf_admblock_local_governingbody_list where corp_id=?', array($data['corp_id']));

                if (!empty($ulb_name)) {
                    $ulb_name = $ulb_name[0][0]['governingbody_name_en'];
                } else {
                    $ulb_name = null;
                }
                $villageid = $data['village'];

                $village = $this->VillageMapping->find("all", array('conditions' => array('village_id' => $villageid)));
                $ward_no = $village[0]['VillageMapping']['halka_no'];
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');

                $json2array = json_decode($json, TRUE);
                if (isset($json2array['prop_attributes_seller'])) {
                    $prop_attributes = $json2array['prop_attributes_seller'];
//            $fields = array('ulb_name' =>$ulb_name,
//                        'ward_name' => $ward_no,
//                        'holding_no'=>$prop_attributes[210]["attribute_value"]
//                       
//                        );
//                    pr($fields);
//                    exit;

                    $fields = array('ulb_name' => 'RANCHI MUNICIPAL CORPORATION',
                        'ward_name' => '8',
                        'holding_no' => '0080006125000Z0'
                    );
                    $url = $interface[0]['external_interface']['interface_url'];

                    $data_string = http_build_query($fields);
                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/x-www-form-urlencoded',
                        'Content-Length: ' . strlen($data_string))
                    );
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//execute post
                    $result1 = curl_exec($ch);
//pr($result1);
//exit;

                    $arr1 = simplexml_load_string($result1);
                    //$xml=(xml_parse($parser, $arr));
                    $json = json_encode($arr1);
                    $decode = json_decode($json, true);
                    $arr_new = (array) $decode;

                    $this->set("arr_new", $arr_new);
                } else {
                    echo 0;
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function testtable() {
        $this->autoRender = FALSE;

        $this->loadModel('TestTable');
        $test = $this->TestTable->find("all");
        pr($test);
        exit;
    }

    public function usage_main_cat($usage_main_catg_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Usagemainmain');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
//            $MainCat = $this->Usagemainmain->find('list', array('fields' => array('Usagemainmain.usage_main_catg_id', 'Usagemainmain.usage_main_catg_desc_' . $laug), 'order' => array('usage_main_catg_desc_en' => 'ASC')));
//            $this->set('MainCat', $MainCat);
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

            $this->set('MainCat', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $MainCat = $this->Usagemainmain->query("select * from ngdrstab_mst_usage_main_category");
            $this->set('MainCat', $MainCat);


            $this->set("fieldlist", $fieldlist = $this->Usagemainmain->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['usage_main_cat']['ip_address'] = $this->request->clientIp();
                $this->request->data['usage_main_cat']['created_date'] = $created_date;
                $this->request->data['usage_main_cat']['user_id'] = $user_id;
                $this->request->data['usage_main_cat']['state_id'] = $stateid;
                $verrors = $this->validatedata($this->request->data['usage_main_cat'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->Usagemainmain->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['usage_main_cat']);
                    if ($checkd) {

                        if ($this->Usagemainmain->save($this->request->data['usage_main_cat'])) {
                            $lastid = $this->Usagemainmain->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }
                            return $this->redirect(array('action' => 'usage_main_cat'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($usage_main_catg_id) && is_numeric($usage_main_catg_id)) {

                $this->Session->write('usage_main_catg_id', $usage_main_catg_id);
                $result = $this->Usagemainmain->find("first", array('conditions' => array('usage_main_catg_id' => $usage_main_catg_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['usage_main_cat'] = $result['Usagemainmain'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_usage_main_cat($usage_main_catg_id = null) {
        $this->autoRender = false;
        $this->loadModel('Usagemainmain');
        try {

            if (isset($usage_main_catg_id) && is_numeric($usage_main_catg_id)) {
                $this->Usagemainmain->usage_main_catg_id = $usage_main_catg_id;
                if ($this->Usagemainmain->delete($usage_main_catg_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'usage_main_cat'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function usage_sub_cat() {
        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'usage_sub_category', 'mainlanguage', 'language', 'Usagemainmain', 'adminLevelConfig'));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('usage_sub_catg_id', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);

            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('adminLevelConfig', $adminLevelConfig);
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

            $this->set('subcatrecord', $this->usage_sub_category->find('all'));

            $this->set("fieldlist", $fieldlist = $this->usage_sub_category->fieldlist($languagelist, $adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {

                $this->check_csrf_token($this->request->data['usage_sub_cat']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');

                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);

                    if ($hfactionval == 'S') {
                        $this->request->data['usage_sub_cat']['req_ip'] = $this->request->clientIp();
                        $this->request->data['usage_sub_cat']['user_id'] = $user_id;
                        $this->request->data['usage_sub_cat']['actiontype'] = $actiontype;
                        $this->request->data['usage_sub_cat']['hfaction'] = $hfactionval;
                        $this->request->data['usage_sub_cat']['state_id'] = $stateid;
                        $this->request->data['usage_sub_cat']['hfid'] = $hfid;
//pr($this->request->data['usage_sub_cat']);exit;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['usage_sub_cat']['usage_sub_catg_id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }

                        $verrors = $this->validatedata($this->request->data['usage_sub_cat'], $fieldlist);
//                        pr($verrors);exit;
                        if ($this->ValidationError($verrors)) {
                            // pr($this->request->data['usage_sub_cat']);exit;
                            $duplicate = $this->usage_sub_category->get_duplicate($languagelist);
                            $checkd = $this->check_duplicate($duplicate, $this->request->data['usage_sub_cat']);
                            if ($checkd) {
                                if ($this->usage_sub_category->save($this->request->data['usage_sub_cat'])) {
                                    $this->Session->setFlash(__($actionvalue));
                                    $this->redirect(array('controller' => 'Property', 'action' => 'usage_sub_cat'));
                                    $this->set('subcatrecord', $this->usage_sub_category->find('all'));
                                } else {
                                    $this->Session->setFlash(__('lblnotsavemsg'));
                                }
                            } else {
                                $this->Session->setFlash(__('lblduplicatemsg'));
                            }
                        } else {
                            $this->Session->setFlash(__('Find validations '));
                        }
                    }
                }
            }

            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {

            pr($exc);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function usage_sub_cat_delete($usage_sub_catg_id = null) {
        //pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('usage_sub_category');
        try {
            $usage_sub_catg_id = $this->decrypt($usage_sub_catg_id, $this->Session->read("randamkey"));
            if (isset($usage_sub_catg_id) && is_numeric($usage_sub_catg_id)) {
                $this->usage_sub_category->usage_sub_catg_id = $usage_sub_catg_id;
                if ($this->usage_sub_category->delete($usage_sub_catg_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'usage_sub_cat'));
                }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function usage_category($usage_cat_id = NULL) {
        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'usage_sub_category', 'mainlanguage', 'language', 'Usagemainmain', 'adminLevelConfig', 'usage_category'));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->set('usage_sub_catg_id', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            //languages are loaded firstly from config (from table)
            $main_cat_data = $this->Usagemainmain->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $laug), 'order' => array('usage_main_catg_id' => 'ASC')));
            $this->set('main_cat_data', $main_cat_data);

            $sub_cat_data = $this->usage_sub_category->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $laug), 'order' => array('usage_sub_catg_id' => 'ASC')));
            $this->set('sub_cat_data', $sub_cat_data);

            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('adminLevelConfig', $adminLevelConfig);
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
//pr($languagelist);exit;
            $qry = $this->usage_category->query("select main.*,
                                               sub.*,
                                               usage.usage_cat_id
                                                from 
                                                ngdrstab_mst_usage_category as usage,
                                                ngdrstab_mst_usage_sub_category as sub,
                                                ngdrstab_mst_usage_main_category as main

                                                where 

                                                usage.usage_main_catg_id=main.usage_main_catg_id
                                                and usage.usage_sub_catg_id=sub.usage_sub_catg_id");

            $this->set('udagecatrecord', $qry);

//            pr($qry);exit;

            $this->set("fieldlist", $fieldlist = $this->usage_category->fieldlist($languagelist, $adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {

                $this->check_csrf_token($this->request->data['usage_category']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');

                $stateid = $this->Auth->User("state_id");

                $this->set('actiontypeval', $actiontype);
                $this->set('hfactionval', $hfactionval);


                $this->request->data['usage_category']['req_ip'] = $this->request->clientIp();
                $this->request->data['usage_category']['user_id'] = $user_id;
                $this->request->data['usage_category']['actiontype'] = $actiontype;
                $this->request->data['usage_category']['hfaction'] = $hfactionval;
                $this->request->data['usage_category']['state_id'] = $stateid;
                $this->request->data['usage_category']['hfid'] = $hfid;


                $verrors = $this->validatedata($this->request->data['usage_category'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->usage_category->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['usage_category']);
                    if ($checkd) {
                        if ($this->usage_category->save($this->request->data['usage_category'])) {
                            $lastid = $this->usage_category->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $actionvalue = 'lblsavemsg';
                            } else {
                                $actionvalue = 'lbleditmsg';
                            }
                            $this->Session->setFlash(__($actionvalue));

                            $this->redirect(array('controller' => 'Property', 'action' => 'usage_category'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }

            if (is_numeric($usage_cat_id)) {

                $usage_cat_edit = $this->usage_category->find("first", array('conditions' => array('usage_cat_id' => $usage_cat_id)));
                if (empty($usage_cat_edit)) {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                    return $this->redirect(array('controller' => 'Property', 'action' => 'usage_category'));
                }
                $this->set('editflag', 'Y');
                $this->request->data['usage_category'] = $usage_cat_edit['usage_category'];
            }


            $this->set_csrf_token();
            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {

            //pr($exc);
            //exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function usage_category_delete($usage_cat_id = null) {
        //pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('usage_category');
        try {
            $usage_cat_id = $this->decrypt($usage_cat_id, $this->Session->read("randamkey"));
            if (isset($usage_cat_id) && is_numeric($usage_cat_id)) {
                $this->usage_category->usage_cat_id = $usage_cat_id;
                if ($this->usage_category->delete($usage_cat_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'usage_category'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function unit() {
        try {
            $this->check_role_escalation();
            $this->loadModel('unit');
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('standard_units');
            $this->set('selectunit', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);

            $this->loadModel('language');
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
            $this->set('languagelist', $languagelist);
            $this->set('unitrecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $user_id = $this->Auth->User("user_id");
            $this->request->data['unit']['req_ip'] = $this->request->clientIp();
            $this->request->data['unit']['user_id'] = $user_id;
            // $this->request->data['unit']['created_date'] = $created_date;
            $this->request->data['unit']['state_id'] = $stateid;
            $stdunits = $this->unit->find('list', array('fields' => array('unit_id', 'unit_desc_en'), 'conditions' => array('conversion_formula' => 1), 'order' => array('unit_id' => 'ASC')));
            $this->set('unitdata', $stdunits);
            $unitrecord = $this->unit->find('all', array('fields' => array('unit.unit_id', 'unit.unit_desc_en', 'unit.unit_desc_ll', 'unit.unit_desc_ll1', 'unit.unit_desc_ll2', 'unit.unit_desc_ll3', 'unit.unit_desc_ll4', 'stdunit.unit_desc_en', 'stdunit.unit_desc_ll', 'stdunit.unit_desc_ll1', 'stdunit.unit_desc_ll2', 'stdunit.unit_desc_ll3', 'stdunit.unit_desc_ll4', 'remark', 'conversion_formula', 'standard_units'),
                'joins' => array(
                    array(
                        'table' => 'ngdrstab_mst_unit',
                        'alias' => 'stdunit',
                        'type' => 'LEFT',
                        'conditions' => array('stdunit.unit_id= unit.standard_units')
            ))));
            //pr($unitrecord);exit;
            $this->set('unitrecord', $unitrecord);
            $fieldlist = array();
            $fielderrorarray = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['unit_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
                    $fieldlist['unit_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicode_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to255";
                }
            }
            $fieldlist['remark']['text'] = 'is_required,is_alphaspace,is_maxlength255';
            $fieldlist['standard_units']['select'] = 'is_select_req';
            $fieldlist['conversion_formula']['text'] = 'is_integerdecimalornot,is_maxlength12';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['unit']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $duplicateflag = 'S';
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['unit']['unit_id'] = $this->request->data['hfid'];
                            $duplicateflag = 'U';
                            $actionvalue = "lbleditmsg";
                        } else {

                            $actionvalue = "lblsavemsg";
                        }
                        // pr($this->request->data['unit']);exit;
                        if ($this->request->data['unit']['type'] == 1) {
                            unset($fieldlist['standard_units']);
                            $this->request->data['unit']['standard_units'] = NULL;
                            $this->request->data['unit']['conversion_formula'] = 1;
                        }

                        $this->request->data['unit'] = $this->istrim($this->request->data['unit']);
                        $errarr = $this->validatedata($this->request->data['unit'], $fieldlist);


                        if ($this->ValidationError($errarr)) {
                            $duplicate['Table'] = 'ngdrstab_mst_unit';
                            $duplicate['Fields'] = array('unit_desc_en');
                            $duplicate['Action'] = $duplicateflag; //U   
                            $duplicate['PrimaryKey'] = 'unit_id';

                            $checkd = $this->check_duplicate($duplicate, $this->request->data['unit']);
                            if ($checkd) {
                                if ($this->unit->save($this->request->data['unit'])) {
                                    $this->Session->setFlash(__($actionvalue));
                                    $this->redirect(array('controller' => 'Property', 'action' => 'unit'));
                                    $this->set('unitrecord', $this->unit->find('all'));
                                } else {
                                    $this->Session->setFlash(__('lblnotsavemsg'));
                                }
                            } else {
                                $this->Session->setFlash(__('lblduplicatemsg'));
                            }

                            if ($actiontype == 2) {
                                $this->set('hfupdateflag', 'Y');
                            }
                        }
                    }
                }
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
            // pr($ex);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function unit_delete($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('unit');
        try {
            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'constructiontype') {
                $this->unit->unit_id = $id;
                if ($this->unit->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'unit'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function unit_mapping($mapping_id = null) {
        try {

            array_map([$this, 'loadModel'], ['unit_mapping', 'Usagemain', 'Usagesub', 'Usagesubsub', 'levelconfig', 'State', 'User', 'NGDRSErrorCode', 'mainlanguage']);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $user_id = $this->Auth->User("user_id");
            $created_date = date('Y/m/d');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            //  $this->set('unitmappingrecord', NULL);
            $this->set('district', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $laug), 'order' => array('district_name_' . $laug => 'ASC'))));

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

            $usagemain = $this->Usagemain->find('list', array(
                'fields' => array('Usagemain.usage_main_catg_id', 'Usagemain.usage_main_catg_desc_en'),
                'joins' => array(array('table' => 'ngdrstab_mst_usage_main_category',
                        'alias' => 'grp',
                        'type' => 'left',
                        'conditions' => array('Usagemain.usage_main_catg_id = grp.usage_main_catg_id', 'Usagemain.state_id= grp.state_id')
                    ))
                    )
            );
            $this->set('usagemain', $usagemain);
            $this->set('unitdata', ClassRegistry::init('unit')->find('list', array('fields' => array('unit_id', 'unit_desc_' . $laug), 'order' => array('unit_desc_' . $laug => 'ASC'))));
            $this->set('usagesub', ClassRegistry::init('Usagesub')->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $laug), 'order' => array('usage_sub_catg_desc_' . $laug => 'ASC'))));
            $this->set('usagesubsub', ClassRegistry::init('usagelnkitemlist')->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_' . $laug), 'conditions' => array('usage_param_type_id' => 1, 'single_unit_flag' => 'Y'), 'order' => array('usage_param_desc_' . $laug => 'ASC'))));

//            $this->set('unitmappingrecord', $this->unit_mapping->find('all'));
            $unitmappingrecord = array();
            $unitmappingrecord = $this->unit_mapping->query("select  dist.*,usagemain.*,usagesub.*, item.*,unit.*,map.* from ngdrstab_mst_unit unit
                JOIN ngdrstab_mst_unit_mapping as map ON map.unit_id=unit.unit_id
                JOIN ngdrstab_mst_usage_main_category as usagemain ON usagemain.usage_main_catg_id=map.usage_main_catg_id
                JOIN ngdrstab_mst_usage_sub_category as usagesub ON usagesub.usage_sub_catg_id=map.usage_sub_catg_id
                LEFT JOIN ngdrstab_mst_usage_items_list as item ON item.usage_param_id=map.usage_param_id
                LEFT JOIN ngdrstab_conf_admblock3_district  as dist ON dist.district_id=map.district_id
                ORDER BY usagemain.usage_main_catg_desc_en,usagesub.usage_sub_catg_desc_en,item.usage_param_desc_en,unit.unit_desc_en");
//             pr($unitmappingrecord);exit;


            $this->set('unitmappingrecord', $unitmappingrecord);
            $fieldlist = array();
            $fieldlist['district_id']['select'] = 'is_select';
            $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
            $fieldlist['usage_sub_catg_id']['select'] = 'is_select_req';
            $fieldlist['usage_param_id']['select'] = 'is_select';
            $fieldlist['unit_id']['select'] = 'is_select_req';
            $fieldlist['sr_no']['text'] = 'is_required,is_numeric';


            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['unit_mapping']['csrftoken']);


                $this->request->data['unit_mapping']['req_ip'] = $this->request->clientIp();
                $this->request->data['unit_mapping']['user_id'] = $user_id;
                $this->request->data['unit_mapping']['created'] = $created_date;

                $this->request->data['unit_mapping'] = $this->istrim($this->request->data['unit_mapping']);
                $errarr = $this->validatedata($this->request->data['unit_mapping'], $fieldlist);
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                } else {
                    foreach ($this->request->data['unit_mapping'] as $key => $value) {
                        if (!is_numeric($value)) {
                            $this->request->data['unit_mapping'][$key] = NULL;
                        }
                    }

                    if ($this->unit_mapping->save($this->request->data['unit_mapping'])) {
                        $this->Session->setFlash(__("lblsavemsg"));
                        $this->redirect(array('controller' => 'Property', 'action' => 'unit_mapping'));
                    } else {
                        $this->Session->setFlash(__('lblnotsavemsg'));
                    }
                }
            }

            $this->set_csrf_token();
        } catch (Exception $ex) {
            pr($ex);
        }
    }

    public function unit_mapping_remove($mapping_id = NULL) {

        $this->loadModel('unit_mapping');
        $this->autoRender = False;
        $this->unit_mapping->deleteAll(array('mapping_id' => $mapping_id));
        $this->Session->setFlash(__("lbldeletemsg"));
        return $this->redirect(array('action' => 'unit_mapping'));
    }

    public function attribute_property($id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('attributes_property');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
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

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $propertydata = $this->attributes_property->query("select * from ngdrstab_mst_attribute_parameter order by eri_attribute_name_en");
            $this->set('propertydata', $propertydata);

//            pr($propertydata);exit;

            $this->set("fieldlist", $fieldlist = $this->attributes_property->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['attribute_property']['ip_address'] = $this->request->clientIp();
                $this->request->data['attribute_property']['created_date'] = $created_date;
                $this->request->data['attribute_property']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['attribute_property'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->attributes_property->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['attribute_property']);
                    if ($checkd) {
                        if ($this->attributes_property->save($this->request->data['attribute_property'])) {
                            $lastid = $this->attributes_property->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }
                            return $this->redirect(array('controller' => 'Property', 'action' => 'attribute_property'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($id) && is_numeric($id)) {

                $this->Session->write('id', $id);
                $result = $this->attributes_property->find("first", array('conditions' => array('id' => $id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->set('is_subpart_flag', $result['attributes_property']['is_subpart_flag']);
                    $this->request->data['attribute_property'] = $result['attributes_property'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            //pr($ex);
            //exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_attribute_property($id = null) {
        $this->autoRender = false;
        $this->loadModel('attributes_property');
        try {

            if (isset($id) && is_numeric($id)) {
                $this->attributes_property->id = $id;
                if ($this->attributes_property->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'attribute_property'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function finyear($finyear_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('finyear');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Developedlandtype');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
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

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $landtdata = $this->finyear->query("select * from ngdrstab_mst_finyear");
            $this->set('landtdata', $landtdata);


            $this->set("fieldlist", $fieldlist = $this->finyear->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['finyear']['ip_address'] = $this->request->clientIp();
                $this->request->data['finyear']['created_date'] = $created_date;
                $this->request->data['finyear']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['finyear'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->finyear->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['finyear']);
                    if ($checkd) {
                        if ($this->request->data['finyear']['current_year'] == 'Y') {
                            $this->finyear->updateAll(array('current_year' => "'N'"));
                        }

                        if ($this->finyear->save($this->request->data['finyear'])) {
                            $lastid = $this->finyear->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }

                            return $this->redirect(array('controller' => 'Property', 'action' => 'finyear'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($finyear_id) && is_numeric($finyear_id)) {

                $this->Session->write('finyear_id', $finyear_id);
                $result = $this->finyear->find("first", array('conditions' => array('finyear_id' => $finyear_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['finyear'] = $result['finyear'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            //  pr($ex);
            //  exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_finyear($finyear_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('finyear');
        try {

            if (isset($finyear_id) && is_numeric($finyear_id)) {
                //  if ($type = 'subdivision') {
                $this->finyear->finyear_id = $finyear_id;
                if ($this->finyear->delete($finyear_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'Property', 'action' => 'finyear'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

}
