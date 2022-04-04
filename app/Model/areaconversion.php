<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of areaconversion
 *
 * @author Administrator
 */
class areaconversion extends AppModel {

    //put your code here.
    public function standardareaconversion($actualarea = NULL, $unitid = NULL) {
        $unit = ClassRegistry::init('unit')->find('all', array('conditions' => array('unit_id' => $unitid)));
//        pr($unit[0]['unit']['conversion_formula']);
//        exit;
        $conversionformula = $actualarea * $unit[0]['unit']['conversion_formula'];
        return eval("return ($conversionformula);");
    }
    
    public function areatypeconversion($actualarea = NULL, $areatypeid = NULL) {
        $areatype = ClassRegistry::init('areatype')->find('all', array('conditions' => array('rate_built_area_type_id' => $areatypeid)));
//        pr($unit[0]['unit']['conversion_formula']);
//        exit;
        $conversionformula = $actualarea * $areatype[0]['areatype']['conversion_factor'];
        return eval("return ($conversionformula);");
    }

}
