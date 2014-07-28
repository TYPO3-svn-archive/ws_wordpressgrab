<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

//Task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Ws\\WsWordpressgrab\\Task\\GrabberTask'] = array(
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:tasks.grabber.name',
	'description' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:tasks.grabber.description',
	'additionalFields' => 'Ws\\WsWordpressgrab\\Task\\GrabberTaskAdditionalFieldProvider'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ws.' . $_EXTKEY,
	'Pi1',
	array(
        'Post'=> 'index',
	),
	// non-cacheable actions
	array(
		
	)
);