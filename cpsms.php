<?php

require_once 'cpsms.civix.php';
// phpcs:disable
use CRM_Cpsms_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function cpsms_civicrm_config(&$config): void {
  _cpsms_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function cpsms_civicrm_install(): void {
  $groupID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup','sms_provider_name','id','name');
  $params  =
    array('option_group_id' => $groupID,
      'label' => 'CPSMS',
      'value' => 'cpsms.handler',
      'name'  => 'cpsms',
      'is_default' => 1,
      'is_active'  => 1,
      'version'    => 3,);

  civicrm_api3( 'option_value','create', $params );

  _cpsms_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function cpsms_civicrm_enable(): void {
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue','cpsms' ,'id','name');
  if ($optionID) {
    CRM_Core_BAO_OptionValue::setIsActive($optionID, TRUE);
  }

  $filter    =  array('name' => 'cpsms.handler');
  $Providers =  CRM_SMS_BAO_Provider::getProviders(False, $filter, False);
  if ($Providers){
    foreach($Providers as $key => $value){
      CRM_SMS_BAO_Provider::setIsActive($value['id'], TRUE);
    }
  }
  _cpsms_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function cpsms_civicrm_disable(): void {
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue','cpsms' ,'id','name');
  if ($optionID) {
    CRM_Core_BAO_OptionValue::setIsActive($optionID, False);
  }

  $filter    =  array('name' => 'cpsms.handler');
  $Providers =  CRM_SMS_BAO_Provider::getProviders(False, $filter, False);
  if ($Providers){
    foreach($Providers as $key => $value){
      CRM_SMS_BAO_Provider::setIsActive($value['id'], TRUE);
    }
  }
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function cpsms_civicrm_uninstall() {
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue','cpsms','id','name');
  if ($optionID) {
    CRM_Core_BAO_OptionValue::del($optionID);
  }

  $filter    =  array('name'  => 'cpsms.handler');
  $Providers =  CRM_SMS_BAO_Provider::getProviders(False, $filter, False);
  if ($Providers){
    foreach($Providers as $key => $value){
      CRM_SMS_BAO_Provider::del($value['id']);
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function cpsms_civicrm_preProcess($formName, &$form): void {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function cpsms_civicrm_navigationMenu(&$menu): void {
//  _cpsms_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _cpsms_civix_navigationMenu($menu);
//}
