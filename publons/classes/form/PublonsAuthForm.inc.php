<?php

/**
 * @file plugins/generic/publons/classes/form/PublonsAuthForm.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsAuthForm
 * @ingroup plugins_generic_publons
 *
 * @brief Plugin settings: connect to a Publons Network
 */

import('lib.pkp.classes.form.Form');
import('plugins.generic.publons.classes.PublonsHelpURLFormValidator');

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
        $this->addCheck(new FormValidator($this, 'auth_key', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.authKeyRequired'));
        $this->addCheck(new FormValidator($this, 'auth_token', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.publons.settings.authTokenRequired'));
        $this->addCheck(new FormValidator($this, 'info_url', FORM_VALIDATOR_OPTIONAL_VALUE, 'plugins.generic.publons.settings.invalidHelpUrl', new PublonsHelpURLFormValidator()));
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
        $this->setData('info_url', $plugin->getSetting($this->_journalId, 'info_url'));

        // If password has already been set, echo back slug
        $password = $plugin->getSetting($this->_journalId, 'password');
        if (!empty($password)) {
            $this->setData('password', $password);
        }
    }

    /**
     * @see Form::readInputData()
     */
    function readInputData() {
        $this->readUserVars(array('auth_key', 'username', 'password', 'info_url'));
        $request =& PKPApplication::getRequest();
        $password = $request->getUserVar('password');

        $this->setData('password', $password);

        if (is_null($_SERVER["HTTP_PUBLONS_URL"])){
            $url = "https://publons.com/api/v2/token/";
        } else {
            $url = $_SERVER["HTTP_PUBLONS_URL"]."/api/v2/token/";
        }

        $curlopt = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS =>  'username='.$this->getData('username').'&password='.$this->getData('password')
        );

        $curl = curl_init();
        curl_setopt_array($curl, $curlopt);

        $httpResult = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpError = curl_error($curl);
        curl_close ($curl);
        $returned = array(
            'status' => $httpStatus,
            'result' => $httpResult,
            'error'  => $httpError
        );

        if($returned['status'] == 200) {
            $token = json_decode($returned['result'], true)['token'];
            $this->setData('auth_token', $token);
        }
    }

    /**
     * @see Form::execute()
     */
    function execute() {
        $plugin =& $this->_plugin;
        $plugin->updateSetting($this->_journalId, 'auth_token', $this->getData('auth_token') , 'string');
        $plugin->updateSetting($this->_journalId, 'auth_key', $this->getData('auth_key'), 'string');
        $plugin->updateSetting($this->_journalId, 'info_url', $this->getData('info_url'), 'string');
    }


}
