<?php

/**
 * @file plugins/generic/publons/pages/PublonsHandler.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PublonsHandler
 * @ingroup plugins_generic_publons
 *
 * @brief Handle Publons requests
 */


import('pages.sectionEditor.SectionEditorHandler');
import('pages.reviewer.ReviewerHandler');
import('classes.handler.Handler');

class PublonsHandler extends Handler {

	/**
	 * Display
	 * @param array $args
	 * @param Request $request
	 */
	function exportReviews($args, &$request) {

		$plugin =& PluginRegistry::getPlugin('generic', PUBLONS_PLUGIN_NAME);
		$templateMgr =& TemplateManager::getManager();

		$rname = $request->getUserVar('rname');
		$remail = $request->getUserVar('remail');
		$rtitle = $request->getUserVar('rtitle');
		$rtitle_en = $request->getUserVar('rtitle_en');
		$rbody = $request->getUserVar('rbody');

		if(!$rbody) {

			$templateMgr->assign('info', __('plugins.generic.publons.bodyIsEmpty'));
			$templateMgr->display($plugin->getTemplatePath() . 'export.tpl');
			return;
		}

		$journalId = $request->getUserVar('journalId');
		$articleId = $request->getUserVar('articleId');
		echo '<pre>' . var_dump($request) . '</pre>';
		$reviewerId = $request->getUserVar('reviewerId');


		$auth_key = $plugin->getSetting($journalId, 'auth_key');
		$auth_token = $plugin->getSetting($journalId, 'auth_token');

		$plugin->import('classes.PublonsReviews');

  		$locale = AppLocale::getLocale();

		$publonsReviews = new PublonsReviews();

		$publonsReviews->setJournalId($journalId);
		$publonsReviews->setArticleId($articleId);
		$publonsReviews->setReviewerId($reviewerId);
		$publonsReviews->setTitleEn($rtitle_en);
		$publonsReviews->setDateAdded(Core::getCurrentDate());

		$publonsReviews->setTitle($rtitle, $locale);// Localized
		$publonsReviews->setContent($rbody, $locale);// Localized


		$publonsReviewsDao = new PublonsReviewsDAO();
		DAORegistry::registerDAO('PublonsReviewsDAO', $publonsReviewsDao);

		$publonsReviewsDao->insertObject($publonsReviews);

		$headers = array(
		    "Authorization: Token ". $auth_token,
		    'Content-Type: application/json'
		);

		$data = array();
		$data["key"] = $auth_key;
		$data["reviewer"]["name"] = $rname;
		$data["reviewer"]["email"] = $remail;
		$data["publication"]["title"] = $rtitle;
		$data["content"] = $rbody;
		$data["complete_date"]["day"] = date('d');
		$data["complete_date"]["month"] = date('m');
		$data["complete_date"]["year"] = date('Y');

		$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);

		$templateMgr->assign('json_data',$json_data);

		$options = array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => "http://172.17.0.1:8000/api/v2/review/",
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $json_data
		);

		$returned = array();
		$returned = $this->_curlPost($options);


		$responseCodes = array(
			'200' => 'OK - Success.',
			'201' => 'Created - Success.',
			'400' => 'Bad Request - You are doing something wrong.',
			'403' => 'Forbidden - You do not have permission to do this.',
			'404' => 'Not Found - Resource not found.',
			'405' => 'Method Not Allowed - You`re probably trying to post to a resource that only supports GET.',
			'500' => '500 Internal Server Error - Please contact api@publons.com.'
		);


		$templateMgr->assign('result',$returned['result']);
		$templateMgr->assign('status',$returned['status'].' '.$responseCodes[$returned['status']]);
		$templateMgr->assign('error', $returned['error']);


		$templateMgr->assign('rname',$rname);
		$templateMgr->assign('rbody',$rbody);
		$templateMgr->assign('rtitle',$rtitle);
		$templateMgr->assign('rtitle_en',$rtitle_en);
		$templateMgr->assign('remail',$remail);

		$templateMgr->display($plugin->getTemplatePath() . 'export.tpl');
	}

	/**
	 * Post a request to a resource using CURL
	 * @param $url string
	 * @param $headers array
	 * @return array
	 */
	function _curlPost($curlopt) {

		$curl = curl_init();
		curl_setopt_array($curl, $curlopt);

		$httpResult = curl_exec($curl);
		$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$httpError = curl_error($curl);
		curl_close ($curl);
		return array(
			'status' => $httpStatus,
			'result' => $httpResult,
			'error'  => $httpError
		);
	}

       	/**
	 * Get whether curl is available
	 * @return boolean
	 */
	function curlInstalled() {
		return function_exists('curl_version');
	}
}


?>
