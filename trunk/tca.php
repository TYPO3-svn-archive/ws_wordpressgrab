<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_wswordpressgrab_log"] = array (
	"ctrl" => $TCA["tx_wswordpressgrab_log"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "postid,tstamp,title,times"
	),
	"feInterface" => $TCA["tx_wswordpressgrab_log"]["feInterface"],
	"columns" => array (
		"postid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ws_wordpressgrab/locallang_db.xml:tx_wswordpressgrab_log.postid",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"eval" => "required,trim,num",
			)
		),
		"title" => Array (   
      "exclude" => 1,   
      "label" => "LLL:EXT:ws_wordpressgrab/locallang_db.xml:tx_wswordpressgrab_log.title",    
      "config" => Array (
        "type" => "input",  
        "size" => "30",
      )
    ),
		"tstamp" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ws_wordpressgrab/locallang_db.xml:tx_wswordpressgrab_log.tstamp",		
			"config" => Array (
				"type" => "input",	
				"size" => "10",	
				"eval" => "datetime",
			)
		),
		"times" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:ws_wordpressgrab/locallang_db.xml:tx_wswordpressgrab_log.times",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",
		    "eval" => "int",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "postid,tstamp,title,times")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>