<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/welcome/*', array('controller' => 'Users', 'action' => 'welcome'));
Router::connect('/login/*', array('controller' => 'Users', 'action' => 'login'));
Router::connect('/logout', array('controller' => 'Users', 'action' => 'logout'));

Router::connect('/', array('controller' => 'Users', 'action' => 'welcomenote'));
Router::connect('/', array('controller' => 'Users', 'action' => 'normalappointment'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/* Registration */
Router::connect('/citizenregistration/*', array('controller' => 'Users', 'action' => 'citizenregistration'));
Router::connect('/checkidproofcitizen/*', array('controller' => 'Users', 'action' => 'checkidproofcitizen'));
Router::connect('/regdivision/*', array('controller' => 'Users', 'action' => 'get_division_name'));
Router::connect('/regdistrict/*', array('controller' => 'Users', 'action' => 'get_district_name'));
Router::connect('/regtaluka/*', array('controller' => 'Users', 'action' => 'get_taluka_name'));
Router::connect('/checkcaptcha/*', array('controller' => 'Users', 'action' => 'checkcaptcha'));
Router::connect('/checkemail/*', array('controller' => 'Users', 'action' => 'checkemail'));
Router::connect('/checkmobileno/*', array('controller' => 'Users', 'action' => 'checkmobileno'));
Router::connect('/checkuser/*', array('controller' => 'Users', 'action' => 'checkuser'));
Router::connect('/activate/*', array('controller' => 'Users', 'action' => 'activate'));
Router::connect('/deactivate/*', array('controller' => 'Users', 'action' => 'deactivate'));
Router::connect('/checkusername/*', array('controller' => 'Users', 'action' => 'checkusername'));
Router::connect('/checkusersro/*', array('controller' => 'Masters', 'action' => 'checkusersro'));

Router::connect('/checkusercitizen/*', array('controller' => 'Users', 'action' => 'checkusercitizen'));
Router::connect('/checkofficename/*', array('controller' => 'Masters', 'action' => 'checkofficename'));
Router::connect('/checkemailcitizen/*', array('controller' => 'Users', 'action' => 'checkemailcitizen'));
Router::connect('/checkmobilenocitizen/*', array('controller' => 'Users', 'action' => 'checkmobilenocitizen'));
Router::connect('/checkuidcitizen/*', array('controller' => 'Users', 'action' => 'checkuidcitizen'));


Router::connect('/getsubsubcategory/*', array('controller' => 'Property', 'action' => 'getsubsubcategory'));
Router::connect('/getsubcategory/*', array('controller' => 'Property', 'action' => 'getsubcategory'));
Router::connect('/getparamlist/*', array('controller' => 'Property', 'action' => 'getusageitemlist'));
// Property valuation
Router::connect('/districtchangeevent/*', array('controller' => 'Property', 'action' => 'district_change_event'));
Router::connect('/village_change_event/*', array('controller' => 'Property', 'action' => 'village_change_event'));

Router::connect('/getDivision/*', array('controller' => 'Functions', 'action' => 'getdivisionlist'));
Router::connect('/getDistrict/*', array('controller' => 'Functions', 'action' => 'getdistrictlist'));
Router::connect('/getSubDivision/*', array('controller' => 'Functions', 'action' => 'getsubdivisionlist'));
Router::connect('/getTaluka/*', array('controller' => 'Functions', 'action' => 'gettalukalist'));
Router::connect('/getLandtype/*', array('controller' => 'Functions', 'action' => 'getlandtype'));
Router::connect('/getCircle/*', array('controller' => 'Functions', 'action' => 'getcirclelist'));
Router::connect('/getVillage/*', array('controller' => 'Functions', 'action' => 'getvillagelist'));
Router::connect('/regoffice/*', array('controller' => 'Users', 'action' => 'regoffice'));

Router::connect('/getLnkItemList/*', array('controller' => 'Functions', 'action' => 'getLinkedInputItemList'));
Router::connect('/getMaxOrder/*', array('controller' => 'Functions', 'action' => 'getMaxOutOrderId'));
Router::connect('/getvalList/*', array('controller' => 'Reports', 'action' => 'getvaluationlist'));
Router::connect('/rptview/*', array('controller' => 'Reports', 'action' => 'rptview'));

//Citizen Entry
Router::connect('/attributedelete/*', array('controller' => 'Citizenentry', 'action' => 'attributedelete'));
Router::connect('/attributesave/*', array('controller' => 'Citizenentry', 'action' => 'attributesave'));
Router::connect('/itemssave/*', array('controller' => 'Citizenentry', 'action' => 'itemssave'));

