<?php

//session_start();
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::import('Vendor', 'captcha/captcha');
App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class LegacyPropertydetailsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('mainlanguage');
        // $this->Session->renew();

        if ($this->name == 'CakeError') {
            $this->layout = 'error';
        }
        $this->response->disableCache();
        //$this->Auth->allow('physub');
        //$this->Auth->allow('Legency_data_entry_new');
    }

    public function property($csrftoken = NULL, $property_id = null) {
        try {

            array_map(array($this, 'loadModel'), array('Leg_generalinformation', 'Leg_application_submitted', 'NGDRSErrorCode', 'VillageMapping', 'Developedlandtype', 'main_category', 'District', 'unit', 'attributetype', 'Leg_property_details_entry', 'Property_location', 'Leg_parameter', 'Leg_trn_valuation', 'Leg_propertydetails', 'attribute_parameter'));
            $gen_data = $this->Leg_application_submitted->find('first', array('conditions' => array('token_no' => $this->Session->read('Leg_Selectedtoken'))));
         //  pr($gen_data);exit();
            if (empty($gen_data)) {
                $this->Session->setFlash(__('Please enter General information first.'));
                return $this->redirect('../LegacyGeneralinfo/information');
            }

            $lang = $this->Session->read("sess_langauge");
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $fieldlist = array();
            $fieldlist['district_id']['select'] = 'is_required,is_select_req';
            $fieldlist['subdivision_id']['select'] = 'is_required,is_select_req';


            $fieldlist['developed_land_types_id']['select'] = 'is_select_req';
            $fieldlist['taluka_id']['select'] = 'is_required,is_select_req';
            $fieldlist['circle_id']['select'] = 'is_required,is_select_req';
            $fieldlist['village_id']['select'] = '';
            $fieldlist['location1_en']['text'] = 'is_address_field';
            // $fieldlist['location2']['text'] = 'is_address_field';
            $fieldlist['unique_property_no_en']['text'] = 'is_alphanumeric';
            $fieldlist['boundries_east_en']['text'] = 'is_required,is_address_field';
            $fieldlist['boundries_west_en']['text'] = 'is_required,is_address_field';
            $fieldlist['boundries_south_en']['text'] = 'is_required,is_address_field';
            $fieldlist['boundries_north_en']['text'] = 'is_required,is_address_field';
            $fieldlist['additional_information_en']['text'] = 'is_address_field';
            /////////////////////////
            if ($lang == 'll') {
                $fieldlist['location2_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['unique_property_no_ll']['text'] = 'is_alphanumeric';
                $fieldlist['boundries_east_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_west_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_south_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['boundries_north_ll']['text'] = 'unicode_rule_' . $lang;
                $fieldlist['additional_information_ll']['text'] = 'unicode_rule_' . $lang;
            }
            //////////////////////
            $fieldlist['usage_main_catg_id']['select'] = 'is_select';
            $fieldlist['usage_sub_catg_id']['select'] = '';//'is_select';
            $fieldlist['item_value']['text'] = 'is_blankdotnumber';
            $fieldlist['area_unit']['select'] = 'is_select';
            $fieldlist['final_value']['text'] = 'is_blankdotnumber';
            $fieldlist['consideration_amt']['text'] = 'is_blankdotnumber';
            $fieldlist['paramter_id']['select'] = 'is_select';
            $fieldlist['paramter_value']['text'] = 'is_alphanumdashslash';
            //////////////////////


           // $fieldlist['paramter_value1']['text'] = 'is_alphanumeric';
            //$fieldlist['paramter_value2']['text'] = 'is_alphanumeric';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            $token_no = $this->Session->read('Leg_Selectedtoken');
            $district = $this->Leg_generalinformation->get_district_id($token_no);

            $this->Session->write('district', $district[0][0]['district_id']);
            $district_id = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_name_en' => 'ASC')));
            $this->set('district_id', $district_id);
            $this->request->data['property_locationctp']['district_id'] = $this->Session->read('district');

            ////////////////////////Added Changes on Dated 01 Dec 2020

            $subdivision = $this->get_subdivision_for_property($this->Session->read('district'));
            $this->set('subdivision', $subdivision);

            $revenue_circle = array();
            $this->set('revenue_circle', $revenue_circle);

            $tehsil = array();
            $this->set('tehsil', $tehsil);

            $village_id = array();
            $this->set('village_id', $village_id);

            //  pr($subdivision);exit;
            //$revenuecircle = $this->get_revenuecircle_for_property($this->Session->read('subdivision_id'));
            // $tehsil = $this->get_tehsil_for_property($this->Session->read('talukaid'));
            // $village = $this->get_village_for_property($this->Session->read('circleid'));
/////////////////////////////
            // $taluka_id = $this->gettaluka_for_property($this->Session->read('district'));
            //$this->set('taluka_id', $taluka_id);
            // $taluka_id=array();
            //  $this->set('taluka_id', $taluka_id);
            // $village_id = array();
            //$this->set('village_id', $village_id);
            $developed_land_types_id = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_id' => 'ASC')));
            $this->set('developed_land_types_id', $developed_land_types_id);

            $main_category = $this->main_category->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'order' => array('usage_main_catg_desc_en' => 'ASC')));
            $this->set('main_category', $main_category);

            $sub_category1 = array();
            $this->set('sub_category1', $sub_category1);

            $unit = $this->unit->find('list', array('fields' => array('unit_id', 'unit_desc_' . $lang), 'order' => array('unit_desc_' . $lang => 'ASC')));
            $this->set('unit', $unit);

            $paramter_id = $this->attribute_parameter->find('list', array('fields' => array('attribute_id', 'eri_attribute_name_en'), 'order' => array('eri_attribute_name_en' => 'ASC')));

           //$paramter_id = $this->attribute_parameter->find('list',array('fields' => array('attribute_id', 'eri_attribute_name_en'), array('conditions' => array('eri_attribute_name_en' => 'Plot No'))));
            //pr($paramter_id);exit;
            //,array('conditions' => array('attribute_id' =>1)
             $this->set('paramter_id', $paramter_id);


            if (!is_null($property_id) && is_numeric($property_id)) {
                $result = $this->Leg_property_details_entry->find("first", array('conditions' => array('property_id' => $property_id)));
                $this->request->data['property_locationctp']['property_id'] = $result['Leg_property_details_entry']['property_id'];
            }

           // pr($this->Auth->user('user_id') );exit();
            if ($this->request->is('post') || $this->request->is('put'))
             {
                //To Read Json file
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
                $json = $file->read(true, 'r');
                $json2array = json_decode($json, TRUE);
                $file->close();


                if ($_POST['action'] == 'Final_submit') {
                    //unset($fieldlist['usage_sub_catg_id']);
                    // pr($this->request->data);exit;
                    $errarr = $this->validatedata($this->request->data['property_locationctp'], $fieldlist);
                    // pr('hooooo');exit;
                     //pr($errarr);exit;
                    if ($this->validationError($errarr)) {
                        if (isset($json2array['prop_attributes_seller']) && isset($json2array['prop_details_seller'])) {
                            if ((count($json2array['prop_attributes_seller']) != 0) && (count($json2array['prop_details_seller']) != 0)) {

                                if ($this->request->data['property_locationctp']['village_id'] == 'empty' || $this->request->data['property_locationctp']['village_id'] == '--Select Village--') {

                                    $this->request->data['property_locationctp']['village_id'] = 0;
                                }


                                $this->request->data['property_locationctp']['token_no'] = $token_no;
                                if ($this->Leg_property_details_entry->save($this->request->data['property_locationctp'])) {
                                    $last_prop_id = $this->Leg_property_details_entry->getLastInsertID($token_no);
                                    //Delete Property Attribute
                                    if (!is_null($property_id) && is_numeric($property_id)) {
                                        $last_prop_id = $property_id;
                                       // pr($last_prop_id);exit;
                                        $this->Leg_parameter->deleteAll(['token_id' => $token_no, 'property_id' => $last_prop_id]);
                                        //Delete entry from ngdrstab_trn_valuation and ngdrstab_trn_valuation_details
                                        $string3 = $this->Leg_trn_valuation->query('select val_id FROM ngdrstab_trn_legacy_valuation where token_no=' . $token_no . ' and property_id=' . $last_prop_id);
                                       // pr($string3);exit;
                                        foreach ($string3 as $s) {
                                            $del_val_id = $this->Leg_trn_valuation->query('delete from ngdrstab_trn_legacy_valuation_details where val_id=' . $s[0]['val_id']);
                                        }
                                        $this->Leg_trn_valuation->deleteAll(['token_no' => $token_no, 'property_id' => $last_prop_id]);
                                    }

                                    /////////////////////////////////////
                                    //Save Property Attribute   
                                    if (isset($json2array['prop_attributes_seller'])) {

                                        $this->Leg_parameter->save_attribute_parameter($json2array['prop_attributes_seller'], $last_prop_id, $token_no);
                                    
                                    }
                                    //Save Property Details Grid Data 
                                    if (isset($json2array['prop_details_seller'])) {
//pr($json2array['prop_details_seller']);exit;
                                        $this->Leg_trn_valuation->save_trn_valuation_parameter($json2array['prop_details_seller'], $last_prop_id, $token_no);
                                        $this->Session->setFlash('Data Added Successfully');
                                    }
                                    return $this->redirect(array('action' => 'property'));

                                    /////////////////////////////////////////////////////         
                                } else {
                                    $this->Session->setFlash(__('Record not saved.'));
                                }
                            } else {
                                $this->Session->setFlash('Add Property Other Details and Attribute Details');
                                return $this->redirect(array('action' => 'property'));
                            }
                        } else {
                            $this->Session->setFlash('Add Property Other Details and Attribute Details');
                            return $this->redirect(array('action' => 'property'));
                        }
                    }
                }
            } else {

                $json2array = array();
                $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                $file->write(json_encode($json2array));
                $Selectedtoken = $this->Session->read('Leg_Selectedtoken');

                if (!empty($Selectedtoken)) {
                    if (!is_null($property_id) && is_numeric($property_id)) {


                        $string = $this->Leg_propertydetails->get_property_info_1_from_finalsave($token_no, $property_id);
                        //  pr($string);exit;
                        $this->request->data['property_locationctp']['district_id'] = $string[0][0]['district_id'];


                        $subdivision = $this->get_subdivision_for_property($string[0][0]['district_id']);
                        $this->request->data['property_locationctp']['subdivision_id'] = $string[0][0]['subdivision_id'];
                        $this->request->data['property_locationctp']['developed_land_types_id'] = $string[0][0]['developed_land_types_id'];

                        //  $revenuecircle = $this->get_revenuecircle_for_property($string[0][0]['subdivision_id']);
                        $this->loadModel('taluka');
                        $revenue_circle = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $string[0][0]['subdivision_id'])));
                        $this->set('revenue_circle', $revenue_circle);
                        //pr($revenue_circle);exit;
                        $this->request->data['property_locationctp']['taluka_id'] = $string[0][0]['taluka_id'];
                        $this->loadModel('circle');
                        $tehsil = $this->circle->find('list', array('fields' => array('circle_id', 'circle_name_' . $lang), 'conditions' => array('taluka_id' => $string[0][0]['taluka_id'])));
                        $this->set('tehsil', $tehsil);
                        $this->request->data['property_locationctp']['circle_id'] = $string[0][0]['circle_id'];

                        $this->loadModel('VillageMapping');
                        // $taluka_id = $_GET['talukaid'];
                        $village_id = $this->VillageMapping->find('list', array('fields' => array('village_id', 'village_name_en'), 'conditions' => array('circle_id' => $string[0][0]['circle_id'])));
                        $this->set('village_id', $village_id);
                        $this->request->data['property_locationctp']['village_id'] = $string[0][0]['village_id'];
                        // pr($tehsil);exit;             
//pr($this->request->data['property_locationctp']['taluka_id']);exit;
                        //  pr($revenuecircle);exit;
                        // $this->request->data['property_locationctp']['taluka_id'] = $string[0][0]['taluka_id'];
                        // $village_id = $this->getvillage_for_property($string[0][0]['taluka_id']);
                        //$this->set('village_id', $village_id);

                        $this->request->data['property_locationctp']['village_id'] = $string[0][0]['village_id'];
                        $this->request->data['property_locationctp']['unique_property_no_en'] = $string[0][0]['unique_property_no_en'];
                        $this->request->data['property_locationctp']['location1_en'] = $string[0][0]['location1_en'];
                        $this->request->data['property_locationctp']['location2_ll'] = $string[0][0]['location2_ll'];
                        $this->request->data['property_locationctp']['boundries_east_en'] = $string[0][0]['boundries_east_en'];
                        $this->request->data['property_locationctp']['boundries_west_en'] = $string[0][0]['boundries_west_en'];
                        $this->request->data['property_locationctp']['boundries_south_en'] = $string[0][0]['boundries_south_en'];
                        $this->request->data['property_locationctp']['boundries_north_en'] = $string[0][0]['boundries_north_en'];
                        $this->request->data['property_locationctp']['additional_information_en'] = $string[0][0]['additional_information_en'];
                        if ($lang != 'en') {
                            $this->request->data['property_locationctp']['unique_property_no_ll'] = $string[0][0]['unique_property_no_ll'];
                            $this->request->data['property_locationctp']['boundries_east_ll'] = $string[0][0]['boundries_east_ll'];
                            $this->request->data['property_locationctp']['boundries_west_ll'] = $string[0][0]['boundries_west_ll'];
                            $this->request->data['property_locationctp']['boundries_south_ll'] = $string[0][0]['boundries_south_ll'];
                            $this->request->data['property_locationctp']['boundries_north_ll'] = $string[0][0]['boundries_north_ll'];
                            $this->request->data['property_locationctp']['additional_information_ll'] = $string[0][0]['additional_information_ll'];
                        }

                        $prp_details = $this->Leg_propertydetails->get_property_info_2_from_finalsave($token_no, $property_id);

                        $prop_details = array();
                        foreach ($prp_details as $value) {
                            $maindesc = $this->Leg_propertydetails->query("select usage_main_catg_desc_$lang from ngdrstab_mst_usage_main_category where usage_main_catg_id=" . $value[0]['usage_main_catg_id']);
//pr($maindesc);exit;
                            $subdesc = $this->Leg_propertydetails->query("select usage_sub_catg_desc_$lang from ngdrstab_mst_usage_sub_category where usage_sub_catg_id=" . $value[0]['usage_sub_catg_id']);
                            $unit_desc = $this->Leg_propertydetails->query("select unit_desc_$lang from ngdrstab_mst_unit where unit_id=" . $value[0]['area_unit']);
                               
                            if(!empty($maindesc))
                            {
                                
                            if ($lang != 'en') {
                                array_push($prop_details, array('usage_main_catg_id' => $value[0]['usage_main_catg_id'], 'usage_sub_catg_id' => $value[0]['usage_sub_catg_id'], 'item_value' => $value[0]['item_value'], 'area_unit' => $value[0]['area_unit'], 'final_value' => $value[0]['final_value'], 'maindesc' => $maindesc[0][0]['usage_main_catg_desc_ll'], 'subdesc' => $subdesc[0][0]['usage_sub_catg_desc_ll'], 'unit_desc' => $unit_desc[0][0]['unit_desc_ll'], 'consideration_amt' => $value[0]['consideration_amt']));
                            } else {
                                array_push($prop_details, array('usage_main_catg_id' => $value[0]['usage_main_catg_id'], 'usage_sub_catg_id' => $value[0]['usage_sub_catg_id'], 'item_value' => $value[0]['item_value'], 'area_unit' => $value[0]['area_unit'], 'final_value' => $value[0]['final_value'], 'maindesc' => $maindesc[0][0]['usage_main_catg_desc_en'], 'subdesc' => $subdesc[0][0]['usage_sub_catg_desc_en'], 'unit_desc' => $unit_desc[0][0]['unit_desc_en'], 'consideration_amt' => $value[0]['consideration_amt']));
                            }
                            }
                            else
                            {
                                if ($lang != 'en') {
                                array_push($prop_details, array('usage_main_catg_id' => $value[0]['usage_main_catg_id'], 'usage_sub_catg_id' => $value[0]['usage_sub_catg_id'], 'item_value' => $value[0]['item_value'], 'area_unit' => $value[0]['area_unit'], 'final_value' => $value[0]['final_value'], 'maindesc' => '', 'subdesc' => '', 'unit_desc' => $unit_desc[0][0]['unit_desc_ll'], 'consideration_amt' => $value[0]['consideration_amt']));
                            } else {
                                array_push($prop_details, array('usage_main_catg_id' => $value[0]['usage_main_catg_id'], 'usage_sub_catg_id' => $value[0]['usage_sub_catg_id'], 'item_value' => $value[0]['item_value'], 'area_unit' => $value[0]['area_unit'], 'final_value' => $value[0]['final_value'], 'maindesc' => '', 'subdesc' => '', 'unit_desc' => $unit_desc[0][0]['unit_desc_en'], 'consideration_amt' => $value[0]['consideration_amt']));
                            }
                            }
                        }
                        $json2array['prop_details_seller'] = $prop_details;
                        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                        $file->write(json_encode($json2array));
                        $this->set('prop_details_seller', $prop_details);
                        $aaa = $this->Leg_propertydetails->get_property_info_3_from_finalsave($token_no, $property_id);

                        $prop_attribute = array();
                        foreach ($aaa as $value) {
                          //  $para_desc = $this->Leg_propertydetails->query('select eri_attribute_name_en from ngdrstab_mst_attribute_parameter where  attribute_id=' . $value[0]['paramter_id']);

                           array_push($prop_attribute, array('paramter_id' => $value[0]['paramter_id'], 'paramter_value' => $value[0]['paramter_value'], 'paramter_value1' => $value[0]['paramter_value1'], 'paramter_value2' => $value[0]['paramter_value2'], 'para_desc' => $para_desc[0][0]['eri_attribute_name_en']));
                           
                        }
                        $json2array['prop_attributes_seller'] = $prop_attribute;
                        $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
                        $file->write(json_encode($json2array));
                        $this->set('prop_attributes_seller', $prop_attribute);
                    }
                }

                //Display Grid Data according to Property Id
                $this->loadModel('Leg_propertydetails');
                $Prop_detail_info = $this->Leg_propertydetails->get_property_detail_information($token_no);

                $this->set('Prop_detail_info', $Prop_detail_info);
            }
            //Clear Json array
        } catch (Exception $ex) {
            pr($ex);exit;

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//    public function gettaluka() {
//        $this->autoRender = FALSE;
//        $this->loadModel('taluka');
//        $districtid = $_GET['districtid'];
//        $taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_en'), 'conditions' => array('district_id' => $districtid)));
//        echo json_encode($taluka);
//        exit;
//    }
//
//    public function getvillage() {
//        $this->autoRender = FALSE;
//        $this->loadModel('VillageMapping');
//        $taluka_id = $_GET['talukaid'];
//        $village_id = $this->VillageMapping->find('list', array('fields' => array('village_id', 'village_name_en'), 'conditions' => array('taluka_id' => $taluka_id)));
//        echo json_encode($village_id);
//        exit;
//    }
//
//    public function gettaluka_for_property($districtid) {
//        //$this->autoRender = FALSE;
//       $lang = $this->Session->read("sess_langauge"); 
//        $this->loadModel('taluka');
//        $taluka = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_'.$lang), 'conditions' => array('district_id' => $districtid)));
//        return $taluka;
//    }
//
//    public function getvillage_for_property($taluka_id) {
//        // $this->autoRender = FALSE;
//        $lang = $this->Session->read("sess_langauge"); 
//        $this->loadModel('VillageMapping');
//        $village_id = $this->VillageMapping->find('list', array('fields' => array('village_id', 'village_name_'.$lang), 'conditions' => array('taluka_id' => $taluka_id)));
//        return $village_id;
//    }

    public function add_property_attribute() {
        try {
            //$this->autoRender=False;

            $this->loadModel("regconfig");
            $regconf_attr = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 151, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            if (@$this->request->data['action'] == 'remove') {
                if (isset($json2array['prop_attributes_seller'][$this->request->data['index_id']])) {
                    unset($json2array['prop_attributes_seller'][$this->request->data['index_id']]);
                    $prop_attributes = $json2array['prop_attributes_seller'];
                }
            } else {
                
                $paramter_id = trim($this->request->data['paramter_id']);
                $paramter_value = trim($this->request->data['paramter_value']);
                
           //     $paramter_value1 = trim($this->request->data['paramter_value1']);
            //    pr($this->request->data['paramter_value1']);exit();
           //     $paramter_value2 = trim($this->request->data['paramter_value2']);
                $para_desc = $this->request->data['para_desc'];
                if (isset($json2array['prop_attributes_seller'])) {
                    $prop_attributes = $json2array['prop_attributes_seller'];
                } else {
                    $prop_attributes = array();
                }
                if (!empty($regconf_attr)) {
                    foreach ($prop_attributes as $key => $value) {
                        if ($this->request->data['paramter_id'] == $value['paramter_id']) {

                            //  unset($prop_attributes[$key]);
                        }
                    }
                }

                //array_push($prop_attributes, array('paramter_id' => $paramter_id, 'paramter_value' => $paramter_value, 'paramter_value1' => $paramter_value1, 'paramter_value2' => $paramter_value2, 'para_desc' => $para_desc));
                array_push($prop_attributes, array('paramter_id' => $paramter_id, 'paramter_value' => $paramter_value, 'paramter_value1' => 0, 'paramter_value2' => 0, 'para_desc' => $para_desc));
                $json2array['prop_attributes_seller'] = $prop_attributes;
            }
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);
            $file->write(json_encode($json2array));
            $this->set('prop_attributes', $prop_attributes);
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function add_property_details() {
        try {
            $this->loadModel("regconfig");
            $regconf_attr = $this->regconfig->find("all", array('conditions' => array('reginfo_id' => 151, 'is_boolean' => 'Y', 'conf_bool_value' => 'Y')));
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json');
            $json = $file->read(true, 'r');
            $json2array = json_decode($json, TRUE);
            //////////////////////////////////////////////
            if (@$this->request->data['action'] == 'remove') {
                if (isset($json2array['prop_details_seller'][$this->request->data['index_id']])) {
                    unset($json2array['prop_details_seller'][$this->request->data['index_id']]);
                    $prop_details = $json2array['prop_details_seller'];
                }
            } else {
                $usage_main_catg_id = trim($this->request->data['usage_main_catg_id']);
             //   pr( $usage_main_catg_id);exit;
               
             $usage_sub_catg_id = '';
             $subdesc = '';
             if( $usage_main_catg_id==1)
              {
                $usage_sub_catg_id = trim($this->request->data['usage_sub_catg_id']);
                $subdesc = $this->request->data['subdesc'];
              } 


                $item_value = trim($this->request->data['item_value']);
                $area_unit = trim($this->request->data['area_unit']);
                $final_value = trim($this->request->data['final_value']);
                $maindesc = $this->request->data['maindesc'];
                
                $unit_desc = $this->request->data['unit_desc'];
                if ($this->request->data['consideration_amt'] == '') {
                    $consideration_amt = 0;
                } else {
                    $consideration_amt = $this->request->data['consideration_amt'];
                }
                if (isset($json2array['prop_details_seller'])) {
                    $prop_details = $json2array['prop_details_seller'];
                } else {
                    $prop_details = array();
                }
                if (!empty($regconf_attr)) {
                    foreach ($prop_details as $key => $value) {
                        if ($this->request->data['usage_main_catg_id'] == $value['usage_main_catg_id']) {
                            //unset($prop_details[$key]);
                        }
                    }
                }

               // pr($usage_sub_catg_id);//exit;
                array_push($prop_details, array('usage_main_catg_id' => $usage_main_catg_id, 'usage_sub_catg_id' => $usage_sub_catg_id, 'item_value' => $item_value, 'area_unit' => $area_unit, 'final_value' => $final_value, 'maindesc' => $maindesc, 'subdesc' => $subdesc, 'unit_desc' => $unit_desc, 'consideration_amt' => $consideration_amt));
               // pr($prop_details);exit;
                $json2array['prop_details_seller'] = $prop_details;
            }
            $file = new File(WWW_ROOT . 'files/jsonfile_' . $this->Auth->user('user_id') . '.json', true);

            $file->write(json_encode($json2array));
            $this->set('prop_details', $prop_details);
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_property_details($csrf = NULL, $property_index_id) {
        $this->autoRender = false;
        $token_no = $this->Session->read('Leg_Selectedtoken');
        $this->loadModel('Leg_property_details_entry');
        $this->loadModel('Leg_trn_valuation');
        $this->loadModel('Leg_parameter');
        $this->Leg_property_details_entry->deleteAll(['token_no' => $token_no, 'property_id' => $property_index_id]);
        $string3 = $this->Leg_trn_valuation->query('select val_id FROM ngdrstab_trn_legacy_valuation where token_no=' . $token_no . ' and property_id=' . $property_index_id);
        foreach ($string3 as $s) {
            $del_val_id = $this->Leg_trn_valuation->query('delete from ngdrstab_trn_legacy_valuation_details where val_id=' . $s[0]['val_id']);
        }
        $this->Leg_trn_valuation->deleteAll(['token_no' => $token_no, 'property_id' => $property_index_id]);
        $this->Leg_parameter->deleteAll(['token_id' => $token_no, 'property_id' => $property_index_id]);
        $this->Session->setFlash('Records Deleted Successfully');
        return $this->redirect(array('action' => 'property'));
    }

    public function get_subcatg($csrftoken = NULL) {

        $lang = $this->Session->read("sess_langauge");
        $this->loadModel('main_category');
        $this->autoRender = FALSE;
        $maincatgid = $_GET['maincatgid'];
        // pr('hioiii');
        // pr($maincatgid); exit();
//        $sub_category = $this->main_category->query("select distinct ngdrstab_mst_usage_sub_category.usage_sub_catg_id, usage_sub_catg_desc_$lang from ngdrstab_mst_usage_category
//inner join  ngdrstab_mst_usage_sub_category on  ngdrstab_mst_usage_category.usage_sub_catg_id=ngdrstab_mst_usage_sub_category.usage_sub_catg_id
//where usage_main_catg_id=$maincatgid;");
//
//        foreach ($sub_category as $sub_cat) {
//            $sub_category1[$sub_cat[0]['usage_sub_catg_id']] = $sub_cat[0]['usage_sub_catg_desc_'.$lang];
//        }
//
//        echo json_encode($sub_category1);
//        exit;

        $this->loadModel('Usagesub');
        $sub_category1 = $this->Usagesub->find('list', array('fields' => array('Usagesub.usage_sub_catg_id', 'Usagesub.usage_sub_catg_desc_' . $lang),
            'joins' => array(
                array('table' => 'ngdrstab_mst_usage_category', 'alias' => 'main_usage', 'conditions' => array('Usagesub.usage_sub_catg_id=main_usage.usage_sub_catg_id')),
            ),
            'conditions' => array('usage_main_catg_id' => $maincatgid)));
        echo json_encode($sub_category1);
        // pr('fghfghfg');
        // pr($sub_category1 );exit();
        exit;
        //   $this->set('sub_category1', $sub_category1);
        //echo json_encode($json2array['sub_category1']);
        // exit;
    }

    ////Added Changes on dated 01Dec2020
    public function get_subdivision_for_property($districtid) {
        //$this->autoRender = FALSE;
        $lang = $this->Session->read("sess_langauge");
        $this->loadModel('Subdivision');
        $sub_division = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_' . $lang), 'conditions' => array('district_id' => $districtid)));
        return $sub_division;
    }

    public function get_revenuecircle_for_property() {
        $subdivisionid = $_GET['subdivisionid'];
        // pr($subdivisionid);exit;
        $this->autoRender = FALSE;
        $lang = $this->Session->read("sess_langauge");
        $this->loadModel('taluka');
        $revenue_circle = $this->taluka->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $subdivisionid)));
        echo json_encode($revenue_circle);
        exit;
        //return $revenue_circle;
    }

    public function get_tehsil_for_property() {
        $talukaid = $_GET['talukaid'];
        $this->autoRender = FALSE;
        $lang = $this->Session->read("sess_langauge");
        $this->loadModel('circle');
        $tehsil = $this->circle->find('list', array('fields' => array('circle_id', 'circle_name_' . $lang), 'conditions' => array('taluka_id' => $talukaid)));
        echo json_encode($tehsil);
        exit;
        //return $tehsil;
    }

    public function get_village_for_property() {
        $circleid = $_GET['circleid'];
        $this->autoRender = FALSE;
        $this->loadModel('VillageMapping');
        // $taluka_id = $_GET['talukaid'];
        $village = $this->VillageMapping->find('list', array('fields' => array('village_id', 'village_name_en'), 'conditions' => array('circle_id' => $circleid)));
        echo json_encode($village);
        exit;
    }

}
