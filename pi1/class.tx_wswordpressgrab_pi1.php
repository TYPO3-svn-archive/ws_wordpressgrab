<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Nikolay Orlenko <okolya@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Wordpress Grabber' for the 'ws_wordpressgrab' extension.
 *
 * @author	Nikolay Orlenko <okolya@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_wswordpressgrab
 */
class tx_wswordpressgrab_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_wswordpressgrab_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_wswordpressgrab_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'ws_wordpressgrab';	// The extension key.
	var $pi_checkCHash = true;
	var $aError = array(); //Errro stack
  var $sAddthisConfObj = '';
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
	  if (t3lib_div::_GP('postid') > 0) {
	    $this->vSetAddThisLog();
	  }
	  
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->vSetFlexFormConfig();
		
	  $sContent = '';
		$mConnection = mysql_connect( $this->conf['server'], $this->conf['user'], $this->conf['password']);
	  if (!$mConnection) {
      $sTmpError = $this->pi_getLL('error_connection') . ': ' . mysql_error(); 
      $this->aError[] = $sTmpError; 
      //die($sTmpError);
    }   

    if(!count($this->aError)) {
      $mD = mysql_select_db($this->conf['database'], $mConnection);
      
      mysql_query("SET NAMES 'utf8'", $mConnection);
      $sSelect = "SELECT * FROM  wp_posts WHERE post_password='' AND post_type='post' ORDER BY post_date DESC LIMIT " . $this->conf['limit'];      
      $mRes = mysql_query($sSelect, $mConnection);
      
      if(!$mRes){
        $sTmpError = $this->pi_getLL('error_query') . ': ' . mysql_error(); 
        $this->aError[] = $sTmpError; 
        //die($sTmpError);
      }
      
      while ($aRow = mysql_fetch_array($mRes)) {
        $sLink = '<a href="' . $aRow['guid'] . '" id="post-url-'. $aRow['ID'] .'" target="_blank">' . $aRow['post_title'] . '</a>';        
        $sTitle = $this->cObj->stdWrap($sLink, $this->conf['wrapTitle']);
        $sDescription = $this->cObj->stdWrap($aRow['post_content'], $this->conf['wrapDescription']);
        $sDescription = preg_replace('/\[caption[^]]*caption="([^"]*)"[^]]*\](.*?)\[\/caption\]/s', '<p>$1</p>$2', $sDescription);
        $sAddThisCode = $this->conf['addthis'];
        $sContent .= $this->cObj->stdWrap($sTitle . $sDescription . $sAddThisCode , $this->conf['wrapPost']);
        $sContent .= $this->sGetAddThisPostCode($aRow['ID'], $aRow['guid'], $aRow['post_title']);
      }
      $GLOBALS['TSFE']->additionalHeaderData['tx_wswordpressgrab_pi1'] = $this->sGetAddThisHandler($this->conf['addthisuser']);
      
      mysql_free_result($aRes);  
    }
    if(count($this->aError)) {
      $sContent = implode("<br/>", $this->aError);      
    }
	 return $this->pi_wrapInBaseClass($sContent);
	}
	
	/**
	 * "Add this" button code. Uses 'addthisconf' for configuration. See http://www.addthis.com/help/client-api#configuration-ui
	 * @param: int $iPostId
	 * @param: string $sUrl
	 * @param: string $sTitle
	 * @return: srring code for "Add this" button 
	 */
	protected function sGetAddThisPostCode($iPostId, $sUrl, $sTitle='') {
	  $sContent = " 
<a id=\"share-post-" . $iPostId . "\"></a>
<script type=\"text/javascript\">
addthis.button('#share-post-" . $iPostId . "', {" . $this->conf['addthisconf'] . "}, {url: \"" . $sUrl . "\", title: \"" . $sTitle . "\"});
</script>
";
	  return $sContent;
	}

/**
 * Set hadler for sharing and send ajax respond to log sharing. Uses prototype for ajax
 * @param $sUserName
 * @return string
 */	
protected function sGetAddThisHandler($sUserName) {
    $sHeader = "<script type=\"text/javascript\" src=\"typo3/contrib/prototype/prototype.js\"></script>";
    $sHeader .= "<script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=". $sUserName ."\"></script>";
    $sHeader .= "   
<script type=\"text/javascript\">
function eventHandler(evt) { 
  urlPost = evt.data.url;
  pos = urlPost.lastIndexOf('=')+1;
  idPost = urlPost.substr(pos);
  titlePost = $('post-url-' + idPost).innerHTML;
  
  var url = '". t3lib_div::getIndpEnv('TYPO3_REQUEST_SCRIPT') ."';
  var params = 'id=" . $GLOBALS['TSFE']->id ."&postid=' + idPost + '&title=' + titlePost;
  var ajax = new Ajax.Request(
     url, 
     {method: 'post',
      parameters: params
     }     
   );
} 

  
addthis.addEventListener('addthis.menu.share', eventHandler);
</script>";     
    return $sHeader;
  }
  
  /**
   * Record to DB table "tx_wswordpressgrab_log" information about sharing
   * @return void
   */
  protected function vSetAddThisLog(){
    $mRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "tx_wswordpressgrab_log", "postid=" . intval(t3lib_div::_GP('postid')));
    $mRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($mRes);
    $iTimes = (!$mRow['times']) ? 0 : $mRow['times'];
    $iTimes++;
    $sTitle = t3lib_div::_GP('title') ? htmlspecialchars_decode(rawurldecode(t3lib_div::_GP('title'))) : '';
    $aFields = array(
      'pid' => $GLOBALS['TSFE']->id,
      'times' => $iTimes, 
      'tstamp'=>$GLOBALS['SIM_EXEC_TIME'], 
      'postid'=>intval(t3lib_div::_GP('postid')),
      'title'=>$sTitle
    );
    if($mRow['uid']) {
      $mRes = $GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_wswordpressgrab_log", "uid=" . $mRow['uid'], $aFields);
    }
    else{
      $mRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_wswordpressgrab_log", $aFields);
    }
     
    $GLOBALS['TYPO3_DB']->sql_free_result($mRes);
    return '';
  }
  
 	/**
   * Set config with flexform data the value out of the flexforms
   *
   * @return  void
   */
	protected function vSetFlexFormConfig(){
	  $this->pi_initPIflexForm(); // Init FlexForm configuration for plugin
    
    // add the flexform values
    $this->conf['server']   = trim($this->mGetFlexForm('sDEF', 'server'));
    $this->conf['user']   = trim($this->mGetFlexForm('sDEF', 'user'));
    $this->conf['password']   = trim($this->mGetFlexForm('sDEF', 'password'));
    $this->conf['database']   = trim($this->mGetFlexForm('sDEF', 'database'));
    $this->conf['limit']   = intval($this->mGetFlexForm('s_add', 'limit'));
    $this->conf['wrapTitle']['stdWrap.']['wrap']   = trim($this->mGetFlexForm('s_add', 'wrapTitle'));
    $this->conf['wrapDescription']['stdWrap.']['wrap']   = trim($this->mGetFlexForm('s_add', 'wrapDescription'));
    $this->conf['wrapPost']['stdWrap.']['wrap']   = trim($this->mGetFlexForm('s_add', 'wrapPost'));
    $this->conf['addthisuser']   = trim($this->mGetFlexForm('s_add', 'addthisuser'));
    $this->conf['addthisconf']   = trim($this->mGetFlexForm('s_add', 'addthisconf'));
    
    
    //Error Checking
    if(!$this->conf['server']) {
      $this->aError[] = $this->pi_getLL('error_server');
    }
	  if(!$this->conf['user']) {
      $this->aError[] = $this->pi_getLL('error_user');
    } 
    /*
	  if(!$this->conf['password']) {
      $this->aError[] = $this->pi_getLL('error_password');
    }
    */
	  if(!$this->conf['database']) {
      $this->aError[] = $this->pi_getLL('error_database');
    }
    
    //Set default values
	  if(!$this->conf['limit']) {
      $this->conf['limit'] = 20;
    }
    if(!$this->conf['wrapTitle']['stdWrap.']['wrap']) {
      $this->conf['wrapTitle']['stdWrap.']['wrap'] = '<div class="title"><h2>|</h2></div>';
    }
	  if(!$this->conf['wrapDescription']['stdWrap.']['wrap']) {
      $this->conf['wrapDescription']['stdWrap.']['wrap'] = '<div class="description">|</div>';
    }
	  if(!$this->conf['wrapPost']['stdWrap.']['wrap']) {
      $this->conf['wrapPost']['stdWrap.']['wrap'] = '<div class="post">|</div>';
    }
	  if(!$this->conf['addthisuser']) {
      $this->conf['addthisuser'] = 'addthis';
    }
    
	  if(!$this->conf['addthisconf']) {
      $this->conf['addthisconf'] = '';
    }

    return '';
	}
	
	
	/**
   * Get the value out of the flexforms
   *
   * @param string    $sheet: The sheed of the flexforms
   * @param string    $key: the name of the flexform field
   * @return  string  The value of the locallang.xml
   */
  protected function mGetFlexForm ($sSheet, $sKey) {
    // Default sheet is sDEF
    $sSheet = ($sSheet=='') ? $sSheet = 'sDEF' : $sSheet;
    $mFlexForm = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $sKey, $sSheet);
    
    return $mFlexForm;
  }  
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ws_wordpressgrab/pi1/class.tx_wswordpressgrab_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ws_wordpressgrab/pi1/class.tx_wswordpressgrab_pi1.php']);
}

?>