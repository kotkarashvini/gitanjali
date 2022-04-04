<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usage_category
 *
 * @author Administrator
 */
class usage_category extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_usage_category';
    public $primaryKey = 'usage_cat_id';
   
    
    
    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_usage_category';
        $duplicate['PrimaryKey'] = 'usage_cat_id';
        $fields = array(); 
        array_push($fields, 'usage_main_catg_id,usage_sub_catg_id'); 
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist, $adminLevelConfig) {

        $fieldlist = array();
         
        $fieldlist['usage_main_catg_id']['select'] = 'is_select_req';
        $fieldlist['usage_sub_catg_id']['select'] = 'is_select_req';
      
        return $fieldlist;
    }
    

    function get_count($data) {
        try {
            $tn = 0;
            $listarray = array();
            $listarray[39] = 2;
            $input = $data['frm']['usage_param_id'];
            for ($i = 0; $i < count($input); $i++) {
                $rec = $this->query('select usage_param_id,is_list_field_flag from ngdrstab_mst_usage_items_list where usage_param_id=?', array($input[$i]));
                if ($rec[0][0]['is_list_field_flag'] == 'N') {
                    $tn = $tn + 1;
                } elseif ($rec[0][0]['is_list_field_flag'] == 'Y') { {
                        $listval = $this->query('select count(*) from ngdrstab_conf_list_items where item_id=?', array($input[$i]));

                        $listarray[$input[$i]] = $listval[0][0]['count'];
                    }
                }
            }

            return $listarray;
        } catch (Exception $ex) {
            
        }
    }

    function get_condition($data) {
        $tn = 0;
        $listarray = array();
        $list = $this->get_count($data);
        $count = 1;
        foreach ($list as $k => $v) {
            $count = $count * $v;
        }
        $input = $data['frm']['usage_param_id'];
        $listarray[0][0] = 'RRR==0';
        $listarray[0][1] = 'RRR!=0';

        $x = 1;
        for ($i = 0; $i < count($input); $i++) {
            $rec = $this->query('select usage_param_id,usage_param_code,is_list_field_flag from ngdrstab_mst_usage_items_list where usage_param_id=?', array($input[$i]));
            if ($rec[0][0]['is_list_field_flag'] == 'Y') { {

                    $listval = $this->query('select item_desc_id from ngdrstab_conf_list_items where item_id=? order by item_desc_id', array($input[$i]));

                    for ($j = 0; $j < count($listval); $j++) {
                        $listarray[$x][$j] = $rec[0][0]['usage_param_code'] . '==' . $listval[$j][0]['item_desc_id'];
                    }
                    $x++;
                }
            }
        }
        $conditions = $this->create_condition($listarray, $count);
        return $conditions;
    }

    function create_condition($listarray, $count) {
        $cond_array = array();

        for ($p = 0; $p < count($listarray); $p++) {
            foreach ($listarray[$p] as $val1) {

                if (isset($listarray[$p + 1])) {
                    foreach ($listarray[$p + 1] as $val2) {
                        if (isset($listarray[$p + 2])) {

                            foreach ($listarray[$p + 2] as $val3) {
                                if (isset($listarray[$p + 3])) {
                                    foreach ($listarray[$p + 3] as $val4) {
                                        if (isset($listarray[$p + 4])) {
                                            foreach ($listarray[$p + 4] as $val5) {
                                                if (isset($listarray[$p + 5])) {
                                                    foreach ($listarray[$p + 5] as $val6) {
                                                        $cond = $val1 . ' && ' . $val2 . ' && ' . $val3 . ' && ' . $val4 . '&&' . $val5 . '&&' . $val6;
                                                        array_push($cond_array, $cond);
                                                    }
                                                } else if (!(isset($listarray[$p + 5])) && count($cond_array) < $count) {
                                                    $cond = $val1 . ' && ' . $val2 . ' && ' . $val3 . ' && ' . $val4 . '&&' . $val5;
                                                    array_push($cond_array, $cond);
                                                }
                                            }
                                        } else if (!(isset($listarray[$p + 4])) && count($cond_array) < $count) {
                                            $cond = $val1 . ' && ' . $val2 . ' && ' . $val3 . ' && ' . $val4;
                                            array_push($cond_array, $cond);
                                        }
                                    }
                                } else if (!(isset($listarray[$p + 3])) && count($cond_array) < $count) {

                                    $cond = $val1 . ' && ' . $val2 . ' && ' . $val3;
                                    array_push($cond_array, $cond);
                                }
                            }
                        } else if (!(isset($listarray[$p + 2])) && count($cond_array) < $count) {
                            $cond = $val1 . ' && ' . $val2;
                            array_push($cond_array, $cond);
                        }
                    }
                } else if (!(isset($listarray[$p + 1])) && count($cond_array) < $count) {
                    $cond = $val1;
                    array_push($cond_array, $cond);
                }
            }
        }
        return $cond_array;
    }
    
    public function get_gridsub($hfcode) {
        try {
            $q1 = $this->query("SELECT distinct a.usage_sub_catg_id,a.usage_main_catg_id,c.id,c.dolr_usage_code,c.usage_sub_catg_desc_en,c.usage_sub_catg_desc_ll
                        ,c.usage_sub_catg_desc_ll1,c.usage_sub_catg_desc_ll2,c.usage_sub_catg_desc_ll3,c.usage_sub_catg_desc_ll4,b.usage_main_catg_desc_en,b.usage_main_catg_desc_ll,c.dolr_usage_code,c.subcatg_ll_activation_flag,c.subcatg_ll1_activation_flag,c.subcatg_ll2_activation_flag
                           from ngdrstab_mst_usage_category a 
                            inner join ngdrstab_mst_usage_main_category b on b.usage_main_catg_id = a.usage_main_catg_id
                            inner join ngdrstab_mst_usage_sub_category c on c.usage_sub_catg_id = a.usage_sub_catg_id
                            where a.usage_main_catg_id= ?",array($hfcode));
           
            return $q1;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    public function get_gridsubsub($hfcode,$hfsubcode) {
        try {
            $q1 = $this->query("SELECT distinct a.usage_sub_sub_catg_id,a.usage_main_catg_id,a.usage_sub_catg_id,d.id,c.dolr_usage_code,c.usage_sub_catg_desc_en,c.usage_sub_catg_desc_ll,
                            c.usage_sub_catg_desc_ll1,c.usage_sub_catg_desc_ll2,c.usage_sub_catg_desc_ll3,c.usage_sub_catg_desc_ll4,b.usage_main_catg_desc_en,b.usage_main_catg_desc_ll,d.usage_sub_sub_catg_desc_en,d.usage_sub_sub_catg_desc_ll,
                            d.usage_sub_sub_catg_desc_ll1,d.usage_sub_sub_catg_desc_ll2,d.usage_sub_sub_catg_desc_ll3,d.usage_sub_sub_catg_desc_ll4,d.contsruction_type_flag,
                            d.depreciation_flag,d.road_vicinity_flag,d.user_defined_dependency1_flag,d.user_defined_dependency2_flag,d.dolr_usage_code
                            from ngdrstab_mst_usage_category a 
                            inner join ngdrstab_mst_usage_main_category b on b.usage_main_catg_id = a.usage_main_catg_id
                            inner join ngdrstab_mst_usage_sub_category c on c.usage_sub_catg_id = a.usage_sub_catg_id
                            inner join ngdrstab_mst_usage_sub_sub_category d on d.usage_sub_sub_catg_id = a.usage_sub_sub_catg_id
                            where a.usage_main_catg_id= ? and a.usage_sub_catg_id= ?",array($hfcode,$hfsubcode));
           
            return $q1;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }
    
    public function get_griditem($hfcode,$hfsubcode,$hfsubsubcode) {
        try {
            $q1 = $this->query("SELECT distinct a.id,a.usage_param_id,a.usage_sub_sub_catg_id,a.usage_main_catg_id,a.usage_sub_catg_id,c.dolr_usage_code,c.usage_sub_catg_desc_en,c.usage_sub_catg_desc_ll,
                            b.usage_main_catg_desc_en,b.usage_main_catg_desc_ll,d.usage_sub_sub_catg_desc_en,d.usage_sub_sub_catg_desc_ll,e.usage_param_desc_en,e.usage_param_desc_ll
                            from ngdrstab_mst_usage_lnk_category a 
                            inner join ngdrstab_mst_usage_main_category b on b.usage_main_catg_id = a.usage_main_catg_id
                            inner join ngdrstab_mst_usage_sub_category c on c.usage_sub_catg_id = a.usage_sub_catg_id
                            inner join ngdrstab_mst_usage_sub_sub_category d on d.usage_sub_sub_catg_id = a.usage_sub_sub_catg_id
                            inner join ngdrstab_mst_usage_items_list e on e.usage_param_id = a.usage_param_id
                            where a.usage_main_catg_id= ? and a.usage_sub_catg_id= ? and a.usage_sub_sub_catg_id= ?",array($hfcode,$hfsubcode,$hfsubsubcode));
           
            return $q1;
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

}
