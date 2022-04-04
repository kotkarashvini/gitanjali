<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of corpcouncil
 *
 * @author Administrator
 */


App::uses('AuthComponent', 'Controller/Component');

class counter extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_counter';
 
}