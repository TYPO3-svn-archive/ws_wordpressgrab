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

/**
 *
 *
 * @package wswordpressgrab
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Post extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Wordpress id
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $wpId;

	/**
	 * header
	 *
	 * @var \string
	 */
	protected $header;

	/**
	 * External Url
	 *
	 * @var \string
	 */
	protected $url;

    /**
     * Image
     *
     * @var string
     */
    protected $image;

	/**
	 * content
	 *
	 * @var \string
	 */
	protected $content;

    /**
     * publish_date
     *
     * @var \DateTime
     */
    protected $publishDate;


    /**
	 * Returns the wpId
	 *
	 * @return \integer $wpId
	 */
	public function getWpId() {
		return $this->wpId;
	}

	/**
	 * Sets the wpId
	 *
	 * @param \integer $wpId
	 * @return void
	 */
	public function setWpId($wpId) {
		$this->wpId = $wpId;
	}

	/**
	 * Returns the header
	 *
	 * @return \string $header
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * Sets the header
	 *
	 * @param \string $header
	 * @return void
	 */
	public function setHeader($header) {
		$this->header = $header;
	}

	/**
	 * Returns the url
	 *
	 * @return \string $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Sets the url
	 *
	 * @param \string $url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

    /**
     * Returns the image
     *
     * @return string $image
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param string $image
     * @return void
     */
    public function setImage($image) {
        $this->image = $image;
    }

	/**
	 * Returns the content
	 *
	 * @return \string $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Sets the content
	 *
	 * @param \string $content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

    /**
     * Returns the publish_date
     *
     * @return \DateTime $publish_date
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Sets the publish_date
     *
     * @param \DateTime $publish_date
     * @return void
     */
    public function setPublishDate($publish_date)
    {
        $this->publishDate = $publish_date;
    }
}