Router::connect('/property/*', array('controller' => 'Citizenentry', 'action' => 'property_details'));

/*---------------- for survey no entry----------------------------------------------------------------------------------------------*/

Router::connect('/get_village_survey/*', array('controller' => 'Functions', 'action' => 'getvillagelist_surveyno'));
Router::connect('/get_level1_list1/*', array('controller' => 'Masters', 'action' => 'getlevel1_list1'));


/* --------------------------------------Property Valuation Rule (15-Feb-2017)---------------------------------------------------------------------------------------- */
Router::connect('/removeUsageItem/*', array('controller' => 'ValuationRules', 'action' => 'remove_usage_item'));
Router::connect('/removeUsageListItem/*', array('controller' => 'ValuationRules', 'action' => 'remove_usage_list_item'));
Router::connect('/removeValRule/*', array('controller' => 'ValuationRules', 'action' => 'remove_val_rule'));
Router::connect('/removeValSubRule/*', array('controller' => 'ValuationRules', 'action' => 'remove_valuation_subrule'));
Router::connect('/getRuleFlags/*', array('controller' => 'ValuationRules', 'action' => 'get_rule_flags'));
Router::connect('/copyValRule/*', array('controller' => 'ValuationRules', 'action' => 'copy_rule'));
Router::connect('/copyValSubrule/*', array('controller' => 'ValuationRules', 'action' => 'copy_subrule'));
Router::connect('/removeRuleItem/*', array('controller' => 'ValuationRules', 'action' => 'remove_rule_item'));

