<?php

/**
 * @file plugins/generic/publons/classes/PublonsReviews.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PublonsReviews
 * @ingroup plugins_generic_publons
 * @see PublonsReviewsDAO
 *
 * @brief Basic class describing a reviews into the Publons.
 */

class PublonsReviews extends DataObject {
	//
	// Get/set methods
	//

	/**
	 * Get the article ID of the referral.
	 * @return int
	 */
	function getArticleId() {
		return $this->getData('articleId');
	}

	/**
	 * Set the article ID of the publons reviews.
	 * @param $articleId int
	 */
	function setArticleId($articleId) {
		return $this->setData('articleId', $articleId);
	}

	/**
	 * Get the journal ID of the publons reviews.
	 * @return int
	 */
	function getJournalId() {
		return $this->getData('journalId');
	}

	/**
	 * Set the journal ID of the publons reviews.
	 * @param $journalId int
	 */
	function setJournalId($journalId) {
		return $this->setData('journalId', $journalId);
	}

	/**
	 * Get the reviewer ID of the publons reviews.
	 * @return int
	 */
	function getReviewerId() {
		return $this->getData('reviewerId');
	}

	/**
	 * Set the journal ID of the publons reviews.
	 * @param $reviewerId int
	 */
	function setReviewerId($reviewerId) {
		return $this->setData('reviewerId', $reviewerId);
	}

	/**
	 * Get the date added a review into the Publons.
	 * @return date
	 */
	function getDateAdded() {
		return $this->getData('dateAdded');
	}

	/**
	 * Set the date added a review into the Publons.
	 * @param $dateAdded date
	 */
	function setDateAdded($dateAdded) {
		return $this->setData('dateAdded', $dateAdded);
	}

	/**
	 * Get the localized title of the article.
	 * @return string
	 */
	function getLocalizedTitle() {
		return $this->getLocalizedData('title');
	}

	/**
	 * Get the title of the article.
	 * @param $locale string
	 * @return string
	 */
	function getTitle($locale) {
		return $this->getData('title', $locale);
	}

	/**
	 * Set the title of the article.
	 * @param $title string
	 * @param $locale string
	 */
	function setTitle($title, $locale) {
		return $this->setData('title', $title?$title:'', $locale);
	}

	/**
	 * Get the title_en of the article.
	 * @return string
	 */
	function getTitleEn() {
		return $this->getData('titleEn');
	}

	/**
	 * Set the title_en of the article.
	 * @param $title string
	 * @param $locale string
	 */
	function setTitleEn($title) {
		return $this->setData('titleEn', $title);
	}

	/**
	 * Get the content of the review for the Publons.
	 * @return string
	 */
	function getLocalizedContent() {
		return $this->getLocalizedData('content');
	}

	/**
	 * Get the content of the review for the Publons.
	 * @param $locale string
	 * @return string
	 */
	function getContent($locale) {
		return $this->getData('content', $locale);
	}

	/**
	 * Set the content of the review for the Publons.
	 * @param $content string
	 * @param $locale string
	 */
	function setContent($content, $locale) {
		return $this->setData('content', $content?$content:'', $locale);
	}

}

?>
