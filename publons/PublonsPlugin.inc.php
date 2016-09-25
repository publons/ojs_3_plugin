<?php

/**
 * @file plugins/generic/publons/PublonsPlugin.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
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
	 * 	the plugin will not be registered.
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
	 * Output filter adds data citation to submission summary.
	 * @param $output string
	 * @param $templateMgr TemplateManager
	 * @return $string
	 */
	function submissionOutputFilter($output, &$templateMgr) {

		$fourStepPoint =strpos($output, '<td>4.</td>');
		if ($fourStepPoint !== false) {
			$beforeCommentOutput = substr($output,$fourStepPoint);
			$tableInsertPoint =strpos($beforeCommentOutput, '<img');
			$indexTable = $fourStepPoint + $tableInsertPoint;
			$newOutput = substr($output,0,$indexTable);
                       // $newOutput .= '<table class="data" width="100%"><tr valign="top"><td class="label" width="30%">';
                        $newOutput .= substr($output, $indexTable);
			$output = $newOutput;


                        $commentPoint =strpos($output, 'comment');
			$afterCommentOutput = substr($output,$commentPoint);
			$insertPoint =strpos($afterCommentOutput, '</td>');
			$index = $commentPoint + $insertPoint;
			$newOutput = substr($output,0,$index);			
                        
			$newOutput .= '<td class="value" width="70%">';

		

			$templateMgr =& TemplateManager::getManager();
			

			$user =& Request::getUser();
			$journal =& Request::getJournal();

			$reviewerSubmissionDao =& DAORegistry::getDAO('ReviewerSubmissionDAO');
			$reviewId = $templateMgr->get_template_vars('reviewId');
			$submission =& $reviewerSubmissionDao->getReviewerSubmission($reviewId);
			$submissionId = $submission->getId();

			$articleCommentDao =& DAORegistry::getDAO('ArticleCommentDAO');
			$reviewAssignmentDao =& DAORegistry::getDAO('ReviewAssignmentDAO');
			$reviewAssignments =& $reviewAssignmentDao->getBySubmissionId($submissionId, $submission->getCurrentRound());
			$reviewIndexes =& $reviewAssignmentDao->getReviewIndexesForRound($submissionId, $submission->getCurrentRound());
			$body = '';
			foreach ($reviewAssignments as $reviewAssignment) {
				// If the reviewer has completed the assignment, then import the review.
				if (!$reviewAssignment->getCancelled()) {
					// Get the comments associated with this review assignment
					$articleComments =& $articleCommentDao->getArticleComments($submissionId, COMMENT_TYPE_PEER_REVIEW, $reviewAssignment->getId());
					if($articleComments) { 
						if (is_array($articleComments)) {
							foreach ($articleComments as $comment) {
								// If the comment is viewable by the author, then add the comment.
								if ($comment->getViewable()) $body .= String::html2text($comment->getComments()) . "\n\n";
							}
						}
					}
					if ($reviewFormId = $reviewAssignment->getReviewFormId()) { 
						$reviewId = $reviewAssignment->getId();
						$reviewFormResponseDao =& DAORegistry::getDAO('ReviewFormResponseDAO');
						$reviewFormElementDao =& DAORegistry::getDAO('ReviewFormElementDAO');
						$reviewFormElements =& $reviewFormElementDao->getReviewFormElements($reviewFormId);

						foreach ($reviewFormElements as $reviewFormElement) if ($reviewFormElement->getIncluded()) {
							$body .= String::html2text($reviewFormElement->getLocalizedQuestion()) . ": \n";
							$reviewFormResponse = $reviewFormResponseDao->getReviewFormResponse($reviewId, $reviewFormElement->getId());
	
							if ($reviewFormResponse) {
								$possibleResponses = $reviewFormElement->getLocalizedPossibleResponses();
								if (in_array($reviewFormElement->getElementType(), $reviewFormElement->getMultipleResponsesElementTypes())) {
									if ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_CHECKBOXES) {
										foreach ($reviewFormResponse->getValue() as $value) {
											$body .= "\t" . String::html2text($possibleResponses[$value-1]['content']) . "\n";
										}
									} else {
										$body .= "\t" . String::html2text($possibleResponses[$reviewFormResponse->getValue()-1]['content']) . "\n";
									}
									$body .= "\n";
								} else {
									$body .= "\t" . $reviewFormResponse->getValue() . "\n\n";
								}
							}
						}
					}
				}        
			}        
			$body = str_replace("\r", '', $body);
			$body = str_replace("\n", '\r\n', $body);
			$templateMgr->assign('rbody', $body);
			$templateMgr->assign('rtitle', $submission->getLocalizedTitle());
			$templateMgr->assign('rtitle_en', $submission->getTitle('en_US'));
			$templateMgr->assign('rname', $user->getFullName());
			$templateMgr->assign('remail', $user->getEmail());
			  
              		$journal =& Request::getJournal();
			$journalId = $journal->getId();
			$user =& Request::getUser();
			$reviewerId = $user->getId(); 

			$publonsReviewsDao =& DAORegistry::getDAO('PublonsReviewsDAO');
			$published =& $publonsReviewsDao->getPublonsReviewsIdByArticle($journalId, $submissionId);	

			$templateMgr->assign('journalId', $journalId);
			$templateMgr->assign('articlelId', $submissionId);
			$templateMgr->assign('reviewerId', $reviewerId);
			$templateMgr->assign('published', $published);


			$newOutput .= $templateMgr->fetch($this->getTemplatePath() . 'code.tpl');		


//			$newOutput .= '</td></tr></table>';
			$newOutput .= '</td>';
			$newOutput .= substr($output, $index);
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
