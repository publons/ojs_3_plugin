<?php

/**
 * @file plugins/generic/publons/PublonsExportForm.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsExportForm
 * @ingroup plugins_generic_publons
 *
 * @brief Form for export reviews to Publons
 */

import('lib.pkp.classes.form.Form');

class PublonsExportForm extends Form {


	/**
	 * @var $plugin object
	 */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $journalId int
	 */
	function PublonsExportForm(&$plugin) {
		$this->_plugin =& $plugin;
		parent::Form($plugin->getTemplatePath() . DIRECTORY_SEPARATOR . 'export.tpl');
	}

	/**
	 * @see Form::validate()
	 */
	function validate() {
		return parent::validate();
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
	}

	/**
	 * @see Form::display()
	 */
	function display() {
		$templateMgr =& TemplateManager::getManager();
		parent::display();
	}
	/**
	 * @see Form::execute()
	 */
	function execute() {
	}

}
