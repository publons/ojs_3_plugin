<?php

/**
 * @file plugins/generic/publons/classes/form/PublonsSettingsForm.inc.php
 *
 * Copyright (c) 2017 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsSettingsForm
 * @ingroup plugins_generic_publons
 *
 * @brief Plugin settings: connect to a Publons Network
 */

import('lib.pkp.classes.form.Form');
import('plugins.generic.publons.classes.PublonsHelpURLFormValidator');

class PublonsSettingsForm extends Form {

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
    function PublonsSettingsForm(&$plugin, $journalId) {
        $this->_plugin =& $plugin;
        $this->_journalId = $journalId;

        parent::__construct($plugin->getTemplatePath() . 'publonsSettingsForm.tpl');
        $this->addCheck(new FormValidator($this, 'auth_token', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.authTokenRequired'));
        $this->addCheck(new FormValidator($this, 'auth_key', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.journalTokenRequired'));
        $this->addCheck(new FormValidator($this, 'info_url', FORM_VALIDATOR_OPTIONAL_VALUE, 'plugins.generic.publons.settings.invalidHelpUrl', new PublonsHelpURLFormValidator()));
        $this->addCheck(new FormValidatorPost($this));
        $this->addCheck(new FormValidatorCSRF($this));
    }

    /**
     * @see Form::initData()
     */
    function initData() {
        $plugin =& $this->_plugin;
        $journalId = $this->_journalId;

        // Initialize from plugin settings
        $this->setData('auth_token', $plugin->getSetting($journalId, 'auth_token'));
        $this->setData('auth_key', $plugin->getSetting($journalId, 'auth_key'));
        $this->setData('info_url', $plugin->getSetting($journalId, 'info_url'));
    }

    /**
     * @see Form::readInputData()
     */
    function readInputData() {
        $this->readUserVars(array('auth_token', 'auth_key', 'info_url'));
    }

    /**
     * Fetch the form.
     * @copydoc Form::fetch()
     */
    function fetch($request, $template = null, $display = false) {
        $templateMgr = TemplateManager::getManager($request);
        $templateMgr->assign('pluginName', $this->_plugin->getName());
        return parent::fetch($request, $template, $display);
    }

    /**
     * @see Form::execute()
     */
    function execute() {
        $plugin =& $this->_plugin;
        $plugin->updateSetting($this->_journalId, 'auth_token', $this->getData('auth_token') , 'string');
        $plugin->updateSetting($this->_journalId, 'auth_key', $this->getData('auth_key'), 'string');
        $plugin->updateSetting($this->_journalId, 'info_url', $this->getData('info_url'), 'string');

        $request = PKPApplication::getRequest();
        $currentUser = $request->getUser();
        $notificationMgr = new NotificationManager();
        $notificationMgr->createTrivialNotification($currentUser->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => __('plugins.generic.publons.notifications.settingsUpdated')));
    }


}
?>
