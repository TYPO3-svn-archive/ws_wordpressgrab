<?php
namespace Ws\WsWordpressgrab\Domain\Model;

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
use TYPO3\CMS\Core\Utility\File\BasicFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @package wswordpressgrab
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Parser {
    const GRABBED_IMAGES = 1;
    const FOLDER_PREFIX_WP = 'wp-content/uploads';
    const FOLDER_PREFIX_T3 = 'user_upload/posts/';
    const ALLOWED_IMAGES = 'png,jpg,gif,jpeg,bmp';
    /**
     * Object manager
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    public function __construct(){
        $this->initObjectManager();
    }

    /**
     * Parse posts from Wordpress to TYPO3
     * @param array $wpPost
     * @return Post
     */
    public function perform($wpPost=array()){
        /** @var \Ws\WsWordpressgrab\Domain\Model\SimpleHtmlDom $domHtml */
        $domHtml = $this->objectManager->get(
            '\\Ws\\WsWordpressgrab\\Domain\\Model\\SimpleHtmlDom'
        );
        /** @var \Ws\WsWordpressgrab\Domain\Model\Post $t3Post */
        $t3Post = $this->objectManager->get(
            '\\Ws\\WsWordpressgrab\\Domain\\Model\\Post'
        );

        if(trim($wpPost['post_content'])){
            //Get rid off special tags
            $wpPost['post_content'] = preg_replace(
                '/\[caption[^]]*id="([^"]*)"[^]]*\](.*?)\[\/caption\]/s',
                '<p class="caption" id="caption-$1">$2</p>',
                $wpPost['post_content']
            );
            $wpPost['post_content'] = preg_replace('/<!--(.|\s)*?-->/', '', $wpPost['post_content']);
            $wpPost['post_content'] = nl2br($wpPost['post_content']);

            //Get images
            $html = $domHtml->str_get_html($wpPost['post_content']);
            // Find all images
            $i = 0;
            foreach($html->find('img') as $element){
                $i++;
                if($i > self::GRABBED_IMAGES) break;

                /** @var \TYPO3\CMS\Core\Utility\File\BasicFileUtility $basicFileFunctions */
                $basicFileFunctions = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');

                $imageSrc = $element->src;
                $filename = $this->parseFilename($imageSrc);
                $filename = $basicFileFunctions->cleanFileName($filename);
                $directory = $this->parseDirectory($imageSrc);
                $directory = $basicFileFunctions->rmDoubleSlash($directory);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if(!in_array($ext, explode(",", self::ALLOWED_IMAGES))){
                    continue;
                }

                $imageContent = GeneralUtility::getUrl($imageSrc);
                $removeElement = strcasecmp($element->parent()->tag, 'a')
                    ? $element->parent()
                    : $element;
                $removeContent = $removeElement->outertext;
                $wpPost['post_content'] = str_replace($removeContent, '', $html->save());

                if($imageContent){
                    // FAL storage
                    /** @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
                    /** @var \TYPO3\CMS\Core\Resource\ResourceStorage $storage */
                    $storageRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
                    $storage = $storageRepository->findByUid(1);

                    try{
                        $folderTmp = $storage->createFolder('_temp_');
                        $fileObjectTmp = $storage->createFile($filename, $folderTmp);
                        $storage->setFileContents($fileObjectTmp, $imageContent);
                        $folder = $storage->createFolder($directory);
                        $fileObject = $storage->copyFile($fileObjectTmp, $folder);
                        $storage->deleteFile($fileObjectTmp);

                        //TODO via inline type and $fileReference
                        /*$fileReference = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
                        $fileReference->setOriginalResource($fileObject);*/
                    }
                    catch(Exception $e){

                    }

                    $t3Post->setImage($fileObject->getPublicUrl());
                }
            }
        }

        $t3Post->setWpId($wpPost['ID']);
        $t3Post->setUrl($wpPost['guid']);
        $t3Post->setHeader(strip_tags($wpPost['post_title']));
        $t3Post->setContent($wpPost['post_content']);

        $t3Post->setPublishDate(new \DateTime('@'.$wpPost['unix_post_date']));
        return $t3Post;
    }

    private function parseFilename($imageSrc){
        $imageSrc = $this->normalizePath($imageSrc);
        $filename = trim(pathinfo($imageSrc, PATHINFO_BASENAME), '/');
        return $filename;
    }

    private function parseDirectory($imageSrc){
        $imageSrc = $this->normalizePath($imageSrc);
        $directory = pathinfo($imageSrc, PATHINFO_DIRNAME);
        $directory = self::FOLDER_PREFIX_T3 . $directory . '/';

        return $directory;
    }

    /**
     * normalize path
     * @param string $path
     * @return mixed
     */
    private function normalizePath($path)
    {
        $path = GeneralUtility::strtolower($path);
        $path = str_replace(self::FOLDER_PREFIX_WP, '', parse_url($path, PHP_URL_PATH));
        $path = trim($path, '/\\');
        $path = str_replace(array('/', '\\'), '/', $path);

        return $path;
    }

    private function initObjectManager(){
        if(!$this->objectManager){
            $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        }
    }
}