<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::allowTableOnStandardPages('tx_wswordpressgrab_log');

$TCA["tx_wswordpressgrab_log"] = array (
  "ctrl" => array (
    'title'     => 'LLL:EXT:ws_wordpressgrab/locallang_db.xml:tx_wswordpressgrab_log',   
    'label'     => 'postid', 
    'tstamp'    => 'tstamp',
    'crdate'    => 'crdate',
    'cruser_id' => 'cruser_id',
    'default_sortby' => "ORDER BY tstamp DESC",  
    'delete' => 'deleted',  
    'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
    'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_wswordpressgrab.gif',
  ),
  "feInterface" => array (
    "fe_admin_fieldList" => "postid,tstamp,title,times",
  )
);


t3lib_div::loadTCA('tt_content');
#$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:ws_wordpressgrab/flexform_ds.xml');


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ws_wordpressgrab/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

?>