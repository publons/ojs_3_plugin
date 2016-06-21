<?php

/**
 * @file plugins/generic/publons/classes/form/PublonsAuthForm.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PublonsAuthForm
 * @ingroup plugins_generic_publons
 *
 * @brief Plugin settings: connect to a Publons Network 
 */
define('PUBLONS_PLUGIN_PASSWORD_SLUG', '********');

import('lib.pkp.classes.form.Form');

class PublonsAuthForm extends Form {

	/** @var $_plugin PublonsPlugin */
	var $_plugin;

	/** @var $_journalId int */
	var $_journalId;

	/**
	 * Constructor. 
	 * @param $plugin PublonsPlugin
	 * @param $journalId int
	 * @see Form::Form()
	 */
	function PublonsAuthForm(&$plugin, $journalId) {
		$this->_plugin =& $plugin;
		$this->_journalId = $journalId;

		parent::Form($plugin->getTemplatePath() . 'publonsAuthForm.tpl');
		$this->addCheck(new FormValidator($this, 'username', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.usernameRequired'));
		$this->addCheck(new FormValidator($this, 'password', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.passwordRequired'));
		$this->addCheck(new FormValidator($this, 'auth_key', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.auth_keyRequired'));
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * @see Form::initData()
	 */
	function initData() {
		$plugin =& $this->_plugin;

		// Initialize from plugin settings
		$this->setData('username', $plugin->getSetting($this->_journalId, 'username'));				 
		$this->setData('auth_key', $plugin->getSetting($this->_journalId, 'auth_key'));		 
		
		// If password has already been set, echo back slug
		$password = $plugin->getSetting($this->_journalId, 'password');
		if (!empty($password)) {
			$this->setData('password', PUBLONS_PLUGIN_PASSWORD_SLUG);
		}
	}

	/**
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array('auth_key', 'username', 'password'));
		$request =& PKPApplication::getRequest();
		$password = $request->getUserVar('password');
		if ($password === PUBLONS_PLUGIN_PASSWORD_SLUG) {
			$plugin =& $this->_plugin;
			$password = $plugin->getSetting($this->_journalId, 'password');
		}
		$this->setData('password', $password);
	}

	/**
	 * @see Form::execute()
	 */
	function execute() {
		$plugin =& $this->_plugin;
		$plugin->updateSetting($this->_journalId, 'username', $this->getData('username'), 'string');		
		$plugin->updateSetting($this->_journalId, 'password', $this->getData('password'), 'string'); 
		$plugin->updateSetting($this->_journalId, 'auth_key', $this->getData('auth_key'), 'string'); 
	}
	

}
