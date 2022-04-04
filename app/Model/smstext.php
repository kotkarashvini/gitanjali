<?php

App::uses('AuthComponent', 'Controller/Component');

class smstext extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_conf_smstext';
    
}