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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @package wswordpressgrab
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PostRepository extends AbstractRepository
{
    public function findPagesWithWSGrabber(){
        $result = array();

        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $sql = "SELECT pid FROM tt_content WHERE list_type='wswordpressgrab_pi1' GROUP BY pid";
        $query->statement($sql);
        $res = $query->execute();
        foreach($res as $row){
            $result[] = $row['pid'];
        }

        return $result;
    }

    public function findAllIndexes(){
        $result = array();

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper  $mapper */
        $mapper = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Mapper\\DataMapper');
        $table = $mapper->convertClassNameToTableName('Ws\WsWordpressgrab\Domain\Model\Post');

        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $sql = "SELECT wp_id FROM ".$table;
        $query->statement($sql);
        $res = $query->execute();
        foreach($res as $row){
            $result[] = $row['wp_id'];
        }

        return $result;
    }

    /**
     * @param null $limit
     * @param string $storagePids
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAll($limit = null, $storagePids = ''){
        $query = $this->createQuery();

        if($storagePids){
            $storagePids = GeneralUtility::trimExplode(",", $storagePids);
            $query->getQuerySettings()->setRespectStoragePage(TRUE);
            $query->getQuerySettings()->setStoragePageIds($storagePids);
        }

        if((int)$limit > 0)
            $query->setLimit((int)$limit);
        $query->setOrderings(
            array(
                'publish_date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
                'wp_id' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            )
        );
        return $query->execute();
    }

}