<?php

namespace Ws\WsWordpressgrab\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Nikolay Orlenko <info@web-spectr.com>, Web.Spectr http://web-spectr.com
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @package wswordpressgrab
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class WordpressRepository
{
    /**
     * connection
     *
     * @var resource
     */
    protected $connection;
    protected $tablePrefix = '';

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $tablePrefix
     */
    public function __construct($host, $username, $password, $database, $tablePrefix='')
    {
        $this->tablePrefix = $tablePrefix;
        $this->connect($host, $username, $password, $database);
    }


    /**
     * @param null $category
     * @return array
     * @throws \InvalidArgumentException
     */
    public function findAllId($category = null)
    {
        $ids = array();
        $category = trim($category);
        if (!empty($category)) {
            $joinCategory = " LEFT JOIN ".$this->tablePrefix."term_relationships ON(wp_posts.ID = ".$this->tablePrefix."term_relationships.object_id)
LEFT JOIN ".$this->tablePrefix."term_taxonomy ON(".$this->tablePrefix."term_relationships.term_taxonomy_id = ".$this->tablePrefix."term_taxonomy.term_taxonomy_id)";
            $whereCategory = " AND ".$this->tablePrefix."term_taxonomy.term_id IN (" . $category . ") AND ".$this->tablePrefix."term_taxonomy.taxonomy = 'category' ";
        } else {
            $joinCategory = "";
            $whereCategory = "";
        }
        mysql_query("SET NAMES 'utf8'", $this->connection);
        $sSelect = "SELECT ".$this->tablePrefix."posts.ID FROM  ".$this->tablePrefix."posts " . $joinCategory . "
          WHERE post_password=''
          " . $whereCategory . "
          AND post_type='post'
          AND post_status='publish'
          ORDER BY ID DESC";

        $mRes = mysql_query($sSelect, $this->connection);
        if($mRes){
            while ($aRow = mysql_fetch_array($mRes))
                $ids[] = $aRow['ID'];
        }

        if(!count($ids)){
            throw new \Exception(
                $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.empty.posts')
            );
        }

        return $ids;
    }


    /**
     * @param array $postIds
     * @return array
     * @throws \InvalidArgumentException
     */
    public function findPosts($postIds)
    {
        $posts = array();

        mysql_query("SET NAMES 'utf8'", $this->connection);
        $sSelect = "SELECT
        UNIX_TIMESTAMP( post_date ) as unix_post_date,
        UNIX_TIMESTAMP( post_modified ) as unix_post_modified,
        ".$this->tablePrefix."posts.* FROM  ".$this->tablePrefix."posts
          WHERE post_password=''
          AND `ID` IN (".implode(", ", $postIds).")
          AND post_type='post'
          AND post_status='publish'
          ORDER BY ID DESC";

        $mRes = mysql_query($sSelect, $this->connection);
        if($mRes){
            while ($aRow = mysql_fetch_array($mRes))
                $posts[] = $aRow;
        }

        return $posts;
    }

    /**
     * @param $host
     * @param $username
     * @param $password
     * @param $database
     * @throws \InvalidArgumentException
     */
    public function connect($host, $username, $password, $database)
    {
        $this->connection = @mysql_connect(
            $host,
            $username,
            $password
        );
        if (!$this->connection) {
            throw new \InvalidArgumentException(
                $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.invalid.connection')
            );
        }

        $db_selected = mysql_select_db($database, $this->connection);
        if (!$db_selected) {
            $this->disconnect();
            throw new \InvalidArgumentException(
                $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.invalid.database') . $database
            );
        }
    }

    /**
     * Disconnect connection
     */
    public function disconnect()
    {
        if ($this->connection)
            mysql_close($this->connection);
        $this->connection = null;
    }
} 