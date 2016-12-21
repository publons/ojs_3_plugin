<?php
/**
 * @file plugins/generic/publons/classes/form/SettingsForm.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class SettingsForm
 * @ingroup plugins_generic_publons
 *
 * @brief Plugin settings.
 */
import('lib.pkp.classes.form.Form');

class SettingsForm extends Form {

	/** @var $journalId int */
	var $_journalId;

	/** @var $plugin object */
	var $_plugin;

	/**
	 * Constructor.
	 * @param $plugin PublonsPlugin
	 * @param $journalId int
	 * @see Form::Form()
	 */
	function SettingsForm(&$plugin, $journalId) {
		$this->_journalId = $journalId;
		$this->_plugin =& $plugin;
		$this->addCheck(new FormValidatorPost($this));
		AppLocale::requireComponents(LOCALE_COMPONENT_APPLICATION_COMMON);
		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
	}
	/**
	 * @see Form::initData()
	 */
	function initData() {
		$plugin =& $this->_plugin;
		$journalId = $this->_journalId;

		$sort = Request::getUserVar('sort');
		$sort = isset($sort) ? $sort : 'date_added';

		$publonsReviewsDao =& DAORegistry::getDAO('PublonsReviewsDAO');
		$reviewsByJournal =& $publonsReviewsDao->getPublonsReviewsByJournal($journalId, $rangeInfo, $sort);

		$userDao =& DAORegistry::getDAO('UserDAO');

   		$this->setData('publonsReviews', $reviewsByJournal);
   		$this->setData('userDAO', $userDao);
	}
	/**
	 * @see Form::readInputData()
	 */
	function readInputData() {
	}
	/**
	 * @see Form::execute()
	 */
	function execute() {
		$plugin =& $this->_plugin;
	}
}
