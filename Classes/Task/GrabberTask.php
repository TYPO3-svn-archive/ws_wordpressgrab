<?php
namespace Ws\WsWordpressgrab\Task;

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
use TYPO3\CMS\Core\Cache\Cache;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * This class provides Scheduler plugin implementation
 */
class GrabberTask extends AbstractTask {
    const DEFAULT_LIMIT = 100;

    /**
     * Object manager
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;
    /**
     * Host name
     *
     * @var string
     */
    protected $host;
    /**
     * Username
     *
     * @var string
     */
    protected $username;
    /**
     * Password
     *
     * @var string
     */
    protected $password;
    /**
     * Database
     *
     * @var string
     */
    protected $database;
    /**
     * Limit
     *
     * @var string
     */
    protected $limit;
    /**
     * Categories
     *
     * @var string
     */
    protected $categories;
    /**
     * Table prefix
     *
     * @var string
     */
    protected $table_prefix;
    /**
     * Pid
     *
     * @var int
     */
    protected $pid;


    public function __construct(){
        $this->initObjectManager();
        parent::__construct();
    }

    /**
     * Get the value of the protected property host
     *
     * @return string Host name
     */
    public function getHost() {
        return $this->host;
    }
    /**
     * Set the value of the private property host.
     *
     * @param string $host Host
     * @return void
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * Get the value of the protected property username
     *
     * @return string Username
     */
    public function getUsername() {
        return $this->username;
    }
    /**
     * Set the value of the private property username.
     *
     * @param string $username Username
     * @return void
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get the value of the protected property password
     *
     * @return string Password
     */
    public function getPassword() {
        return $this->password;
    }
    /**
     * Set the value of the private property password.
     *
     * @param string $password password
     * @return void
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Get the value of the protected property database
     *
     * @return string database
     */
    public function getDatabase() {
        return $this->database;
    }
    /**
     * Set the value of the private property database.
     *
     * @param string $database database
     * @return void
     */
    public function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * Get the value of the protected property limit
     *
     * @return string limit
     */
    public function getLimit() {

        return intval($this->limit) ? $this->limit : self::DEFAULT_LIMIT;
    }
    /**
     * Set the value of the private property limit.
     *
     * @param string $limit
     * @return void
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * Get the value of the protected property categories
     *
     * @return string categories
     */
    public function getCategories() {
        return $this->categories;
    }
    /**
     * Set the value of the private property categories.
     *
     * @param string $categories
     * @return void
     */
    public function setCategories($categories) {
        $this->categories = $categories;
    }

    /**
     * Get the value of the protected property pid
     *
     * @return string pid
     */
    public function getPid() {
        return $this->pid ? $this->pid : 1;
    }
    /**
     * Set the value of the private property pid.
     *
     * @param int $pid
     * @return void
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }

    /**
     * Get the value of the protected property table_prefix
     *
     * @return string table_prefix
     */
    public function getTablePrefix() {
        return $this->table_prefix;
    }
    /**
     * Set the value of the private property table_prefix.
     *
     * @param string $table_prefix
     * @return void
     */
    public function setTablePrefix($table_prefix) {
        $this->table_prefix = $table_prefix;
    }

	/**
	 * Function execute from the Scheduler
	 *
	 * @return boolean TRUE on successful execution, FALSE on error
	 * @throws \InvalidArgumentException if the email template file can not be read
	 */
	public function execute() {
        /** @var \Ws\WsWordpressgrab\Domain\Repository\WordpressRepository $wordpressRepository */
        $wordpressRepository = $this->objectManager->get(
            '\\Ws\\WsWordpressgrab\\Domain\\Repository\\WordpressRepository',
            $this->getHost(),
            $this->getUsername(),
            $this->getPassword(),
            $this->getDatabase(),
            $this->getTablePrefix()
        );

        /** @var \Ws\WsWordpressgrab\Domain\Repository\PostRepository $postRepository */
        $postRepository = $this->objectManager->get(
            '\\Ws\\WsWordpressgrab\\Domain\\Repository\\PostRepository'
        );
        $allExistedPosts = $postRepository->findAllIndexes();
        $allExistedPosts = count($allExistedPosts) ? $allExistedPosts : array();
        $wpIds = $wordpressRepository->findAllId(
            $this->getCategories()
        );

        //Check existed ids
        $postsToImport = array();
        foreach($wpIds as $wpId){
            if(count($postsToImport) <= $this->getLimit()){
                if(!in_array($wpId, $allExistedPosts)){
                    $postsToImport[] = $wpId;
                }
            }
            else
                break;
        }

        $posts = $wordpressRepository->findPosts($postsToImport);

        try {
            /** @var \Ws\WsWordpressgrab\Domain\Model\Parser $parser */
            $parser = $this->objectManager->get(
                '\\Ws\\WsWordpressgrab\\Domain\\Model\\Parser'
            );
            $persistenceManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            foreach($posts as $wpPost){
                $t3Post = $parser->perform($wpPost);
                $t3Post->setPid($this->getPid());
                $postRepository->add($t3Post);
                $persistenceManager->persistAll();
            }

            if(count($posts)){
                //Clear pages where frontend plugin is installed
                $pagesToClear = $postRepository->findPagesWithWSGrabber();
                /** @var \TYPO3\CMS\Extbase\Service\CacheService $cacheService */
                $cacheService = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Service\\CacheService');
                $cacheService->clearPageCache($pagesToClear);
            }

            $success = TRUE;
        } catch (\Exception $e) {
            $success = FALSE;
        }

        $wordpressRepository->disconnect();
        return $success;
	}

    private function initObjectManager(){
        if(!$this->objectManager){
            $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        }
    }
}