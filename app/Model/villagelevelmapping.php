
<?php

class villagelevelmapping extends AppModel {

    //put your code here.
 
 public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_lnk_village_location_mapping';
    
    public function get_level1grid($village_id) {
        $record = $this->query("select distinct a.level_1_id, a.id,m.village_id,a.level_1_desc_en,a.level_1_desc_ll,a.level_1_desc_ll1,a.level_1_desc_ll2,a.level_1_desc_ll3,a.level_1_desc_ll4,b.village_name_en,
                        b.village_name_ll,c.surveynotype_desc_en,c.surveynotype_desc_ll,c.surveynotype_id
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_location_levels_1_property a on a.village_id=m.village_id and a.level_1_id = m.level1_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id
                            left outer join ngdrstab_mst_surveyno_type c on c.surveynotype_id=a.surveynotype_id
                            where m.village_id=?", array($village_id));
        return $record;
    }
    
     public function get_level1listgrid($village_id,$hflevel1code) {
        $record = $this->query("select distinct a.prop_level1_list_id,a.id,m.village_id,a.list_1_desc_en,a.list_1_desc_ll,a.list_1_desc_ll1,a.list_1_desc_ll2,a.list_1_desc_ll3,a.list_1_desc_ll4,b.village_name_en,
                        b.village_name_ll,a.level_1_from_range,a.level_1_to_range
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_loc_level_1_prop_list a on a.village_id=m.village_id and a.prop_level1_list_id = m.prop_level1_list_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id                            
                            where m.village_id=? and m.level1_id =?", array($village_id,$hflevel1code));
        return $record;
    }
    
    public function get_level2grid($village_id,$hflevel1code,$hflevellist1code) {
        $record = $this->query("select  distinct a.level_2_id,a.id,m.village_id,a.level_2_desc_en,a.level_2_desc_ll,a.level_2_desc_ll1,a.level_2_desc_ll2,a.level_2_desc_ll3,a.level_2_desc_ll4,b.village_name_en,
                        b.village_name_ll,c.surveynotype_desc_en,c.surveynotype_desc_ll,c.surveynotype_id
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_location_levels_2_property a on a.village_id=m.village_id and a.level_2_id = m.level2_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id 
			    left outer join ngdrstab_mst_surveyno_type c on c.surveynotype_id=a.surveynotype_id                           
                           where m.village_id=? and m.level1_id =? and m.prop_level1_list_id=?", array($village_id,$hflevel1code,$hflevellist1code));
        return $record;
    }
    
    public function get_level2listgrid($village_id,$hflevel1code,$hflevellist1code,$hflevel2code) {
        $record = $this->query("select distinct a.prop_level2_list_id,a.id,m.village_id,a.list_2_desc_en,a.list_2_desc_ll,a.list_2_desc_ll1,a.list_2_desc_ll2,a.list_2_desc_ll3,a.list_2_desc_ll4,b.village_name_en,
                            b.village_name_ll,a.level_2_from_range,a.level_2_to_range
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_loc_level_2_prop_list a on a.village_id=m.village_id and a.prop_level2_list_id = m.prop_level2_list_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id                            
                            where m.village_id=? and m.level1_id =? and m.prop_level1_list_id=? and m.level2_id=?", array($village_id,$hflevel1code,$hflevellist1code,$hflevel2code));
        return $record;
    }
    
     public function get_level3grid($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code) {
        $record = $this->query("select  distinct a.level_3_id,a.id,m.village_id,a.level_3_desc_en,a.level_3_desc_ll,a.level_3_desc_ll1,a.level_3_desc_ll2,a.level_3_desc_ll3,a.level_3_desc_ll4,b.village_name_en,
                        b.village_name_ll,c.surveynotype_desc_en,c.surveynotype_desc_ll,c.surveynotype_id
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_location_levels_3_property a on a.village_id=m.village_id and a.level_3_id = m.level3_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id 
			    left outer join ngdrstab_mst_surveyno_type c on c.surveynotype_id=a.surveynotype_id                            
                            where m.village_id=? and m.level1_id =? and m.prop_level1_list_id=? and m.level2_id=? and m.prop_level2_list_id=?", array($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code));
        return $record;
    }
    
    public function get_level3listgrid($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code,$hflevel3code) {
        $record = $this->query("select distinct a.prop_leve3_list_id,a.id,m.village_id,a.list_3_desc_en,a.list_3_desc_ll,a.list_3_desc_ll1,a.list_3_desc_ll2,a.list_3_desc_ll3,a.list_3_desc_ll4,b.village_name_en,
                            b.village_name_ll,a.level_3_from_range,a.level_3_to_range
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_loc_level_3_prop_list a on a.village_id=m.village_id and a.prop_leve3_list_id = m.prop_level3_list_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id                           
                            where m.village_id=? and m.level1_id =? and m.prop_level1_list_id=? and m.level2_id=? and m.prop_level2_list_id=? and m.level3_id=?", array($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code,$hflevel3code));
        return $record;
    }
    
    public function get_level4grid($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code,$hflevel3code,$hflevellist3code) {
        $record = $this->query("select  distinct a.level_4_id,a.id,m.village_id,a.level_4_desc_en,a.level_4_desc_ll,a.level_4_desc_ll1,a.level_4_desc_ll2,a.level_4_desc_ll3,a.level_4_desc_ll4,b.village_name_en,
                        b.village_name_ll,c.surveynotype_desc_en,c.surveynotype_desc_ll,c.surveynotype_id
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_location_levels_4_property a on a.village_id=m.village_id and a.level_4_id = m.level4_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id 
			    left outer join ngdrstab_mst_surveyno_type c on c.surveynotype_id=a.surveynotype_id                            
                            where m.village_id=? and m.level1_id =? and m.prop_level1_list_id=? and m.level2_id=? and m.prop_level2_list_id=? and m.level3_id=? and m.prop_level3_list_id=?", array($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code,$hflevel3code,$hflevellist3code));
        return $record;
    }
    
    public function get_level4listgrid($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code,$hflevel3code,$hflevellist3code,$hflevel4code) {
        $record = $this->query("select distinct a.prop_level4_list_id,a.id,m.village_id,a.list_4_desc_en,a.list_4_desc_ll,a.list_4_desc_ll1,a.list_4_desc_ll2,a.list_4_desc_ll3,a.list_4_desc_ll4,b.village_name_en,
                            b.village_name_ll,a.level_4_from_range,a.level_4_to_range
                            from ngdrstab_conf_lnk_village_location_mapping m
                            inner join ngdrstab_mst_loc_level_4_prop_list a on a.village_id=m.village_id and a.prop_level4_list_id = m.prop_level4_list_id
                            inner join ngdrstab_conf_admblock7_village_mapping b on b.village_id=m.village_id                              
                            where m.village_id=? and m.level1_id =? and m.prop_level1_list_id=? and m.level2_id=? and m.prop_level2_list_id=? and m.level3_id=? and m.prop_level3_list_id=? and m.level4_id=?", array($village_id,$hflevel1code,$hflevellist1code,$hflevel2code,$hflevellist2code,$hflevel3code,$hflevellist3code,$hflevel4code));
        return $record;
    }
    
    

}

