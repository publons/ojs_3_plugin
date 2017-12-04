<?php

/**
 * @file plugins/generic/publons/PublonsExportReviewForm.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsExportReviewForm
 * @ingroup plugins_generic_publons
 *
 * @brief Form for export reviews to Publons
 */

import('lib.pkp.classes.form.Form');

class PublonsExportReviewForm extends Form {


	/**
	 * @var $plugin object
	 */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $journalId int
	 */
	function PublonsExportReviewForm(&$plugin) {
		$this->_plugin =& $plugin;
        parent::__construct($plugin->getTemplatePath() . 'publonsExportReviewForm.tpl');
	}

}