Router::connect('/removeRulefeeItem/*', array('controller' => 'Fees', 'action' => 'remove_rule_fee_item'));
/* ---------------------------------------------------------------------------------------------------------------------------- */
// --------------------------------------Usage Category-------------------------------------------------------------- 
Router::connect('/getusage_sub_catg_id/*', array('controller' => 'ValuationRules', 'action' => 'get_usage_sub_category_list'));
Router::connect('/getusage_sub_sub_catg_id/*', array('controller' => 'ValuationRules', 'action' => 'get_usage_sub_sub_category_list'));
// ------------------------------------------------------------------------------------------------------------------
Router::connect('/saveEvalSubRule/*', array('controller' => 'ValuationRules', 'action' => 'save_eval_subrule'));
Router::connect('/removeEvalSubRule/*', array('controller' => 'ValuationRules', 'action' => 'remove_eval_subrule'));
Router::connect('/getcdrflags/*', array('controller' => 'ValuationRules', 'action' => 'getcdrflags'));
Router::connect('/getrulebycdrv/*', array('controller' => 'ValuationRules', 'action' => 'getrulebycdrv'));
Router::connect('/getsubsubruledesc/*', array('controller' => 'ValuationRules', 'action' => 'getsubsubruledesc'));
Router::connect('/getcategoryids/*', array('controller' => 'ValuationRules', 'action' => 'getcategoryids'));
Router::connect('/getMaxEvalSubRuleOrder/*', array('controller' => 'ValuationRules', 'action' => 'getMaxOutOrderId'));
Router::connect('/getitemtype/*', array('controller' => 'Functions', 'action' => 'getitemtype'));
Router::connect('/getSubruleList/*', array('controller' => 'ValuationRules', 'action' => 'get_subrule_list'));
Router::connect('/getSubrule/*', array('controller' => 'Functions', 'action' => 'getsubrule'));
Router::connect('/getLnkItemList/*', array('controller' => 'Functions', 'action' => 'getLinkedInputItemList'));
/* ------------------------------------------------------------------------------------------------------------------------------------------------------ */
//-------------------------------------Fee Item Related-------------------------------------------------------------------------------------
Router::connect('/removeFeeItem/*', array('controller' => 'Fees', 'action' => 'remove_article_fee_item'));
Router::connect('/removeFeeListItem/*', array('controller' => 'Fees', 'action' => 'remove_fee_item_list'));
//* ------------------------------------Fees Rule Related----------------------------------------------------------------------------------- */
Router::connect('/copyFeeRule/*', array('controller' => 'Fees', 'action' => 'copy_fee_rule'));
Router::connect('/removeFeeRule/*', array('controller' => 'Fees', 'action' => 'remove_fee_rule'));
Router::connect('/removeFeeRuleItem/*', array('controller' => 'Fees', 'action' => 'remove_fee_rule_item'));
Router::connect('/copyFeeSubRule/*', array('controller' => 'Fees', 'action' => 'copy_fee_sub_rule'));
Router::connect('/removeFeeSubRule/*', array('controller' => 'Fees', 'action' => 'remove_fee_sub_rule'));
//---------------------------------------------------------------------------------------------------------
Router::connect('/getArticleDesc/*', array('controller' => 'Fees', 'action' => 'get_article_desc'));
//Router::connect('/saveFeeSubRule/*', array('controller' => 'Fees', 'action' => 'savefeesubrule'));
Router::connect('/getFeeRuleList/*', array('controller' => 'Fees', 'action' => 'get_json_article_rule_list'));
Router::connect('/getFeeRuleCheckList/*', array('controller' => 'Fees', 'action' => 'get_article_rule_check_list'));
Router::connect('/getFeeMaxOrder/*', array('controller' => 'Fees', 'action' => 'get_fee_max_order_id'));
//Router::connect('/getFeeSubRuleData/*', array('controller' => 'Fees', 'action' => 'getfeesubruledata'));
//Router::connect('/getFeeSubRuleList/*', array('controller' => 'Fees', 'action' => 'getfeesubrulelist'));
Router::connect('/getFeeRuleItems/*', array('controller' => 'Fees', 'action' => 'get_article_fee_rule_items'));
Router::connect('/getFeeRuleInputs/*', array('controller' => 'Fees', 'action' => 'get_article_fee_rule_item_input'));
Router::connect('/getFeeRuleInputs1/*', array('controller' => 'Fees', 'action' => 'get_article_fee_rule_item_input_sd'));
//---------------------------------------------------------------------------------------------------------
Router::connect('/calculateFees/*', array('controller' => 'Fees', 'action' => 'calculate_fees'));
Router::connect('/calculateMV/*', array('controller' => 'Fees', 'action' => 'calculate_mv'));
Router::connect('/getFeeCalcList/*', array('controller' => 'Reports', 'action' => 'rpt_fee_calc_list'));
Router::connect('/getArticleGovBodyFlag/*', array('controller' => 'Fees', 'action' => 'get_article_gov_body_flag'));
Router::connect('/viewFeeCalc/*', array('controller' => 'Fees', 'action' => 'view_fee_calculation'));
Router::connect('/deleteexemption/*', array('controller' => 'Fees', 'action' => 'delete_fee_exemption'));
//* ---------------------------------------------------------------------------------------------------------------------------------------- */
Router::connect('/getCertFees/*', array('controller' => 'Fees', 'action' => 'get_certificate_fees'));
Router::connect('/getPaymentDetails/*', array('controller' => 'Registration', 'action' => 'get_payment_details_simple_reciept'));
/* -------------------------------------------------Citizon Entry----------------------------------------------------------------------------- */
//* ---------------------------------------------------------------------------------------------------------------------------------------- */

/* -------------------------------------------------Citizon Entry----------------------------------------------------------------------------- */
Router::connect('/annexure11/*', array('controller' => 'Reports', 'action' => 'pre_registration_docket'));
Router::connect('/getFeesCalcFlag/*', array('controller' => 'Citizenentry', 'action' => 'get_fees_falc_ids'));
Router::connect('/updateSD/*', array('controller' => 'Citizenentry', 'action' => 'update_sd'));
Router::connect('/viewSDCalc/*', array('controller' => 'Fees', 'action' => 'view_sd_calc'));
Router::connect('/viewExemption/*', array('controller' => 'Fees', 'action' => 'view_exemption'));
Router::connect('/getAdjDocExsAmt/*', array('controller' => 'Citizenentry', 'action' => 'get_adj_doc_exess_amt'));
Router::connect('/getAdjDocExsAmtDetail/*', array('controller' => 'Citizenentry', 'action' => 'get_adj_doc_exess_amt_detail'));
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
Router::connect('/viewRegSummary1/*', array('controller' => 'Reports', 'action' => 'rpt_reg_summary1'));
Router::connect('/viewRegSummary2/*', array('controller' => 'Reports', 'action' => 'rpt_reg_summary2'));
Router::connect('/payment_cashbook_pdf/*', array('controller' => 'Reports', 'action' => 'payment_cashbook'));


Router::connect('/index_register_pdf/*', array('controller' => 'PunjabReports', 'action' => 'index_register_1'));
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
