<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_wswordpressgrab_domain_model_post'] = array(
	'ctrl' => $TCA['tx_wswordpressgrab_domain_model_post']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, crdate, tstamp, wp_id, header, url, image, content',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, crdate, tstamp, wp_id, header, url, image, content'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_wswordpressgrab_domain_model_post',
				'foreign_table_where' => 'AND tx_wswordpressgrab_domain_model_post.pid=###CURRENT_PID### AND tx_wswordpressgrab_domain_model_post.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),

        'publish_date' => Array (
            'exclude' => 0,
            'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.crdate',
            'config' => Array (
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
            )
        ),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'wp_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.wp_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			),
		),
		'header' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.header',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'url' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.url',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'link_popup.gif',
                        'script' => 'browse_links.php?mode=wizard',
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    )
                )
            )
		),
        //TODO
        /*'image' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', array(
                'appearance' => array(
                    'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                ),
                'minitems' => 0,
                'maxitems' => 1,
            ), $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ),*/
         'image' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.image',
            'config'  => array(
                 'type'          => 'group',
                 'internal_type' => 'file',
                 'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                 'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
                 'uploadfolder'  => '',
                 'show_thumbs'   => 1,
                 'size'          => 1,
                 'maxitems'      => 1,
                 'minitems'      => 0
            ),
        ),
		'content' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang_db.xlf:tx_wswordpressgrab_domain_model_post.content',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'wizard_rte2.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'script' => 'wizard_rte.php',
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
			'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
		),
	),
);