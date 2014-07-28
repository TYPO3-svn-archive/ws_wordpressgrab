<?php
namespace Ws\WsWordpressgrab\Controller\Common;

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
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 *
 *
 * @package wswordpressgrab
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
abstract class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * Book Repository
     *
     * @var \Ws\WsWordpressgrab\Domain\Repository\PostRepository
     * @inject
     */
    protected $postRepository;

    /**
     * Translation shortcut
     *
     * @param $key
     * @param NULL|array $arguments
     * @return NULL|string
     */
    public function translate($key, $arguments = NULL) {
        return LocalizationUtility::translate(
            $key,
            $this->controllerContext->getRequest()->getControllerExtensionKey(),
            $arguments
        );
    }

}