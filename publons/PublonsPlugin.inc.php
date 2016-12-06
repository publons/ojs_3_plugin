<?php

/**
 * @file plugins/generic/publons/PublonsPlugin.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v2.
 *
 * @class PublonsPlugin
 * @ingroup plugins_generic_publons
 *
 * @brief Publons plugin
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class PublonsPlugin extends GenericPlugin {

    /**
     * Called as a plugin is registered to the registry
     * @param $category String Name of category plugin was registered to
     * @return boolean True iff plugin initialized successfully; if false,
     *  the plugin will not be registered.
     */
    function register($category, $path) {

        if (parent::register($category, $path)) {

                        if (!$this->php5Installed()) return false;

            $this->import('classes.PublonsReviews');
            $this->import('classes.PublonsReviewsDAO');
            $publonsReviewsDao = new PublonsReviewsDAO();
            DAORegistry::registerDAO('PublonsReviewsDAO', $publonsReviewsDao);

            HookRegistry::register('TemplateManager::display', array(&$this, 'handleTemplateDisplay'));
            HookRegistry::register ('LoadHandler', array(&$this, 'handleRequest'));
            return true;
        }
        return false;
    }

    /**
     * Get the symbolic name of this plugin
     * @return string
     */
    function getName() {
        // This should not be used as this is an abstract class
        return 'PublonsPlugin';
    }

    /**
     * Get the display name of this plugin
     * @return string
     * @see PKPPlugin::getDisplayName()
     */
    function getDisplayName() {
        return __('plugins.generic.publons.displayName');
    }
        /**
     * Get the description of this plugin
     * @return string
    */
    function getDescription() {
        return __('plugins.generic.publons.description');
    }

    /**
     * @see PKPPlugin::getHandlerPath()
     * @return string
     */
    function getHandlerPath() {
        return $this->getPluginPath() . DIRECTORY_SEPARATOR . 'pages';
    }

    /**
     * @see PKPPlugin::getTemplatePath()
     */
    function getTemplatePath() {
        return parent::getTemplatePath() . 'templates/';
    }

    /**
     * @see PKPPlugin::getInstallSchemaFile()
     * @return string
     */
    function getInstallSchemaFile() {
        return $this->getPluginPath() . DIRECTORY_SEPARATOR . 'schema.xml';
    }

    /**
     * @see GenericPlugin::getManagementVerbs()
     */
    function getManagementVerbs() {
        $verbs = array();
        if ($this->getEnabled()) {
            $verbs[] = array('connect', __('plugins.generic.publons.settings.connect'));
            $verbs[] = array('settings', __('plugins.generic.publons.settings'));
        }
        return parent::getManagementVerbs($verbs);
    }

    /**
     * @see GenericPlugin::manage()
     */
    function manage($verb, $args, &$message, &$messageParams) {
        if (!parent::manage($verb, $args, $message, $messageParams)) return false;

        $templateMgr =& TemplateManager::getManager();
        $templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));

        $journal =& Request::getJournal();

        switch ($verb) {
            case 'connect':
                $this->import('classes.form.PublonsAuthForm');
                $form = new PublonsAuthForm($this, $journal->getId());
                if (Request::getUserVar('save')) {
                    $form->readInputData();
                    if ($form->validate()) {
                        $form->execute();
                         Request::redirect(null, 'manager', 'plugin', array('generic', $this->getName(), 'select'));
                        return false;
                    } else {
                        $form->display();
                    }
                } else {
                    $form->initData();
                    $form->display();
                }
                return true;
            case 'settings':
                $publonsReviewsDao =& DAORegistry::getDAO('PublonsReviewsDAO');
                $reviewsByJournal =& $publonsReviewsDao->getPublonsReviewsByJournal($journal->getId());

                $this->import('classes.form.SettingsForm');
                $form = new SettingsForm($this, $journal->getId());
                    $form->initData();
                $form->display();
                return true;

            default:
                // Unknown management verb
                assert(false);
                return false;
        }
    }

    /**
     * Hook callback: register output filter to add data citation to submission
     * summaries; add data citation to reading tools' suppfiles and metadata views.
     * @see TemplateManager::display()
     */
    function handleTemplateDisplay($hookName, $args) {
        if ($this->getEnabled()) {
            $templateMgr =& $args[0];
            $template =& $args[1];
            if ($template != 'reviewer/submission.tpl') return false;


            $templateMgr->register_outputfilter(array(&$this, 'submissionOutputFilter'));
            return false;
        }
    }

    function handleRequest($hookName, $params) {
        $page =& $params[0];
        $request = Application::getRequest();
        AppLocale::requireComponents(LOCALE_COMPONENT_APPLICATION_COMMON);
        if ($page == 'reviewer' && $this->getEnabled()) {
            $op =& $params[1];
            if ($op == 'exportReviews') {

                define('HANDLER_CLASS', 'PublonsHandler');
                define('PUBLONS_PLUGIN_NAME', $this->getName());
                AppLocale::requireComponents(LOCALE_COMPONENT_APPLICATION_COMMON);
                $handlerFile =& $params[2];
                $handlerFile = $this->getHandlerPath() . DIRECTORY_SEPARATOR . 'PublonsHandler.inc.php';
            }
        }
        return false;

    }

    /**
     * Output filter adds publons export step to submission process.
     * @param $output string
     * @param $templateMgr TemplateManager
     * @return $string
     */
    function submissionOutputFilter($output, &$templateMgr) {

        preg_match('/id="reviewSteps".+<td>5\.<\/td>.+<\/tr>(.+)/s', $output, $matches, PREG_OFFSET_CAPTURE);
        if (!is_null($matches[1])){
            $beforeInsertPoint = substr($output, 0, $matches[1][1]);
            $afterInsertPoint = substr($output, $matches[1][1] - strlen($output));

            $reviewId = $templateMgr->get_template_vars('reviewId');

            $publonsReviewsDao =& DAORegistry::getDAO('PublonsReviewsDAO');
            $published =& $publonsReviewsDao->getPublonsReviewsIdByReviewId($reviewId);

            $templateMgr =& TemplateManager::getManager();
            $templateMgr->assign('reviewId', $reviewId);
            $templateMgr->assign('published', $published);

            $newOutput = $beforeInsertPoint;
            $newOutput .= $templateMgr->fetch($this->getTemplatePath() . 'publonsStep.tpl');
            $newOutput .= $afterInsertPoint;

            $output = $newOutput;
        }


        $templateMgr->unregister_outputfilter('submissionOutputFilter');
        return $output;
    }

    /**
     * Get whether we're running php 5
     * @return boolean
     */
    function php5Installed() {
        return version_compare(PHP_VERSION, '5.0.0', '>=');
    }

    /**
     * Get whether curl is available
     * @return boolean
     */
    function curlInstalled() {
        return function_exists('curl_version');
    }
    /**
     * @see PKPPlugin::smartyPluginUrl()
     */
    function smartyPluginUrl($params, &$smarty) {
        $path = array($this->getCategory(), $this->getName());
        if (is_array($params['path'])) {
            $params['path'] = array_merge($path, $params['path']);
        } elseif (!empty($params['path'])) {
            $params['path'] = array_merge($path, array($params['path']));
        } else {
            $params['path'] = $path;
        }

        if (!empty($params['id'])) {
            $params['path'] = array_merge($params['path'], array($params['id']));
            unset($params['id']);
        }
        return $smarty->smartyUrl($params, $smarty);
    }

}

?>
